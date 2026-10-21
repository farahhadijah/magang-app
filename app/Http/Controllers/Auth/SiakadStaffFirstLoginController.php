<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SiakadStaffFirstLoginController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if (! $user || ! $user->staff) {
            abort(403);
        }

        $staff = $user->staff;

        if (! $staff->is_active) {
            Auth::logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'username' => 'Akun staff dinonaktifkan.',
                ]);
        }

        return view(
            'auth.siakad-staff-first-login',
            compact('staff')
        );
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (! $user || ! $user->staff) {
            abort(403);
        }

        $staffId = (int) $user->staff->id;

        $request->validate(
            [
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    \Illuminate\Validation\Rule::unique('staff', 'email')->ignore($staffId),
                ],
                'password' => ['required', 'confirmed', 'min:8'],
            ],
            [
                'email.required' => 'Email wajib diisi.',
                'email.email'    => 'Format email tidak valid.',
                'email.unique'   => 'Email sudah digunakan oleh staff lain.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            ]
        );

        $user->staff->update([
            'email' => strtolower(trim($request->email)),
        ]);

        $user->update([
            'password' => Hash::make($request->password),
            'first_login' => false,
        ]);

        return redirect()->route('dashboard');
    }
}