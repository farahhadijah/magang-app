<?php

namespace App\Http\Controllers\Kaprodi;
use App\Http\Controllers\Controller;
use App\Models\PengajuanPkl;
use App\Models\Verifikasi;
use App\Models\Pkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengajuanPklController extends Controller
{
    /**
     * Dashboard Kaprodi
     */
    public function dashboard()
    {
        $totalMahasiswa = PengajuanPkl::distinct('id_mhs')->count();

        $totalMenunggu = PengajuanPkl::where('status','pending')
            ->whereHas('verifikasi', function($q){
                $q->where('level','tu')->where('status','approved');
            })->count();

        $totalAktif = PengajuanPkl::whereHas('verifikasi', function($q){
            $q->where('level','kaprodi')->where('status','approved');
        })->count();

        $totalSelesai = Pkl::whereNotNull('tgl_selesai')->count();

        return view('kaprodi.dashboard', compact(
            'totalMahasiswa','totalMenunggu','totalAktif','totalSelesai'
        ));
    }


    /**
     * Index pengajuan yang menunggu Kaprodi
     */
    public function index()
    {
        $pengajuans = PengajuanPkl::with(['mahasiswa','tempatPkl'])
            ->whereIn('status', ['pending_kaprodi', 'disetujui'])
            ->whereHas('verifikasi', function($q){
                $q->where('level','tu')->where('status','approved');
            })
            ->orderBy('created_at','desc')
            ->get();

        return view('kaprodi.pengajuan.index', compact('pengajuans'));
    }

    /**
     * Detail pengajuan
     */
    public function show($id)
    {
        $pengajuan = PengajuanPkl::with(['mahasiswa','tempatPkl','dokumenPengajuan'])
            ->findOrFail($id);

        return view('kaprodi.pengajuan.show', compact('pengajuan'));
    }

    /**
     * Approve pengajuan Kaprodi
     */
    public function approve($id)
    {
        $pengajuan = PengajuanPkl::findOrFail($id);

        if ($pengajuan->status !== 'pending_kaprodi') {
            return back()->with('success','Pengajuan sudah diproses.');
        }


        DB::transaction(function() use($pengajuan){
            Verifikasi::create([
                'id_pengajuan_pkl' => $pengajuan->id,
                'id_user'          => auth()->user()->id,
                'level'            => 'kaprodi',
                'status'           => 'approved',
                'catatan'          => null,
                'tgl_verifikasi'   => now(),
            ]);

            $pengajuan->update([
                'status' => 'disetujui',
            ]);
        });

        return redirect()->route('kaprodi.pengajuan.index')
            ->with('success','Pengajuan PKL berhasil disetujui Kaprodi.');
    }

    /**
     * Reject pengajuan Kaprodi
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string|max:255',
        ]);

        $pengajuan = PengajuanPkl::findOrFail($id);

        if ($pengajuan->status !== 'pending_kaprodi') {
            return back()->with('success','Pengajuan sudah diproses.');
        }


        DB::transaction(function() use($pengajuan, $request){
            Verifikasi::create([
                'id_pengajuan_pkl' => $pengajuan->id,
                'id_user'          => auth()->user()->id,
                'level'            => 'kaprodi',
                'status'           => 'rejected',
                'catatan'          => $request->catatan,
                'tgl_verifikasi'   => now(),
            ]);

            $pengajuan->update([
                'status' => 'ditolak_kaprodi',
                'catatan_kaprodi'=> $request->catatan,
            ]);
        });

        return redirect()->route('kaprodi.pengajuan.index')
            ->with('warning','Pengajuan PKL berhasil ditolak Kaprodi.');
    }

    /**
     * Histori pengajuan ditolak Kaprodi
     */
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