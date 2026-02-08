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
    // LIST PENGAJUAN
    public function index()
    {
        $pengajuans = PengajuanPkl::with(['mahasiswa', 'tempatPkl'])
            ->whereIn('status', [
                'pending_tu',
                'pending_kaprodi',
                'disetujui'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('staff.pengajuan.index', compact('pengajuans'));
    }

    // DETAIL PENGAJUAN
    public function show($id)
    {
        $pengajuan = PengajuanPkl::with([
            'mahasiswa',
            'tempatPkl',
            'dokumenPengajuan'
        ])->findOrFail($id);

        return view('staff.pengajuan.show', compact('pengajuan'));
    }

    // APPROVE PENGAJUAN TU
    public function approve($id)
    {
        $pengajuan = PengajuanPkl::findOrFail($id);

        // hanya pengajuan pending_tu yang bisa diapprove
        if ($pengajuan->status !== 'pending_tu') {
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

            // lanjut ke Kaprodi
            $pengajuan->update([
                'status' => 'pending_kaprodi',
            ]);
        });

        return redirect()
            ->route('staff.pengajuan.index')
            ->with('success', 'Pengajuan PKL berhasil diverifikasi TU dan diteruskan ke Kaprodi.');
    }

    // REJECT PENGAJUAN TU
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string|max:255',
        ]);

        $pengajuan = PengajuanPkl::findOrFail($id);

        if ($pengajuan->status !== 'pending_tu') {
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
                'status'     => 'ditolak_tu',
                'catatan_tu' => $request->catatan,
            ]);
        });

        return redirect()
            ->route('staff.pengajuan.index')
            ->with('warning', 'Pengajuan PKL berhasil DITOLAK oleh Staff TU.');
    }
    // histori ditolak
    public function historiDitolak()
    {
        $pengajuansDitolak = PengajuanPkl::with(['mahasiswa', 'tempatPkl'])
            ->where('status', 'ditolak_tu')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('staff.pengajuan.histori_ditolak', compact('pengajuansDitolak'));
    }
    public function dashboard()
    {
        // Menunggu verifikasi TU
        $totalMenunggu = PengajuanPkl::where('status', 'pending_tu')->count();

        // Disetujui TU
        $totalDisetujuiTu = PengajuanPkl::whereHas('verifikasi', function($q){
            $q->where('level', 'tu')->where('status', 'approved');
        })->count();

        // Ditolak TU
        $totalDitolak = PengajuanPkl::where('status', 'ditolak_tu')->count();

        return view('staff.dashboard', compact('totalMenunggu', 'totalDisetujuiTu', 'totalDitolak'));
    }


}