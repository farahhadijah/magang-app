<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SiakadDosenFirstLoginController extends Controller
{
    public function show()
    {
        $dosen = session('siakad_dosen_first_login');

        if (!$dosen) {
            return redirect()->route('login');
        }

        $existingDosen = Dosen::where(
            'nidn',
            $dosen['nidn']
        )->first();

        if (
            $existingDosen &&
            !$existingDosen->is_active
        ) {
            session()->forget(
                'siakad_dosen_first_login'
            );

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
        $sessionData = session(
            'siakad_dosen_first_login'
        );

        if (!$sessionData) {
            return redirect()->route('login');
        }

        $request->validate(
            [
                'prodi_id' => ['required', 'exists:prodi,id'],
                'keahlian' => ['nullable', 'string', 'max:100'],
                'jabatan' => ['required', 'in:dosen,kaprodi'],
                'no_hp' => [ 'required', 'regex:/^08[0-9]{8,13}$/' ],
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

        /*
        |--------------------------------------------------------------------------
        | Cegah Duplikasi Dosen
        |--------------------------------------------------------------------------
        */

        $existingDosen = Dosen::where(
            'nidn',
            $sessionData['nidn']
        )->first();

        if ($existingDosen) {

            session()->forget(
                'siakad_dosen_first_login'
            );

            return redirect()
                ->route('login')
                ->withErrors([
                    'username' =>
                        'Dosen sudah terdaftar. Silakan login menggunakan password yang telah dibuat.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan Dosen
        |--------------------------------------------------------------------------
        */

        $dosen = Dosen::create([
            'nidn'      => $sessionData['nidn'],
            'nama'      => $sessionData['nama'],
            'prodi_id'  => $request->prodi_id,
            'keahlian'  => $request->keahlian,
            'jabatan'   => $request->jabatan,
            'no_hp'     => $request->no_hp,
            'is_active' => true,
        ]);

        $user = $dosen->user;

        if (!$user) {
            return back()->withErrors([
                'username' => 'User gagal dibuat otomatis.'
            ]);
        }

        $user->update([
            'password' => Hash::make(
                $request->password
            ),
            'first_login' => false,
        ]);

        session()->forget(
            'siakad_dosen_first_login'
        );

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }
}