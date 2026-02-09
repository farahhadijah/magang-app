<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Logbook;

class ReviewLogbookController extends Controller
{
    /**
     * Halaman index review logbook dosen
     */
    public function index()
    {
        $dosen = auth()->user()->dosen;

        if (!$dosen) {
            abort(403, 'Akun ini bukan dosen');
        }

        $logbooks = Logbook::whereHas('pkl', function ($q) use ($dosen) {
                $q->where('id_dosen', $dosen->id)
                  ->where('status', 'aktif');
            })
            ->with([
                'pkl.pengajuanPkl.mahasiswa'
            ])
            ->orderBy('tgl', 'desc')
            ->get();

        return view('dosen.logbook.index', compact('logbooks'));
    }

    /**
     * Review logbook (NON AJAX)
     */
    public function review(Request $request, $id)
    {
        $request->validate([
            'status'   => 'required|in:approved,revisi',
            'catatan'  => 'nullable|string',
        ]);

        $logbook = Logbook::findOrFail($id);

        // Proteksi dosen pembimbing
        if ($logbook->pkl->id_dosen !== auth()->user()->dosen->id) {
            return back()->with('error', 'Anda tidak memiliki akses ke logbook ini.');
        }

        // Tidak boleh review ulang
        if ($logbook->status_approve === 'approved') {
            return back()->with('error', 'Logbook sudah disetujui dan tidak bisa diubah.');
        }

        // Mapping status (ENUM SAFE)
        if ($request->status === 'approved') {
            $logbook->update([
                'status_approve' => 'approved',
                'catatan'        => null,
            ]);
        } else {
            // revisi → pending + catatan
            $logbook->update([
                'status_approve' => 'pending',
                'catatan'        => $request->catatan,
            ]);
        }

        return back()->with('success', 'Logbook berhasil direview.');
    }

    /**
     * Review logbook (AJAX)
     */
    public function reviewAjax(Request $request, Logbook $logbook)
    {
        
        $request->validate([
            'status'   => 'required|in:approved,revisi',
            'catatan'  => 'nullable|string',
        ]);

        // Proteksi dosen pembimbing
        if ($logbook->pkl->id_dosen !== auth()->user()->dosen->id) {
            abort(403, 'Anda tidak berhak mengakses logbook ini');
        }

        // Lock jika sudah approved
        if ($logbook->status_approve === 'approved') {
            abort(403, 'Logbook sudah disetujui dan tidak bisa diubah');
        }

        // Mapping status (ENUM SAFE)
        if ($request->status === 'approved') {
            $logbook->update([
                'status_approve' => 'approved',
                'catatan'        => null,
            ]);
        } else {
            $logbook->update([
                'status_approve' => 'pending',
                'catatan'        => $request->catatan,
            ]);
        }

        return response()->json([
            'success' => true,
            'status'  => $logbook->status_approve,
            'catatan' => $logbook->catatan,
        ]);
    }
}