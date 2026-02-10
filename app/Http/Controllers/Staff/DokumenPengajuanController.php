<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DokumenPengajuan;
use Illuminate\Http\Request;

class DokumenPengajuanController extends Controller
{
    /**
     * ===============================
     * TANDAI DOKUMEN VALID
     * ===============================
     */
    public function valid($id)
{
    $dokumen = DokumenPengajuan::with('pengajuan')->findOrFail($id);

    if ($dokumen->pengajuan->status !== 'pending_tu') {
        return redirect()
            ->back()
            ->withFragment('dokumen-' . $dokumen->id)
            ->with(
                'warning',
                'Dokumen tidak dapat diverifikasi karena verifikasi TU telah selesai.'
            );
    }

    $dokumen->tandaiValid();

    return redirect()
        ->back()
        ->withFragment('dokumen-' . $dokumen->id)
        ->with('success', 'Dokumen berhasil ditandai VALID.');
}


    /**
     * ===============================
     * TANDAI DOKUMEN INVALID
     * ===============================
     */
    public function invalid(Request $request, $id)
{
    $request->validate([
        'catatan' => 'required|string|max:255',
    ]);

    $dokumen = DokumenPengajuan::with('pengajuan')->findOrFail($id);

    if ($dokumen->pengajuan->status !== 'pending_tu') {
        return redirect()
            ->back()
            ->withFragment('dokumen-' . $dokumen->id)
            ->with(
                'warning',
                'Dokumen tidak dapat diverifikasi karena verifikasi TU telah selesai.'
            );
    }

    $dokumen->tandaiInvalid($request->catatan);

    return redirect()
        ->back()
        ->withFragment('dokumen-' . $dokumen->id)
        ->with('warning', 'Dokumen ditandai INVALID.');
}

}