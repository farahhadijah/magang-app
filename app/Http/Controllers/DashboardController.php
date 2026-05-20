<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
{
    $user = auth()->user();

    switch ($user->role) {
        case 'mahasiswa':
            return redirect()->route('mahasiswa.dashboard');

        case 'dosen':
            return redirect()->route('dosen.dashboard');

        case 'staff_tu':
            return redirect()->route('staff.dashboard');

        case 'admin':
            return redirect()->route('admin.dashboard');

        case 'kaprodi':
            return redirect()->route('kaprodi.dashboard');

        case 'pimpinan':
            // pimpinan area does not have a named dashboard route; send to the pimpinan prefix
            return redirect('/pimpinan');

        case 'mitra': // 🔥 TAMBAHKAN INI
            return redirect()->route('mitra.dashboard');

        default:
            abort(403);
    }
}

}