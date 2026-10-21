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
use Illuminate\Support\Facades\Mail;
use App\Mail\AkunMitraMail;
use Throwable;


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
public function histori(Request $request)
{
    $prodiId = $this->getProdiId();

    // gunakan query param bila diberikan
    $perPage = (int) $request->query('perPage');

    if (! $perPage) {
        $ua = $request->header('User-Agent', '');
        $isMobile = stripos($ua, 'Mobile') !== false
            || stripos($ua, 'Android') !== false
            || stripos($ua, 'iPhone') !== false;

        $perPage = $isMobile ? 15 : 9;
    }

        $verifikasis = \App\Models\Verifikasi::with([
            'pengajuan.mahasiswa',
            'pengajuan.tempatPkl',
            'user'
        ])
        ->where('level', 'tu')
        ->whereHas('pengajuan.mahasiswa', function ($q) use ($prodiId) {
            $q->where('prodi_id', $prodiId);
        })
        // exclude verifications for pengajuan whose PKL has already been completed (status = 'selesai')
        ->whereDoesntHave('pengajuan', function ($q) {
            $q->whereHas('pkl', function ($q2) {
                $q2->where('status', 'selesai');
            });
        })
        ->orderBy('tgl_verifikasi', 'desc')
        ->paginate($perPage)
        ->withQueryString();

    return view('staff.pengajuan.histori', compact('verifikasis'));
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