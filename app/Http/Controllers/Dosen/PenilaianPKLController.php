<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenilaianPKLController extends Controller
{
    /**
     * Halaman daftar mahasiswa yang bisa dinilai
     */
    public function index()
    {
        // nanti diisi data mahasiswa bimbingan
        return view('dosen.penilaian.index');
    }

    /**
     * Form penilaian mahasiswa
     */
    public function create($mahasiswaId)
    {
        // nanti ambil data mahasiswa berdasarkan ID
        return view('dosen.penilaian.form', compact('mahasiswaId'));
    }

    /**
     * Simpan nilai PKL
     */
    public function store(Request $request, $mahasiswaId)
    {
        $request->validate([
            'nilai'   => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);

        // LOGIC SIMPAN NILAI (nanti, setelah ada tabel)
        // PenilaianPKL::create([...]);

        return redirect()
            ->route('dosen.penilaian.index')
            ->with('success', 'Nilai PKL berhasil disimpan');
    }
}