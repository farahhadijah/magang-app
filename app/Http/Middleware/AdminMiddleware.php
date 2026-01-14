<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // belum login
        if (!auth()->check()) {
            abort(401, 'Unauthorized');
        }

        // bukan admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak - Bukan Admin');
        }

        return $next($request);
    }
}