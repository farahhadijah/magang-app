<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'in:dosen,kaprodi,staff_tu'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'role' => $request->role,
            // assign plain password; User model mutator will hash it
            'password' => $request->password,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'User berhasil dibuat');
    }
}