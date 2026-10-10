<?php
namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;
use App\Models\PengajuanPkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResumeController extends Controller
{
    /**
     * Mengambil prodi milik Staff TU yang sedang login.
     */
    private function getProdiId(): int
    {
        $prodiId = auth()->user()?->staff?->prodi_id;

        // Mencegah data seluruh prodi tampil jika akun belum memiliki prodi.
        abort_if(
            $prodiId === null,
            403,
            'Akun Staff TU belum terhubung dengan program studi.'
        );

        return (int) $prodiId;
    }

    /**
     * Menampilkan daftar angkatan beserta jumlah pengajuan PKL.
     */
    
public function index()
{
    $prodiId = $this->getProdiId();

    // Query dasar: hanya pengajuan yang disetujui dan PKL aktif.
    $queryResume = DB::table('pengajuan_pkl')
        ->join(
            'mahasiswa',
            'pengajuan_pkl.id_mhs',
            '=',
            'mahasiswa.id'
        )
        ->where('mahasiswa.prodi_id', $prodiId)
        ->where('pengajuan_pkl.status', 'disetujui');

    // Daftar angkatan yang memiliki pengajuan sesuai kriteria.
    $angkatanList = (clone $queryResume)
        ->select(
            'mahasiswa.angkatan',
            DB::raw('COUNT(pengajuan_pkl.id) as total_pengajuan')
        )
        ->groupBy('mahasiswa.angkatan')
        ->orderByDesc('mahasiswa.angkatan')
        ->paginate(10)
        ->withQueryString();

    // Total pengajuan yang disetujui dan PKL-nya aktif.
    $totalPengajuan = (clone $queryResume)
        ->count('pengajuan_pkl.id');

    // Total angkatan yang memiliki pengajuan sesuai kriteria.
    $totalAngkatan = (clone $queryResume)
        ->distinct()
        ->count('mahasiswa.angkatan');

    return view('staff.resume.angkatan', compact(
        'angkatanList',
        'totalPengajuan',
        'totalAngkatan'
    ));
}


    /**
     * Menampilkan resume pengajuan PKL berdasarkan angkatan.
     */
    
public function showByAngkatan(Request $request, $angkatan)
{
    abort_unless(
        preg_match('/^\d{4}$/', (string) $angkatan),
        404
    );

    $prodiId = $this->getProdiId();
    $search = trim((string) $request->query('search', ''));

    $pengajuanList = PengajuanPkl::with([
        'mahasiswa',
        'tempatPkl',
        'dokumenPengajuan',
    ])
        // Filter prodi dan angkatan.
        ->whereHas('mahasiswa', function ($query) use (
            $angkatan,
            $prodiId
        ) {
            $query->where('angkatan', $angkatan)
                ->where('prodi_id', $prodiId);
        })

        // Hanya pengajuan yang sudah disetujui.
        ->where('status', 'disetujui')

        // Pencarian berdasarkan mahasiswa, tempat, semester, atau status.
        ->when($search !== '', function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('mahasiswa', function ($mhs) use ($search) {
                    $mhs->where('nama', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                })
                ->orWhereHas('tempatPkl', function ($tempat) use ($search) {
                    $tempat->where(
                        'nama_tempat',
                        'like',
                        "%{$search}%"
                    );
                })
                ->orWhere('semester', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%");
            });
        })
        ->latest('created_at')
        ->paginate(10)
        ->withQueryString();

    return view('staff.resume.index', compact(
        'pengajuanList',
        'angkatan',
        'search'
    ));
}

}
