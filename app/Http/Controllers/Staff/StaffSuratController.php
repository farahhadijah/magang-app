<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPkl;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

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
        // Ambil hanya dari prodi staff yang login.
        $pengajuan = $this->pengajuanProdiQuery()
            ->with('mahasiswa')
            ->findOrFail($id);

        $pengajuan->update([

            'status_surat' => 'siap_diambil',

            'pesan_surat' =>
                'Surat pengantar PKL Anda telah selesai diproses dan sudah ditandatangani rektor. Silakan mengambil surat di Tata Usaha (TU).',

        ]);

        $waList = [];

        if ($pengajuan->mahasiswa && $pengajuan->mahasiswa->no_hp) {

            $nomor = $this->formatWa($pengajuan->mahasiswa->no_hp);

            $pesan = "Halo {$pengajuan->mahasiswa->nama}, surat pengantar PKL Anda telah selesai diproses dan sudah ditandatangani rektor. Silakan mengambil surat di Tata Usaha (TU).";

            $waList[] = [
                'nama' => $pengajuan->mahasiswa->nama,
                'nomor' => $nomor,
                'link' => 'https://wa.me/' . $nomor . '?text=' . urlencode($pesan),
            ];
        }

        return redirect()->back()->with([
            'success' => 'Validasi berhasil dikirim ke mahasiswa.',
            'wa_list' => $waList,
        ]);
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
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'Tidak ada mahasiswa dipilih.');
        }

        $this->pastikanIdsMilikProdi($ids);

        $pengajuanList = $this->pengajuanProdiQuery()
            ->with('mahasiswa')
            ->whereIn('id', $ids)
            ->get();

        foreach ($pengajuanList as $pengajuan) {
            $pengajuan->update([
                'status_surat' => 'siap_diambil',
                'pesan_surat' => 'Surat pengantar PKL Anda telah selesai diproses dan sudah ditandatangani rektor. Silakan mengambil surat di Tata Usaha (TU).',
            ]);
        }

        $waList = [];

        foreach ($pengajuanList as $item) {
            if (!$item->mahasiswa || !$item->mahasiswa->no_hp) {
                continue;
            }

            $nomor = $this->formatWa($item->mahasiswa->no_hp);

            // Lewati nomor yang tidak valid.
            if (!$nomor) {
                continue;
            }

            $pesan = "Halo {$item->mahasiswa->nama}, surat pengantar PKL Anda telah selesai diproses dan sudah ditandatangani rektor. Silakan mengambil surat di Tata Usaha (TU).";

            $waList[] = [
                'nama' => $item->mahasiswa->nama,
                'nomor' => $nomor,
                'link' => 'https://wa.me/' . $nomor . '?text=' . urlencode($pesan),
            ];
        }

        return back()->with([
            'success' => 'Validasi berhasil dikirim ke mahasiswa terpilih.',
            'wa_list' => $waList,
        ]);
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

}