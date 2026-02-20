<?php
namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;
use App\Models\PengajuanPkl;
class DashboardController extends Controller
{
   public function index()
{
    $totalMenunggu = PengajuanPkl::where('status', 'pending_tu')->count();
    $totalSelesaiTu = PengajuanPkl::where('status', 'pending_kaprodi')->count();
    $totalDitolak = PengajuanPkl::where('status', 'ditolak_tu')->count();
    return view('staff.dashboard', compact(
        'totalMenunggu',
        'totalSelesaiTu',
        'totalDitolak'
    ));
}

}