<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Prodi;
// use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DosenImport;
class DosenController extends Controller
{
    public function index(Request $request)
{
    $query = Dosen::with('prodi');

    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('nama', 'like', '%'.$request->search.'%')
              ->orWhere('nidn', 'like', '%'.$request->search.'%');
        });
    }

    if ($request->prodi_id) {
        $query->where('prodi_id', $request->prodi_id);
    }

    $dosen = $query->paginate(10);
    $prodi = Prodi::where('is_active',1)->get();

    return view('admin.dosen.index', compact('dosen','prodi'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $prodi = Prodi::where('is_active',1)->get();
    return view('admin.dosen.create', compact('prodi'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'nidn' => 'required|unique:dosen,nidn',
        'nama' => 'required|string|max:100',
        'prodi_id' => 'required|exists:prodi,id',
    ]);

    DB::beginTransaction();

    try {

        $dosen = Dosen::create([
            'nidn' => $request->nidn,
            'nama' => $request->nama,
            'prodi_id' => $request->prodi_id,
            'keahlian' => $request->keahlian,
            'no_hp' => $request->no_hp,
            'is_active' => 1,
        ]);

        DB::commit();

        return redirect()->route('admin.dosen.index')
            ->with('success','Dosen berhasil ditambahkan.');

    } catch (\Exception $e) {

        DB::rollBack();
        return back()->with('error','Terjadi kesalahan.');
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
    public function edit(Dosen $dosen)
{
    $prodi = Prodi::where('is_active',1)->get();
    return view('admin.dosen.edit', compact('dosen','prodi'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dosen $dosen)
{
    $request->validate([
        'nidn' => 'required|unique:dosen,nidn,'.$dosen->id,
        'nama' => 'required|string|max:100',
        'prodi_id' => 'required|exists:prodi,id',
    ]);

    DB::transaction(function () use ($request, $dosen) {

        $dosen->update([
            'nidn' => $request->nidn,
            'nama' => $request->nama,
            'prodi_id' => $request->prodi_id,
            'keahlian' => $request->keahlian,
            'no_hp' => $request->no_hp,
        ]);

        if ($dosen->user) {
            $dosen->user->update([
                'username' => $request->nidn
            ]);
        }
    });

    return redirect()->route('admin.dosen.index')
        ->with('success','Dosen berhasil diperbarui.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dosen $dosen)
{
    DB::transaction(function () use ($dosen) {

        $dosen->update(['is_active' => 0]);

        if ($dosen->user) {
            $dosen->user->update(['is_active' => 0]);
        }
    });

    return back()->with('success','Dosen dinonaktifkan.');
}
public function resetPassword(Dosen $dosen)
{
    if (!$dosen->user) {
        return back()->with('error','User tidak ditemukan.');
    }

    $dosen->user->update([
        'password' => Hash::make($dosen->nidn),
        'first_login' => 1
    ]);

    return back()->with('success','Password berhasil direset.');
}
public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    try {

        Excel::import(new DosenImport, $request->file('file'));

        return back()->with('success','Import dosen berhasil.');

    } catch (\Exception $e) {

        return back()->with('error','Import gagal: '.$e->getMessage());
    }
}
}