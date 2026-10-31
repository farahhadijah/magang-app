<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\Informasi;

class AuthController extends Controller
{
    public function showLogin()
    {
        $informasi = Informasi::active()
            ->forRole('mahasiswa')
            ->latest()
            ->get();

        return view('auth.login', compact('informasi'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $this->ensureIsNotRateLimited($request);

        if (Auth::attempt($request->only('username', 'password'))) {
            $request->session()->regenerate();
            RateLimiter::clear($this->throttleKey($request));

            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'username' => 'Akun tidak aktif.',
                ]);
            }

            if ($user->first_login) {
                // Bersihkan intended URL agar tidak mencemari login berikutnya
                session()->forget('url.intended');

                if ($user->role === 'mahasiswa') {
                    return redirect()->route('siakad.first-login');
                }
                if ($user->role === 'dosen') {
                    return redirect()->route('siakad.dosen.first-login');
                }
                if ($user->role === 'staff_tu') {
                    return redirect()->route('siakad.staff.first-login');
                }

                return redirect()->route('password.first');
            }

            // Jika user sudah menyelesaikan first login, pastikan intended URL bukan halaman first login
            $intended = session()->get('url.intended');
            if ($intended && str_contains($intended, 'first-login')) {
                session()->forget('url.intended');
            }

            return redirect()->intended(route('dashboard'));
        }

        RateLimiter::hit($this->throttleKey($request));

        throw ValidationException::withMessages([
            'username' => 'Username atau password salah',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        event(new Lockout($request));

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(
            Str::lower($request->input('username')).'|'.$request->ip()
        );
    }
}
