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

            // Boleh akses halaman first-login saja
            if (!$request->routeIs('password.first')) {
                return redirect()->route('password.first');
            }
        }

        return $next($request);
    }
}