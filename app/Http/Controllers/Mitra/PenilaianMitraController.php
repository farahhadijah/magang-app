<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Pkl;
use App\Models\PenilaianMitra;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PenilaianMitraController extends Controller
{
    /**
     * Daftar mahasiswa aktif yang bisa dinilai
     */
    public function index()
    {
        $mitra = Auth::user()->mitra;

        if (!$mitra) {
            abort(403, 'Data mitra tidak ditemukan.');
        }

        $pkls = Pkl::where('status', 'aktif')

            ->whereHas('pengajuanPkl', function ($q) use ($mitra) {
                $q->where('id_tempat_pkl', $mitra->tempat_pkl_id);
            })

            ->with([
                'mahasiswa.user',
                'mahasiswa.prodi',
                'penilaianMitra'
            ])

            ->get();

        return view('mitra.penilaian.index', compact('pkls'));
    }

    /**
     * Form input/edit nilai
     */
    public function form($id)
    {
        $mitra = Auth::user()->mitra;

        $pkl = Pkl::where('id', $id)

            ->where('status', 'aktif')

            ->whereHas('pengajuanPkl', function ($q) use ($mitra) {
                $q->where('id_tempat_pkl', $mitra->tempat_pkl_id);
            })

            ->with([
                'mahasiswa.user',
                'mahasiswa.prodi',
                'penilaianMitra'
            ])

            ->firstOrFail();

        return view('mitra.penilaian.form', compact('pkl'));
    }

    /**
     * Simpan nilai
     */
    public function store(Request $request, $id)
    {
        $mitra = Auth::user()->mitra;

        $pkl = Pkl::where('id', $id)

            ->where('status', 'aktif')

            ->whereHas('pengajuanPkl', function ($q) use ($mitra) {
                $q->where('id_tempat_pkl', $mitra->tempat_pkl_id);
            })

            ->with([
                'mahasiswa.user',
                'mahasiswa.prodi'
            ])

            ->firstOrFail();

        $request->validate([

            'kedisiplinan' => 'required|integer|min:0|max:100',
            'kreativitas' => 'required|integer|min:0|max:100',
            'ketekunan' => 'required|integer|min:0|max:100',
            'kerjasama' => 'required|integer|min:0|max:100',
            'kejujuran' => 'required|integer|min:0|max:100',
            'kesopanan' => 'required|integer|min:0|max:100',
            'semangat_kerja' => 'required|integer|min:0|max:100',
            'kedalaman_materi' => 'required|integer|min:0|max:100',

        ]);

        // =========================
        // HITUNG RATA-RATA
        // =========================

        $nilai = [

            $request->kedisiplinan,
            $request->kreativitas,
            $request->ketekunan,
            $request->kerjasama,
            $request->kejujuran,
            $request->kesopanan,
            $request->semangat_kerja,
            $request->kedalaman_materi,

        ];

        $rataRata = array_sum($nilai) / count($nilai);

        // =========================
        // HITUNG GRADE
        // =========================

        if ($rataRata >= 86) {

            $grade = 'A';

        } elseif ($rataRata >= 71) {

            $grade = 'B';

        } elseif ($rataRata >= 56) {

            $grade = 'C';

        } else {

            $grade = 'D';
        }

        // =========================
        // SIMPAN / UPDATE
        // =========================

        $existing = PenilaianMitra::where('id_pkl', $pkl->id)->first();

        $penilaian = PenilaianMitra::updateOrCreate(

            [
                'id_pkl' => $pkl->id
            ],

            [

                'kedisiplinan' => $request->kedisiplinan,
                'kreativitas' => $request->kreativitas,
                'ketekunan' => $request->ketekunan,
                'kerjasama' => $request->kerjasama,
                'kejujuran' => $request->kejujuran,
                'kesopanan' => $request->kesopanan,
                'semangat_kerja' => $request->semangat_kerja,
                'kedalaman_materi' => $request->kedalaman_materi,

                'rata_rata' => round($rataRata, 2),
                'grade' => $grade,

                'verification_token' =>
                    $existing?->verification_token ?? Str::uuid(),

                'tgl_input' => now(),
            ]
        );

        // =========================
        // GENERATE PDF
        // =========================

        $pdf = Pdf::loadView(
            'mitra.penilaian.pdf',
            [
                'pkl' => $pkl,
                'penilaian' => $penilaian
            ]
        );

        // nama file
        $filename = 'penilaian-' . $pkl->id . '.pdf';

        // lokasi file
        $path = 'penilaian/' . $filename;

        // simpan pdf
        Storage::disk('public')->put(
            $path,
            $pdf->output()
        );

        // update path file
        $penilaian->update([
            'file_pdf' => $path
        ]);

        return redirect()
            ->route('mitra.penilaian')
            ->with(
                'success',
                'Nilai berhasil disimpan dan PDF berhasil dibuat.'
            );
    }
}