<?php

namespace App\Http\Controllers\Dosen;
use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\Pkl;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
public function index()
{
    $dosenId = auth()->user()->dosen_id; // ✅ BENAR

    $mahasiswaCount = Pkl::where('id_dosen', $dosenId)
        ->where('status', 'aktif')
        ->count();

    $logbookPendingCount = Logbook::whereHas('pkl', function ($q) use ($dosenId) {
        $q->where('id_dosen', $dosenId)
          ->where('status', 'aktif');
    })->where('status_approve', 'pending')
      ->count();

    $pklSelesaiCount = Pkl::where('id_dosen', $dosenId)
        ->where('status', 'selesai')
        ->count();

    return view('dosen.dashboard', compact(
        'mahasiswaCount',
        'logbookPendingCount',
        'pklSelesaiCount'
    ));
}
}