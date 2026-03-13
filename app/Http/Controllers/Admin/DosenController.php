<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Services\UserAutoCreateService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DosenImport;
use Maatwebsite\Excel\HeadingRowImport;

class DosenController extends Controller
{

public function index(Request $request)
{
    $query = Dosen::with('prodi');

    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('nama','like','%'.$request->search.'%')
              ->orWhere('nidn','like','%'.$request->search.'%');
        });
    }

    if ($request->prodi_id) {
        $query->where('prodi_id',$request->prodi_id);
    }

    // dosen terbaru tampil paling atas
    $dosen = $query->orderBy('created_at','desc')->paginate(10);

    $prodi = Prodi::where('is_active',1)->get();

    return view('admin.dosen.index',compact('dosen','prodi'));
}


public function create()
{
    $prodi = Prodi::where('is_active',1)->get();
    return view('admin.dosen.create',compact('prodi'));
}


public function store(Request $request)
{
    $request->validate([
        'nidn' => 'required|unique:dosen,nidn',
        'nama' => 'required|string|max:100',
        'prodi_id' => 'required|exists:prodi,id',
        'jabatan' => 'nullable|string|max:255',
    ]);

    try {

        DB::beginTransaction();

        $dosen = Dosen::create([
            'nidn' => $request->nidn,
            'nama' => $request->nama,
            'prodi_id' => $request->prodi_id,
            'keahlian' => $request->keahlian,
            'jabatan' => $request->jabatan,
            'no_hp' => $request->no_hp,
            'is_active' => 1,
        ]);

        // auto create user
        UserAutoCreateService::fromDosen($dosen);

        DB::commit();

        return redirect()
            ->route('admin.dosen.index')
            ->with('success','Dosen berhasil ditambahkan.');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->with(
            'error',
            'Terjadi kesalahan: '.$e->getMessage()
        );
    }
}


public function edit(Dosen $dosen)
{
    $prodi = Prodi::where('is_active',1)->get();
    return view('admin.dosen.edit',compact('dosen','prodi'));
}


public function update(Request $request, Dosen $dosen)
{
    $request->validate([
        'nidn' => 'required|unique:dosen,nidn,'.$dosen->id,
        'nama' => 'required|string|max:100',
        'prodi_id' => 'required|exists:prodi,id',
        'jabatan' => 'nullable|in:dosen,kaprodi',
    ]);

    DB::transaction(function () use ($request, $dosen) {

        $dosen->update([
            'nidn' => $request->nidn,
            'nama' => $request->nama,
            'prodi_id' => $request->prodi_id,
            'keahlian' => $request->keahlian,
            'jabatan' => $request->jabatan,
            'no_hp' => $request->no_hp,
        ]);

        // sinkronisasi user
        UserAutoCreateService::fromDosen($dosen);

    });

    return redirect()
        ->route('admin.dosen.index')
        ->with('success','Dosen berhasil diperbarui.');
}


public function destroy(Dosen $dosen)
{
    DB::transaction(function () use ($dosen) {

        $dosen->update([
            'is_active' => 0
        ]);

        if ($dosen->user) {
            $dosen->user->update([
                'is_active' => 0
            ]);
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

    $headings = (new HeadingRowImport)
        ->toArray($request->file('file'));

    $header = $headings[0][0] ?? [];

    $required = [
        'nidn',
        'nama',
        'keahlian',
        'jabatan',
        'no_hp',
        'kode_prodi'
    ];

    $header = array_map(
        fn($h)=>strtolower(trim($h)),
        $header
    );

    foreach ($required as $col) {
        if (!in_array($col,$header)) {

            return back()->with(
                'error',
                'Format file salah. Kolom wajib: nidn, nama, keahlian, jabatan, no_hp, kode_prodi'
            );
        }
    }

    try {

        Excel::import(
            new DosenImport,
            $request->file('file')
        );

        return back()->with(
            'success',
            'Import dosen berhasil.'
        );

    } catch (\Exception $e) {

        return back()->with(
            'error',
            'Import gagal: '.$e->getMessage()
        );
    }
}

}