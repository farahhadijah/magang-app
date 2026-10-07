<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class InformasiController extends Controller
{
    /**
     * Menampilkan daftar informasi.
     */
    public function index()
    {
        $informasi = Informasi::with('uploader')
            ->latest()
            ->paginate(10);

        return view('admin.informasi.index', compact('informasi'));
    }

    /**
     * Menampilkan form tambah informasi.
     */
    public function create()
    {
        return view('admin.informasi.create');
    }

    /**
     * Menyimpan informasi baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:150'],

            'jenis' => [
                'required',
                Rule::in([
                    'flowchart',
                    'format_laporan',
                    'manual_book',
                ]),
            ],

            'target_role' => [
                'required',
                Rule::in([
                    'admin',
                    'mahasiswa',
                    'dosen',
                    'staff_tu',
                    'mitra',
                    'pimpinan',
                ]),
            ],

            'deskripsi' => ['nullable', 'string'],

            'file' => [
                'required',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120',
            ],

            'is_active' => ['nullable', 'boolean'],
        ]);

        $filePath = $request->file('file')->store(
            'informasi',
            'public'
        );

        Informasi::create([
            'judul' => $validated['judul'],
            'jenis' => $validated['jenis'],
            'target_role' => $validated['target_role'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'file_path' => $filePath,
            'uploaded_by' => auth()->user()->id,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.informasi.index')
            ->with('success', 'Informasi berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit informasi.
     */
    public function edit(Informasi $informasi)
    {
        return view('admin.informasi.edit', compact('informasi'));
    }

    /**
     * Memperbarui informasi.
     */
    public function update(Request $request, Informasi $informasi)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:150'],

            'jenis' => [
                'required',
                Rule::in([
                    'flowchart',
                    'format_laporan',
                    'manual_book',
                ]),
            ],

            'target_role' => [
                'required',
                Rule::in([
                    'admin',
                    'mahasiswa',
                    'dosen',
                    'staff_tu',
                    'mitra',
                    'pimpinan',
                ]),
            ],

            'deskripsi' => ['nullable', 'string'],

            'file' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120',
            ],

            'is_active' => ['nullable', 'boolean'],
        ]);

        $data = [
            'judul' => $validated['judul'],
            'jenis' => $validated['jenis'],
            'target_role' => $validated['target_role'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];

        // Jika admin mengganti file
        if ($request->hasFile('file')) {
            // Hapus file lama
            if (
                $informasi->file_path &&
                Storage::disk('public')->exists($informasi->file_path)
            ) {
                Storage::disk('public')->delete($informasi->file_path);
            }

            // Simpan file baru
            $data['file_path'] = $request->file('file')->store(
                'informasi',
                'public'
            );
        }

        $informasi->update($data);

        return redirect()
            ->route('admin.informasi.index')
            ->with('success', 'Informasi berhasil diperbarui.');
    }

    /**
     * Menghapus informasi.
     */
    public function destroy(Informasi $informasi)
    {
        if (
            $informasi->file_path &&
            Storage::disk('public')->exists($informasi->file_path)
        ) {
            Storage::disk('public')->delete($informasi->file_path);
        }

        $informasi->delete();

        return redirect()
            ->route('admin.informasi.index')
            ->with('success', 'Informasi berhasil dihapus.');
    }
}