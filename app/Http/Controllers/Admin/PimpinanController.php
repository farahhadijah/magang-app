<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pimpinan;
use Illuminate\Support\Facades\Hash;

class PimpinanController extends Controller
{
    public function index()
    {
        $pimpinan = Pimpinan::orderBy('nama')->paginate(10);

        return view('admin.pimpinan.index', compact('pimpinan'));
    }

    public function create()
    {
        return view('admin.pimpinan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nip' => 'required|string|unique:pimpinan,nip',
            'nama' => 'required|string',
            'no_hp' => 'nullable|string'
        ]);

        Pimpinan::create($data + ['is_active' => true]);

        return redirect()->route('admin.pimpinan.index')->with('success', 'Pimpinan berhasil ditambahkan.');
    }

    public function edit(Pimpinan $pimpinan)
    {
        return view('admin.pimpinan.edit', compact('pimpinan'));
    }

    public function update(Request $request, Pimpinan $pimpinan)
    {
        $data = $request->validate([
            'nip' => 'required|string|unique:pimpinan,nip,' . $pimpinan->id,
            'nama' => 'required|string',
            'no_hp' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        $pimpinan->update($data);

        return redirect()->route('admin.pimpinan.index')->with('success', 'Pimpinan berhasil diperbarui.');
    }

    public function destroy(Pimpinan $pimpinan)
    {
        // mark as non-active instead of deleting
        $pimpinan->update(['is_active' => 0]);

        if ($pimpinan->user) {
            $pimpinan->user->update(['is_active' => 0]);
        }

        return redirect()->route('admin.pimpinan.index')->with('success', 'Pimpinan berhasil dinonaktifkan.');
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

        return back()->with('success', 'Password berhasil direset.');
    }

    public function activate($id)
    {
        $pimpinan = Pimpinan::findOrFail($id);

        $pimpinan->update(['is_active' => 1]);

        if ($pimpinan->user) {
            $pimpinan->user->update(['is_active' => 1]);
        }

        return back()->with('success', 'Pimpinan berhasil diaktifkan.');
    }
}
