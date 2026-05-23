<?php

namespace App\Http\Controllers;

use App\Models\PenilaianMitra;

class VerifikasiPenilaianController extends Controller
{
    public function show($token)
    {
        $penilaian = PenilaianMitra::with([
            'pkl.mahasiswa.prodi',
            // load pengajuanPkl -> tempatPkl to get instansi name
            'pkl.pengajuanPkl.tempatPkl'
        ])
        ->where('verification_token', $token)
        ->first();

        if (!$penilaian) {

            return view(
                'verifikasi.penilaian-invalid'
            );
        }

        return view(
            'verifikasi.penilaian',
            compact('penilaian')
        );
    }
}