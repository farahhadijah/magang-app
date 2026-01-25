<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;

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
public function store(Request $request): RedirectResponse
{
    $request->validate([
        'username' => ['required', 'string'],
        'password' => ['required'],
    ]);

    $credentials = $request->only('username', 'password');

    if (!Auth::attempt($credentials)) {
        throw ValidationException::withMessages([
            'username' => __('Username atau password salah.'),
        ]);
    }

    $request->session()->regenerate();

    $user = Auth::user();

    // ⛔ akun nonaktif
    if (!$user->is_active) {
        Auth::logout();
        abort(403, 'Akun tidak aktif');
    }

    // 🔐 first login
    if ($user->first_login) {
        return redirect()->route('password.first');
    }

    return redirect()->route('dashboard');
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