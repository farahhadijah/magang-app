<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProdiImport;
use Maatwebsite\Excel\HeadingRowImport;

class ProdiController extends Controller
{
    public function index()
    {
        $prodi = Prodi::latest()->paginate(10);
        return view('admin.prodi.index', compact('prodi'));
    }

    public function create()
    {
        return view('admin.prodi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:prodi,kode',
            'nama' => 'required|string|max:100',
        ]);

        Prodi::create([
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
            'is_active' => 1,
        ]);

        return redirect()->route('admin.prodi.index')
            ->with('success', 'Prodi berhasil ditambahkan.');
    }

    public function edit(Prodi $prodi)
    {
        return view('admin.prodi.edit', compact('prodi'));
    }

    public function update(Request $request, Prodi $prodi)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:prodi,kode,' . $prodi->id,
            'nama' => 'required|string|max:100',
        ]);

        $prodi->update([
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.prodi.index')
            ->with('success', 'Prodi berhasil diperbarui.');
    }

    public function destroy(Prodi $prodi)
    {
        $prodi->delete();
        return back()->with('success', 'Prodi berhasil dihapus.');
    }

    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv'
    ]);

    // Ambil header baris pertama
    $headings = (new HeadingRowImport)->toArray($request->file('file'));

    $header = $headings[0][0] ?? [];

    $required = ['kode', 'nama'];

    // Normalisasi header jadi lowercase
    $header = array_map(fn($h) => strtolower(trim($h)), $header);

    foreach ($required as $col) {
        if (!in_array($col, $header)) {
            return back()->with('error',
                'Format file salah. Kolom wajib: kode, nama'
            );
        }
    }

    // Kalau header valid → lanjut import
    Excel::import(new ProdiImport, $request->file('file'));

    return back()->with('success', 'Data Prodi berhasil diimport.');
}
}