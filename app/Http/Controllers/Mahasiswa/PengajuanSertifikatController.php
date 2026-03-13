<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\PengajuanSertifikat;
use App\Models\Pkl;
use Illuminate\Support\Facades\Auth;

class PengajuanSertifikatController extends MahasiswaBaseController
{

    public function index()
    {
        $user = Auth::user();

        // cek status mitra (hanya untuk informasi UI)
        $cekMitra = $this->cekMitraAktif($user->mahasiswa_id);

        $pkl = Pkl::whereHas('pengajuanPkl', function ($q) use ($user) {
            $q->where('id_mhs', $user->mahasiswa_id);
        })
        ->with('pengajuanPkl.mahasiswa')
        ->first();

        $pengajuan = null;

        if ($pkl) {
            $pengajuan = PengajuanSertifikat::where('pkl_id', $pkl->id)->first();
        }

        return view('mahasiswa.sertifikat.index', compact(
            'pkl',
            'pengajuan',
            'cekMitra'
        ));
    }


    public function store()
    {
        $user = Auth::user();

        // validasi mitra (blokir jika belum punya akun)
        $cek = $this->cekMitraAktif($user->mahasiswa_id);

        if (!$cek['status']) {
            return back()->with('error', $cek['message']);
        }

        $pkl = Pkl::whereHas('pengajuanPkl', function ($q) use ($user) {
            $q->where('id_mhs', $user->mahasiswa_id);
        })->first();

        if (!$pkl) {
            return back()->with('error','PKL tidak ditemukan.');
        }

        if (PengajuanSertifikat::where('pkl_id', $pkl->id)->exists()) {
            return back()->with('error','Pengajuan sertifikat sudah pernah dilakukan.');
        }

        PengajuanSertifikat::create([
            'pkl_id' => $pkl->id,
            'tanggal_pengajuan' => now(),
            'status' => 'pending'
        ]);

        return back()->with('success','Pengajuan sertifikat berhasil dikirim.');
    }

}