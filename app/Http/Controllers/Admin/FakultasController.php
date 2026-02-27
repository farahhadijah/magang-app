<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FakultasImport;
use Maatwebsite\Excel\HeadingRowImport;

class FakultasController extends Controller
{
    public function index()
    {
        $fakultas = Fakultas::latest()->paginate(10);
        return view('admin.fakultas.index', compact('fakultas'));
    }

    public function create()
    {
        return view('admin.fakultas.create');
    }

    public function edit(Fakultas $fakultas)
    {
        return view('admin.fakultas.edit', compact('fakultas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:fakultas,nama',
        ]);

        Fakultas::create([
            'nama' => ucwords(strtolower(trim($request->nama))),
            'is_active' => 1,
        ]);

        return redirect()
            ->route('admin.fakultas.index')
            ->with('success', 'Fakultas berhasil ditambahkan.');
    }

    public function update(Request $request, Fakultas $fakultas)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:fakultas,nama,' . $fakultas->id,
        ]);

        $fakultas->update([
            'nama' => ucwords(strtolower(trim($request->nama))),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.fakultas.index')
            ->with('success', 'Fakultas berhasil diperbarui.');
    }

    public function destroy(Fakultas $fakultas)
    {
        if ($fakultas->prodi()->exists()) {
            return back()->with('error',
                'Fakultas tidak dapat dihapus karena masih memiliki prodi.'
            );
        }

        $fakultas->delete();

        return back()->with('success', 'Fakultas berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        $headings = (new HeadingRowImport)
            ->toArray($request->file('file'));

        $header = $headings[0][0] ?? [];

        $required = ['nama'];

        $header = array_map(
            fn($h) => strtolower(trim($h)),
            $header
        );

        foreach ($required as $col) {
            if (!in_array($col, $header)) {
                return back()->with('error',
                    'Format file salah. Kolom wajib: nama'
                );
            }
        }

        Excel::import(new FakultasImport, $request->file('file'));

        return back()->with('success',
            'Data Fakultas berhasil diimport.');
    }
}