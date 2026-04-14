<?php
namespace App\Http\Controllers\Kaprodi;
use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\PengajuanPkl;
use App\Models\Pkl;
use App\Models\SuratPengantar;
use App\Models\User;
use App\Models\Verifikasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengajuanPklController extends Controller
{
    /* ================= DASHBOARD ================= */
    public function dashboard()
{
    $prodiId = $this->getProdiId();

    $totalMahasiswa = PengajuanPkl::whereIn('status', [
        'pending_tu',
        'pending_kaprodi'
    ])
    ->whereHas('mahasiswa', function ($q) use ($prodiId) {
        $q->where('prodi_id', $prodiId);
    })
    ->distinct('id_mhs')
    ->count('id_mhs');

    $totalMenunggu = PengajuanPkl::where('status', 'pending_kaprodi')
        ->whereHas('mahasiswa', function ($q) use ($prodiId) {
            $q->where('prodi_id', $prodiId);
        })
        ->whereHas('verifikasi', function ($q) {
            $q->where('level', 'tu')
              ->where('status', 'approved');
        })
        ->count();

    $totalAktif = Pkl::where('status', 'aktif')
        ->whereHas('pengajuan.mahasiswa', function ($q) use ($prodiId) {
            $q->where('prodi_id', $prodiId);
        })
        ->count();

    $totalSelesai = Pkl::where('status', 'selesai')
        ->whereHas('pengajuan.mahasiswa', function ($q) use ($prodiId) {
            $q->where('prodi_id', $prodiId);
        })
        ->count();

    return view('kaprodi.dashboard', compact(
        'totalMahasiswa',
        'totalMenunggu',
        'totalAktif',
        'totalSelesai'
    ));
}
    /* ================= LIST PENGAJUAN ================= */
    public function index()
{
    $prodiId = $this->getProdiId();

    $pengajuans = PengajuanPkl::with(['mahasiswa', 'tempatPkl'])
        ->munculUntukKaprodi()
        ->whereHas('mahasiswa', function ($q) use ($prodiId) {
            $q->where('prodi_id', $prodiId);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(15);

    return view('kaprodi.pengajuan.index', compact('pengajuans'));
}
    /* ================= DETAIL ================= */
    public function show($id)
{
    $prodiId = $this->getProdiId();

    $pengajuan = PengajuanPkl::query()
        ->whereKey($id)
        ->whereHas('mahasiswa', fn($q) => 
            $q->where('prodi_id', $prodiId)
        )
        ->with([
            'mahasiswa:id,nama,nim,prodi_id',
            'mahasiswa.prodi:id,nama',
            'tempatPkl:id,nama_tempat,jenis_tempat,lokasi_maps',
            'dokumenPengajuan:id,id_pengajuan_pkl,jenis_dokumen,path_file',

            'verifikasi:id,id_pengajuan_pkl,id_user,level,status,tgl_verifikasi',
            'verifikasi.user:id,username,role,staff_id,mahasiswa_id,dosen_id',
            'verifikasi.user.staff:id,nama',
            'verifikasi.user.mahasiswa:id,nama',
            'verifikasi.user.dosen:id,nama'
        ])
        ->firstOrFail();

    if (!$pengajuan->bisaDiverifikasiKaprodi()) {
        return redirect()
            ->route('kaprodi.pengajuan.index')
            ->with('warning', 'Pengajuan sudah diproses.');
    }

    // ambil prodi + fakultas dari kaprodi
    $dosenKaprodi = auth()->user()->dosen;
    $prodiId      = $dosenKaprodi->prodi_id;
    $fakultasId   = $dosenKaprodi->prodi->fakultas_id;

    // ambil semua dosen 1 fakultas + relasi prodi
    $dosenAll = Dosen::with('prodi')
    ->whereHas('prodi', function ($q) use ($fakultasId) {
        $q->where('fakultas_id', $fakultasId);
    })
    ->where('is_active', 1)
    ->orderBy('nama')
    ->get(['id', 'nama', 'prodi_id', 'keahlian']);

    // grouping berdasarkan prodi
    $dosenGrouped = $dosenAll->groupBy('prodi_id');

    // urutkan: prodi sendiri di atas
    $dosenGrouped = $dosenGrouped->sortByDesc(function ($items, $key) use ($prodiId) {
        return $key == $prodiId ? 1 : 0;
    });

    /* ================= HITUNG JARAK ================= */
    $jarak = null;

    $mapsUrl = $pengajuan->tempatPkl->lokasi_maps ?? null;

    $kampusLat = -7.1224094;
    $kampusLng = 112.4223971;

    $coords = $this->extractLatLng($mapsUrl);

    if ($coords) {
        $jarak = $this->hitungJarak(
            $kampusLat,
            $kampusLng,
            $coords['lat'],
            $coords['lng']
        );
    }
    /* ================= RIWAYAT TEMPAT PKL ================= */
    $tempatId = $pengajuan->tempatPkl->id ?? null;

    $jumlahRiwayat = 0;
    $terakhirDigunakan = null;

    if ($tempatId) {

        $riwayat = PengajuanPkl::where('id_tempat_pkl', $tempatId)
            ->where('id', '!=', $pengajuan->id)
            ->selectRaw('COUNT(*) as total, MAX(created_at) as terakhir')
            ->first();

        $jumlahRiwayat = $riwayat->total ?? 0;

        if ($riwayat->terakhir) {
            $terakhirDigunakan = Carbon::parse($riwayat->terakhir);
        }
    }

    return view('kaprodi.pengajuan.show', compact(
        'pengajuan',
        'dosenGrouped',
        'jarak',
        'jumlahRiwayat',
        'terakhirDigunakan'
    ));
    }
    /* ================= APPROVE ================= */

    public function approve(Request $request, $id)
{
    $request->validate([
        'id_dosen' => 'required|exists:dosen,id',
    ]);

    $prodiId = $this->getProdiId();

    $pengajuan = PengajuanPkl::with([
        'mahasiswa.prodi',
        'tempatPkl',
        'pkl'
    ])
    ->whereHas('mahasiswa', function ($q) use ($prodiId) {
        $q->where('prodi_id', $prodiId);
    })
    ->findOrFail($id);

    if (!$pengajuan->bisaDiverifikasiKaprodi()) {
        return back()->with('warning', 'Pengajuan sudah diproses.');
    }

    if ($pengajuan->pkl) {
        return back()->with('warning', 'PKL sudah dibuat sebelumnya.');
    }

    $dosen = \App\Models\Dosen::with('prodi')
    ->where('id', $request->id_dosen)
    ->where('is_active', 1)
    ->first();

    // ✅ FIX: cukup cek dosen saja
    if (!$dosen) {
        return back()->with('warning', 'Dosen tidak valid.');
    }

    $fakultasId = auth()->user()->dosen->prodi->fakultas_id;

    if ($dosen->prodi->fakultas_id != $fakultasId) {
        return back()->with('warning', 'Dosen harus dari fakultas yang sama.');
    }

    try {

        DB::transaction(function () use ($pengajuan, $dosen) {

            /* ================= VERIFIKASI ================= */
            Verifikasi::create([
                'id_pengajuan_pkl' => $pengajuan->id,
                'id_user'          => auth()->user()->getKey(),
                'level'            => 'kaprodi',
                'status'           => 'approved',
                'catatan'          => null,
                'tgl_verifikasi'   => now(),
            ]);

            $pengajuan->update([
                'status' => 'disetujui',
            ]);

            /* ================= BUAT PKL ================= */
            $pkl = Pkl::create([
                'id_pengajuan_pkl' => $pengajuan->id,
                'id_dosen'         => $dosen->id, // ✅ FIX: pakai dosen.id
                'tgl_mulai'        => now(),
                'tgl_selesai'      => null, // ✅ sesuai konsep baru
                'status'           => 'aktif',
            ]);

            /* ================= GENERATE NOMOR SURAT ================= */
            $bulanRomawi = [
                1 => 'I','II','III','IV','V','VI',
                'VII','VIII','IX','X','XI','XII'
            ];

            $urutan = SuratPengantar::count() + 1;

            $noSurat = sprintf(
                "%03d/UNISLA/PKL/%s/%s",
                $urutan,
                $bulanRomawi[now()->month],
                now()->year
            );

            /* ================= AMBIL DATA KAPRODI ================= */
            $kaprodi = \App\Models\Dosen::where('jabatan', 'kaprodi')
                ->where('is_active', 1)
                ->first();

            /* ================= GENERATE PDF ================= */
            $pdf = Pdf::loadView('surat.pengantar', [
                'pengajuan' => $pengajuan,
                'pkl'       => $pkl,
                'noSurat'   => $noSurat,
                'kaprodi'   => $kaprodi,
            ])->setPaper('A4', 'portrait');

            $filename = 'surat_pkl_' . $pkl->id . '.pdf';
            $path = 'surat/' . $filename;

            Storage::disk('public')->put($path, $pdf->output());

            /* ================= SIMPAN KE DATABASE ================= */
            SuratPengantar::create([
                'id_pkl'     => $pkl->id,
                'no_surat'   => $noSurat,
                'tgl_terbit' => now(),
                'path_file'  => $path,
            ]);
        });

        return redirect()->route('kaprodi.pengajuan.index')
            ->with('success', 'Pengajuan disetujui, PKL aktif, dan Surat berhasil dibuat.');

    } catch (\Exception $e) {

        \Log::error($e); // 🔥 biar bisa cek di log

        return back()->with('error', 'Terjadi kesalahan saat approve.');
    }
}

    /* ================= REJECT ================= */

    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string|max:255',
        ]);

        $prodiId = $this->getProdiId();

        $pengajuan = PengajuanPkl::whereHas('mahasiswa', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            })
            ->findOrFail($id);

        if (!$pengajuan->bisaDiverifikasiKaprodi()) {
            return back()->with('warning', 'Pengajuan sudah diproses.');
        }

        DB::transaction(function () use ($pengajuan, $request) {

            Verifikasi::create([
                'id_pengajuan_pkl' => $pengajuan->id,
                'id_user' => auth()->user()->getKey(),
                'level'            => 'kaprodi',
                'status'           => 'rejected',
                'catatan'          => $request->catatan,
                'tgl_verifikasi'   => now(),
            ]);

            $pengajuan->update([
                'status'          => 'ditolak_kaprodi',
                'catatan_kaprodi' => $request->catatan,
            ]);
        });

        return redirect()->route('kaprodi.pengajuan.index')
            ->with('warning', 'Pengajuan PKL berhasil ditolak Kaprodi.');
    }

    /* ================= HISTORI ================= */

    public function histori()
    {
        $prodiId = $this->getProdiId();

        $pengajuans = PengajuanPkl::with([
                'mahasiswa',
                'tempatPkl',
                'verifikasi' => function ($q) {
                    $q->where('level', 'kaprodi');
                }
            ])
            ->whereHas('mahasiswa', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            })
            ->whereHas('verifikasi', function ($q) {
                $q->where('level', 'kaprodi');
            })
            ->orderByDesc('updated_at')
            ->paginate(15);
        return view('kaprodi.pengajuan.histori', compact('pengajuans'));
    }
    private function getProdiId()
    {
        $dosen = auth()->user()->dosen;

        if (!$dosen || !$dosen->prodi_id) {
            abort(403, 'Kaprodi tidak memiliki prodi.');
        }

        return $dosen->prodi_id;
    }

    private function extractLatLng($url)
    {
        if (!$url) return null;

        // format ?q=lat,lng
        if (preg_match('/q=(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $matches)) {
            return [
                'lat' => (float)$matches[1],
                'lng' => (float)$matches[2]
            ];
        }

        // format @lat,lng
        if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $matches)) {
            return [
                'lat' => (float)$matches[1],
                'lng' => (float)$matches[2]
            ];
        }

        return null;
    }
    private function hitungJarak($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }
}