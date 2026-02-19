<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pkl;
use App\Models\LaporanAkhir;
use Illuminate\Support\Facades\Storage;

class LaporanAkhirController extends Controller
{
    private function getActivePkl()
    {
        $user = auth()->user();

        if (!$user || !$user->mahasiswa) {
            return null;
        }

        return Pkl::whereHas('pengajuanPkl', function ($q) use ($user) {
                $q->where('id_mhs', $user->mahasiswa->id);
            })
            ->whereIn('status', ['aktif', 'menunggu_laporan'])
            ->latest()
            ->first();
    }

    public function index()
    {
        $pkl = $this->getActivePkl();

        if (!$pkl) {
            return redirect()->route('mahasiswa.dashboard');
        }

        $laporan = $pkl->laporanAkhir;

        return view('mahasiswa.laporan.index', compact('pkl', 'laporan'));
    }

    public function create()
    {
        $pkl = $this->getActivePkl();

        if (!$pkl) {
            return back()->with('warning', 'Belum ada PKL aktif.');
        }

        if (!$pkl->isSiapUploadLaporan()) {
            return back()->with('warning', 'Belum memenuhi syarat upload laporan akhir.');
        }

        if ($pkl->laporanAkhir && $pkl->laporanAkhir->status_approve === 'approved') {
            return back()->with('warning', 'Laporan sudah disetujui dan tidak bisa diubah.');
        }

        return view('mahasiswa.laporan.create', compact('pkl'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:2048'
        ]);

        $pkl = $this->getActivePkl();

        if (!$pkl || !$pkl->isSiapUploadLaporan()) {
            abort(403, 'Tidak memenuhi syarat upload laporan.');
        }

        $existing = $pkl->laporanAkhir;

        if ($existing && $existing->status_approve === 'approved') {
            abort(403, 'Laporan sudah disetujui.');
        }

        $path = $request->file('file')->store('laporan_akhir', 'public');

        if ($existing) {

            Storage::disk('public')->delete($existing->path_file);

            $existing->update([
                'path_file'     => $path,
                'status_approve'=> 'pending',
                'catatan_dosen' => null,
                'approved_by'   => null,
                'approved_at'   => null,
            ]);

        } else {

            LaporanAkhir::create([
                'id_pkl'         => $pkl->id,
                'path_file'      => $path,
                'status_approve' => 'pending',
            ]);
        }

        // 🔥 Set status PKL jadi menunggu laporan
        $pkl->ajukanLaporan();

        return redirect()
            ->route('mahasiswa.laporan.index')
            ->with('success', 'Laporan akhir berhasil diupload.');
    }
}