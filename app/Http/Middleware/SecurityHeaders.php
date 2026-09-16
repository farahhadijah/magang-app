<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Izinkan iframe same-origin untuk preview/cetak surat PKL (modal Staff TU)
        $allowSameOriginFrame = $request->is(
            'staff/surat-pengantar/*/preview',
            'staff/surat-pengantar/*/cetak',
            'staff/surat-pengantar/bulk-preview',
            'staff/surat-pengantar/bulk-print'
        );

        $response->header('X-Frame-Options', $allowSameOriginFrame ? 'SAMEORIGIN' : 'DENY');

        // Prevent MIME type sniffing
        $response->header('X-Content-Type-Options', 'nosniff');

        // Enable XSS protection
        $response->header('X-XSS-Protection', '1; mode=block');

        // Referrer policy
        $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Content Security Policy - DIPERBAIKI
        $response->header('Content-Security-Policy', 
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://unpkg.com https://cdn.jsdelivr.net; " .
            "style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.bunny.net https://unpkg.com; " .
            "font-src 'self' https://cdnjs.cloudflare.com https://fonts.bunny.net https://fonts.gstatic.com; " .
            "img-src 'self' data: https:; " .
            "connect-src 'self';"
        );

        // Permissions Policy
        $response->header('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        return $response;
    }
}