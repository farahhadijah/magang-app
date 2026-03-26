<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StaffImport;
use Maatwebsite\Excel\HeadingRowImport;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::latest()->paginate(10);
        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        $prodi = Prodi::all();
        return view('admin.staff.create', compact('prodi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:staff,nip',
            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'prodi_id' => 'required|exists:prodi,id',
        ]);

        DB::transaction(function () use ($request) {

            Staff::create([
                'nip' => $request->nip,
                'nama' => $request->nama,
                'no_hp' => $request->no_hp,
                'prodi_id' => $request->prodi_id,
                'is_active' => 1,
            ]);

        });

        return redirect()->route('admin.staff.index')
            ->with('success','Staff berhasil ditambahkan.');
    }

    public function edit(Staff $staff)
    {
        $prodi = Prodi::all();
        return view('admin.staff.edit', compact('staff','prodi'));
    }

    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'prodi_id' => 'required|exists:prodi,id',
        ]);

        $staff->update([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'prodi_id' => $request->prodi_id,
        ]);

        return back()->with('success','Data staff diperbarui.');
    }

    public function destroy(Staff $staff)
    {
        DB::transaction(function () use ($staff) {

            $staff->update(['is_active' => 0]);

            if ($staff->user) {
                $staff->user->update(['is_active' => 0]);
            }

        });

        return back()->with('success','Staff dinonaktifkan.');
    }

    public function reset($id)
    {
        $staff = Staff::findOrFail($id);

        if ($staff->user) {
            $staff->user->update([
                'password' => Hash::make($staff->nip),
                'first_login' => 1,
            ]);
        }

        return back()->with('success','Password berhasil direset.');
    }


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $headings = (new HeadingRowImport)->toArray($request->file('file'));
        $header = $headings[0][0] ?? [];

        $required = ['nip','nama','no_hp','kode_prodi'];

        $header = array_map(fn($h) => strtolower(trim($h)), $header);

        foreach ($required as $col) {
            if (!in_array($col, $header)) {
                return back()->with(
                    'error',
                    'Format file salah. Kolom wajib: nip, nama, no_hp, kode_prodi'
                );
            }
        }

        Excel::import(new StaffImport, $request->file('file'));

        return back()->with('success','Import staff berhasil.');
    }
}