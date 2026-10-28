<?php
namespace App\Http\Controllers\Kaprodi;
use App\Http\Controllers\Controller;
use App\Mail\NotifikasiSuratPengantarStaffTU;
use App\Models\Dosen;
use App\Models\PengajuanPkl;
use App\Models\Pkl;
use App\Models\SuratPengantar;
use App\Models\User;
use App\Models\Mitra;
use App\Models\Verifikasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\StatusPengajuanMail;
use App\Mail\AkunMitraMail;
use App\Models\EmailLog;
use Illuminate\Support\Facades\Log;
class PengajuanPklController extends Controller
{
    private function kirimNotifikasiSuratPengantarStaffTU(int $prodiId): void
    {
        // Hitung surat pengantar yang belum dicetak dan masih perlu dikelola.
        $jumlahBelumDicetak = DB::table('surat_pengantar as sp')
            ->join('pkl as p', 'p.id', '=', 'sp.id_pkl')
            ->join('pengajuan_pkl as pp', 'pp.id', '=', 'p.id_pengajuan_pkl')
            ->join('mahasiswa as m', 'm.id', '=', 'pp.id_mhs')
            ->where('m.prodi_id', $prodiId)
            ->where('p.status', 'aktif')
            ->where(function ($query) {
                $query->whereNull('pp.status_surat')
                    ->orWhereNotIn('pp.status_surat', [
                        'sudah_dicetak',
                        'siap_diambil',
                    ]);
            })
            ->count();

        // Email hanya dikirim pada jumlah 3, 6, 9, dan seterusnya.
        if ($jumlahBelumDicetak === 0 || $jumlahBelumDicetak % 3 !== 0) {
            return;
        }

        // Ambil Staff TU aktif dari prodi yang bersangkutan.
        $staffTu = DB::table('staff')
            ->where('prodi_id', $prodiId)
            ->where('is_active', 1)
            ->whereNotNull('email')
            ->where('email', '<>', '')
            ->get(['id', 'nama', 'email']);

        foreach ($staffTu as $staff) {
            try {
                Mail::to($staff->email)->send(
                    new NotifikasiSuratPengantarStaffTU(
                        namaStaff: $staff->nama,
                        jumlahBelumDicetak: $jumlahBelumDicetak,
                    )
                );
            } catch (\Throwable $e) {
                Log::error(
                    'Gagal mengirim notifikasi surat pengantar kepada Staff TU.',
                    [
                        'staff_id' => $staff->id,
                        'email' => $staff->email,
                        'prodi_id' => $prodiId,
                        'jumlah_belum_dicetak' => $jumlahBelumDicetak,
                        'error' => $e->getMessage(),
                    ]
                );
            }
        }
    }

    public function index()
    {
        $prodiId = $this->getProdiId();
        $pengajuans = PengajuanPkl::with(['mahasiswa', 'tempatPkl'])
            ->munculUntukKaprodi()
            ->whereHas('mahasiswa', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('kaprodi.pengajuan.index', compact('pengajuans'));
    }

    public function show($id)
    {
        $dosenKaprodi = auth()->user()->dosen;
        $prodiId = $dosenKaprodi->prodi_id;
        $pengajuan = PengajuanPkl::query()
            ->whereKey($id)
            ->whereHas('mahasiswa', fn ($q) => $q->where('prodi_id', $prodiId)
            )
            ->with([
                'mahasiswa:id,nama,nim,prodi_id,angkatan',
                'mahasiswa.prodi:id,nama',
                'tempatPkl:id,nama_tempat,nama_normalized,jenis_tempat,email,lokasi_maps',
                'dokumenPengajuan:id,id_pengajuan_pkl,jenis_dokumen,path_file',
                'verifikasi:id,id_pengajuan_pkl,id_user,level,status,tgl_verifikasi',
                'verifikasi.user:id,username,role,staff_id,mahasiswa_id,dosen_id',
                'verifikasi.user.staff:id,nama',
                'verifikasi.user.mahasiswa:id,nama',
                'verifikasi.user.dosen:id,nama',
            ])
            ->firstOrFail();
        if (! $pengajuan->bisaDiverifikasiKaprodi()) {
            return redirect()
                ->route('kaprodi.pengajuan.index')
                ->with('warning', 'Pengajuan sudah diproses.');
        }
        $dosenList = Dosen::query()
            ->leftJoin('pkl', function ($join) {
                $join->on('dosen.id', '=', 'pkl.id_dosen')
                    ->where('pkl.status', 'aktif');
            })
            ->where('dosen.prodi_id', $prodiId)
            ->where('dosen.is_active', 1)
            ->select(
                'dosen.id',
                'dosen.nama',
                'dosen.keahlian',
                DB::raw('COUNT(pkl.id) as total_bimbingan')
            )
            ->groupBy('dosen.id', 'dosen.nama', 'dosen.keahlian')
            ->orderBy('dosen.nama')
            ->get();
        $jarak = null;
        $mapsUrl = $pengajuan->tempatPkl->lokasi_maps ?? null;
        $kampusLat = -7.1224094;
        $kampusLng = 112.4223971;
        $coords = $this->extractLatLng($mapsUrl);
        if ($coords) {
            $jarak = $this->hitungJarak(
                $kampusLat,
                $kampusLng,
                $coords['lat'],
                $coords['lng']
            );
        }
        $namaNormalized = $pengajuan->tempatPkl->nama_normalized ?? null;
        $namaTempat = $pengajuan->tempatPkl->nama_tempat ?? null;
        $angkatan = $pengajuan->mahasiswa->angkatan ?? null;
        $jumlahRiwayat = 0;
        $terakhirDigunakan = null;
        if (($namaNormalized || $namaTempat) && $angkatan !== null && $angkatan !== '') {
            $riwayat = PengajuanPkl::where('id', '!=', $pengajuan->id)
                ->whereHas('tempatPkl', function ($q) use ($namaNormalized, $namaTempat) {
                    if ($namaNormalized) {
                        $q->where('nama_normalized', $namaNormalized);
                    } else {
                        $q->where('nama_tempat', $namaTempat);
                    }
                })
                ->whereHas('mahasiswa', function ($q) use ($prodiId, $angkatan) {
                    $q->where('prodi_id', $prodiId)
                        ->where('angkatan', $angkatan);
                })
                ->whereHas('pkl', function ($q) {
                    $q->where('status', 'aktif');
                })
                ->selectRaw('COUNT(*) as total, MAX(created_at) as terakhir')
                ->first();
            $jumlahRiwayat = $riwayat->total ?? 0;
            if ($riwayat->terakhir) {
                $terakhirDigunakan = Carbon::parse($riwayat->terakhir);
            }
        }
        return view('kaprodi.pengajuan.show', compact(
            'pengajuan',
            'dosenList',
            'jarak',
            'jumlahRiwayat',
            'terakhirDigunakan'
        ));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'id_dosen' => 'required|exists:dosen,id',
        ]);
        $prodiId = $this->getProdiId();
        $pengajuan = PengajuanPkl::with([
            'mahasiswa.prodi',
            'tempatPkl',
            'pkl',
        ])
            ->whereHas('mahasiswa', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            })
            ->findOrFail($id);
        if (! $pengajuan->bisaDiverifikasiKaprodi()) {
            return back()->with(
                'warning',
                'Pengajuan sudah diproses.'
            );
        }
        if ($pengajuan->pkl) {
            return back()->with(
                'warning',
                'PKL sudah dibuat sebelumnya.'
            );
        }
        $dosen = Dosen::where('id', $request->id_dosen)
            ->where('is_active', 1)
            ->where('prodi_id', $prodiId)
            ->first();
        if (! $dosen) {
            return back()->with(
                'warning',
                'Dosen tidak valid atau bukan dari prodi yang sama.'
            );
        }
        $mitraBaru = null;
        $emailInstansiKosong = false;
        try {
            DB::transaction(function () use (
                $pengajuan,
                $dosen,
                $prodiId,
                &$mitraBaru,
                &$emailInstansiKosong
            ) {
                Verifikasi::create([
                    'id_pengajuan_pkl' => $pengajuan->id,
                    'id_user' => auth()->user()->getKey(),
                    'level' => 'kaprodi',
                    'status' => 'approved',
                    'tgl_verifikasi' => now(),
                ]);
                $pengajuan->update([
                    'status' => 'disetujui',
                ]);
                $pkl = Pkl::create([
                    'id_pengajuan_pkl' => $pengajuan->id,
                    'id_dosen' => $dosen->id,
                    'tgl_mulai' => now(),
                    'tgl_selesai' => null,
                    'status' => 'aktif',
                ]);
                $bulanRomawi = [
                    1 => 'I',
                    2 => 'II',
                    3 => 'III',
                    4 => 'IV',
                    5 => 'V',
                    6 => 'VI',
                    7 => 'VII',
                    8 => 'VIII',
                    9 => 'IX',
                    10 => 'X',
                    11 => 'XI',
                    12 => 'XII',
                ];
                $noSurat = sprintf(
                    '%03d/UNISLA/PKL/%s/%s',
                    SuratPengantar::count() + 1,
                    $bulanRomawi[now()->month],
                    now()->year
                );
                $kaprodi = Dosen::where('jabatan', 'Kaprodi')
                    ->where('prodi_id', $prodiId)
                    ->where('is_active', 1)
                    ->first();
                $pdf = Pdf::loadView('surat.pengantar', [
                    'pengajuan' => $pengajuan,
                    'pkl' => $pkl,
                    'noSurat' => $noSurat,
                    'kaprodi' => $kaprodi,
                ])->setPaper('A4', 'portrait');
                $path = 'surat/surat_pkl_' . $pkl->id . '.pdf';
                Storage::disk('public')->put(
                    $path,
                    $pdf->output()
                );
                SuratPengantar::create([
                    'id_pkl' => $pkl->id,
                    'no_surat' => $noSurat,
                    'tgl_terbit' => now(),
                    'path_file' => $path,
                ]);
                $tempat = $pengajuan->tempatPkl;
                if (
                    $tempat &&
                    ! Mitra::where('tempat_pkl_id', $tempat->id)->exists()
                ) {
                    $email = strtolower(trim((string) $tempat->email));
                    if ($email === '') {
                        $emailInstansiKosong = true;
                        Log::warning(
                            'Akun mitra belum dibuat karena email instansi kosong.',
                            [
                                'tempat_pkl_id' => $tempat->id,
                            ]
                        );
                        EmailLog::create([
                            'mitra_id' => null,
                            'tempat_pkl_id' => $tempat->id,
                            'email_tujuan' => '(kosong)',
                            'jenis' => EmailLog::JENIS_AKUN_BARU,
                            'status' => EmailLog::STATUS_PENDING,
                            'error_message' =>
                                'Email instansi belum tersedia saat persetujuan Kaprodi. '
                                . 'Staff TU perlu melengkapi email dan mengirim ulang.',
                            'triggered_by' => auth()->user()?->getKey(),
                        ]);
                    } else {
                        $baseUsername = 'mitra_' .
                            (Str::slug($tempat->nama_tempat, '_') ?: 'instansi');
                        $username = $baseUsername;
                        $counter = 1;
                        while (User::where('username', $username)->exists()) {
                            $username = $baseUsername . '_' . $counter;
                            $counter++;
                        }
                        $password = Str::random(12);
                        $user = User::create([
                            'username' => $username,
                            'password' => Hash::make($password),
                            'role' => 'mitra',
                            'first_login' => 1,
                        ]);
                        $mitra = Mitra::create([
                            'user_id' => $user->id,
                            'tempat_pkl_id' => $tempat->id,
                            'no_hp' => null,
                        ]);
                        $mitraBaru = [
                            'mitra_id' => $mitra->id,
                            'tempat_id' => $tempat->id,
                            'email' => $email,
                            'nama_tempat' => $tempat->nama_tempat,
                            'username' => $username,
                            'password' => $password,
                        ];
                    }
                }
            });
        } catch (\Throwable $e) {
            Log::error('Gagal menyetujui pengajuan PKL.', [
                'pengajuan_id' => $id,
                'error' => $e->getMessage(),
            ]);
            return back()->with(
                'error',
                'Persetujuan gagal diproses. Periksa log aplikasi untuk mengetahui penyebabnya.'
            );
        }
        
        // Notifikasi Staff TU setelah surat berhasil dibuat.
        $this->kirimNotifikasiSuratPengantarStaffTU($prodiId);

        $emailMitraBerhasil = false;
        if ($mitraBaru) {
            $statusEmailMitra = EmailLog::STATUS_FAILED;
            $errorEmailMitra = null;
            try {
                Mail::to($mitraBaru['email'])->send(
                    new AkunMitraMail(
                        $mitraBaru['nama_tempat'],
                        $mitraBaru['username'],
                        $mitraBaru['password']
                    )
                );
                $emailMitraBerhasil = true;
                $statusEmailMitra = EmailLog::STATUS_SENT;
            } catch (\Throwable $e) {
                $errorEmailMitra = $e->getMessage();
                Log::error('Gagal mengirim email akun mitra.', [
                    'pengajuan_id' => $id,
                    'email' => $mitraBaru['email'],
                    'error' => $e->getMessage(),
                ]);
            }
            try {
                EmailLog::create([
                    'mitra_id' => $mitraBaru['mitra_id'],
                    'tempat_pkl_id' => $mitraBaru['tempat_id'],
                    'email_tujuan' => $mitraBaru['email'],
                    'jenis' => EmailLog::JENIS_AKUN_BARU,
                    'status' => $statusEmailMitra,
                    'error_message' => $errorEmailMitra,
                    'triggered_by' => auth()->user()?->getKey(),
                ]);
            } catch (\Throwable $e) {
                Log::error('Gagal mencatat hasil email akun mitra.', [
                    'pengajuan_id' => $id,
                    'mitra_id' => $mitraBaru['mitra_id'],
                    'error' => $e->getMessage(),
                ]);
            }
        }
        try {
            $pengajuan->refresh()->load([
                'mahasiswa',
                'tempatPkl',
            ]);
            $emailMahasiswa = $pengajuan->mahasiswa->email ?? null;
            if ($emailMahasiswa) {
                Mail::to($emailMahasiswa)->send(
                    new StatusPengajuanMail($pengajuan)
                );
            } else {
                Log::warning('Email mahasiswa kosong saat persetujuan PKL.', [
                    'pengajuan_id' => $pengajuan->id,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error(
                'Gagal mengirim email persetujuan PKL kepada mahasiswa.',
                [
                    'pengajuan_id' => $pengajuan->id,
                    'error' => $e->getMessage(),
                ]
            );
        }
        $pesan = 'Pengajuan disetujui dan surat pengantar PKL berhasil diterbitkan.';
        if ($mitraBaru && $emailMitraBerhasil) {
            $pesan .= ' Akun mitra berhasil dibuat dan kredensial dikirim ke email instansi.';
            return redirect()
                ->route('kaprodi.pengajuan.index')
                ->with('success', $pesan);
        }
        if ($mitraBaru && ! $emailMitraBerhasil) {
            $pesan .= ' Akun mitra berhasil dibuat, tetapi pengiriman email gagal. Email perlu dikirim ulang.';
            return redirect()
                ->route('kaprodi.pengajuan.index')
                ->with('warning', $pesan);
        }
        if ($emailInstansiKosong) {
            $pesan .= ' Akun mitra belum dibuat karena email instansi belum tersedia.';
            return redirect()
                ->route('kaprodi.pengajuan.index')
                ->with('warning', $pesan);
        }
        return redirect()
            ->route('kaprodi.pengajuan.index')
            ->with('success', $pesan);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string|max:255',
        ]);
        $prodiId = $this->getProdiId();
        $pengajuan = PengajuanPkl::with([
            'mahasiswa',
            'tempatPkl',
        ])
            ->whereHas('mahasiswa', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            })
            ->findOrFail($id);
        if (! $pengajuan->bisaDiverifikasiKaprodi()) {
            return back()->with(
                'warning',
                'Pengajuan sudah diproses.'
            );
        }
        DB::transaction(function () use ($pengajuan, $request) {
            Verifikasi::create([
                'id_pengajuan_pkl' => $pengajuan->id,
                'id_user' => auth()->user()->getKey(),
                'level' => 'kaprodi',
                'status' => 'rejected',
                'catatan' => $request->catatan,
                'tgl_verifikasi' => now(),
            ]);
            $pengajuan->update([
                'status' => 'ditolak_kaprodi',
                'catatan_kaprodi' => $request->catatan,
            ]);
        });
        try {
            $pengajuan->refresh()->load([
                'mahasiswa',
                'tempatPkl',
            ]);
            $emailMahasiswa = $pengajuan->mahasiswa->email ?? null;
            if ($emailMahasiswa) {
                Mail::to($emailMahasiswa)->send(
                    new StatusPengajuanMail($pengajuan)
                );
            } else {
                Log::warning(
                    'Email mahasiswa kosong saat penolakan Kaprodi.',
                    ['pengajuan_id' => $pengajuan->id]
                );
            }
        } catch (\Throwable $e) {
            Log::error(
                'Gagal mengirim email penolakan Kaprodi.',
                [
                    'pengajuan_id' => $pengajuan->id,
                    'error' => $e->getMessage(),
                ]
            );
        }
        return redirect()
            ->route('kaprodi.pengajuan.index')
            ->with(
                'warning',
                'Pengajuan PKL berhasil ditolak Kaprodi.'
            );
    }

    public function histori()
    {
        $prodiId = $this->getProdiId();
        $pengajuans = PengajuanPkl::with([
            'mahasiswa',
            'tempatPkl',
            'verifikasi' => function ($q) {
                $q->where('level', 'kaprodi');
            },
        ])
            ->whereHas('mahasiswa', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            })
            ->whereHas('verifikasi', function ($q) {
                $q->where('level', 'kaprodi');
            })
            ->whereDoesntHave('pkl', function ($q) {
                $q->where('status', 'selesai');
            })
            ->orderByDesc('updated_at')
            ->paginate(15);
        return view('kaprodi.pengajuan.histori', compact('pengajuans'));
    }

    private function getProdiId()
    {
        $dosen = auth()->user()->dosen;
        if (! $dosen || ! $dosen->prodi_id) {
            abort(403, 'Kaprodi tidak memiliki prodi.');
        }
        return $dosen->prodi_id;
    }

    private function extractLatLng($url)
    {
        if (! $url) {
            return null;
        }
        if (preg_match('/q=(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $matches)) {
            return [
                'lat' => (float) $matches[1],
                'lng' => (float) $matches[2],
            ];
        }
        if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $matches)) {
            return [
                'lat' => (float) $matches[1],
                'lng' => (float) $matches[2],
            ];
        }
        return null;
    }

    private function hitungJarak($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}
