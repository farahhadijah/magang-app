<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
class MahasiswaBaseController extends Controller
{
    protected function cekMitraAktif($mahasiswaId)
{
    $pkl = \App\Models\Pkl::whereHas('pengajuanPkl', function ($q) use ($mahasiswaId) {
        $q->where('id_mhs', $mahasiswaId);
    })->with('pengajuanPkl.mahasiswa')->first();

    if (!$pkl) {
        return [
            'status' => false,
            'message' => 'Data PKL tidak ditemukan.'
        ];
    }

    $mitra = $pkl->pengajuanPkl->tempatPkl->mitra ?? null;

    if (!$mitra || !$mitra->user_id) {

        // ambil mahasiswa
        $mahasiswa = $pkl->pengajuanPkl->mahasiswa;

        // ambil staff berdasarkan prodi mahasiswa
        $staff = \App\Models\Staff::where('prodi_id', $mahasiswa->prodi_id)->first();

        $wa = $staff->no_hp ?? null;

        return [
            'status' => false,
            'message' => 'Akun mitra belum dibuat.',
            'wa' => $wa
        ];
    }

    return [
        'status' => true
    ];
}
}