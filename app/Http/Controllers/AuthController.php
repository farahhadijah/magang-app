<?php

namespace App\Http\Controllers;

use App\Services\SiakadService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request, SiakadService $siakadService)
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
                if ($user->role === 'mahasiswa') {
                    return redirect()->route('siakad.first-login');
                }
                if ($user->role === 'dosen') {
                    return redirect()->route('siakad.dosen.first-login');
                }

                return redirect()->route('password.first');
            }

            return redirect()->intended(route('dashboard'));
        }

        if ($request->username === $request->password) {
            $mahasiswa = $siakadService->findMahasiswaByNim($request->username);

            if ($mahasiswa) {
                RateLimiter::clear($this->throttleKey($request));

                session([
                    'siakad_first_login' => [
                        'nim' => $mahasiswa['nim'],
                        'nama' => $mahasiswa['nama'],
                        'angkatan' => $mahasiswa['angkatan'],
                        'jenis_kelamin' => $mahasiswa['jenis_kelamin'],
                        'kelas' => $mahasiswa['kelas'],
                    ],
                ]);

                return redirect()->route('siakad.first-login');
            }
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
