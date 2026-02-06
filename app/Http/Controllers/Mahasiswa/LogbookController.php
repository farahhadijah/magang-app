<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogbookController extends Controller
{
    public function index()
    {
        // nanti: ambil data logbook dari DB
        return view('mahasiswa.logbook.index');
    }

    public function create()
    {
        return view('mahasiswa.logbook.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'    => 'required|date',
            'kegiatan'   => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        // TODO:
        // simpan ke tabel logbook
        // relasikan dengan mahasiswa & pengajuan PKL

        return redirect()
            ->route('mahasiswa.logbook.index')
            ->with('success', 'Logbook berhasil ditambahkan.');
    }
}