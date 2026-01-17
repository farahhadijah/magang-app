<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FirstLoginController extends Controller
{
    public function show()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // kalau bukan first login, lempar ke dashboard
        if (!$user || !$user->first_login) {
            return redirect()->route('dashboard');
        }
        return view('auth.first-login');
    }
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->first_login) {
            abort(403);
        }

        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user->update([
            'password'    => Hash::make($request->password),
            'first_login' => false,
        ]);
        

        return redirect()->route('dashboard')
            ->with('status', 'Password berhasil diperbarui');
    }
}