<?php

namespace App\Http\Controllers\Dosen;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pkl;

class LaporanAkhirController extends Controller
{
    private function getDosenId()
    {
        return auth()->user()->dosen->id ?? null;
    }

public function index()
{
    $dosenId = $this->getDosenId();
    
    $pkls = Pkl::where('id_dosen', $dosenId)
        ->where('status', '!=', 'selesai')
        ->whereHas('laporanAkhir')
        ->with([
            'pengajuanPkl.mahasiswa',
            'laporanAkhir'
        ])
        ->latest()
        ->paginate(15); // pagination aktif

    return view('dosen.laporan.index', compact('pkls'));
}

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

    DB::transaction(function () use ($laporan, $pkl) {

        $laporan->approve(auth()->user()->dosen->id);
    });

    return back()->with(
        'success',
        'Laporan berhasil disetujui.'
    );
}

    public function reject(Request $request, Pkl $pkl)
    {
        if ($pkl->id_dosen !== $this->getDosenId()) {
            abort(403);
        }
        $laporan = $pkl->laporanAkhir;
        if (!$laporan) {
            abort(404);
        }
        $request->validate([
            'catatan_dosen' => 'required|string|max:2000'
        ]);
        $laporan->reject($this->getDosenId(), $request->catatan_dosen);
        return back()->with('success', 'Laporan ditolak dan mahasiswa dapat mengupload ulang.');
    }
}