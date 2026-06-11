<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;
use App\Services\SiakadService;
use App\Models\Mahasiswa;
use App\Models\Dosen;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(
        Request $request,
        SiakadService $siakadService
    ): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $credentials = $request->only(
            'username',
            'password'
        );

        /**
         * LOGIN NORMAL
         */
        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();

            if (!$user->is_active) {

                Auth::logout();

                abort(403, 'Akun tidak aktif');
            }

            if ($user->first_login) {
                return redirect()->route('password.first');
            }

            return redirect()->route('dashboard');
        }

        /**
         * LOGIN PERTAMA DARI SIAKAD
         *
         * username = nim
         * password = nim
         */
        if (
            $request->username === $request->password
        ) {

            $mahasiswa = $siakadService
                ->findMahasiswaByNim(
                    $request->username
                );

            if ($mahasiswa) {

                $existingMahasiswa = Mahasiswa::where(
                    'nim',
                    $mahasiswa['nim']
                )->first();

                if (
                    $existingMahasiswa &&
                    !$existingMahasiswa->is_active
                ) {
                    throw ValidationException::withMessages([
                        'username' => 'Akun mahasiswa dinonaktifkan. Silakan hubungi admin.'
                    ]);
                }

                session([
                    'siakad_first_login' => [
                        'nim' => $mahasiswa['nim'],
                        'nama' => $mahasiswa['nama'],
                        'angkatan' => $mahasiswa['angkatan'],
                        'jenis_kelamin' => $mahasiswa['jenis_kelamin'],
                        'kelas' => $mahasiswa['kelas'],
                    ]
                ]);

                return redirect()->route(
                    'siakad.first-login'
                );
            }

            $dosen = $siakadService
                ->findDosenByNidn(
                    $request->username
                );

            if ($dosen) {

                $existingDosen = Dosen::where(
                    'nidn',
                    $dosen['nidn']
                )->first();

                if (
                    $existingDosen &&
                    !$existingDosen->is_active
                ) {
                    throw ValidationException::withMessages([
                        'username' => 'Akun dosen dinonaktifkan. Silakan hubungi admin.'
                    ]);
                }

                session([
                    'siakad_dosen_first_login' => [
                        'nidn' => $dosen['nidn'],
                        'nama' => $dosen['nama'],
                    ]
                ]);

                return redirect()->route(
                    'siakad.dosen.first-login'
                );
            }
        }

        throw ValidationException::withMessages([
            'username' => __('Username / password salah.'),
        ]);
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}