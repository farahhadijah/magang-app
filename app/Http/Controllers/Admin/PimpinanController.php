<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pimpinan;
use App\Models\Fakultas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PimpinanController extends Controller
{
    public function index()
    {
        $pimpinan = Pimpinan::with('fakultas')
            ->orderBy('nama')
            ->paginate(10);

        return view('admin.pimpinan.index', compact('pimpinan'));
    }

    public function create()
    {
        $fakultas = Fakultas::where('is_active', true)
            ->orderBy('nama')
            ->get();

        return view('admin.pimpinan.create', compact('fakultas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nip' => 'required|string|max:30|unique:pimpinan,nip',
            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:15',

            'fakultas_id' => [
                'required',
                'exists:fakultas,id',
                Rule::unique('pimpinan', 'fakultas_id'),
            ],
        ]);

        Pimpinan::create($data + [
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.pimpinan.index')
            ->with('success', 'Pimpinan berhasil ditambahkan.');
    }

    public function edit(Pimpinan $pimpinan)
    {
        $fakultas = Fakultas::where('is_active', true)
            ->orderBy('nama')
            ->get();

        return view('admin.pimpinan.edit', compact(
            'pimpinan',
            'fakultas'
        ));
    }

    public function update(Request $request, Pimpinan $pimpinan)
    {
        $data = $request->validate([
            'nip' => [
                'required',
                'string',
                'max:30',
                'unique:pimpinan,nip,' . $pimpinan->id,
            ],

            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:15',

            'fakultas_id' => [
                'required',
                'exists:fakultas,id',
                Rule::unique('pimpinan', 'fakultas_id')
                    ->ignore($pimpinan->id),
            ],

            'is_active' => 'nullable|boolean',
        ]);

        $pimpinan->update($data);

        return redirect()
            ->route('admin.pimpinan.index')
            ->with('success', 'Pimpinan berhasil diperbarui.');
    }

    public function destroy(Pimpinan $pimpinan)
    {
        // Tandai sebagai nonaktif, bukan menghapus data
        $pimpinan->update([
            'is_active' => 0,
        ]);

        if ($pimpinan->user) {
            $pimpinan->user->update([
                'is_active' => 0,
            ]);
        }

        return redirect()
            ->route('admin.pimpinan.index')
            ->with('success', 'Pimpinan berhasil dinonaktifkan.');
    }

    public function reset($id)
    {
        $pimpinan = Pimpinan::findOrFail($id);

        if ($pimpinan->user) {
            $pimpinan->user->update([
                'password' => Hash::make($pimpinan->nip),
                'first_login' => 1,
            ]);
        }

        return back()->with(
            'success',
            'Password berhasil direset.'
        );
    }

    public function activate($id)
    {
        $pimpinan = Pimpinan::findOrFail($id);

        $pimpinan->update([
            'is_active' => 1,
        ]);

        if ($pimpinan->user) {
            $pimpinan->user->update([
                'is_active' => 1,
            ]);
        }

        return back()->with(
            'success',
            'Pimpinan berhasil diaktifkan.'
        );
    }
}