<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SiakadDosenFirstLoginController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if (! $user || ! $user->dosen) {
            abort(403);
        }

        if (! $user->first_login) {
            return redirect()->route('dashboard');
        }

        $dosen = $user->dosen;

        if (! $dosen->is_active) {
            Auth::logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'username' => 'Akun dosen dinonaktifkan.',
                ]);
        }

        return view(
            'auth.siakad-dosen-first-login',
            compact('dosen')
        );
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (! $user || ! $user->dosen) {
            abort(403);
        }

        if (! $user->first_login) {
            return redirect()->route('dashboard');
        }

        // Ambil ID dosen untuk validasi unique
        $dosenId = $user->dosen->id;

        $request->validate(
            [
                'keahlian' => ['nullable', 'string', 'max:100'],
                
                // ✅ Tambah validasi email
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    "unique:dosen,email,{$dosenId}",
                ],

                // ❌ Hapus validasi no_hp
                
                'password' => ['required', 'confirmed', 'min:8'],
            ],
            [
                // ✅ Pesan error email
                'email.required' => 'Email wajib diisi.',
                'email.email'    => 'Format email tidak valid.',
                'email.unique'   => 'Email sudah digunakan oleh dosen lain.',
                'email.max'      => 'Email maksimal 255 karakter.',

                // ❌ Hapus pesan error no_hp

                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            ]
        );

        // ✅ Simpan email & keahlian (tanpa no_hp)
        $user->dosen->update([
            'email' => strtolower(trim($request->email)),
            'keahlian' => $request->keahlian,
        ]);

        $user->update([
            'password' => Hash::make($request->password),
            'first_login' => false,
        ]);

        session()->forget('url.intended');

        return redirect()->route('dashboard');
    }
}