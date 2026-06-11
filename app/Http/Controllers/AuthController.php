<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\SiakadService;
class AuthController extends Controller
{
    // tampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }
    // proses login
    public function login(
        Request $request,
        SiakadService $siakadService
    )
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        /**
         * Login normal (user sudah ada)
         */
        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        /**
         * Khusus mahasiswa SIAKAD:
         * username = NIM
         * password = NIM
         */
        if (
            $request->username === $request->password
        ) {

            $mahasiswa = $siakadService
                ->findMahasiswaByNim($request->username);

            if ($mahasiswa) {

                session([
                    'siakad_first_login' => [
                        'nim' => $mahasiswa['nim'],
                        'nama' => $mahasiswa['nama'],
                        'angkatan' => $mahasiswa['angkatan'],
                        'jenis_kelamin' => $mahasiswa['jenis_kelamin'],
                        'kelas' => $mahasiswa['kelas'],
                    ]
                ]);

                return redirect()
                    ->route('siakad.first-login');
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah',
        ]);
    }
    // logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}