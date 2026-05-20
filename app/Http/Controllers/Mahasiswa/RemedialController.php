<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\FormulirRemedial;
use App\Services\SiakadService;
use Illuminate\Support\Facades\Auth;

class RemedialController extends Controller
{
    public function index(SiakadService $siakadService)
    {
        $mahasiswa = Auth::user()?->mahasiswa;
        abort_if(!$mahasiswa, 404);

        /**
         * Ambil daftar matakuliah nilai D/E
         */
        $matakuliahRemedial = $siakadService
            ->getNilaiBermasalah($mahasiswa->nim);

        if (empty($matakuliahRemedial)) {
            return redirect()
                ->route('mahasiswa.pengajuan.create')
                ->with('success', 'Anda tidak memiliki nilai D/E.');
        }
        /**
         * Hitung total biaya remedial
         */
        $totalBiaya = $siakadService
            ->hitungBiayaRemedial($mahasiswa->nim);

        /**
         * Ambil fakultas mahasiswa
         */
        $fakultasId = $mahasiswa
            ->prodi
            ->fakultas_id;

        /**
         * Ambil formulir remedial sesuai fakultas
         */
        $formulirRemedial = FormulirRemedial::where(
            'fakultas_id',
            $fakultasId
        )->latest()
        ->first();

        return view('mahasiswa.remedial.index', compact(
            'mahasiswa',
            'matakuliahRemedial',
            'totalBiaya',
            'formulirRemedial'
        ));
    }
}