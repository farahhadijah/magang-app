<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PengajuanPkl;
use App\Models\TempatPkl;
use App\Models\DokumenPengajuan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\SuratPengantar;

class PengajuanPklController extends Controller
{
    /**
     * Form pengajuan PKL
     */
    public function create()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        abort_if(!$mahasiswa, 403);

        $pengajuanAktif = PengajuanPkl::where('id_mhs', $mahasiswa->id)
            ->whereIn('status', [
                'pending_tu',
                'diverifikasi_tu',
                'disetujui',
                'pending_kaprodi'
            ])
            ->exists();

        if ($pengajuanAktif) {
            return redirect()
                ->route('mahasiswa.dashboard')
                ->with('error', 'Kamu sudah memiliki pengajuan PKL yang sedang diproses.');
        }
        $semesterAktif = $this->hitungSemester($mahasiswa->angkatan);
        $jumlahWajibKhs = $semesterAktif - 1;

        return view('mahasiswa.pengajuan-pkl', compact('semesterAktif', 'jumlahWajibKhs'));
    }

    /**
     * Simpan pengajuan PKL
     */
    public function store(Request $request)
{
    $request->validate([
        'nama_tempat'  => 'required|string|max:150',
        'jenis_tempat' => 'required|in:Pemerintah,Sekolah,PT,CV',
        'no_hp'        => ['required', 'regex:/^08[0-9]{7,14}$/'],
        'lokasi_maps' => 'required|string|max:500',
        'semester' => [
            'required',
            'regex:/^(I|II|III|IV|V|VI|VII|VIII|IX|X)$/'
        ],
        'alamat_asal' => 'required|string|max:500',

        'dokumen_khs'     => 'required|array|min:1',
        'dokumen_khs.*'   => 'file|mimes:pdf,doc,docx|max:2048',
        'dokumen_pembayaran' => 'required|file|mimes:pdf,jpg,png|max:2048',
        'dokumen_studi_tour' => 'required|file|mimes:pdf,doc,docx|max:2048',
        'dokumen_form_pkn'     => 'required|file|mimes:pdf|max:2048',
        'dokumen_krs' => 'required|file|mimes:pdf|max:2048',
    ]);

    $mahasiswa = Auth::user()->mahasiswa;
    abort_if(!$mahasiswa, 403);

    // 🔥 hitung semester otomatis
    $semesterAktif = $this->hitungSemester($mahasiswa->angkatan);

    // 🔥 jumlah KHS wajib = semester - 1
    $jumlahWajibKhs = $semesterAktif - 1;

    // 🔥 jumlah file yang dikirim user
    $jumlahUploadKhs = count($request->file('dokumen_khs'));

    // 🔥 VALIDASI KERAS
    if ($jumlahUploadKhs !== $jumlahWajibKhs) {
        return back()
            ->withInput()
            ->with('error', "Jumlah KHS tidak sesuai! Semester kamu saat ini: {$semesterAktif}, maka wajib upload {$jumlahWajibKhs} KHS.");
    }

    $mahasiswa = Auth::user()->mahasiswa;
    abort_if(!$mahasiswa, 403);

    $pengajuanAktif = PengajuanPkl::where('id_mhs', $mahasiswa->id)
    ->whereIn('status', [
        'pending_tu',
        'diverifikasi_tu',
        'pending_kaprodi'
    ])
    ->exists();

    if ($pengajuanAktif) {
        return redirect()
            ->route('mahasiswa.dashboard')
            ->with('error', 'Kamu sudah memiliki pengajuan PKL yang sedang diproses.');
    }

    $namaNormalized = $this->normalizeNamaTempat($request->nama_tempat);

    DB::transaction(function () use ($request, $mahasiswa, $namaNormalized) {

        $tempatPkl = TempatPkl::where('nama_normalized', $namaNormalized)
            ->where('no_hp', $request->no_hp)
            ->first();

        if (!$tempatPkl) {
            $lokasi = $this->normalizeGoogleMapsLink($request->lokasi_maps);

            $tempatPkl = TempatPkl::create([
                'nama_tempat'     => $request->nama_tempat,
                'nama_normalized' => $namaNormalized,
                'jenis_tempat'    => $request->jenis_tempat,
                'no_hp'           => $request->no_hp,
                'lokasi_maps'     => $lokasi,
            ]);
        }

        $pengajuan = PengajuanPkl::create([
            'id_mhs'        => $mahasiswa->id,
            'id_tempat_pkl' => $tempatPkl->id,
            'semester'      => $request->semester,
            'alamat_asal'   => $request->alamat_asal,
            'status'        => 'pending_tu',
            'tgl_pengajuan' => now(),
        ]);

        $basePath = "dokumen_pengajuan_pkl/{$mahasiswa->nim}";

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ MULTIPLE KHS
        |--------------------------------------------------------------------------
        */
        foreach ($request->file('dokumen_khs') as $file) {
            $path = $file->store($basePath, 'public');

            DokumenPengajuan::create([
                'id_pengajuan_pkl' => $pengajuan->id,
                'jenis_dokumen'    => DokumenPengajuan::JENIS_KHS,
                'path_file'        => $path,
                'status_verifikasi'=> 'pending',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ PEMBAYARAN (LAMA - TETAP)
        |--------------------------------------------------------------------------
        */
        $pembayaranPath = $request->file('dokumen_pembayaran')
            ->store($basePath, 'public');

        DokumenPengajuan::create([
            'id_pengajuan_pkl' => $pengajuan->id,
            'jenis_dokumen'    => DokumenPengajuan::JENIS_PEMBAYARAN,
            'path_file'        => $pembayaranPath,
            'status_verifikasi'=> 'pending',
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3️⃣ STUDI TOUR (LAMA - TETAP)
        |--------------------------------------------------------------------------
        */
        $studiTourPath = $request->file('dokumen_studi_tour')
            ->store($basePath, 'public');

        DokumenPengajuan::create([
            'id_pengajuan_pkl' => $pengajuan->id,
            'jenis_dokumen'    => DokumenPengajuan::JENIS_STUDI_TOUR,
            'path_file'        => $studiTourPath,
            'status_verifikasi'=> 'pending',
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4️⃣ FORM PKN (BARU)
        |--------------------------------------------------------------------------
        */
        $formPknPath = $request->file('dokumen_form_pkn')
            ->store($basePath, 'public');

        DokumenPengajuan::create([
            'id_pengajuan_pkl' => $pengajuan->id,
            'jenis_dokumen'    => DokumenPengajuan::JENIS_FORM_PKN,
            'path_file'        => $formPknPath,
            'status_verifikasi'=> 'pending',
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5️⃣ KRS (BARU - SINGLE FILE)
        |--------------------------------------------------------------------------
        */
        $krsPath = $request->file('dokumen_krs')
            ->store($basePath, 'public');

        DokumenPengajuan::create([
            'id_pengajuan_pkl' => $pengajuan->id,
            'jenis_dokumen'    => DokumenPengajuan::JENIS_KRS,
            'path_file'        => $krsPath,
            'status_verifikasi'=> 'pending',
        ]);
    });

    return redirect()
        ->route('mahasiswa.pengajuan.status')
        ->with('success', 'Pengajuan PKL berhasil dikirim dan menunggu verifikasi TU.');
}

    /**
     * Upload ulang dokumen invalid
     */
    public function uploadUlangDokumen(Request $request, $id)
    {
        $request->validate([
            'dokumen' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        abort_if(!$mahasiswa, 403);

        $dokumen = DokumenPengajuan::with('pengajuan')->findOrFail($id);
        $pengajuan = $dokumen->pengajuan;

        abort_if(
            $pengajuan->id_mhs !== $mahasiswa->id ||
            $pengajuan->status !== 'ditolak_tu' ||
            $dokumen->status_verifikasi !== 'invalid',
            403
        );

        DB::transaction(function () use ($request, $dokumen, $pengajuan, $mahasiswa) {

            $path = $request->file('dokumen')
                ->store("dokumen_pengajuan_pkl/{$mahasiswa->nim}", 'public');

            $dokumen->update([
                'path_file'        => $path,
                'status_verifikasi'=> 'pending',
                'catatan'          => null,
            ]);

            $hasInvalid = $pengajuan->dokumenPengajuan()
                ->where('status_verifikasi', 'invalid')
                ->exists();

            if (!$hasInvalid) {
                $pengajuan->update([
                    'status'     => 'pending_tu',
                    'catatan_tu' => null,
                ]);
            }
        });

        return back()->with('success', 'Dokumen berhasil diupload ulang dan menunggu verifikasi TU.');
    }

    // hitung semester
    private function hitungSemester($angkatan)
    {
        $tahunSekarang = now()->year;

        // selisih tahun × 2 semester
        $semester = ($tahunSekarang - $angkatan) * 2;

        // asumsi: kalau sudah lewat tengah tahun → +1 semester
        if (now()->month >= 7) {
            $semester += 1;
        }

        return max(1, $semester);
    }
    public function downloadSuratPengantar($id)
{
    $mahasiswa = Auth::user()->mahasiswa;
    abort_if(!$mahasiswa, 403);

    $surat = SuratPengantar::findOrFail($id);

    $path = $surat->path_file;

    if (!Storage::disk('public')->exists($path)) {
        abort(404, 'File tidak ditemukan.');
    }

    return Storage::disk('public')->download(
        $path,
        'Surat_Pengantar_PKL.pdf'
    );
}
        private function normalizeGoogleMapsLink($url)
{
    // jika shortlink → resolve dulu
    if (str_contains($url, 'maps.app.goo.gl') || str_contains($url, 'goo.gl')) {

        $resolved = $this->resolveGoogleMapsUrl($url);

        if ($resolved) {
            $url = $resolved;
        }
    }

    // ambil koordinat dari URL
    $coords = $this->extractCoordinates($url);

    if ($coords) {
        return "https://www.google.com/maps?q={$coords['lat']},{$coords['lng']}";
    }

    // fallback jika gagal
    return $url;
}

    private function resolveGoogleMapsUrl($url)
    {
        try {
            $response = Http::withOptions([
                'allow_redirects' => true,
            ])->get($url);

            return (string) $response->effectiveUri();

        } catch (\Exception $e) {
            return null;
        }
    }
    private function extractCoordinates($url)
{
    // format paling akurat dari google maps
    if (preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $url, $match)) {
        return [
            'lat' => $match[1],
            'lng' => $match[2]
        ];
    }

    // fallback jika format !3d !4d tidak ada
    if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $match)) {
        return [
            'lat' => $match[1],
            'lng' => $match[2]
        ];
    }

    return null;
}

    /**
     * AJAX - Cek kemiripan nama tempat (soft warning)
     */
    public function cekKemiripanAjax(Request $request)
    {
        $request->validate([
            'nama_tempat' => 'required|string'
        ]);

        $mirip = $this->cekKemiripanTempat($request->nama_tempat);

        if ($mirip) {
            return response()->json([
                'mirip' => true,
                'nama_mirip' => $mirip->nama_tempat
            ]);
        }

        return response()->json([
            'mirip' => false
        ]);
    }

    /**
     * Logic cek kemiripan (Levenshtein)
     */
    private function cekKemiripanTempat($namaInput)
    {
        $namaInputNormalized = $this->normalizeNamaTempat($namaInput);

        $semuaTempat = TempatPkl::select('id', 'nama_tempat', 'nama_normalized')
            ->get();

        foreach ($semuaTempat as $tempat) {

            if ($namaInputNormalized === $tempat->nama_normalized) {
                continue;
            }

            $distance = levenshtein(
                $namaInputNormalized,
                $tempat->nama_normalized
            );

            $maxLength = max(
                strlen($namaInputNormalized),
                strlen($tempat->nama_normalized)
            );

            $similarity = (1 - ($distance / $maxLength)) * 100;

            if ($similarity >= 80) {
                return $tempat;
            }
        }

        return null;
    }

    /**
     * Normalisasi nama tempat
     */
    private function normalizeNamaTempat($nama)
    {
        $nama = strtolower($nama);
        $nama = preg_replace('/[^a-z0-9\s]/', '', $nama);
        $nama = preg_replace('/\s+/', ' ', $nama);
        return trim($nama);
    }

    /**
     * Status pengajuan mahasiswa
     */
    public function status()
{
    $mahasiswa = Auth::user()->mahasiswa;
    abort_if(!$mahasiswa, 403);

    $pengajuan = PengajuanPkl::with([
            'tempatPkl',
            'dokumenPengajuan',
            'pkl.suratPengantar'
        ])
        ->where('id_mhs', $mahasiswa->id)
        ->latest()
        ->first();

    // 🔥 Hitung dokumen invalid (yang belum diperbaiki)
    $jumlahInvalid = 0;

    if ($pengajuan) {
        $jumlahInvalid = $pengajuan->dokumenPengajuan
            ->where('status_verifikasi', 'invalid')
            ->count();
    }

    return view('mahasiswa.status-pengajuan', compact(
        'pengajuan',
        'jumlahInvalid'
    ));
}

}