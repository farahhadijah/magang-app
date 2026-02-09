<?php

namespace App\Http\Controllers\Kaprodi;

use Carbon\Carbon;
use App\Models\Pkl;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Verifikasi;
use App\Models\PengajuanPkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PengajuanPklController extends Controller
{
    public function dashboard()
    {
        $totalMahasiswa = PengajuanPkl::distinct('id_mhs')->count();

        $totalMenunggu = PengajuanPkl::where('status','pending_kaprodi')
            ->whereHas('verifikasi', function($q){
                $q->where('level','tu')->where('status','approved');
            })->count();

        $totalAktif = Pkl::where('status','aktif')->count();
        $totalSelesai = Pkl::where('status','selesai')->count();

        return view('kaprodi.dashboard', compact(
            'totalMahasiswa','totalMenunggu','totalAktif','totalSelesai'
        ));
    }

    public function index()
    {
        $pengajuans = PengajuanPkl::with(['mahasiswa','tempatPkl'])
            ->munculUntukKaprodi()
            ->orderBy('created_at','desc')
            ->get();

        return view('kaprodi.pengajuan.index', compact('pengajuans'));
    }



// DETAIL pengajuan + daftar dosen
    public function show($id)
    {
        $pengajuan = PengajuanPkl::with(['mahasiswa','tempatPkl','dokumenPengajuan'])
            ->findOrFail($id);

        // Ambil semua dosen dari tabel dosen
        $dosenList = Dosen::select('id','nama')->get();

        return view('kaprodi.pengajuan.show', compact('pengajuan','dosenList'));
    }

    // Approve pengajuan Kaprodi dengan memilih dosen
    public function approve(Request $request, $id)
{
    $request->validate([
        'id_dosen' => 'required|exists:users,id',
    ]);

    $pengajuan = PengajuanPkl::findOrFail($id);

    if (!$pengajuan->bisaDiverifikasiKaprodi()) {
        return back()->with('success', 'Pengajuan sudah diproses.');
    }

    DB::transaction(function() use($pengajuan, $request) {
        // Verifikasi Kaprodi
        Verifikasi::create([
            'id_pengajuan_pkl' => $pengajuan->id,
            'id_user'          => auth()->user()->id,
            'level'            => 'kaprodi',
            'status'           => 'approved',
            'catatan'          => null,
            'tgl_verifikasi'   => now(),
        ]);

        // Update status pengajuan
        $pengajuan->update([
            'status' => 'disetujui',
        ]);

        // Buat record PKL dengan dosen yang dipilih Kaprodi
        $tglMulai = now();
        $tglSelesai = now()->addDays(30); // durasi PKL, bisa diubah

        Pkl::create([
            'id_pengajuan_pkl' => $pengajuan->id,
            'id_dosen'         => $request->id_dosen,
            'tgl_mulai'        => $tglMulai,
            'tgl_selesai'      => $tglSelesai,
            'status'           => 'aktif',
        ]);
    });

    return redirect()->route('kaprodi.pengajuan.index')
        ->with('success', 'Pengajuan PKL berhasil disetujui Kaprodi dan PKL aktif.');
}



    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string|max:255',
        ]);

        $pengajuan = PengajuanPkl::findOrFail($id);

        if (!$pengajuan->bisaDiverifikasiKaprodi()) {
            return back()->with('warning','Pengajuan sudah diproses dan tidak dapat diverifikasi kembali.');
        }

        DB::transaction(function() use($pengajuan, $request){
            // catatan verifikasi Kaprodi
            Verifikasi::create([
                'id_pengajuan_pkl' => $pengajuan->id,
                'id_user'          => auth()->user()->id,
                'level'            => 'kaprodi',
                'status'           => 'rejected',
                'catatan'          => $request->catatan,
                'tgl_verifikasi'   => now(),
            ]);

            // update status pengajuan
            $pengajuan->update([
                'status'         => 'ditolak_kaprodi',
                'catatan_kaprodi'=> $request->catatan,
            ]);
        });

        return redirect()->route('kaprodi.pengajuan.index')
            ->with('warning','Pengajuan PKL berhasil ditolak Kaprodi.');
    }

    public function historiDitolak()
    {
        $pengajuans = PengajuanPkl::with(['mahasiswa','tempatPkl'])
            ->whereHas('verifikasi', function($q){
                $q->where('level','kaprodi')->where('status','rejected');
            })
            ->orderBy('updated_at','desc')
            ->get();

        return view('kaprodi.pengajuan.histori_ditolak', compact('pengajuans'));
    }
}