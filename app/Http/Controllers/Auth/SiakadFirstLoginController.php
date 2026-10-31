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

        if (! $user->first_login) {
            return redirect()->route('dashboard');
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

        if (! $user->first_login) {
            return redirect()->route('dashboard');
        }

        // ✅ Ambil ID mahasiswa untuk validasi unique (abaikan diri sendiri)
        $mahasiswaId = $user->mahasiswa->id;

        $request->validate(
            [
                // ✅ Tambah validasi email
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    "unique:mahasiswa,email,{$mahasiswaId}",
                ],
                'no_hp' => ['required', 'regex:/^08[0-9]{8,13}$/'],
                'password' => ['required', 'confirmed', 'min:8'],
            ],
            [
                // ✅ Pesan error email
                'email.required' => 'Email wajib diisi.',
                'email.email'    => 'Format email tidak valid.',
                'email.unique'   => 'Email sudah digunakan oleh mahasiswa lain.',
                'email.max'      => 'Email maksimal 255 karakter.',

                'no_hp.required' => 'Nomor HP wajib diisi.',
                'no_hp.regex' => 'Nomor HP harus diawali 08 dan hanya berisi angka.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            ]
        );

        // ✅ Simpan email & no_hp sekaligus
        $user->mahasiswa->update([
            'email' => strtolower(trim($request->email)),
            'no_hp' => $request->no_hp,
        ]);

        $user->update([
            'password' => Hash::make($request->password),
            'first_login' => false,
        ]);

        session()->forget('url.intended');

        return redirect()->route('dashboard');
    }
}
