<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Pkl;

class NilaiController extends Controller
{
    private function getProdiId()
    {
        $dosen = auth()->user()->dosen;

        if (!$dosen || !$dosen->prodi_id) {
            abort(403, 'Kaprodi tidak memiliki prodi.');
        }

        return $dosen->prodi_id;
    }

    public function index()
    {
        $prodiId = $this->getProdiId();

        $pkls = Pkl::where('status', 'selesai')
            ->whereHas('nilaiPkl')
            ->whereHas('pengajuan.mahasiswa', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            })
            ->with([
                'pengajuan.mahasiswa',
                'nilaiPkl'
            ])
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('kaprodi.nilai.index', compact('pkls'));
    }
}