<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Pkl;

class NilaiController extends Controller
{
    public function index()
    {
        // Ambil data PKL beserta mahasiswa dan nilai jika ada
        $pkls = Pkl::with('pengajuan.mahasiswa')->get();

        return view('kaprodi.nilai.index', compact('pkls'));
    }
}