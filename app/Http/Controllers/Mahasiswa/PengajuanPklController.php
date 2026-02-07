<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\PengajuanPkl;
use App\Models\TempatPkl;
use App\Models\DokumenPengajuan;

class PengajuanPklController extends Controller
{
    public function create()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            abort(403, 'Mahasiswa tidak ditemukan');
        }

        // Cegah pengajuan ganda
        $pengajuanAktif = PengajuanPkl::where('id_mhs', $mahasiswa->id)
            ->whereIn('status', ['pending', 'disetujui'])
            ->first();

        if ($pengajuanAktif) {
            return redirect()
                ->route('mahasiswa.dashboard')
                ->with('error', 'Kamu sudah memiliki pengajuan PKL.');
        }

        return view('mahasiswa.pengajuan-pkl');
    }

    public function store(Request $request)
    {
        $request->validate(
        [
            'nama_tempat'     => 'required|string|max:150',
            'jenis_tempat'    => 'required|in:Pemerintah,Sekolah,PT,CV',
            'no_hp'           => 'required|string|max:15',
            'lokasi_maps'     => 'required|string',

            'dokumen'         => 'required|file|mimes:pdf,doc,docx|max:2048',
        ],
        [
            'dokumen.required' => 'Dokumen pendukung wajib diunggah.',
            'dokumen.mimes'    => 'Dokumen harus berupa PDF, DOC, atau DOCX.',
            'dokumen.max'      => 'Ukuran dokumen maksimal 2 MB.',

            'tanggal_selesai.after_or_equal' =>
                'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
        ]
    );


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
                'id_tempat_pkl' => $tempatPkl->id,
                'status'        => 'pending',
                'tgl_pengajuan' => now(),
            ]);

            $path = $request->file('dokumen')
                ->store("dokumen_pengajuan_pkl/{$mahasiswa->nim}", 'public');

            DokumenPengajuan::create([
                'id_pengajuan_pkl' => $pengajuan->id,
                'path_file'        => $path,
                'jenis_dokumen'    => 'khs',
            ]);
        });

        return redirect()
            ->route('mahasiswa.pengajuan.status')
            ->with('success', 'Pengajuan PKL berhasil dikirim dan menunggu verifikasi.');
    }


    public function status()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        $pengajuan = PengajuanPkl::with([
                'tempatPkl',
                'dokumenPengajuan',
                'pkl.dosen'
            ])
            ->where('id_mhs', $mahasiswa->id)
            ->latest()
            ->first();

        return view('mahasiswa.status-pengajuan', compact('pengajuan'));
    }
}