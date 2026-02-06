<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReviewLogbookController extends Controller
{
    public function index($mahasiswa)
    {
        // nanti:
        // 1. cek dosen login adalah pembimbing
        // 2. ambil logbook mahasiswa

        return view('dosen.logbook.index');
    }

    public function review(Request $request, $logbook)
    {
        $request->validate([
            'status'   => 'required|in:disetujui,revisi',
            'catatan' => 'nullable|string',
        ]);

        // TODO:
        // update status logbook
        // simpan catatan dosen

        return back()->with('success', 'Logbook berhasil direview.');
    }
}