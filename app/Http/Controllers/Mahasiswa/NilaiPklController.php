<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pkl;
use Illuminate\Support\Facades\Auth;

class NilaiPklController extends Controller
{
    public function index()
    {
        $mahasiswaId = Auth::user()->mahasiswa->id ?? null;

        $pkl = Pkl::with('nilaiPkl')
            ->whereHas('pengajuanPkl', function ($q) use ($mahasiswaId) {
                $q->where('id_mhs', $mahasiswaId);
            })
            ->where('status', 'selesai')
            ->latest()
            ->first();

        return view('mahasiswa.nilai.index', compact('pkl'));
    }
}