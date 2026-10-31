<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FirstLoginGuard
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->first_login) {
                // 1. Mahasiswa → halaman lengkapi profil
                if ($user->role === 'mahasiswa') {
                    if (
                        !$request->routeIs('siakad.first-login') &&
                        !$request->routeIs('siakad.first-login.store')
                    ) {
                        return redirect()->route('siakad.first-login');
                    }
                }
                
                // 2. Dosen → halaman lengkapi profil
                elseif ($user->role === 'dosen') {
                    if (
                        !$request->routeIs('siakad.dosen.first-login') &&
                        !$request->routeIs('siakad.dosen.first-login.store')
                    ) {
                        return redirect()->route('siakad.dosen.first-login');
                    }
                }

                // 3. STAFF TU → halaman lengkapi profil
                elseif ($user->role === 'staff_tu') {
                    if (
                        !$request->routeIs('siakad.staff.first-login') &&
                        !$request->routeIs('siakad.staff.first-login.store')
                    ) {
                        return redirect()->route('siakad.staff.first-login');
                    }
                }

                // 4. Admin & Pimpinan → halaman ganti password biasa
                else {
                    if (
                        !$request->routeIs('password.first') &&
                        !$request->routeIs('password.first.update')
                    ) {
                        return redirect()->route('password.first');
                    }
                }
            } else {
                // User sudah melewati first login: cegah akses ke halaman first login
                if (
                    $request->routeIs('siakad.first-login*') ||
                    $request->routeIs('siakad.dosen.first-login*') ||
                    $request->routeIs('siakad.staff.first-login*') ||
                    $request->routeIs('password.first*')
                ) {
                    return redirect()->route('dashboard');
                }
            }
        }

        return $next($request);
    }
}