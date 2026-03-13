<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSertifikat;

class SertifikatController extends Controller
{

    // ================= DOSEN =================
    public function indexDosen()
    {
        $user = auth()->user();
        $dosenId = $user->dosen->id;

        $data = PengajuanSertifikat::where('status', 'disetujui')
            ->whereHas('pkl', function ($q) use ($dosenId) {
                $q->where('id_dosen', $dosenId);
            })
            ->with([
                'pkl.pengajuanPkl.mahasiswa',
                'pkl.pengajuanPkl.tempatPkl'
            ])
            ->latest()
            ->get();

        return view('monitoring.sertifikat.index', compact('data'));
    }


    // ================= KAPRODI =================
    public function indexKaprodi()
    {
        $user = auth()->user();
        $prodiId = $user->dosen->prodi_id;

        $data = PengajuanSertifikat::where('status', 'disetujui')
            ->whereHas('pkl.pengajuanPkl.mahasiswa', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            })
            ->with([
                'pkl.pengajuanPkl.mahasiswa',
                'pkl.pengajuanPkl.tempatPkl'
            ])
            ->latest()
            ->get();

        return view('monitoring.sertifikat.index', compact('data'));
    }
}