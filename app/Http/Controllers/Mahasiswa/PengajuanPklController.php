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
            ->whereIn('status', [
                'pending_tu',
                'diverifikasi_tu',
                'pending_kaprodi'
            ])
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
            // nomor instansi harus diawali 08 (format lokal Indonesia)
            'no_hp'        => ['required', 'regex:/^08[0-9]{7,14}$/'],
            // lokasi maps harus berupa URL Google Maps
            'lokasi_maps'  => ['required', 'url', 'regex:/google\\./i'],

            'dokumen_khs'        => 'required|file|mimes:pdf,doc,docx|max:2048',
            'dokumen_pembayaran' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'dokumen_studi_tour' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'no_hp.regex' => 'Nomor telepon harus diawali dengan 08 dan hanya mengandung angka setelahnya (contoh: 08123456789).',
            'lokasi_maps.url' => 'Alamat lokasi harus berupa URL yang valid.',
            'lokasi_maps.regex' => 'Alamat lokasi harus mengandung link Google Maps (mis. maps.google.com atau goo.gl).',
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
                'status'        => 'pending_tu',
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
            'dokumenPengajuan',
            'pkl.suratPengantar'   // 🔥 INI YANG KURANG
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
            // After updating this dokumen, check if there are any remaining
            // dokumen with status 'invalid'. If none remain, the mahasiswa
            // has finished fixing all invalid documents and we should forward
            // the pengajuan back to Staff TU for verification.
            $hasInvalid = $pengajuan->dokumenPengajuan()
                ->where('status_verifikasi', 'invalid')
                ->exists();

            if (! $hasInvalid) {
                // set pengajuan kembali ke pending_tu sehingga TU melihatnya
                $pengajuan->update([
                    'status'     => 'pending_tu',
                    'catatan_tu' => null,
                ]);
            }
        });

        return back()->with('success', 'Dokumen berhasil diupload ulang dan menunggu verifikasi TU.');
    }
}