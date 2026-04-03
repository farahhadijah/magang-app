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

    $hariPkl = 0;
    $logbookTotal = 0;
    $logbookKosong = 0;
    $isPklSelesai = false;
    $targetHari = 30;

    if ($pengajuan && $pengajuan->pkl) {

        $pkl = $pengajuan->pkl;

        // ================= LOGBOOK =================
        $mulai = Carbon::parse($pkl->tgl_mulai)->startOfDay();

        if ($pkl->status === 'selesai' && $pkl->tgl_selesai) {
            $end = Carbon::parse($pkl->tgl_selesai)->startOfDay();
        } else {
            $end = Carbon::now('Asia/Jakarta')->startOfDay();
        }

        $hariPkl = $mulai->diffInDays($end) + 1;
        $targetHari = 30;
        $logbookTotal = Logbook::where('id_pkl', $pkl->id)->count();

        $logbookKosong = max($hariPkl - $logbookTotal, 0);

        $isPklSelesai = $pkl->status === 'selesai';

        // ================= TUGAS =================
        $tugasList = TugasMitra::where('id_pkl', $pkl->id)
                        ->latest()
                        ->get();

        $tugas = $tugasList->first();

        $submit = $tugas 
        ? $tugas->submit()
            ->where('id_pkl', $pkl->id)
            ->first()
        : null;
    }

    $notifikasiPenolakan = null;

    if ($pengajuan) {
        if ($pengajuan->status === 'ditolak_tu') {
            $notifikasiPenolakan = [
                'tipe' => 'tu',
                'pesan' => $pengajuan->catatan_tu
            ];
        }

        if ($pengajuan->status === 'ditolak_kaprodi') {
            $notifikasiPenolakan = [
                'tipe' => 'kaprodi',
                'pesan' => $pengajuan->catatan_kaprodi
            ];
        }
    }

    return view('mahasiswa.dashboard', compact(
        'pengajuan',
        'tugas',
        'tugasList',
        'submit',
        'hariPkl',
        'logbookTotal',
        'logbookKosong',
        'isPklSelesai',
        'targetHari',
        'notifikasiPenolakan'
    ));
}
}