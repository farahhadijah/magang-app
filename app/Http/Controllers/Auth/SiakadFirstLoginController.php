<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use Illuminate\Http\Request;
use App\Services\SiakadService;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class SiakadFirstLoginController extends Controller
{
    public function show(SiakadService $siakadService)
    {
        $mahasiswa = session('siakad_first_login');

        if (!$mahasiswa) {
            return redirect()->route('login');
        }
        $existingMahasiswa = Mahasiswa::where(
            'nim',
            $mahasiswa['nim']
        )->first();

        if (
            $existingMahasiswa &&
            !$existingMahasiswa->is_active
        ) {
            session()->forget('siakad_first_login');

            return redirect()
                ->route('login')
                ->withErrors([
                    'username' => 'Akun mahasiswa dinonaktifkan.'
                ]);
        }
        if (Prodi::count() === 0) {

            $siakadService
                ->syncProdiDanFakultas();
        }
        $prodi = Prodi::orderBy('nama')->get();

        return view('auth.siakad-first-login', compact(
            'mahasiswa',
            'prodi'
        ));
    }
    public function store(Request $request)
    {
        $sessionData = session('siakad_first_login');

        if (!$sessionData) {
            return redirect()->route('login');
        }

        $request->validate(
            [
                'prodi_id' => ['required', 'exists:prodi,id'],
                'no_hp' => [ 'required', 'regex:/^08[0-9]{8,13}$/' ],
                'password' => ['required', 'confirmed', 'min:8'],
            ],
            [
                'prodi_id.required' => 'Prodi wajib dipilih.',
                'no_hp.required' => 'Nomor HP wajib diisi.',
                'no_hp.regex' => 'Nomor HP harus diawali 08 dan hanya berisi angka.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Cegah Duplikasi Mahasiswa
        |--------------------------------------------------------------------------
        */

        $existingMahasiswa = Mahasiswa::where(
            'nim',
            $sessionData['nim']
        )->first();

        if ($existingMahasiswa) {

            session()->forget('siakad_first_login');

            return redirect()
                ->route('login')
                ->withErrors([
                    'username' => 'Mahasiswa sudah terdaftar. Silakan login menggunakan password yang telah dibuat.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan Mahasiswa
        |--------------------------------------------------------------------------
        */

        $mahasiswa = Mahasiswa::create([
            'nim' => $sessionData['nim'],
            'nama' => $sessionData['nama'],
            'angkatan' => $sessionData['angkatan'],
            'prodi_id' => $request->prodi_id,
            'no_hp' => $request->no_hp,
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Observer otomatis membuat User
        |--------------------------------------------------------------------------
        */

        $user = $mahasiswa->user;

        if (!$user) {
            return back()->withErrors([
                'username' => 'User gagal dibuat otomatis.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Password baru
        |--------------------------------------------------------------------------
        */

        $user->update([
            'password' => Hash::make($request->password),
            'first_login' => false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Hapus session sementara
        |--------------------------------------------------------------------------
        */

        session()->forget('siakad_first_login');

        /*
        |--------------------------------------------------------------------------
        | Login otomatis
        |--------------------------------------------------------------------------
        */

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }
}