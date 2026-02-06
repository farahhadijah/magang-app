<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengajuanPKLController extends Controller
{
    public function index()
    {
        // nanti ambil dari database
        return view('staff.pengajuan.index');
    }

    public function show($id)
    {
        // nanti ambil detail pengajuan berdasarkan ID
        return view('staff.pengajuan.show', compact('id'));
    }

    public function approve($id)
    {
        // logic set status = disetujui
        return redirect()
            ->route('staff.pengajuan.index')
            ->with('success', 'Pengajuan PKL disetujui');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string',
        ]);

        // logic set status = ditolak + simpan catatan
        return redirect()
            ->route('staff.pengajuan.index')
            ->with('success', 'Pengajuan PKL ditolak');
    }
}