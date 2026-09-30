<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Pkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratBalasanController extends Controller
{
    /**
     * Menampilkan daftar mahasiswa PKL milik Mitra.
     */
    public function index()
    {
        $mitra = auth()->user()->mitra;

        abort_unless($mitra, 403);

        $pkls = Pkl::with([
            'pengajuanPkl.mahasiswa',
            'suratBalasan',
        ])
            ->where('status', 'aktif')
            ->whereHas('pengajuanPkl', function ($query) use ($mitra) {
                $query->where('id_tempat_pkl', $mitra->tempat_pkl_id);
            })
            ->latest('id')
            ->paginate(10);

        return view('mitra.surat-balasan.index', compact('pkls'));
    }

    /**
     * Upload atau upload ulang surat balasan instansi.
     */
    public function store(Request $request, Pkl $pkl)
    {
        $request->validate([
            'surat_balasan' => [
                'required',
                'file',
                'mimes:pdf',
                'max:5120',
            ],
        ]);

        $mitra = auth()->user()->mitra;

        abort_unless($mitra, 403);

        // Pastikan PKL ini milik Mitra yang sedang login.
        abort_unless(
            $pkl->pengajuanPkl &&
            $pkl->pengajuanPkl->id_tempat_pkl === $mitra->tempat_pkl_id,
            403
        );

        // Hanya PKL yang masih aktif yang dapat menerima surat balasan.
        abort_unless($pkl->status === 'aktif', 403);

        $suratLama = $pkl->suratBalasan;

        // Hapus file lama ketika melakukan upload ulang.
        if ($suratLama && $suratLama->path_file) {
            Storage::disk('public')->delete($suratLama->path_file);
        }

        // Simpan file baru.
        $path = $request->file('surat_balasan')
            ->store('surat_balasan', 'public');

        // Upload pertama = create.
        // Upload ulang = update.
        $pkl->suratBalasan()->updateOrCreate(
            [
                'id_pkl' => $pkl->id,
            ],
            [
                'path_file' => $path,
            ]
        );

        return back()->with(
            'success',
            'Surat balasan instansi berhasil diunggah.'
        );
    }
}