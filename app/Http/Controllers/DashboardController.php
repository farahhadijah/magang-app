<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        return match ($user->role) {
            'admin'      => redirect()->route('admin.dashboard'),
            'mahasiswa'  => view('dashboard'),
            'dosen'      => redirect()->route('dosen.dashboard'),
            'kaprodi'    => redirect()->route('kaprodi.dashboard'),
            'staff_tu'   => redirect()->route('staff.dashboard'),
            default      => abort(403, 'Role tidak dikenali'),
        };
    }
}