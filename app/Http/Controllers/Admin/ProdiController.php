<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProdiImport;
use Maatwebsite\Excel\HeadingRowImport;

class ProdiController extends Controller
{
    public function index()
    {
        $prodi = Prodi::with('fakultas')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.prodi.index', compact('prodi'));
    }

    public function create()
    {
        $fakultas = Fakultas::where('is_active', 1)
            ->orderBy('nama')
            ->get();

        return view('admin.prodi.create', compact('fakultas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:prodi,kode',
            'nama' => 'required|string|max:100',
            'fakultas_id' => 'required|exists:fakultas,id',
        ]);

        Prodi::create([
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
            'fakultas_id' => $request->fakultas_id,
            'is_active' => 1,
        ]);

        return redirect()->route('admin.prodi.index')
            ->with('success', 'Prodi berhasil ditambahkan.');
    }

    public function edit(Prodi $prodi)
    {
        $fakultas = Fakultas::where('is_active', 1)
            ->orderBy('nama')
            ->get();

        return view('admin.prodi.edit', compact('prodi', 'fakultas'));
    }

    public function update(Request $request, Prodi $prodi)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:prodi,kode,' . $prodi->id,
            'nama' => 'required|string|max:100',
            'fakultas_id' => 'required|exists:fakultas,id',
        ]);

        $prodi->update([
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
            'fakultas_id' => $request->fakultas_id,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.prodi.index', [
            'page' => $request->page
        ])->with('success', 'Prodi berhasil diperbarui.');
    }

    public function destroy(Request $request, Prodi $prodi)
{
    if (
        $prodi->dosen()->exists() ||
        $prodi->mahasiswa()->exists() ||
        $prodi->staff()->exists()
    ) {
        return redirect()->route('admin.prodi.index', [
            'page' => $request->page
        ])->with('error',
            'Prodi tidak dapat dihapus karena masih digunakan oleh master data.'
        );
    }

    $prodi->delete();

    return redirect()->route('admin.prodi.index', [
        'page' => $request->page
    ])->with('success','Prodi berhasil dihapus');
}

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        $headings = (new HeadingRowImport)
            ->toArray($request->file('file'));

        $header = $headings[0][0] ?? [];

        $required = ['kode', 'nama', 'fakultas_id'];

        $header = array_map(
            fn($h) => strtolower(trim($h)),
            $header
        );

        foreach ($required as $col) {
            if (!in_array($col, $header)) {
                return back()->with('error',
                    'Format file salah. Kolom wajib: kode, nama, fakultas_id'
                );
            }
        }

        Excel::import(new ProdiImport, $request->file('file'));

        return back()->with('success',
            'Data Prodi berhasil diimport.');
    }
}