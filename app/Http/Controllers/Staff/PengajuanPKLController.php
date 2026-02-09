<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengajuanPklController extends Controller
{
    /**
     * ===============================
     * LIST PENGAJUAN UNTUK TU
     * ===============================
     */
    public function index()
    {
        $pengajuans = PengajuanPkl::with([
                'mahasiswa',
                'tempatPkl',
                'dokumenPengajuan'
            ])
            ->whereIn('status', ['pending', 'draft']) // hanya yang bisa diverifikasi TU
            ->orderBy('created_at', 'desc')
            ->get();

        return view('staff.pengajuan.index', compact('pengajuans'));
    }

    /**
     * ===============================
     * DETAIL PENGAJUAN
     * ===============================
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
     * ===============================
     * APPROVE OLEH TU
     * ===============================
     * Syarat:
     * - status = pending
     * - semua dokumen VALID
     */
    public function approve($id)
    {
        $pengajuan = PengajuanPkl::with('dokumenPengajuan')->findOrFail($id);

        if ($pengajuan->status !== 'pending') {
            return back()->with('warning', 'Pengajuan tidak bisa disetujui karena status bukan pending.');
        }

        if (! $pengajuan->bisaDisetujuiTu()) {
            return back()->with('warning', 'Pengajuan belum memenuhi syarat untuk disetujui.');
        }

        DB::transaction(function () use ($pengajuan) {
            $pengajuan->update([
                'status' => 'disetujui', // sesuai alur Sibolang, TU setuju langsung disetujui
            ]);
        });

        return redirect()
            ->route('staff.pengajuan.index')
            ->with('success', 'Pengajuan PKL berhasil disetujui TU.');
    }

    /**
     * ===============================
     * REJECT / KEMBALIKAN KE MAHASISWA
     * ===============================
     * Syarat:
     * - status = pending
     * - ada dokumen INVALID
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string|max:255',
        ]);

        $pengajuan = PengajuanPkl::with('dokumenPengajuan')->findOrFail($id);

        if ($pengajuan->status !== 'pending') {
            return back()->with('warning', 'Pengajuan tidak dapat dikembalikan karena status bukan pending.');
        }

        if (! $pengajuan->bisaDikembalikanKeMahasiswa()) {
            return back()->with('warning', 'Pengajuan tidak dapat dikembalikan karena semua dokumen valid.');
        }

        DB::transaction(function () use ($pengajuan, $request) {
            $pengajuan->update([
                'status'     => 'ditolak_tu',
                'catatan_tu' => $request->catatan,
            ]);
        });

        return redirect()
            ->route('staff.pengajuan.index')
            ->with('warning', 'Pengajuan PKL dikembalikan ke mahasiswa untuk perbaikan.');
    }

    /**
     * ===============================
     * HISTORI DITOLAK TU
     * ===============================
     */
    public function historiDitolak()
    {
        $pengajuans = PengajuanPkl::with([
                'mahasiswa',
                'tempatPkl'
            ])
            ->where('status', 'ditolak_tu') // sesuaikan dengan alur Sibolang
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('staff.pengajuan.histori_ditolak', compact('pengajuans'));
    }
}