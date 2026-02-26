<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Formulir;
use App\Models\Prodi;
use Illuminate\Support\Facades\Storage;

class FormulirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $formulir = Formulir::with('prodi')->latest()->get();
        return view('admin.formulir.index', compact('formulir'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $prodi = Prodi::all();
        return view('admin.formulir.create', compact('prodi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'file' => 'required|mimes:pdf,doc,docx|max:5120',
        ]);

        $path = $request->file('file')->store('formulir', 'public');

        Formulir::create([
            'nama' => $request->nama,
            'file_path' => $path,
            'prodi_id' => $request->prodi_id,
            'is_active' => true
        ]);

        return redirect()->route('admin.formulir.index')
            ->with('success', 'Formulir berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Formulir $formulir)
    {
        $prodi = \App\Models\Prodi::all();
        return view('admin.formulir.edit', compact('formulir','prodi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Formulir $formulir)
    {
        $request->validate([
            'nama' => 'required',
            'file' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        if ($request->hasFile('file')) {
            // hapus file lama
            if ($formulir->file_path && Storage::disk('public')->exists($formulir->file_path)) {
                Storage::disk('public')->delete($formulir->file_path);
            }

            $path = $request->file('file')->store('formulir', 'public');
            $formulir->file_path = $path;
        }

        $formulir->update([
            'nama' => $request->nama,
            'prodi_id' => $request->prodi_id,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.formulir.index')
            ->with('success','Formulir berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Formulir $formulir)
    {
        if ($formulir->file_path && Storage::disk('public')->exists($formulir->file_path)) {
            Storage::disk('public')->delete($formulir->file_path);
        }

        $formulir->delete();

        return redirect()->route('admin.formulir.index')
            ->with('success','Formulir berhasil dihapus');
    }
}