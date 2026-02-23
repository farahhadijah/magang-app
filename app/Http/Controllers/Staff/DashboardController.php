<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPkl;

class DashboardController extends Controller
{
    public function index()
    {
        $prodiId = $this->getProdiId();

        $totalMenunggu = PengajuanPkl::where('status', 'pending_tu')
            ->whereHas('mahasiswa', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            })
            ->count();

        $totalSelesaiTu = PengajuanPkl::where('status', 'pending_kaprodi')
            ->whereHas('mahasiswa', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            })
            ->count();

        $totalDitolak = PengajuanPkl::where('status', 'ditolak_tu')
            ->whereHas('mahasiswa', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            })
            ->count();

        return view('staff.dashboard', compact(
            'totalMenunggu',
            'totalSelesaiTu',
            'totalDitolak'
        ));
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