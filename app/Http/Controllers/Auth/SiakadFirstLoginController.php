<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SiakadFirstLoginController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if (! $user || ! $user->mahasiswa) {
            abort(403);
        }

        $mahasiswa = $user->mahasiswa;

        if (! $mahasiswa->is_active) {
            Auth::logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'username' => 'Akun mahasiswa dinonaktifkan.',
                ]);
        }

        return view(
            'auth.siakad-first-login',
            compact('mahasiswa')
        );
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (! $user || ! $user->mahasiswa) {
            abort(403);
        }

        $request->validate(
            [
                'no_hp' => ['required', 'regex:/^08[0-9]{8,13}$/'],
                'password' => ['required', 'confirmed', 'min:8'],
            ],
            [
                'no_hp.required' => 'Nomor HP wajib diisi.',
                'no_hp.regex' => 'Nomor HP harus diawali 08 dan hanya berisi angka.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            ]
        );

        $user->mahasiswa->update([
            'no_hp' => $request->no_hp,
        ]);

        $user->update([
            'password' => Hash::make($request->password),
            'first_login' => false,
        ]);

        return redirect()->route('dashboard');
    }
}
