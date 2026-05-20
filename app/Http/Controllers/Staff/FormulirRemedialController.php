<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\FormulirRemedial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
class FormulirRemedialController extends Controller
{
    /**
     * Tampilkan daftar formulir remedial.
     */
    public function index()
{
    $staff = auth()->user()->staff;

    $fakultas = $staff->prodi->fakultas;

    $formulirs = FormulirRemedial::where(
        'fakultas_id',
        $fakultas->id
    )->latest()->get();

    return view('staff.formulir-remedial.index', compact(
        'formulirs',
        'fakultas'
    ));
}

    /**
     * Simpan formulir baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        $path = $request->file('file')
            ->store('formulir-remedial', 'public');

        $fakultasId = auth()->user()
            ->staff
            ->prodi
            ->fakultas
            ->id;

        FormulirRemedial::create([
            'fakultas_id' => $fakultasId,
            'nama'        => $validated['nama'],
            'path_file'   => $path,
        ]);

        return back()->with(
            'success',
            'Formulir remedial berhasil ditambahkan.'
        );
    }

    /**
     * Update formulir.
     */
    public function update(Request $request, FormulirRemedial $formulirRemedial)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'file' => 'nullable|mimes:pdf|max:2048',
        ]);

        $data = [
            'nama' => $validated['nama'],
        ];

        if ($request->hasFile('file')) {

            // hapus file lama
            if (
                $formulirRemedial->path_file &&
                Storage::disk('public')->exists($formulirRemedial->path_file)
            ) {
                Storage::disk('public')
                    ->delete($formulirRemedial->path_file);
            }

            $data['path_file'] = $request->file('file')
                ->store('formulir-remedial', 'public');
        }

        $formulirRemedial->update($data);

        return back()->with(
            'success',
            'Formulir remedial berhasil diperbarui.'
        );
    }

    /**
     * Hapus formulir.
     */
    public function destroy(FormulirRemedial $formulirRemedial)
    {
        if ($formulirRemedial->path_file &&
            Storage::disk('public')->exists($formulirRemedial->path_file)) {

            Storage::disk('public')
                ->delete($formulirRemedial->path_file);
        }

        $formulirRemedial->delete();

        return back()->with('success', 'Formulir remedial berhasil dihapus.');
    }
}