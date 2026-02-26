<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Formulir;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FormulirController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        $formulir = Formulir::where('is_active', true)
            ->where(function ($query) use ($mahasiswa) {
                $query->where('prodi_id', $mahasiswa->prodi_id)
                      ->orWhereNull('prodi_id');
            })
            ->latest()
            ->get();

        return view('mahasiswa.formulir.index', compact('formulir'));
    }

    public function download($id)
    {
        $mahasiswa = Auth::user()->mahasiswa;

        $formulir = Formulir::where('id', $id)
            ->where('is_active', true)
            ->where(function ($query) use ($mahasiswa) {
                $query->where('prodi_id', $mahasiswa->prodi_id)
                      ->orWhereNull('prodi_id');
            })
            ->firstOrFail();

        return Storage::disk('public')->download($formulir->file_path);
    }
}