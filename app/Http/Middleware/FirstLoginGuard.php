<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FirstLoginGuard
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->first_login) {

            $user = Auth::user();

            // Mahasiswa → halaman lengkapi profil
            if ($user->role === 'mahasiswa') {

                if (
                    !$request->routeIs('siakad.first-login') &&
                    !$request->routeIs('siakad.first-login.store')
                ) {
                    return redirect()->route('siakad.first-login');
                }
            }

            // Dosen → halaman lengkapi profil
            elseif ($user->role === 'dosen') {

                if (
                    !$request->routeIs('siakad.dosen.first-login') &&
                    !$request->routeIs('siakad.dosen.first-login.store')
                ) {
                    return redirect()->route('siakad.dosen.first-login');
                }
            }

            // Admin / Staff / Pimpinan
            else {

                if (
                    !$request->routeIs('password.first') &&
                    !$request->routeIs('password.first.update')
                ) {
                    return redirect()->route('password.first');
                }
            }
        }

        return $next($request);
    }
}