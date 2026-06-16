<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SiakadDosenFirstLoginController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if (!$user || !$user->dosen) {
            abort(403);
        }

        $dosen = $user->dosen;

        if (!$dosen->is_active) {

            Auth::logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'username' => 'Akun dosen dinonaktifkan.'
                ]);
        }

        $prodi = Prodi::orderBy('nama')->get();

        return view(
            'auth.siakad-dosen-first-login',
            compact('dosen', 'prodi')
        );
    }
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->dosen) {
            abort(403);
        }

        $request->validate(
            [
                'prodi_id' => ['required', 'exists:prodi,id'],
                'keahlian' => ['nullable', 'string', 'max:100'],
                'jabatan' => ['required', 'in:dosen,kaprodi'],
                'no_hp' => ['required', 'regex:/^08[0-9]{8,13}$/'],
                'password' => ['required', 'confirmed', 'min:8'],
            ],
            [
                'prodi_id.required' => 'Prodi wajib dipilih.',
                'jabatan.required' => 'Jabatan wajib dipilih.',
                'no_hp.required' => 'Nomor HP wajib diisi.',
                'no_hp.regex' => 'Nomor HP harus diawali 08 dan hanya berisi angka.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            ]
        );

        $user->dosen->update([
            'prodi_id' => $request->prodi_id,
            'keahlian' => $request->keahlian,
            'jabatan'  => $request->jabatan,
            'no_hp'    => $request->no_hp,
        ]);

        $user->update([
            'password' => Hash::make($request->password),
            'first_login' => false,
        ]);

        return redirect()->route('dashboard');
    }
}