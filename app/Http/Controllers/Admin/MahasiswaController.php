<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Imports\MahasiswaImport;
use Maatwebsite\Excel\Facades\Excel;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = Mahasiswa::with('prodi');

    // Search nama atau nim
    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('nama', 'like', '%'.$request->search.'%')
              ->orWhere('nim', 'like', '%'.$request->search.'%');
        });
    }

    // Filter prodi
    if ($request->prodi_id) {
        $query->where('prodi_id', $request->prodi_id);
    }

    $mahasiswa = $query->orderBy('created_at', 'desc')->paginate(10);
    $prodi = Prodi::where('is_active', 1)->get();

    return view('admin.mahasiswa.index', compact('mahasiswa','prodi'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $prodi = Prodi::where('is_active', 1)->get();
    return view('admin.mahasiswa.create', compact('prodi'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'nim' => 'required|unique:mahasiswa,nim',
        'nama' => 'required|string|max:100',
        'angkatan' => 'required|digits:4',
        'prodi_id' => 'required|exists:prodi,id',
    ]);

    DB::beginTransaction();

    try {

        $mahasiswa = Mahasiswa::create([
            'nim' => $request->nim,
            'nama' => $request->nama,
            'angkatan' => $request->angkatan,
            'no_hp' => $request->no_hp,
            'prodi_id' => $request->prodi_id,
            'is_active' => 1,
        ]);

        DB::commit();

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan.');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
    }
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
    public function edit(Mahasiswa $mahasiswa)
{
    $prodi = Prodi::where('is_active', 1)->get();
    return view('admin.mahasiswa.edit', compact('mahasiswa','prodi'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mahasiswa $mahasiswa)
{
    $request->validate([
        'nim' => 'required|unique:mahasiswa,nim,'.$mahasiswa->id,
        'nama' => 'required|string|max:100',
        'angkatan' => 'required|digits:4',
        'prodi_id' => 'required|exists:prodi,id',
    ]);

    DB::beginTransaction();

    try {

        $mahasiswa->update([
            'nim' => $request->nim,
            'nama' => $request->nama,
            'angkatan' => $request->angkatan,
            'no_hp' => $request->no_hp,
            'prodi_id' => $request->prodi_id,
        ]);

        // Sinkron username kalau nim berubah
        if ($mahasiswa->user) {
            $mahasiswa->user->update([
                'username' => $request->nim
            ]);
        }

        DB::commit();

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil diperbarui.');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mahasiswa $mahasiswa)
{
    DB::transaction(function () use ($mahasiswa) {

        $mahasiswa->update(['is_active' => 0]);

        if ($mahasiswa->user) {
            $mahasiswa->user->update(['is_active' => 0]);
        }
    });

    return back()->with('success', 'Mahasiswa dinonaktifkan.');
}
public function resetPassword(Mahasiswa $mahasiswa)
{
    if (!$mahasiswa->user) {
        return back()->with('error', 'User tidak ditemukan.');
    }

    $mahasiswa->user->update([
        'password' => Hash::make($mahasiswa->nim),
        'first_login' => 1,
    ]);

    return back()->with('success', 'Password berhasil direset ke NIM.');
}
public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    try {

        Excel::import(new MahasiswaImport, $request->file('file'));

        return back()->with('success', 'Import berhasil.');

    } catch (\Exception $e) {

        return back()->with('error', 'Import gagal: '.$e->getMessage());
    }
}

}