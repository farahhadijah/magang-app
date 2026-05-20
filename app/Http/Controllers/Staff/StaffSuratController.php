<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPkl;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class StaffSuratController extends Controller
{
    public function index()
    {
        $data = PengajuanPkl::with([
            'mahasiswa',
            'tempatPkl',
            'pkl'
        ])
        ->where('status', 'disetujui')

        // sembunyikan PKL selesai
        ->whereDoesntHave('pkl', function ($q) {
            $q->where('status', 'selesai');
        })

        ->latest()
        ->paginate(10);

        return view('staff.surat.index', compact('data'));
    }
    public function cetak($id)
    {
        $pengajuan = PengajuanPkl::with([
            'mahasiswa.prodi.fakultas',
            'tempatPkl'
        ])->findOrFail($id);

        $noSurat = '0' . $pengajuan->id . '/UNISLA/PKL/' . date('Y');

        $pdf = Pdf::loadView('surat.pengantar', compact('pengajuan', 'noSurat'))
            ->setPaper([0,0,595,935], 'portrait');

        return $pdf->stream();
    }
    public function validasi($id)
    {
        $pengajuan = PengajuanPkl::with('mahasiswa')
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
        $pengajuan = PengajuanPkl::with([
            'mahasiswa.prodi.fakultas',
            'tempatPkl'
        ])->findOrFail($id);

        $noSurat = '0' . $pengajuan->id . '/UNISLA/PKL/' . date('Y');

        return Pdf::loadView('surat.pengantar', compact('pengajuan','noSurat'))
            ->setPaper([0,0,595,935], 'portrait') // WAJIB SAMA
            ->stream();
    }

    public function bulkPrint(Request $request)
    {
        $ids = $request->ids ?? [];

        $data = PengajuanPkl::with([
            'mahasiswa.prodi.fakultas',
            'tempatPkl'
        ])
        ->whereIn('id', $ids)
        ->get();

        if ($data->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data terpilih atau surat tidak tersedia untuk dicetak.');
        }

        $pdf = Pdf::loadView('staff.surat.bulk_fixed', compact('data'))
            ->setPaper([0,0,595,935], 'portrait');

        return $pdf->stream('Bulk_Surat_PKL.pdf');
    }
    public function bulkValidasi(Request $request)
    {
        $ids = $request->ids ?? [];

        if (empty($ids)) {
            return back()->with('error', 'Tidak ada mahasiswa dipilih.');
        }

        $pengajuanList = PengajuanPkl::with('mahasiswa')
            ->whereIn('id', $ids)
            ->get();

        foreach ($pengajuanList as $pengajuan) {

            $pengajuan->update([

                'status_surat' => 'siap_diambil',

                'pesan_surat' =>
                    'Surat pengantar PKL Anda telah selesai diproses dan sudah ditandatangani rektor. Silakan mengambil surat di Tata Usaha (TU).',

            ]);
        }

        // =========================
        // GENERATE LINK WHATSAPP
        // =========================
        $waList = [];

        foreach ($pengajuanList as $item) {

            if (!$item->mahasiswa || !$item->mahasiswa->no_hp) {
                continue;
            }

            $nomor = $this->formatWa($item->mahasiswa->no_hp);

            $pesan = "Halo {$item->mahasiswa->nama}, surat pengantar PKL Anda telah selesai diproses dan sudah ditandatangani rektor. Silakan mengambil surat di Tata Usaha (TU).";

            $linkWa = 'https://wa.me/' . $nomor . '?text=' . urlencode($pesan);

            $waList[] = [
                'nama' => $item->mahasiswa->nama,
                'nomor' => $nomor,
                'link' => $linkWa,
            ];
        }

        return back()->with([
            'success' => 'Validasi berhasil dikirim ke mahasiswa terpilih.',
            'wa_list' => $waList,
        ]);
    }
    public function bulkPreview(Request $request)
    {
        $ids = $request->ids ?? [];

        $data = PengajuanPkl::with([
            'mahasiswa.prodi.fakultas',
            'tempatPkl'
        ])
        ->whereIn('id', $ids)
        ->get();

        if ($data->isEmpty()) {
            abort(404);
        }

        $pdf = Pdf::loadView('staff.surat.bulk_fixed', compact('data'))
            ->setPaper([0,0,595,935], 'portrait');

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
}