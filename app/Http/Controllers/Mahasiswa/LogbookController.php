<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pkl;
use App\Models\Logbook;

class LogbookController extends Controller
{
    /**
     * Ambil PKL aktif mahasiswa login
     */
    private function getActivePkl()
    {
        return Pkl::whereHas('pengajuanPkl', function ($q) {
                $q->where('id_mhs', auth()->user()->mahasiswa->id);
            })
            ->where('status', 'aktif')
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

        return view('mahasiswa.logbook.index', compact('logbooks'));
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

        return view('mahasiswa.logbook.create');
    }

    /**
     * Simpan logbook baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'tgl'       => 'required|date',
            'kegiatan'  => 'required|string',
        ]);

        $pkl = $this->getActivePkl();

        if (!$pkl) {
            return redirect()
                ->route('mahasiswa.logbook.index')
                ->with('warning', 'Belum ada PKL aktif.');
        }

        Logbook::create([
            'id_pkl'         => $pkl->id,
            'tgl'            => $request->tgl,
            'kegiatan'       => $request->kegiatan,
            'status_approve' => 'pending',
        ]);

        return redirect()
            ->route('mahasiswa.logbook.index')
            ->with('success', 'Logbook berhasil ditambahkan.');
    }

    /**
     * Update logbook (jika ada fitur edit)
     */
    public function update(Request $request, Logbook $logbook)
    {
        // 🔐 pastikan logbook milik mahasiswa login
        if ($logbook->pkl->pengajuanPkl->id_mhs !== auth()->user()->mahasiswa->id) {
            abort(403, 'Akses ditolak');
        }

        // 🔒 TIDAK BOLEH EDIT JIKA SUDAH APPROVED
        if ($logbook->status_approve === 'approved') {
            abort(403, 'Logbook sudah disetujui dan tidak bisa diubah');
        }

        $request->validate([
            'tgl'      => 'required|date',
            'kegiatan' => 'required|string',
        ]);

        $logbook->update([
            'tgl'            => $request->tgl,
            'kegiatan'       => $request->kegiatan,
            // kalau diedit → kembali pending
            'status_approve' => 'pending',
        ]);

        return redirect()
            ->route('mahasiswa.logbook.index')
            ->with('success', 'Logbook berhasil diperbarui.');
    }
}