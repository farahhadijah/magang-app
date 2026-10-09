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
            ->whereHas('pengajuanPkl', function ($query) use ($mitra) {
                $query->where('id_tempat_pkl', $mitra->tempat_pkl_id);
            })
            ->where(function ($query) {
                // Tampilkan PKL yang belum selesai.
                $query->where('status', 'aktif')

                    // Atau PKL yang sudah selesai tetapi
                    // belum memiliki surat balasan.
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('status', 'selesai')
                            ->whereDoesntHave('suratBalasan');
                    });
            })
            ->latest('id')
            ->paginate(10);

        return view('mitra.surat-balasan.index', compact('pkls'));
    }

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

        // Pastikan PKL ini milik mitra yang sedang login.
        abort_unless(
            $pkl->pengajuanPkl &&
            $pkl->pengajuanPkl->id_tempat_pkl == $mitra->tempat_pkl_id,
            403
        );

        // Izinkan upload untuk PKL aktif maupun selesai.
        abort_unless(
            in_array($pkl->status, ['aktif', 'selesai'], true),
            403,
            'Surat balasan hanya dapat diunggah untuk PKL aktif atau selesai.'
        );

        $suratLama = $pkl->suratBalasan;

        // Simpan file baru.
        $path = $request->file('surat_balasan')
            ->store('surat_balasan', 'public');

        // Buat data baru atau perbarui data surat balasan.
        $pkl->suratBalasan()->updateOrCreate(
            [
                'id_pkl' => $pkl->id,
            ],
            [
                'path_file' => $path,
            ]
        );

        // Hapus file lama setelah data baru berhasil disimpan.
        if ($suratLama && $suratLama->path_file) {
            Storage::disk('public')->delete($suratLama->path_file);
        }

        return back()->with(
            'success',
            'Surat balasan instansi berhasil diunggah.'
        );
    }

}