<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pkl;
use Illuminate\Support\Facades\Auth;

class MahasiswaBimbinganController extends Controller
{
    public function index()
    {
        $dosen = Auth::user()->dosen;

        $pkls = Pkl::where('id_dosen', $dosen->id)
        ->where('status', 'aktif')
        ->with([
                'pengajuan.mahasiswa.prodi',
                'pengajuan.tempatPkl',
                'suratBalasan',
            ])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('dosen.mahasiswa-bimbingan', compact('pkls'));
    }
}
