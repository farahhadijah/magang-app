<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);
        $credentials = $request->only(
            'username',
            'password'
        );
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if (!$user->is_active) {
                Auth::logout();
                abort(
                    403,
                    'Akun tidak aktif'
                );
            }
            if ($user->first_login) {
                if ($user->role === 'mahasiswa') {
                    return redirect()->route(
                        'siakad.first-login'
                    );
                }
                if ($user->role === 'dosen') {
                    return redirect()->route(
                        'siakad.dosen.first-login'
                    );
                }
                return redirect()->route(
                    'password.first'
                );
            }
            return redirect()->route(
                'dashboard'
            );
        }
        throw ValidationException::withMessages([
            'username' => __('Username / password salah.'),
        ]);
    }
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}