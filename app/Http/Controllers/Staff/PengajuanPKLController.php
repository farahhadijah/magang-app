<?php
namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;
use App\Models\Mitra;
// use App\Models\Verifikasi;
use App\Models\PengajuanPkl;
use App\Models\TempatPkl;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PengajuanPklController extends Controller
{
    /**
     * ===============================
     * LIST PENGAJUAN UNTUK TU
     * ===============================
     */
    public function index()
    {
        $pengajuans = PengajuanPkl::with([
                'mahasiswa',
                'tempatPkl',
                'dokumenPengajuan'
            ])
            ->where('status', 'pending_tu')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('staff.pengajuan.index', compact('pengajuans'));
    }
    /**
     * ===============================
     * DETAIL PENGAJUAN
     * ===============================
     */
    public function show($id)
    {
        $pengajuan = PengajuanPkl::with([
            'mahasiswa',
            'tempatPkl',
            'dokumenPengajuan'
        ])->findOrFail($id);
        return view('staff.pengajuan.show', compact('pengajuan'));
    }
    /**
     * ===============================
     * SELESAIKAN VERIFIKASI TU
     * ===============================
     */
    public function approve($id)
    {
        $pengajuan = PengajuanPkl::with('dokumenPengajuan')->findOrFail($id);
        // Proteksi ulang
        if (! $pengajuan->bisaDisetujuiTu()) {
            return back()->with(
                'warning',
                'Pengajuan tidak dapat diverifikasi atau sudah diproses.'
            );
        }
        DB::transaction(function () use ($pengajuan) {
        $pengajuan->update([
            'status' => 'pending_kaprodi',
            'catatan_tu' => null,
        ]);
        $pengajuan->verifikasi()->create([
            'id_user' => auth()->user()->getKey(),
            'level'          => 'tu',
            'status'         => 'approved',
            'tgl_verifikasi' => now(),
        ]);
    });
        return redirect()
            ->route('staff.pengajuan.index')
            ->with(
                'success',
                'Verifikasi administrasi selesai. Pengajuan diteruskan ke Kaprodi.'
            );
    }
    /**
     * ===============================
     * KEMBALIKAN KE MAHASISWA
     * ===============================
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string|max:255',
        ]);
        $pengajuan = PengajuanPkl::with('dokumenPengajuan')->findOrFail($id);
        if ($pengajuan->status !== 'pending_tu') {
            return back()->with(
                'warning',
                'Pengajuan tidak dapat dikembalikan karena sudah diproses.'
            );
        }
        if (! $pengajuan->bisaDikembalikanKeMahasiswa()) {
            return back()->with(
                'warning',
                'Pengajuan tidak dapat dikembalikan karena tidak ada dokumen invalid.'
            );
        }

        DB::transaction(function () use ($pengajuan, $request) {

        $pengajuan->update([
            'status'     => 'ditolak_tu',
            'catatan_tu' => $request->catatan,
        ]);

        $pengajuan->verifikasi()->create([
            'id_user' => auth()->user()->getKey(),
            'level'          => 'tu',
            'status'         => 'rejected',
            'catatan'        => $request->catatan,
            'tgl_verifikasi' => now(),
        ]);
    });
        return redirect()
            ->route('staff.pengajuan.index')
            ->with(
                'warning',
                'Pengajuan PKL dikembalikan ke mahasiswa untuk perbaikan.'
            );
    }
    /**
     * ===============================
     * HISTORI DITOLAK TU
     * ===============================
     */
    public function historiDitolak()
    {
        $pengajuans = PengajuanPkl::with([
                'mahasiswa',
                'tempatPkl'
            ])
            ->where('status', 'ditolak_tu')
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('staff.pengajuan.histori_ditolak', compact('pengajuans'));
    }

    public function storeMitra(Request $request, $id)
    {
        $request->validate([
            'nama_perwakilan' => 'required|string|max:255',
            'username'        => 'required|string|unique:users,username',
        ]);

        $tempat = TempatPkl::findOrFail($id);

        if ($tempat->mitra) {
            return back()->with('error', 'Tempat PKL sudah memiliki mitra.');
        }

        $password = Str::random(8);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($password),
            'role'     => 'mitra',
        ]);

        Mitra::create([
            'user_id'         => $user->id,
            'tempat_pkl_id'   => $tempat->id,
            'nama_perwakilan' => $request->nama_perwakilan,
        ]);

        return back()->with('success', 
            "Akun mitra berhasil dibuat. Password: $password"
        );
    }

    
    public function manajemenMitra()
    {
        $tempatPkls = TempatPkl::with(['mitra', 'pengajuans.pkl'])
            ->whereHas('pengajuans.pkl') // hanya tempat yang sudah benar-benar punya PKL aktif
            ->get();

        return view('staff.mitra.index', compact('tempatPkls'));
    }



}