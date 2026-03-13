<?php
namespace App\Http\Controllers\Mahasiswa;
use App\Http\Controllers\Controller;
use App\Models\PengajuanPkl;
use App\Models\TugasMitra;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Logbook;

class DashboardController extends Controller
{
        public function index()
{
    $user = Auth::user();
    $mahasiswa = $user->mahasiswa;

    if (!$mahasiswa) {
        abort(403, 'Mahasiswa tidak ditemukan');
    }

    $pengajuan = PengajuanPkl::with([
        'tempatPkl',
        'pkl.dosen',
        'pkl'
    ])
    ->where('id_mhs', $mahasiswa->id)
    ->latest('created_at')
    ->first();

    $tugasList = collect();
    $tugas = null;
    $submit = null;

    if ($pengajuan && $pengajuan->pkl) {

        $tugasList = TugasMitra::where('id_pkl', $pengajuan->pkl->id)
            ->latest()
            ->get();

        $tugas = $tugasList->first();

        if ($tugas) {
            $submit = $tugas->submit()
                ->where('id_pkl', $pengajuan->pkl->id)
                ->first();
        }
    }

    $logbookTotal = 0;
    $hariPkl = 0;
    $logbookKosong = 0;

    if ($pengajuan && $pengajuan->pkl) {

        $pkl = $pengajuan->pkl;

        $mulai = Carbon::parse($pkl->tgl_mulai)->startOfDay();
        $today = Carbon::now('Asia/Jakarta')->startOfDay();

        $hariPkl = $mulai->diffInDays($today) + 1;

        $logbookTotal = Logbook::where('id_pkl', $pkl->id)->count();

        $logbookKosong = max($hariPkl - $logbookTotal, 0);
    }

    return view('mahasiswa.dashboard', compact(
        'pengajuan',
        'tugas',
        'tugasList',
        'submit',
        'hariPkl',
        'logbookTotal',
        'logbookKosong'
    ));
}
}