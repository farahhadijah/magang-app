<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswas = Mahasiswa::whereHas('pengajuanPkl.pkl', function ($q) {
                $q->where('status', 'aktif');
            })
            ->with('prodi')
            ->orderBy('nama')
            ->paginate(9);

        return view('kaprodi.mahasiswa.index', compact('mahasiswas'));
    }
    public function belumMengajukan()
    {
        $mahasiswas = Mahasiswa::whereDoesntHave('pengajuanPkl', function ($q) {
                $q->whereNotIn('status', ['ditolak_tu', 'ditolak_kaprodi']);
            })
            ->with(['prodi', 'pengajuanPkl'])
            ->orderBy('nama')
            ->paginate(15);

        return view('kaprodi.mahasiswa.belum', compact('mahasiswas'));
    }
}