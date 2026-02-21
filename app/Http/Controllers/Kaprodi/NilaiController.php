<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Pkl;

class NilaiController extends Controller
{
    public function index()
    {
        $pkls = Pkl::where('status', 'selesai')
            ->whereHas('nilaiPkl') // hanya yang sudah dinilai
            ->with([
                'pengajuan.mahasiswa',
                'nilaiPkl'
            ])
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('kaprodi.nilai.index', compact('pkls'));
    }
}