<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Pkl;
use App\Models\TugasMitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasMitraController extends Controller
{
    public function index()
{
    $mitra = Auth::user()->mitra;

    $tugas = TugasMitra::whereHas('pkl.pengajuanPkl', function ($q) use ($mitra) {
        $q->where('id_tempat_pkl', $mitra->tempat_pkl_id);
    })
    ->with([
        'pkl.mahasiswa',
        'submit' => function($q){
            $q->latest();
        }
    ])
    ->latest()
    ->get();

    return view('mitra.tugas.index', compact('tugas'));
}
    public function create()
    {
        $mitra = Auth::user()->mitra;

        $pkls = Pkl::whereHas('pengajuanPkl', function ($q) use ($mitra) {
            $q->where('id_tempat_pkl', $mitra->tempat_pkl_id);
        })->with('mahasiswa')->get();

        return view('mitra.tugas.create', compact('pkls'));
    }
    public function store(Request $request)
{
    $request->validate([
        'id_pkl' => 'required',
        'judul' => 'required',
        'deskripsi' => 'nullable',
        'deadline' => 'nullable|date',

        // file optional
        'file' => 'nullable|file|max:10240'
    ]);

    $data = $request->only([
        'id_pkl',
        'judul',
        'deskripsi',
        'deadline'
    ]);

    if ($request->hasFile('file')) {

        $path = $request->file('file')
            ->store('tugas_mitra', 'public');

        $data['file'] = $path;
    }

    TugasMitra::create($data);

    return redirect()
        ->route('mitra.tugas.index')
        ->with('success', 'Tugas berhasil dibuat');
}
    public function show($id)
    {
        $tugas = TugasMitra::with([
            'pkl.mahasiswa',
            'submit'
        ])->findOrFail($id);

        return view('mitra.tugas.show', compact('tugas'));
    }
    public function edit($id)
    {
        $tugas = TugasMitra::findOrFail($id);

        $mitra = Auth::user()->mitra;

        $pkls = Pkl::whereHas('pengajuanPkl', function ($q) use ($mitra) {
            $q->where('id_tempat_pkl', $mitra->tempat_pkl_id);
        })->with('mahasiswa')->get();

        return view('mitra.tugas.edit', compact('tugas', 'pkls'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pkl' => 'required',
            'judul' => 'required',
            'deskripsi' => 'nullable',
            'deadline' => 'nullable|date'
        ]);

        $tugas = TugasMitra::findOrFail($id);

        $tugas->update([
            'id_pkl' => $request->id_pkl,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'deadline' => $request->deadline
        ]);

        return redirect()
            ->route('mitra.tugas.index')
            ->with('success', 'Tugas berhasil diperbarui');
    }
    public function destroy($id)
    {
        $tugas = TugasMitra::findOrFail($id);

        $tugas->delete();

        return redirect()
            ->route('mitra.tugas.index')
            ->with('success', 'Tugas berhasil dihapus');
    }
    public function verifikasi(Request $request, $id)
    {
        $submit = \App\Models\TugasMitraSubmit::findOrFail($id);

        if($request->aksi == 'selesai')
        {
            $submit->update([
                'status' => 'selesai',
                'revisi' => false,
                'catatan_revisi' => null
            ]);
        }

        if($request->aksi == 'revisi')
        {
            $submit->update([
                'status' => 'pending',
                'revisi' => true,
                'catatan_revisi' => $request->catatan_revisi
            ]);
        }

        return back()->with('success','Verifikasi berhasil');
    }
}