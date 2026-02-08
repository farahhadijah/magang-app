<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPkl;
use App\Models\Verifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanPKLController extends Controller
{
    /**
     * List pengajuan PKL (Staff TU)
     * HANYA yang status = pending
     */
    public function index()
        {
            $pengajuans = PengajuanPkl::with(['mahasiswa', 'tempatPkl'])
                ->whereIn('status', [
                    'pending_tu',
                    'ditolak_tu',
                    'pending_dosen',
                    'disetujui'
                ])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('staff.pengajuan.index', compact('pengajuans'));
        }



    /**
     * Detail pengajuan PKL
     */
    public function show($id)
    {
        $pengajuan = PengajuanPkl::with([
            'mahasiswa',
            'tempatPkl',
            'dokumenPengajuan'
        ])->findOrFail($id);

        return view('staff.pengajuan.show', compact('pengajuan'));
    }

    /**
     * APPROVE oleh Staff TU
     * (verifikasi administratif)
     */
    public function approve($id)
    {
        $pengajuan = PengajuanPkl::findOrFail($id);

        // hanya boleh proses DRAFT
        if ($pengajuan->status !== 'pending') {
            return back()->with('success', 'Pengajuan sudah diproses.');
        }

        DB::transaction(function () use ($pengajuan) {

            Verifikasi::create([
                'id_pengajuan_pkl' => $pengajuan->id,
                'id_user'          => auth()->user()->id,
                'level'            => 'tu',
                'status'           => 'approved',
                'catatan'          => null,
                'tgl_verifikasi'   => now(),
            ]);

            // 🔥 KUNCI: lanjut ke Kaprodi
            $pengajuan->update([
                'status' => 'pending',
            ]);
        });

    return redirect()
        ->route('staff.pengajuan.index')
        ->with('success', 'Pengajuan PKL berhasil diverifikasi TU dan diteruskan ke Kaprodi.');
}

    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string|max:255',
        ]);

        $pengajuan = PengajuanPkl::findOrFail($id);

        if ($pengajuan->status !== 'pending') {
            return back()->with('success', 'Pengajuan sudah diproses.');
        }



        DB::transaction(function () use ($pengajuan, $request) {

            Verifikasi::create([
                'id_pengajuan_pkl' => $pengajuan->id,
                'id_user'          => auth()->user()->id,
                'level'            => 'tu',
                'status'           => 'rejected',
                'catatan'          => $request->catatan,
                'tgl_verifikasi'   => now(),
            ]);

            $pengajuan->update([
                'status'      => 'ditolak_tu',
                'catatan_tu'  => $request->catatan, // 🔥 supaya mahasiswa bisa lihat
            ]);
        });

        return redirect()
            ->route('staff.pengajuan.index')
            ->with('warning', 'Pengajuan PKL berhasil DITOLAK oleh Staff TU.');

    }

}