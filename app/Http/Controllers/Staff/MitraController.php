<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Models\Mitra;
use App\Models\User;
use App\Http\Controllers\Controller;
// use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class MitraController extends Controller
{

public function index(Request $request)
{
    $search = $request->search;

    $mitras = Mitra::with('tempatPkl')
        ->when($search, function ($query) use ($search) {

            $query->where(function ($q) use ($search) {

                // search di jabatan
                $q->where('jabatan', 'like', "%{$search}%")

                // search di tempat pkl
                ->orWhereHas('tempatPkl', function ($sub) use ($search) {
                    $sub->where('nama_tempat', 'like', "%{$search}%")
                        ->orWhere('jenis_tempat', 'like', "%{$search}%");
                });

            });

        })
        ->paginate(10)
        ->withQueryString();

    return view('staff.manajemenMitra.index', compact('mitras','search'));
}

public function show($id)
{
    $mitra = Mitra::join('tempat_pkl','mitra.tempat_pkl_id','=','tempat_pkl.id')
        ->select(
            'mitra.*',
            'tempat_pkl.nama_tempat',
            'tempat_pkl.no_hp as hp_tempat'
        )
        ->where('mitra.id',$id)
        ->firstOrFail();

    // mahasiswa yang PKL di tempat tersebut
    $mahasiswa = DB::table('pengajuan_pkl')
        ->join('mahasiswa','pengajuan_pkl.id_mhs','=','mahasiswa.id')
        ->where('pengajuan_pkl.id_tempat_pkl', $mitra->tempat_pkl_id)
        ->where('pengajuan_pkl.status', 'disetujui')
        ->select(
            'mahasiswa.nim',
            'mahasiswa.nama',
            'mahasiswa.angkatan',
            'mahasiswa.no_hp'
        )
        ->paginate(10);

    return view('staff.manajemenMitra.show', compact('mitra','mahasiswa'));
}

public function regenerate($id)
{
    $mitra = Mitra::findOrFail($id);

    // ambil user berdasarkan user_id
    $user = User::findOrFail($mitra->user_id);

    $passwordBaru = Str::random(8);

    $user->password = Hash::make($passwordBaru);
    $user->save();

    return redirect()->back()->with([
        'username' => $user->username,
        'password' => $passwordBaru
    ]);
}
}