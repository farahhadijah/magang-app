<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\Fakultas;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard',[
            'totalMahasiswa' => Mahasiswa::count(),
            'totalDosen' => Dosen::count(),
            'totalProdi' => Prodi::count(),
            'totalFakultas' => Fakultas::count(),
        ]);
    }
}