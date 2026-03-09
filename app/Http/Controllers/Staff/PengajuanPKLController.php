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
        $prodiId = $this->getProdiId();

        $pengajuans = PengajuanPkl::with([
                'mahasiswa',
                'tempatPkl',
                'dokumenPengajuan'
            ])
            ->where('status', 'pending_tu')
            ->whereHas('mahasiswa', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('staff.pengajuan.index', compact('pengajuans'));
    }
    /**
     * ===============================
     * DETAIL PENGAJUAN
     * ===============================
     */
    public function show($id)
{
    $prodiId = $this->getProdiId();

    $pengajuan = PengajuanPkl::with([
        'mahasiswa',
        'tempatPkl',
        'dokumenPengajuan'
    ])
    ->whereHas('mahasiswa', function ($q) use ($prodiId) {
        $q->where('prodi_id', $prodiId);
    })
    ->findOrFail($id);

    return view('staff.pengajuan.show', compact('pengajuan'));
}
    /**
     * ===============================
     * SELESAIKAN VERIFIKASI TU
     * ===============================
     */
    public function approve($id)
{
    $prodiId = $this->getProdiId();

    $pengajuan = PengajuanPkl::with('dokumenPengajuan')
        ->whereHas('mahasiswa', function ($q) use ($prodiId) {
            $q->where('prodi_id', $prodiId);
        })
        ->findOrFail($id);

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
            'level' => 'tu',
            'status' => 'approved',
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

    $prodiId = $this->getProdiId();

    $pengajuan = PengajuanPkl::with('dokumenPengajuan')
        ->whereHas('mahasiswa', function ($q) use ($prodiId) {
            $q->where('prodi_id', $prodiId);
        })
        ->findOrFail($id);

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
            'status' => 'ditolak_tu',
            'catatan_tu' => $request->catatan,
        ]);

        $pengajuan->verifikasi()->create([
            'id_user' => auth()->user()->getKey(),
            'level' => 'tu',
            'status' => 'rejected',
            'catatan' => $request->catatan,
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
    public function histori()
{
    $prodiId = $this->getProdiId();

    $verifikasis = \App\Models\Verifikasi::with([
            'pengajuan.mahasiswa',
            'pengajuan.tempatPkl',
            'user'
        ])
        ->where('level', 'tu')
        ->whereHas('pengajuan.mahasiswa', function ($q) use ($prodiId) {
            $q->where('prodi_id', $prodiId);
        })
        ->orderBy('tgl_verifikasi', 'desc')
        ->paginate(9);

    return view('staff.pengajuan.histori', compact('verifikasis'));
}

    public function storeMitra(Request $request, $id)
{
    $tempat = TempatPkl::findOrFail($id);

    if ($tempat->mitra) {
        return back()->with('error', 'Tempat PKL sudah memiliki mitra.');
    }

    return DB::transaction(function () use ($tempat) {

        $baseUsername = 'mitra_' . Str::slug($tempat->nama_tempat, '_');
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . '_' . $counter;
            $counter++;
        }

        $password = Str::random(8);

        $user = User::create([
            'username' => $username,
            'password' => Hash::make($password),
            'role' => 'mitra',
            'first_login' => 1,
        ]);

        Mitra::create([
            'user_id' => $user->id,
            'tempat_pkl_id' => $tempat->id,
            'no_hp' => $tempat->no_hp,
        ]);

        return redirect()
            ->route('staff.mitra.akun', ['id' => $tempat->id])
            ->with('generated_account', [
                'username' => $username,
                'password' => $password
            ]);
    });
}
    public function showAkunMitra($id)
{
    $akun = session('generated_account');

    if (!$akun) {
        return redirect()
            ->route('staff.mitra.index')
            ->with('warning', 'Akun hanya dapat dilihat setelah dibuat.');
    }

    $tempat = TempatPkl::with([
        'mitra.user',
        'pengajuans.mahasiswa'
    ])->findOrFail($id);

    // ambil semua mahasiswa yang memiliki no_hp
    $mahasiswas = $tempat->pengajuans
        ->pluck('mahasiswa')
        ->filter(function ($mhs) {
            return !empty($mhs->no_hp);
        });

    return view('staff.mitra.akun', [
        'tempat' => $tempat,
        'akun' => $akun,
        'mahasiswas' => $mahasiswas,
        'account_notice' => true,
    ]);
}
    public function manajemenMitra()
{
    $prodiId = $this->getProdiId();

    $tempatPkls = TempatPkl::with('mitra')
        ->whereHas('pengajuans', function ($q) use ($prodiId) {
            $q->where('status', 'disetujui')
              ->whereHas('mahasiswa', function ($q2) use ($prodiId) {
                  $q2->where('prodi_id', $prodiId);
              })
              ->whereHas('pkl'); // pastikan sudah jadi PKL
        })
        ->withCount([
            'pengajuans as jumlah_mahasiswa' => function ($q) use ($prodiId) {
                $q->where('status', 'disetujui')
                  ->whereHas('mahasiswa', function ($q2) use ($prodiId) {
                      $q2->where('prodi_id', $prodiId);
                  })
                  ->whereHas('pkl');
            }
        ])
        ->orderBy('nama_tempat')
        ->paginate(9);

    return view('staff.mitra.index', compact('tempatPkls'));
}
private function getProdiId()
{
    $staff = auth()->user()->staff;

    if (!$staff || !$staff->prodi_id) {
        abort(403, 'Staff tidak memiliki prodi.');
    }

    return $staff->prodi_id;
}


}