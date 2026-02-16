<?php
namespace App\Http\Controllers\Kaprodi;
use App\Models\Pkl;
use App\Models\User;
use App\Models\Verifikasi;
use App\Models\PengajuanPkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\SuratPengantar;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
class PengajuanPklController extends Controller
{
    /* ================= DASHBOARD ================= */
    public function dashboard()
    {
        $totalMahasiswa = PengajuanPkl::distinct('id_mhs')->count();
        $totalMenunggu = PengajuanPkl::where('status', 'pending_kaprodi')
            ->whereHas('verifikasi', function ($q) {
                $q->where('level', 'tu')
                  ->where('status', 'approved');
            })->count();
        $totalAktif = Pkl::where('status', 'aktif')->count();
        $totalSelesai = Pkl::where('status', 'selesai')->count();
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
        $pengajuans = PengajuanPkl::with(['mahasiswa', 'tempatPkl'])
            ->munculUntukKaprodi()
            ->orderBy('created_at', 'desc')
            ->get();
        return view('kaprodi.pengajuan.index', compact('pengajuans'));
    }
    /* ================= DETAIL ================= */
    public function show($id)
    {
        $pengajuan = PengajuanPkl::with([
            'mahasiswa.prodi',
            'tempatPkl',
            'dokumenPengajuan',
            'verifikasi.user'
        ])->findOrFail($id);

        $prodiId = $pengajuan->mahasiswa->prodi_id;

        // 🔥 Filter dosen sesuai prodi mahasiswa
        $dosenList = User::where('role', 'dosen')
            ->where('is_active', 1)
            ->whereHas('dosen', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId)
                  ->where('is_active', 1);
            })
            ->with('dosen')
            ->get();

        return view('kaprodi.pengajuan.show', compact('pengajuan', 'dosenList'));
    }

    /* ================= APPROVE ================= */

    public function approve(Request $request, $id)
{
    $request->validate([
        'id_dosen' => 'required|exists:dosen,id',
    ]);

    $pengajuan = PengajuanPkl::with([
        'mahasiswa.prodi',
        'tempatPkl',
        'pkl'
    ])->findOrFail($id);

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

        $pengajuan = PengajuanPkl::findOrFail($id);

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

    public function historiDitolak()
    {
        $pengajuans = PengajuanPkl::with(['mahasiswa', 'tempatPkl'])
            ->whereHas('verifikasi', function ($q) {
                $q->where('level', 'kaprodi')
                  ->where('status', 'rejected');
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('kaprodi.pengajuan.histori_ditolak', compact('pengajuans'));
    }
}