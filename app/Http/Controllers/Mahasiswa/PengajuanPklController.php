<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PengajuanPkl;
use App\Models\TempatPkl;
use App\Models\DokumenPengajuan;

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
            ->whereIn('status', ['draft', 'pending'])
            ->exists();

        if ($pengajuanAktif) {
            return redirect()
                ->route('mahasiswa.dashboard')
                ->with('error', 'Kamu sudah memiliki pengajuan PKL yang sedang diproses.');
        }

        return view('mahasiswa.pengajuan-pkl');
    }

    /**
     * Simpan pengajuan PKL
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_tempat'  => 'required|string|max:150',
            'jenis_tempat' => 'required|in:Pemerintah,Sekolah,PT,CV',
            'no_hp'        => 'required|string|max:15',
            'lokasi_maps'  => 'required|string',

            'dokumen_khs'        => 'required|file|mimes:pdf,doc,docx|max:2048',
            'dokumen_pembayaran' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'dokumen_studi_tour' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        abort_if(!$mahasiswa, 403);

        DB::transaction(function () use ($request, $mahasiswa) {

            $tempatPkl = TempatPkl::create([
                'nama_tempat'  => $request->nama_tempat,
                'jenis_tempat' => $request->jenis_tempat,
                'no_hp'        => $request->no_hp,
                'lokasi_maps'  => $request->lokasi_maps,
            ]);

            $pengajuan = PengajuanPkl::create([
                'id_mhs'        => $mahasiswa->id,
                'id_tempat_pkl'=> $tempatPkl->id,
                'status'        => 'pending',
                'tgl_pengajuan' => now(),
            ]);

            $basePath = "dokumen_pengajuan_pkl/{$mahasiswa->nim}";

            $dokumenMap = [
                'dokumen_khs'        => 'KHS',
                'dokumen_pembayaran' => 'Pembayaran',
                'dokumen_studi_tour' => 'StudiTour',
            ];

            foreach ($dokumenMap as $input => $jenis) {
                $path = $request->file($input)->store($basePath, 'public');

                DokumenPengajuan::create([
                    'id_pengajuan_pkl' => $pengajuan->id,
                    'jenis_dokumen'    => $jenis,
                    'path_file'        => $path,
                    'status_verifikasi'=> 'pending',
                ]);
            }
        });

        return redirect()
            ->route('mahasiswa.pengajuan.status')
            ->with('success', 'Pengajuan PKL berhasil dikirim dan menunggu verifikasi Staff TU.');
    }

    /**
     * Status pengajuan PKL mahasiswa
     */
    public function status()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        abort_if(!$mahasiswa, 403);

        $pengajuan = PengajuanPkl::with([
                'tempatPkl',
                'dokumenPengajuan'
            ])
            ->where('id_mhs', $mahasiswa->id)
            ->latest()
            ->first();

        return view('mahasiswa.status-pengajuan', compact('pengajuan'));
    }

    /**
     * 🔁 Upload ulang dokumen INVALID
     */
    public function uploadUlangDokumen(Request $request, $id)
    {
        $request->validate([
            'dokumen' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        abort_if(!$mahasiswa, 403);

        $dokumen = DokumenPengajuan::with('pengajuan')->findOrFail($id);
        $pengajuan = $dokumen->pengajuan;

        // Guard keamanan
        abort_if(
            $pengajuan->id_mhs !== $mahasiswa->id ||
            $pengajuan->status !== 'ditolak_tu' ||
            $dokumen->status_verifikasi !== 'invalid',
            403
        );

        DB::transaction(function () use ($request, $dokumen, $pengajuan, $mahasiswa) {

            $path = $request->file('dokumen')
                ->store("dokumen_pengajuan_pkl/{$mahasiswa->nim}", 'public');

            // Update dokumen
            $dokumen->update([
                'path_file'        => $path,
                'status_verifikasi'=> 'pending',
                'catatan'          => null,
            ]);

            // Kembalikan status pengajuan ke TU
            $pengajuan->update([
                'status'      => 'pending',
                'catatan_tu'  => null,
            ]);
        });

        return back()->with('success', 'Dokumen berhasil diupload ulang dan menunggu verifikasi TU.');
    }
}