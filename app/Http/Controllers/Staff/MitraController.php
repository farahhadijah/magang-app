<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Models\Mitra;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MitraController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $prodiId = auth()->user()->staff->prodi_id;

        $mitras = Mitra::with('tempatPkl')
            ->whereHas('tempatPkl.pengajuans', function ($q) use ($prodiId) {
                $q->where('status', 'disetujui')
                    ->whereHas('mahasiswa', function ($m) use ($prodiId) {
                        $m->where('prodi_id', $prodiId);
                    });
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('jabatan', 'like', "%{$search}%")
                        ->orWhereHas('tempatPkl', function ($sub) use ($search) {
                            $sub->where('nama_tempat', 'like', "%{$search}%")
                                ->orWhere('jenis_tempat', 'like', "%{$search}%");
                        });
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('staff.manajemenMitra.index', compact('mitras', 'search'));
    }


    /**
     * Detail Mitra
     * Hanya menampilkan informasi mitra
     * dan daftar angkatan mahasiswa.
     */
    public function show($id)
    {
        $mitra = Mitra::join(
            'tempat_pkl',
            'mitra.tempat_pkl_id',
            '=',
            'tempat_pkl.id'
        )
            ->select(
                'mitra.*',
                'tempat_pkl.nama_tempat',
                'tempat_pkl.no_hp as hp_tempat'
            )
            ->where('mitra.id', $id)
            ->firstOrFail();

        $prodiId = auth()->user()->staff->prodi_id;

        /*
         * Ambil jumlah mahasiswa berdasarkan angkatan.
         */
        $angkatan = DB::table('pengajuan_pkl')
            ->join(
                'mahasiswa',
                'pengajuan_pkl.id_mhs',
                '=',
                'mahasiswa.id'
            )
            ->where(
                'pengajuan_pkl.id_tempat_pkl',
                $mitra->tempat_pkl_id
            )
            ->where(
                'pengajuan_pkl.status',
                'disetujui'
            )
            ->where(
                'mahasiswa.prodi_id',
                $prodiId
            )
            ->select(
                'mahasiswa.angkatan',
                DB::raw('COUNT(*) as jumlah_mahasiswa')
            )
            ->groupBy('mahasiswa.angkatan')
            ->orderByDesc('mahasiswa.angkatan')
            ->get();

        return view(
            'staff.manajemenMitra.show',
            compact('mitra', 'angkatan')
        );
    }


    /**
     * Daftar mahasiswa berdasarkan angkatan.
     */
    public function mahasiswaByAngkatan($id, $tahun)
    {
        $mitra = Mitra::join(
            'tempat_pkl',
            'mitra.tempat_pkl_id',
            '=',
            'tempat_pkl.id'
        )
            ->select(
                'mitra.*',
                'tempat_pkl.nama_tempat'
            )
            ->where('mitra.id', $id)
            ->firstOrFail();

        $prodiId = auth()->user()->staff->prodi_id;

        $mahasiswa = DB::table('pengajuan_pkl')
            ->join(
                'mahasiswa',
                'pengajuan_pkl.id_mhs',
                '=',
                'mahasiswa.id'
            )
            ->where(
                'pengajuan_pkl.id_tempat_pkl',
                $mitra->tempat_pkl_id
            )
            ->where(
                'pengajuan_pkl.status',
                'disetujui'
            )
            ->where(
                'mahasiswa.prodi_id',
                $prodiId
            )
            ->where(
                'mahasiswa.angkatan',
                $tahun
            )
            ->select(
                'mahasiswa.nim',
                'mahasiswa.nama',
                'mahasiswa.angkatan',
                'mahasiswa.no_hp'
            )
            ->orderBy('mahasiswa.nama')
            ->paginate(10)
            ->withQueryString();

        return view(
            'staff.manajemenMitra.mahasiswa',
            compact(
                'mitra',
                'mahasiswa',
                'tahun'
            )
        );
    }


    public function regenerate($id)
    {
        $mitra = Mitra::findOrFail($id);

        $user = User::findOrFail($mitra->user_id);

        $passwordBaru = Str::random(8);

        $user->password = Hash::make($passwordBaru);

        // Paksa mitra mengganti password saat login berikutnya
        $user->first_login = true;

        // Pastikan akun aktif
        $user->is_active = true;

        $user->save();

        return redirect()->back()->with([
            'username' => $user->username,
            'password' => $passwordBaru
        ]);
    }
}