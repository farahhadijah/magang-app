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
    // ambil prodi kaprodi dulu (🔥 WAJIB DI ATAS)
    $dosenKaprodi = auth()->user()->dosen;
    $prodiId      = $dosenKaprodi->prodi_id;

    $pengajuan = PengajuanPkl::query()
        ->whereKey($id)
        ->whereHas('mahasiswa', fn($q) => 
            $q->where('prodi_id', $prodiId)
        )
        ->with([
            'mahasiswa:id,nama,nim,prodi_id,angkatan',
            'mahasiswa.prodi:id,nama',
            'tempatPkl:id,nama_tempat,nama_normalized,jenis_tempat,lokasi_maps',
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

    $dosenList = Dosen::query()
    ->leftJoin('pkl', function ($join) {
        $join->on('dosen.id', '=', 'pkl.id_dosen')
             ->where('pkl.status', 'aktif');
    })
    ->where('dosen.prodi_id', $prodiId)
    ->where('dosen.is_active', 1)
    ->select(
        'dosen.id',
        'dosen.nama',
        'dosen.keahlian',
        DB::raw('COUNT(pkl.id) as total_bimbingan')
    )
    ->groupBy('dosen.id', 'dosen.nama', 'dosen.keahlian')
    ->orderBy('dosen.nama')
    ->get();

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

    /* ================= RIWAYAT TEMPAT PKL (nama tempat + prodi + angkatan + PKL masih aktif) ================= */
    $namaNormalized = $pengajuan->tempatPkl->nama_normalized ?? null;
    $namaTempat = $pengajuan->tempatPkl->nama_tempat ?? null;
    $angkatan = $pengajuan->mahasiswa->angkatan ?? null;

    $jumlahRiwayat = 0;
    $terakhirDigunakan = null;

    if (($namaNormalized || $namaTempat) && $angkatan !== null && $angkatan !== '') {
        $riwayat = PengajuanPkl::where('id', '!=', $pengajuan->id)
            ->whereHas('tempatPkl', function ($q) use ($namaNormalized, $namaTempat) {
                if ($namaNormalized) {
                    $q->where('nama_normalized', $namaNormalized);
                } else {
                    $q->where('nama_tempat', $namaTempat);
                }
            })
            ->whereHas('mahasiswa', function ($q) use ($prodiId, $angkatan) {
                $q->where('prodi_id', $prodiId)
                    ->where('angkatan', $angkatan);
            })
            ->whereHas('pkl', function ($q) {
                $q->where('status', 'aktif');
            })
            ->selectRaw('COUNT(*) as total, MAX(created_at) as terakhir')
            ->first();

        $jumlahRiwayat = $riwayat->total ?? 0;

        if ($riwayat->terakhir) {
            $terakhirDigunakan = Carbon::parse($riwayat->terakhir);
        }
    }

    return view('kaprodi.pengajuan.show', compact(
        'pengajuan',
        'dosenList',
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

    $dosen = Dosen::where('id', $request->id_dosen)
        ->where('is_active', 1)
        ->first();

    if (!$dosen) {
        return back()->with('warning', 'Dosen tidak valid.');
    }

    //  WAJIB SATU PRODI
    if ($dosen->prodi_id != $prodiId) {
        return back()->with('warning', 'Dosen harus dari prodi yang sama.');
    }

    try {
        DB::transaction(function () use ($pengajuan, $dosen, $prodiId) {

            Verifikasi::create([
                'id_pengajuan_pkl' => $pengajuan->id,
                'id_user'          => auth()->user()->getKey(),
                'level'            => 'kaprodi',
                'status'           => 'approved',
                'tgl_verifikasi'   => now(),
            ]);

            $pengajuan->update([
                'status' => 'disetujui',
            ]);

            $pkl = Pkl::create([
                'id_pengajuan_pkl' => $pengajuan->id,
                'id_dosen'         => $dosen->id,
                'tgl_mulai'        => now(),
                'tgl_selesai'      => null,
                'status'           => 'aktif',
            ]);

            // nomor surat
            $bulanRomawi = [
                1 => 'I','II','III','IV','V','VI',
                'VII','VIII','IX','X','XI','XII'
            ];

            $noSurat = sprintf(
                "%03d/UNISLA/PKL/%s/%s",
                SuratPengantar::count() + 1,
                $bulanRomawi[now()->month],
                now()->year
            );

            // ambil kaprodi dari prodi yg sama
            $kaprodi = Dosen::where('jabatan', 'Kaprodi')
                ->where('prodi_id', $prodiId)
                ->where('is_active', 1)
                ->first();

            $pdf = Pdf::loadView('surat.pengantar', [
                'pengajuan' => $pengajuan,
                'pkl'       => $pkl,
                'noSurat'   => $noSurat,
                'kaprodi'   => $kaprodi,
            ])->setPaper('A4', 'portrait');

            $path = 'surat/surat_pkl_' . $pkl->id . '.pdf';
            Storage::disk('public')->put($path, $pdf->output());

            SuratPengantar::create([
                'id_pkl'     => $pkl->id,
                'no_surat'   => $noSurat,
                'tgl_terbit' => now(),
                'path_file'  => $path,
            ]);
        });

        return redirect()->route('kaprodi.pengajuan.index')
            ->with('success', 'Pengajuan disetujui & surat berhasil dibuat.');

    } catch (\Exception $e) {
        \Log::error($e);
        return back()->with('error', 'Terjadi kesalahan.');
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