<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswas = Mahasiswa::all(); // ambil semua mahasiswa
        return view('kaprodi.mahasiswa.index', compact('mahasiswas'));
    }
}