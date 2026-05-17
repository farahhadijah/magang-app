<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Pkl;

class NilaiMitraMahasiswaController extends MahasiswaBaseController
{
    public function index()
    {
        $mahasiswa = auth()->user()->mahasiswa;

        $pengajuan = $mahasiswa->pengajuanPkl()
            ->latest()
            ->first();

        $pkl = null;

        if ($pengajuan && $pengajuan->pkl) {

            $pkl = Pkl::with([
                'penilaianMitra'
            ])->find($pengajuan->pkl->id);
        }

        return view(
            'mahasiswa.penilaianMitra.index',
            compact('pkl')
        );
    }
}