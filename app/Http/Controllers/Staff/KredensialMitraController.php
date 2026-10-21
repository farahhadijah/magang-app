<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Mail\AkunMitraResendMail;
use App\Models\EmailLog;
use App\Models\Mitra;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class KredensialMitraController extends Controller
{
    private ?string $lastPlainPassword = null;
    /**
     * ================================================
     * HALAMAN KELOLA KREDENSIAL
     * ================================================
     */
    public function show(Mitra $mitra)
    {
        $this->authorizeMitra($mitra);

        $mitra->load([
            'user',
            'tempatPkl',
        ]);

        // Riwayat email (10 terakhir)
        $emailLogs = EmailLog::with('triggeredBy:id,username,role')
            ->where('mitra_id', $mitra->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Status email terakhir
        $lastEmail = $emailLogs->first();

        return view(
            'staff.mitra.kredensial',
            compact('mitra', 'emailLogs', 'lastEmail')
        );
    }

    /**
     * ================================================
     * AKSI 1 — KIRIM ULANG (password tetap, tanpa ubah email)
     * ================================================
     *
     * Catatan: sesuai kesepakatan, setiap resend generate password baru
     * supaya password lama invalid.
     */
    public function resend(Mitra $mitra)
    {
        $this->authorizeMitra($mitra);

        $tempat = $mitra->tempatPkl;
        $user   = $mitra->user;

        if (!$tempat || !$user) {
            return back()->with('error', 'Data mitra tidak lengkap.');
        }

        if (empty($tempat->email)) {
            return back()->with(
                'error',
                'Email instansi belum tersedia. Gunakan aksi "Edit Email & Kirim" terlebih dahulu.'
            );
        }

        // Generate password baru
        $passwordBaru = $this->generatePassword();

        try {
            DB::transaction(function () use ($user, $passwordBaru) {
                $user->update([
                    'password'    => Hash::make($passwordBaru),
                    'first_login' => 1,
                    'is_active'   => 1,
                ]);
            });
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'Gagal menyiapkan password baru.');
        }

        // Kirim & catat log
        $log = $this->sendCredentials(
            $mitra,
            EmailLog::JENIS_RESEND,
            $passwordBaru
        );

        $this->lastPlainPassword = $passwordBaru;

        return $this->redirectWithLog($mitra, $log, 'Kredensial berhasil dikirim ulang');
    }

    /**
     * ================================================
     * AKSI 2 — EDIT EMAIL LALU KIRIM
     * ================================================
     */
    public function updateEmail(Request $request, Mitra $mitra)
    {
        $this->authorizeMitra($mitra);

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ], [
            'email.required' => 'Email instansi wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $tempat = $mitra->tempatPkl;
        $user   = $mitra->user;

        if (!$tempat || !$user) {
            return back()->with('error', 'Data mitra tidak lengkap.');
        }

        $emailBaru = strtolower(trim($validated['email']));

        // Cegah duplikasi email antar instansi (opsional, sesuaikan kalau tidak perlu)
        // Kalau kamu tidak mau cek duplikat, hapus blok ini.
        $duplikat = \App\Models\TempatPkl::where('email', $emailBaru)
            ->where('id', '!=', $tempat->id)
            ->exists();

        if ($duplikat) {
            return back()->with(
                'error',
                'Email tersebut sudah digunakan oleh instansi lain.'
            );
        }

        // Generate password baru (konsisten: setiap kirim = password baru)
        $passwordBaru = $this->generatePassword();

        try {
            DB::transaction(function () use ($tempat, $user, $emailBaru, $passwordBaru) {
                $tempat->update(['email' => $emailBaru]);

                $user->update([
                    'password'    => Hash::make($passwordBaru),
                    'first_login' => 1,
                    'is_active'   => 1,
                ]);
            });
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'Gagal memperbarui email & password.');
        }

        $log = $this->sendCredentials(
            $mitra->fresh(),
            EmailLog::JENIS_EDIT_EMAIL,
            $passwordBaru
        );

        $this->lastPlainPassword = $passwordBaru;

        return $this->redirectWithLog(
            $mitra->fresh(),
            $log,
            'Email berhasil diperbarui dan kredensial dikirim'
        );
    }

    /**
     * ================================================
     * AKSI 3 — RESET PASSWORD LALU KIRIM
     * ================================================
     */
    public function resetPassword(Mitra $mitra)
    {
        $this->authorizeMitra($mitra);

        $tempat = $mitra->tempatPkl;
        $user   = $mitra->user;

        if (!$tempat || !$user) {
            return back()->with('error', 'Data mitra tidak lengkap.');
        }

        if (empty($tempat->email)) {
            return back()->with(
                'error',
                'Email instansi belum tersedia. Gunakan aksi "Edit Email & Kirim" terlebih dahulu.'
            );
        }

        $passwordBaru = $this->generatePassword();

        try {
            DB::transaction(function () use ($user, $passwordBaru) {
                $user->update([
                    'password'    => Hash::make($passwordBaru),
                    'first_login' => 1,
                    'is_active'   => 1,
                ]);
            });
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'Gagal mereset password.');
        }

        $log = $this->sendCredentials(
            $mitra,
            EmailLog::JENIS_RESET_PASSWORD,
            $passwordBaru
        );

        $this->lastPlainPassword = $passwordBaru;

        return $this->redirectWithLog(
            $mitra,
            $log,
            'Password berhasil direset dan kredensial dikirim'
        );
    }

    /* =========================================================
     * PRIVATE HELPERS
     * ========================================================= */

    /**
     * Kirim email kredensial & catat ke email_logs.
     * SELALU mengembalikan EmailLog (tidak throw).
     */
    private function sendCredentials(
        Mitra $mitra,
        string $jenis,
        string $passwordPlaintext
    ): EmailLog {
        $tempat = $mitra->tempatPkl;
        $user   = $mitra->user;

        $email  = $tempat?->email;
        $status = EmailLog::STATUS_FAILED;
        $error  = null;

        try {
            if (empty($email)) {
                throw new \RuntimeException('Email instansi kosong.');
            }

            if (!$user) {
                throw new \RuntimeException('User mitra tidak ditemukan.');
            }

            Mail::to($email)->send(
                new AkunMitraResendMail(
                    namaTempat: $tempat->nama_tempat,
                    username:   $user->username,
                    password:   $passwordPlaintext,
                    jenis:      $jenis,
                    namaStaff:  auth()->user()?->getNama(),
                )
            );

            $status = EmailLog::STATUS_SENT;

        } catch (Throwable $e) {
            $status = EmailLog::STATUS_FAILED;
            $error  = $e->getMessage();

            Log::error('Gagal mengirim email kredensial mitra.', [
                'mitra_id'  => $mitra->id,
                'email'     => $email,
                'jenis'     => $jenis,
                'error'     => $error,
            ]);
        }

        return EmailLog::create([
            'mitra_id'      => $mitra->id,
            'tempat_pkl_id' => $mitra->tempat_pkl_id,
            'email_tujuan'  => $email ?? '(kosong)',
            'jenis'         => $jenis,
            'status'        => $status,
            'error_message' => $error,
            'triggered_by'  => auth()->user()?->getKey(),
        ]);
    }

    /**
     * Redirect kembali dengan pesan sesuai status log.
     */
    private function redirectWithLog(Mitra $mitra, EmailLog $log, string $successMessage)
    {
        $route = route('staff.mitra.kredensial.show', $mitra->id);

        if ($log->status === EmailLog::STATUS_SENT) {
            return redirect($route)
                ->with('success', $successMessage . ' ke ' . $log->email_tujuan . '.')
                ->with('password_baru', $this->lastPlainPassword);  // ← tambah
        }

        return redirect($route)->with(
            'warning',
            'Perubahan tersimpan, tetapi email gagal dikirim. Error: ' . ($log->error_message ?? 'tidak diketahui')
        );
    }
    /**
     * Generate password plaintext.
     * Konsisten dengan alur lama: Str::random(12).
     */
    private function generatePassword(): string
    {
        return Str::random(12);
    }

    /**
     * Pastikan staff hanya bisa akses mitra di prodi-nya sendiri.
     */
    private function authorizeMitra(Mitra $mitra): void
    {
        $staff   = auth()->user()->staff;
        $prodiId = $staff?->prodi_id;

        if (!$prodiId) {
            abort(403, 'Staff tidak memiliki prodi.');
        }

        $isInProdi = $mitra->tempatPkl()
            ->whereHas('pengajuans', function ($q) use ($prodiId) {
                $q->where('status', 'disetujui')
                    ->whereHas('mahasiswa', function ($m) use ($prodiId) {
                        $m->where('prodi_id', $prodiId);
                    });
            })
            ->exists();

        if (!$isInProdi) {
            abort(403, 'Anda tidak memiliki akses ke mitra ini.');
        }
    }
}