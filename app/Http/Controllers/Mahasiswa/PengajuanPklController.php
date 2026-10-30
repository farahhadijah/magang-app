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
use Illuminate\Validation\Rule;
use App\Mail\NotifikasiPengajuanStaffTU;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
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
        $jenisTempat = TempatPkl::JENIS_TEMPAT;
        return view('mahasiswa.pengajuan-pkl', compact('semesterAktif', 'jenisTempat'));
    }

    /**
     * Simpan pengajuan PKL
     */
    
    public function store(Request $request)
    {
        $request->validate([
            'nama_tempat' => 'required|string|max:150',
            'jenis_tempat' => [
                'required',
                Rule::in(TempatPkl::JENIS_TEMPAT),
            ],
            'email' => ['required', 'email:rfc', 'max:255'],
            'lokasi_maps' => 'required|string|max:500',

            'semester' => [
                'required',
                'regex:/^(I|II|III|IV|V|VI|VII|VIII|IX|X)$/',
            ],
            'alamat_asal' => 'required|string|max:500',

            'dokumen_pembayaran' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'dokumen_studi_tour' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:2048',
            // 'dokumen_krs' DIHAPUS — validasi KRS sekarang via API SIAKAD
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        abort_if(!$mahasiswa, 403);

        $pengajuanAktif = PengajuanPkl::where('id_mhs', $mahasiswa->id)
            ->whereIn('status', [
                'pending_tu',
                'diverifikasi_tu',
                'disetujui',
                'pending_kaprodi',
            ])
            ->exists();

        if ($pengajuanAktif) {
            return redirect()
                ->route('mahasiswa.dashboard')
                ->with('error', 'Kamu sudah memiliki pengajuan PKL yang sedang diproses.');
        }

        $namaNormalized = $this->normalizeNamaTempat($request->nama_tempat);
        $email = strtolower(trim($request->email));
        $lokasi = $this->normalizeGoogleMapsLink($request->lokasi_maps);

        DB::transaction(function () use (
            $request,
            $mahasiswa,
            $namaNormalized,
            $email,
            $lokasi
        ) {
            // Tempat dianggap sama hanya jika email dan lokasi sama.
            $tempatPkl = TempatPkl::where('email', $email)
                ->where('lokasi_maps', $lokasi)
                ->first();

            if (!$tempatPkl) {
                $tempatPkl = TempatPkl::create([
                    'nama_tempat' => $request->nama_tempat,
                    'nama_normalized' => $namaNormalized,
                    'jenis_tempat' => $request->jenis_tempat,
                    'email' => $email,
                    'lokasi_maps' => $lokasi,
                ]);
            }

            $pengajuan = PengajuanPkl::create([
                'id_mhs' => $mahasiswa->id,
                'id_tempat_pkl' => $tempatPkl->id,
                'semester' => $request->semester,
                'alamat_asal' => $request->alamat_asal,
                'status' => 'pending_tu',
                'tgl_pengajuan' => now(),
            ]);

            $basePath = "dokumen_pengajuan_pkl/{$mahasiswa->nim}";

            // Dokumen pembayaran
            $pembayaranPath = $request->file('dokumen_pembayaran')
                ->store($basePath, 'public');

            DokumenPengajuan::create([
                'id_pengajuan_pkl' => $pengajuan->id,
                'jenis_dokumen' => DokumenPengajuan::JENIS_PEMBAYARAN,
                'path_file' => $pembayaranPath,
                'status_verifikasi' => 'pending',
            ]);

            // Dokumen studi tour
            $studiTourPath = $request->file('dokumen_studi_tour')
                ->store($basePath, 'public');

            DokumenPengajuan::create([
                'id_pengajuan_pkl' => $pengajuan->id,
                'jenis_dokumen' => DokumenPengajuan::JENIS_STUDI_TOUR,
                'path_file' => $studiTourPath,
                'status_verifikasi' => 'pending',
            ]);  
        });

        
        // Ambil prodi mahasiswa yang mengajukan PKL.
        $prodiId = $mahasiswa->prodi_id;

        $jumlahPengajuanPending = PengajuanPkl::query()
            ->where('status', 'pending_tu')
            ->whereHas('mahasiswa', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })
            ->count();

        if (
            $jumlahPengajuanPending >= 2 &&
            $jumlahPengajuanPending % 2 === 0
        ) {
            $staffTu = DB::table('staff')
                ->where('prodi_id', $prodiId)
                ->where('is_active', 1)
                ->whereNotNull('email')
                ->where('email', '<>', '')
                ->get(['id', 'nama', 'email']);

            foreach ($staffTu as $staff) {
                try {
                    Mail::to($staff->email)->send(
                        new NotifikasiPengajuanStaffTU(
                            namaStaff: $staff->nama,
                            jumlahPengajuan: $jumlahPengajuanPending,
                        )
                    );
                } catch (\Throwable $e) {
                    // Email gagal tidak membatalkan pengajuan mahasiswa.
                    Log::error(
                        'Gagal mengirim notifikasi pengajuan PKL kepada Staff TU.',
                        [
                            'staff_id' => $staff->id,
                            'email' => $staff->email,
                            'jumlah_pengajuan' => $jumlahPengajuanPending,
                            'error' => $e->getMessage(),
                        ]
                    );
                }
            }
        }


        return redirect()
            ->route('mahasiswa.pengajuan.status')
            ->with('success', 'Pengajuan PKL berhasil dikirim dan menunggu verifikasi TU.');
    }


    public function uploadUlangDokumen(Request $request, $id)
    {
        $request->validate([
            'dokumen' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:2048',
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
    private function normalizeGoogleMapsLink($url)
    {
        $url = trim($url);

        // jika shortlink → resolve dulu
        if (str_contains($url, 'maps.app.goo.gl') || str_contains($url, 'goo.gl')) {
            $resolved = $this->resolveGoogleMapsUrl($url);
            if ($resolved) {
                $url = trim($resolved);
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
        // 1. Format paling akurat dari google maps (!3d... !4d...)
        if (preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $url, $match)) {
            return [
                'lat' => round((float) $match[1], 6),
                'lng' => round((float) $match[2], 6),
            ];
        }

        // 2. Format query ?q=lat,lng atau &q=lat,lng atau q=lat,lng
        if (preg_match('/[?&]q=(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $match) || preg_match('/q=(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $match)) {
            return [
                'lat' => round((float) $match[1], 6),
                'lng' => round((float) $match[2], 6),
            ];
        }

        // 3. Fallback jika format @lat,lng
        if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $match)) {
            return [
                'lat' => round((float) $match[1], 6),
                'lng' => round((float) $match[2], 6),
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

}