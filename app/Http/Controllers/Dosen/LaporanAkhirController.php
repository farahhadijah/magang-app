<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pkl;
use App\Models\LaporanAkhir;

class LaporanAkhirController extends Controller
{
    private function getDosenId()
    {
        return auth()->user()->dosen->id ?? null;
    }

    /**
     * List laporan mahasiswa bimbingan
     */
    public function index()
    {
        $dosenId = $this->getDosenId();

        $pkls = Pkl::where('id_dosen', $dosenId)
            ->whereHas('laporanAkhir')
            ->with(['pengajuanPkl.mahasiswa', 'laporanAkhir'])
            ->get();

        return view('dosen.laporan.index', compact('pkls'));
    }

    /**
     * Detail laporan
     */
    public function show(Pkl $pkl)
    {
        if ($pkl->id_dosen !== $this->getDosenId()) {
            abort(403, 'Bukan mahasiswa bimbingan Anda.');
        }

        $laporan = $pkl->laporanAkhir;

        if (!$laporan) {
            abort(404);
        }

        return view('dosen.laporan.show', compact('pkl', 'laporan'));
    }

    /**
     * Approve laporan
     */
    public function approve(Request $request, Pkl $pkl)
    {
        if ($pkl->id_dosen !== $this->getDosenId()) {
            abort(403);
        }

        $laporan = $pkl->laporanAkhir;

        if (!$laporan) {
            abort(404);
        }

        if ($laporan->status_approve === 'approved') {
            return back()->with('warning', 'Laporan sudah disetujui.');
        }

        $request->validate([
            'catatan_dosen' => 'nullable|string|max:2000'
        ]);

        $laporan->update([
            'status_approve' => 'approved',
            'catatan_dosen' => $request->catatan_dosen,
        ]);

        return back()->with('success', 'Laporan berhasil disetujui.');
    }
}