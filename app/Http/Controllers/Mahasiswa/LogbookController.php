<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pkl;
use App\Models\Logbook;
use Carbon\Carbon;

class LogbookController extends Controller
{
    /**
     * Ambil PKL aktif mahasiswa login
     */
    private function getActivePkl()
    {
        $user = auth()->user();

        if (!$user || !$user->mahasiswa) {
            return null;
        }

        return Pkl::whereHas('pengajuanPkl', function ($q) use ($user) {
                $q->where('id_mhs', $user->mahasiswa->id);
            })
            ->where('status', 'aktif')
            ->latest()
            ->first();
    }

    /**
     * Index: tampilkan semua logbook mahasiswa
     */
    public function index()
    {
        $pkl = $this->getActivePkl();

        $logbooks = $pkl
            ? $pkl->logbook()->orderBy('tgl', 'desc')->get()
            : collect();

        // Cek apakah sudah ada logbook untuk hari ini
        $hasToday = false;
        if ($pkl) {
            $hasToday = $pkl->logbook()
                ->whereDate('tgl', Carbon::today())
                ->exists();
        }

        return view('mahasiswa.logbook.index', compact('logbooks', 'hasToday'));
    }

    /**
     * Form tambah logbook
     */
    public function create()
{
    $pkl = $this->getActivePkl();

    if (!$pkl) {
        return redirect()
            ->route('mahasiswa.logbook.index')
            ->with('warning', 'Belum ada PKL aktif. Tidak bisa menambah logbook.');
    }

    // Cegah membuat logbook lebih dari satu untuk hari ini
    $hasToday = $pkl->logbook()
        ->whereDate('tgl', Carbon::today())
        ->exists();

    if ($hasToday) {
        return redirect()
            ->route('mahasiswa.logbook.index')
            ->with('warning', 'Anda sudah membuat logbook untuk hari ini.');
    }

    return view('mahasiswa.logbook.create', compact('pkl'));
}


    /**
     * Simpan logbook baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'tgl'      => 'required|date|before_or_equal:today',
            'kegiatan' => 'required|string|max:2000',
        ]);

        $pkl = $this->getActivePkl();

        if (!$pkl) {
            return redirect()
                ->route('mahasiswa.logbook.index')
                ->with('warning', 'Belum ada PKL aktif.');
        }

        // 🔒 Pastikan PKL masih aktif
        if ($pkl->status !== 'aktif') {
            abort(403, 'PKL sudah selesai.');
        }

        $tgl = Carbon::parse($request->tgl);

        // ✅ Validasi periode PKL
        if ($tgl->lt($pkl->tgl_mulai) || $tgl->gt($pkl->tgl_selesai)) {
            return back()->with('error', 'Tanggal di luar periode PKL.');
        }

        // ✅ Cek duplikat tanggal
        $exists = Logbook::where('id_pkl', $pkl->id)
            ->whereDate('tgl', $request->tgl)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Logbook untuk tanggal tersebut sudah ada.');
        }

        Logbook::create([
            'id_pkl'         => $pkl->id,
            'tgl'            => $request->tgl,
            'kegiatan'       => $request->kegiatan,
            'status_approve' => 'pending',
            'catatan_dosen'  => null,
        ]);

        return redirect()
            ->route('mahasiswa.logbook.index')
            ->with('success', 'Logbook berhasil ditambahkan.');
    }

    /**
     * Update logbook (untuk revisi)
     */
    public function update(Request $request, Logbook $logbook)
    {
        $user = auth()->user();

        if (!$user || !$user->mahasiswa) {
            abort(403);
        }

        // 🔐 Pastikan logbook milik mahasiswa login
        if ($logbook->pkl->pengajuanPkl->id_mhs !== $user->mahasiswa->id) {
            abort(403, 'Akses ditolak.');
        }

        // 🔒 PKL harus masih aktif
        if ($logbook->pkl->status !== 'aktif') {
            abort(403, 'PKL sudah selesai.');
        }

        // 🔒 Tidak boleh edit jika sudah approved
        if ($logbook->status_approve === 'approved') {
            abort(403, 'Logbook sudah disetujui dan tidak bisa diubah.');
        }

        $request->validate([
            'tgl'      => 'required|date|before_or_equal:today',
            'kegiatan' => 'required|string|max:2000',
        ]);

        $pkl = $logbook->pkl;
        $tgl = Carbon::parse($request->tgl);

        // ✅ Validasi periode PKL
        if ($tgl->lt($pkl->tgl_mulai) || $tgl->gt($pkl->tgl_selesai)) {
            return back()->with('error', 'Tanggal di luar periode PKL.');
        }

        // ✅ Cek duplikat tanggal (kecuali dirinya sendiri)
        $exists = Logbook::where('id_pkl', $pkl->id)
            ->where('tgl', $request->tgl)
            ->where('id', '!=', $logbook->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Logbook untuk tanggal tersebut sudah ada.');
        }

        $logbook->update([
            'tgl'            => $request->tgl,
            'kegiatan'       => $request->kegiatan,
            'status_approve' => 'pending',   // kembali pending setelah revisi
            'catatan_dosen'  => null,        // reset catatan lama agar clean
        ]);

        return redirect()
            ->route('mahasiswa.logbook.index')
            ->with('success', 'Logbook berhasil diperbarui.');
    }

    /**
     * Hapus logbook (opsional tapi enterprise-ready)
     */
    public function destroy(Logbook $logbook)
    {
        $user = auth()->user();

        if (!$user || !$user->mahasiswa) {
            abort(403);
        }

        if ($logbook->pkl->pengajuanPkl->id_mhs !== $user->mahasiswa->id) {
            abort(403, 'Akses ditolak.');
        }

        if ($logbook->status_approve === 'approved') {
            abort(403, 'Logbook yang sudah disetujui tidak dapat dihapus.');
        }

        $logbook->delete();

        return back()->with('success', 'Logbook berhasil dihapus.');
    }
    public function edit(Logbook $logbook)
{
    $user = auth()->user();

    if (!$user || !$user->mahasiswa) {
        abort(403);
    }

    if ($logbook->pkl->pengajuanPkl->id_mhs !== $user->mahasiswa->id) {
        abort(403, 'Akses ditolak.');
    }

    if ($logbook->pkl->status !== 'aktif') {
        abort(403, 'PKL sudah selesai.');
    }

    if ($logbook->status_approve === 'approved') {
        abort(403, 'Logbook sudah disetujui dan tidak bisa diubah.');
    }

    return view('mahasiswa.logbook.edit', compact('logbook'));
}

}