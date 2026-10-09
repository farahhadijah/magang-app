<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Pkl;
use App\Models\TugasMitra;
use App\Models\TugasMitraSubmit;

class MitraController extends Controller
{
    public function mahasiswa()
    {
        $mitra = Auth::user()->mitra;

        if (!$mitra) {
            abort(403, 'Data mitra tidak ditemukan.');
        }

        $pkls = Pkl::whereHas('pengajuanPkl', function ($q) use ($mitra) {
                $q->where('id_tempat_pkl', $mitra->tempat_pkl_id);
            })
            ->where('status', 'aktif')
            ->with([
                'mahasiswa.user',
                'penilaianMitra'
            ])
            ->paginate(10);

        return view('mitra.mahasiswa', compact('pkls'));
    }


    public function logbook($pklId)
    {
        $mitra = Auth::user()->mitra;

        if (!$mitra) {
            abort(403, 'Data mitra tidak ditemukan.');
        }

        $pkl = Pkl::where('id', $pklId)
            ->whereHas('pengajuanPkl', function ($q) use ($mitra) {
                $q->where('id_tempat_pkl', $mitra->tempat_pkl_id);
            })
            ->with([
                'mahasiswa.user',
                'logbooks' => function ($query) {
                    $query->orderBy('tgl', 'asc');
                }
            ])
            ->firstOrFail();

        // Ambil link Google Drive dokumentasi PKL
        $logbookDrive = $pkl->logbooks
            ->first(function ($logbook) {
                return !empty($logbook->link_dokumentasi);
            });

        return view('mitra.logbook', compact(
            'pkl',
            'logbookDrive'
        ));
    }

    /**
     * List mahasiswa yang sudah mengisi logbook di tempat mitra ini.
     */
    public function logbookList()
    {
        $mitra = Auth::user()->mitra;

        if (!$mitra) {
            abort(403, 'Data mitra tidak ditemukan.');
        }

        $pkls = Pkl::whereHas('pengajuanPkl', function ($q) use ($mitra) {
                $q->where('id_tempat_pkl', $mitra->tempat_pkl_id);
            })
            ->where('status', 'aktif')
            ->whereHas('logbooks')
            ->with([
                'mahasiswa',
                'logbooks' => function ($q) {
                    $q->orderBy('tgl', 'desc');
                }
            ])
            ->paginate(10);

        return view('mitra.logbook_list', compact('pkls'));
    }

    public function dashboard()
    {
        $mitra = Auth::user()->mitra;

        if (!$mitra) {
            abort(403, 'Data mitra tidak ditemukan.');
        }

        // PKL milik tempat mitra ini, baik aktif maupun selesai
        $baseQuery = Pkl::whereHas('pengajuanPkl', function ($q) use ($mitra) {
            $q->where('id_tempat_pkl', $mitra->tempat_pkl_id);
        })->whereIn('status', ['aktif', 'selesai']);

        // Jumlah mahasiswa dengan PKL aktif
        $jumlahMahasiswa = (clone $baseQuery)
            ->where('status', 'aktif')
            ->count();

        // Ambil ID PKL yang masih aktif
        $pklAktifIds = (clone $baseQuery)
            ->where('status', 'aktif')
            ->pluck('id');

        // Jumlah tugas yang sudah dikumpulkan dari PKL aktif
        $sudahSubmit = TugasMitraSubmit::whereIn('id_pkl', $pklAktifIds)
            ->count();

        // Jumlah mahasiswa yang belum mendapat surat balasan
        $belumSuratBalasan = (clone $baseQuery)
            ->whereDoesntHave('suratBalasan')
            ->count();

        // Jumlah mahasiswa yang belum dinilai oleh mitra
        $belumDinilai = (clone $baseQuery)
            ->whereDoesntHave('penilaianMitra')
            ->count();

        return view('mitra.dashboard', [
            'mitra' => $mitra,
            'jumlahMahasiswa' => $jumlahMahasiswa,
            'sudahSubmit' => $sudahSubmit,
            'belumSuratBalasan' => $belumSuratBalasan,
            'belumDinilai' => $belumDinilai,
        ]);
    }
}