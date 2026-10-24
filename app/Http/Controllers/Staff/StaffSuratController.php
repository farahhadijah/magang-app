<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Mail\SuratSiapDiambilMail;
use App\Models\PengajuanPkl;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class StaffSuratController extends Controller
{
    
    public function index()
    {
        $data = $this->pengajuanProdiQuery()
            ->with([
                'mahasiswa',
                'tempatPkl',
                'pkl.suratPengantar',
            ])
            ->where('status', 'disetujui')

            // Sembunyikan PKL yang sudah selesai.
            ->whereDoesntHave('pkl', function ($q) {
                $q->where('status', 'selesai');
            })

            ->latest()
            ->paginate(10);

        return view('staff.surat.index', compact('data'));
    }

    public function cetak($id)
    {
        $this->pengajuanProdiQuery()->findOrFail($id);

        return $this->serveSuratPdf((int) $id, inline: false);
    }

    
    public function validasi($id)
    {
        $pengajuan = $this->pengajuanProdiQuery()
            ->with('mahasiswa')
            ->where('status', 'disetujui')
            ->whereDoesntHave('pkl', function ($q) {
                $q->where('status', 'selesai');
            })
            ->findOrFail($id);

        if ($pengajuan->status_surat !== 'sudah_dicetak') {
            return back()->with(
                'error',
                'Validasi ditolak. Tandai surat sudah dicetak terlebih dahulu.'
            );
        }

        // Validasi surat dan simpan pesan dashboard terlebih dahulu.
        $pengajuan->update([
            'status_surat' => 'siap_diambil',
            'pesan_surat' => 'Surat pengantar PKL Anda telah selesai diproses dan sudah ditandatangani rektor. Silakan mengambil surat di Tata Usaha (TU).',
        ]);

        // Email hanya sebagai pemberitahuan tambahan.
        $hasil = $this->kirimEmailSuratSiapDiambil($pengajuan);

        if ($hasil['status'] === 'sent') {
            return back()->with(
                'success',
                'Validasi berhasil. Pesan dashboard mahasiswa telah diperbarui dan email pemberitahuan berhasil dikirim.'
            );
        }

        if ($hasil['status'] === 'skipped') {
            return back()->with(
                'success',
                'Validasi berhasil. Pesan sudah tersedia di dashboard mahasiswa, tetapi email tidak dikirim karena alamat email kosong atau tidak valid.'
            );
        }

        return back()->with(
            'success',
            'Validasi berhasil. Pesan sudah tersedia di dashboard mahasiswa, tetapi pengiriman email gagal. Mahasiswa tetap dapat melihat pemberitahuan di dashboard.'
        );
    }


    public function preview($id)
    {
        $pengajuan = $this->pengajuanProdiQuery()
            ->with([
                'mahasiswa.prodi.fakultas',
                'tempatPkl',
            ])
            ->findOrFail($id);

        $noSurat = '0' . $pengajuan->id . '/UNISLA/PKL/' . date('Y');

        return Pdf::loadView('surat.pengantar', compact('pengajuan', 'noSurat'))
            ->setPaper([0, 0, 595, 935], 'portrait')
            ->stream();
    }

    public function bulkPrint(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'Tidak ada mahasiswa dipilih.');
        }

        $this->pastikanIdsMilikProdi($ids);

        $data = $this->pengajuanProdiQuery()
            ->with([
                'mahasiswa.prodi.fakultas',
                'tempatPkl',
            ])
            ->whereIn('id', $ids)
            ->get();

        if ($data->isEmpty()) {
            return back()->with(
                'error',
                'Tidak ada data terpilih atau surat tidak tersedia untuk dicetak.'
            );
        }

        $pdf = Pdf::loadView('staff.surat.bulk_fixed', compact('data'))
            ->setPaper([0, 0, 595, 935], 'portrait');

        return $pdf->stream('Bulk_Surat_PKL.pdf');
    }

        
    public function bulkValidasi(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|distinct',
        ]);

        $ids = array_map('intval', $validated['ids']);

        $this->pastikanIdsMilikProdi($ids);

        $pengajuanList = $this->pengajuanProdiQuery()
            ->with('mahasiswa')
            ->where('status', 'disetujui')
            ->whereDoesntHave('pkl', function ($q) {
                $q->where('status', 'selesai');
            })
            ->whereIn('id', $ids)
            ->get();

        $berhasilValidasi = 0;
        $emailTerkirim = 0;
        $emailDilewati = 0;
        $emailGagal = 0;
        $belumDicetak = 0;
        $sudahDivalidasi = 0;

        foreach ($pengajuanList as $pengajuan) {
            // Jangan memvalidasi ulang surat yang sudah siap diambil.
            if ($pengajuan->status_surat === 'siap_diambil') {
                $sudahDivalidasi++;
                continue;
            }

            // Surat harus sudah ditandai dicetak.
            if ($pengajuan->status_surat !== 'sudah_dicetak') {
                $belumDicetak++;
                continue;
            }

            // Selesaikan validasi terlebih dahulu.
            $pengajuan->update([
                'status_surat' => 'siap_diambil',
                'pesan_surat' => 'Surat pengantar PKL Anda telah selesai diproses dan sudah ditandatangani rektor. Silakan mengambil surat di Tata Usaha (TU).',
            ]);

            $berhasilValidasi++;

            // Coba kirim email, tetapi hasilnya tidak membatalkan validasi.
            $hasil = $this->kirimEmailSuratSiapDiambil($pengajuan);

            if ($hasil['status'] === 'sent') {
                $emailTerkirim++;
            } elseif ($hasil['status'] === 'skipped') {
                $emailDilewati++;
            } else {
                $emailGagal++;
            }
        }

        $pesan = "{$berhasilValidasi} surat berhasil divalidasi.";
        $pesan .= " {$emailTerkirim} email berhasil dikirim.";

        if ($emailDilewati > 0) {
            $pesan .= " {$emailDilewati} email dilewati karena alamat kosong atau tidak valid.";
        }

        if ($emailGagal > 0) {
            $pesan .= " {$emailGagal} email gagal dikirim; pemberitahuan dashboard tetap tersimpan.";
        }

        if ($belumDicetak > 0) {
            $pesan .= " {$belumDicetak} surat dilewati karena belum ditandai dicetak.";
        }

        if ($sudahDivalidasi > 0) {
            $pesan .= " {$sudahDivalidasi} surat sebelumnya sudah divalidasi.";
        }

        $jumlahTidakMemenuhiSyarat = count($ids) - $pengajuanList->count();

        if ($jumlahTidakMemenuhiSyarat > 0) {
            $pesan .= " {$jumlahTidakMemenuhiSyarat} data dilewati karena pengajuan tidak memenuhi syarat.";
        }

        if ($berhasilValidasi === 0) {
            return back()->with('warning', $pesan);
        }

        return back()->with('success', $pesan);
    }

    
    public function bulkPreview(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            abort(404, 'Tidak ada data yang dipilih.');
        }

        $this->pastikanIdsMilikProdi($ids);

        $data = $this->pengajuanProdiQuery()
            ->with([
                'mahasiswa.prodi.fakultas',
                'tempatPkl',
            ])
            ->whereIn('id', $ids)
            ->get();

        if ($data->isEmpty()) {
            abort(404);
        }

        $pdf = Pdf::loadView('staff.surat.bulk_fixed', compact('data'))
            ->setPaper([0, 0, 595, 935], 'portrait');

        return $pdf->stream('Preview_Bulk_Surat.pdf');
    }
    private function formatWa($nohp)
    {
        // hapus semua karakter selain angka
        $nohp = preg_replace('/\D/', '', trim($nohp));

        // jika kosong
        if (!$nohp) {
            return null;
        }

        /*
        FORMAT:
        08123xxxx  -> 628123xxxx
        8123xxxxx  -> 628123xxxx
        628123xxxx -> tetap
        */

        // jika diawali 0
        if (str_starts_with($nohp, '0')) {

            $nohp = '62' . substr($nohp, 1);

        }

        // jika diawali 8
        elseif (str_starts_with($nohp, '8')) {

            $nohp = '62' . $nohp;

        }

        // jika tidak diawali 62
        elseif (!str_starts_with($nohp, '62')) {

            return null;
        }

        return $nohp;
    }

    
    /**
     * Kirim email pemberitahuan surat siap diambil secara langsung
     * (bukan queue), agar tidak tertahan jika queue worker tidak berjalan.
     *
     * @return array{status: string, message: ?string}
     */
    
    private function kirimEmailSuratSiapDiambil(PengajuanPkl $pengajuan): array
    {
        $pengajuan->loadMissing('mahasiswa');

        $email = strtolower(trim(
            (string) ($pengajuan->mahasiswa->email ?? '')
        ));

        // Email bukan syarat validasi surat.
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'status' => 'skipped',
                'message' => 'Email kosong atau format email tidak valid.',
            ];
        }

        try {
            Mail::to($email)->send(
                new SuratSiapDiambilMail($pengajuan)
            );

            return [
                'status' => 'sent',
                'message' => null,
            ];
        } catch (Throwable $e) {
            Log::error('Gagal mengirim email validasi surat pengantar.', [
                'pengajuan_id' => $pengajuan->id,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return [
                'status' => 'failed',
                'message' => 'Pengiriman email gagal.',
            ];
        }
    }


    /**
     * Mengambil prodi milik staff yang sedang login.
     */
    private function getProdiId(): int
    {
        $prodiId = auth()->user()?->staff?->prodi_id;

        abort_if(!$prodiId, 403, 'Data prodi staff tidak ditemukan.');

        return (int) $prodiId;
    }

    /**
     * Query pengajuan PKL yang mahasiswanya berasal
     * dari prodi staff yang sedang login.
     */
    private function pengajuanProdiQuery()
    {
        $prodiId = $this->getProdiId();

        return PengajuanPkl::whereHas('mahasiswa', function ($query) use ($prodiId) {
            $query->where('prodi_id', $prodiId);
        });
    }

    /**
     * Memastikan seluruh ID yang dipilih termasuk
     * dalam prodi staff yang sedang login.
     */
    private function pastikanIdsMilikProdi(array $ids): void
    {
        $ids = array_unique(array_map('intval', $ids));

        if (empty($ids)) {
            abort(404, 'Tidak ada data yang dipilih.');
        }

        $jumlahValid = $this->pengajuanProdiQuery()
            ->whereIn('id', $ids)
            ->count();

        abort_if(
            $jumlahValid !== count($ids),
            403,
            'Terdapat data surat dari prodi lain.'
        );
    }

    public function tandaiDicetak($id)
    {
        $pengajuan = $this->pengajuanProdiQuery()
            ->where('status', 'disetujui')
            ->whereDoesntHave('pkl', function ($q) {
                $q->where('status', 'selesai');
            })
            ->findOrFail($id);

        if ($pengajuan->status_surat === 'siap_diambil') {
            return back()->with(
                'warning',
                'Surat sudah divalidasi sebelumnya.'
            );
        }

        if ($pengajuan->status_surat === 'sudah_dicetak') {
            return back()->with(
                'warning',
                'Surat sudah ditandai dicetak.'
            );
        }

        $pengajuan->update([
            'status_surat' => 'sudah_dicetak',
        ]);

        return back()->with(
            'success',
            'Surat berhasil ditandai sudah dicetak dan siap divalidasi.'
        );
    }

    public function bulkTandaiDicetak(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|distinct',
        ]);

        $ids = array_map('intval', $validated['ids']);

        // Pastikan semua ID berasal dari prodi staff yang login.
        $this->pastikanIdsMilikProdi($ids);

        // Ambil hanya pengajuan yang disetujui dan PKL belum selesai.
        $pengajuanList = $this->pengajuanProdiQuery()
            ->where('status', 'disetujui')
            ->whereDoesntHave('pkl', function ($q) {
                $q->where('status', 'selesai');
            })
            ->whereIn('id', $ids)
            ->get();

        $berhasil = 0;
        $sudahDicetak = 0;
        $sudahDivalidasi = 0;
        $tidakMemenuhiSyarat = count($ids) - $pengajuanList->count();

        foreach ($pengajuanList as $pengajuan) {
            // Jangan mengubah surat yang sudah divalidasi.
            if ($pengajuan->status_surat === 'siap_diambil') {
                $sudahDivalidasi++;
                continue;
            }

            // Jangan memproses surat yang sudah ditandai dicetak.
            if ($pengajuan->status_surat === 'sudah_dicetak') {
                $sudahDicetak++;
                continue;
            }

            $pengajuan->update([
                'status_surat' => 'sudah_dicetak',
            ]);

            $berhasil++;
        }

        $pesan = "{$berhasil} surat berhasil ditandai sudah dicetak.";

        if ($sudahDicetak > 0) {
            $pesan .= " {$sudahDicetak} surat dilewati karena sudah ditandai dicetak.";
        }

        if ($sudahDivalidasi > 0) {
            $pesan .= " {$sudahDivalidasi} surat dilewati karena sudah divalidasi.";
        }

        if ($tidakMemenuhiSyarat > 0) {
            $pesan .= " {$tidakMemenuhiSyarat} data dilewati karena pengajuan tidak disetujui atau PKL sudah selesai.";
        }

        if ($berhasil === 0) {
            return back()->with('warning', $pesan);
        }

        if (
            $sudahDicetak > 0 ||
            $sudahDivalidasi > 0 ||
            $tidakMemenuhiSyarat > 0
        ) {
            return back()->with('warning', $pesan);
        }

        return back()->with('success', $pesan);
    }


}