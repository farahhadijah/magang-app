<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\TugasMitra;
use App\Models\TugasMitraSubmit;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function index()
    {
        $mahasiswa = auth()->user()->mahasiswa;

        $pengajuan = $mahasiswa->pengajuanPkl()
                        ->latest()
                        ->first();

        $tugas = collect();

        if ($pengajuan && $pengajuan->pkl) {

            $pklId = $pengajuan->pkl->id;

            $tugas = TugasMitra::with(['submit' => function ($q) use ($pklId) {
                            $q->where('id_pkl', $pklId);
                        }])
                        ->where('id_pkl', $pklId)
                        ->latest()
                        ->get();
        }

        return view('mahasiswa.tugas.index', compact('tugas'));
    }

    public function show($id)
    {
        $mahasiswa = auth()->user()->mahasiswa;

        $pengajuan = $mahasiswa->pengajuanPkl()
                        ->latest()
                        ->first();

        $tugas = TugasMitra::findOrFail($id);

        $submit = null;

        if ($pengajuan && $pengajuan->pkl) {

            $submit = TugasMitraSubmit::where('id_tugas', $tugas->id)
                        ->where('id_pkl', $pengajuan->pkl->id)
                        ->first();
        }

        return view('mahasiswa.tugas.show', compact('tugas', 'submit'));
    }

    public function submit(Request $request, $id)
{
    $request->validate([
        'laporan' => 'required',
        'file' => 'nullable|file|max:2048'
    ]);

    $mahasiswa = auth()->user()->mahasiswa;

    $pengajuan = $mahasiswa->pengajuanPkl()
                    ->latest()
                    ->first();

    if (!$pengajuan || !$pengajuan->pkl) {
        abort(403, 'PKL tidak ditemukan.');
    }

    $pklId = $pengajuan->pkl->id;

    $submit = TugasMitraSubmit::where('id_tugas', $id)
                ->where('id_pkl', $pklId)
                ->first();

    $filePath = null;

    if ($request->file('file')) {
        $filePath = $request->file('file')->store('tugas', 'public');
    }

    if ($submit) {

        // UPDATE jika revisi / upload ulang
        $submit->update([
            'laporan' => $request->laporan,
            'file' => $filePath ?? $submit->file,
            'status' => 'pending',
            'revisi' => false,
            'catatan_revisi' => null
        ]);

    } else {

        // SUBMIT pertama
        TugasMitraSubmit::create([
            'id_tugas' => $id,
            'id_pkl' => $pklId,
            'laporan' => $request->laporan,
            'file' => $filePath,
            'status' => 'pending',
            'revisi' => false
        ]);

    }

    return redirect()
        ->route('mahasiswa.tugas')
        ->with('success', 'Tugas berhasil dikumpulkan');
}
}