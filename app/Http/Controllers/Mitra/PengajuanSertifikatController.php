<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanSertifikat;
use Illuminate\Support\Facades\Auth;

class PengajuanSertifikatController extends Controller
{

    public function index()
    {
        $mitra = Auth::user()->mitra;

        $pengajuan = PengajuanSertifikat::whereHas(
            'pkl.pengajuanPkl',
            function ($q) use ($mitra) {
                $q->where('id_tempat_pkl',$mitra->tempat_pkl_id);
            }
        )
        ->with('pkl.pengajuanPkl.mahasiswa')
        ->latest()
        ->get();

        return view('mitra.sertifikat.index', compact('pengajuan'));
    }



    public function upload(Request $request, $id)
    {
        $request->validate([
            'file_sertifikat' => 'required|mimes:pdf,jpg,png|max:2048'
        ]);

        $sertifikat = PengajuanSertifikat::findOrFail($id);

        $path = $request->file('file_sertifikat')
                        ->store('sertifikat','public');

        $sertifikat->update([
            'file_sertifikat' => $path,
            'status' => 'disetujui'
        ]);

        return back()->with('success','Sertifikat berhasil diupload.');
    }

}