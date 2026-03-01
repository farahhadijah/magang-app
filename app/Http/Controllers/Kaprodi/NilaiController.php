<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Pkl;

class NilaiController extends Controller
{
    public function index()
{
    $prodiId = auth()->user()->staff->prodi_id;

    $pkls = Pkl::where('status', 'selesai')
        ->whereHas('nilaiPkl')
        ->whereHas('pengajuan.mahasiswa', function ($q) use ($prodiId) {
            $q->where('prodi_id', $prodiId);
        })
        ->with([
            'pengajuan.mahasiswa',
            'nilaiPkl'
        ])
        ->orderByDesc('updated_at')
        ->paginate(10);

    return view('kaprodi.nilai.index', compact('pkls'));
}
}