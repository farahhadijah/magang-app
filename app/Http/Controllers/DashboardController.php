<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin'     => view('admin.dashboard'),
            'mahasiswa' => view('mahasiswa.dashboard'),
            'dosen'     => view('dosen.dashboard'),
            'kaprodi'   => view('kaprodi.dashboard'),
            'staff_tu'  => view('staff.dashboard'),
            default     => abort(403, 'Role tidak dikenali'),
        };
    }
}