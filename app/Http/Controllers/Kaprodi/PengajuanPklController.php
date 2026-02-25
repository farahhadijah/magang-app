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
            'verifikasi.user:id,username'
        ])
        ->firstOrFail();

    if (!$pengajuan->bisaDiverifikasiKaprodi()) {
        return redirect()
            ->route('kaprodi.pengajuan.index')
            ->with('warning', 'Pengajuan sudah diproses.');
    }

    // $dosenList = User::query()
    // ->select('id', 'username')
    // ->where('role', 'dosen')
    // ->where('is_active', 1)
    // ->whereHas('dosen', function ($q) use ($prodiId) {
    //     $q->where('prodi_id', $prodiId)
    //       ->where('is_active', 1);
    // })
    // ->with(['dosen:id,nama,prodi_id'])
    // ->get();
    $dosenList = Dosen::where('prodi_id', $prodiId)
    ->where('is_active', 1)
    ->orderBy('nama')
    ->get(['id', 'nama']);

    return view('kaprodi.pengajuan.show', compact('pengajuan', 'dosenList'));
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

    $dosen = \App\Models\Dosen::where('id', $request->id_dosen)
        ->where('is_active', 1)
        ->first();

    if (!$dosen) {
        return back()->with('warning', 'Dosen tidak valid.');
    }

    DB::transaction(function () use ($pengajuan, $request, $dosen) {

        /* ================= VERIFIKASI ================= */

        Verifikasi::create([
            'id_pengajuan_pkl' => $pengajuan->id,
            'id_user' => auth()->user()->getKey(),
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
            'id_dosen'         => $request->id_dosen,
            'tgl_mulai'        => now(),
            'tgl_selesai'      => now()->addDays(60),
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

        $kaprodi = \App\Models\Staff::where('jabatan', 'kaprodi')
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

        // pastikan folder ada
        // if (!Storage::exists('public/surat')) {
        //     Storage::makeDirectory('public/surat');
        // }

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
        $staff = auth()->user()->staff;

        if (!$staff || !$staff->prodi_id) {
            abort(403, 'Kaprodi tidak memiliki prodi.');
        }

        return $staff->prodi_id;
    }
}