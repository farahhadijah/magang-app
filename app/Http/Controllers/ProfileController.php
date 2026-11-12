<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information (email).
     *
     * Email disimpan di table master (mahasiswa/dosen/staff) sesuai role.
     * Role tanpa sumber email (pimpinan, mitra, admin) otomatis di-reject
     * oleh ProfileUpdateRequest (rule: prohibited).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Guard tambahan (defense-in-depth) — request sudah validasi,
        // tapi tetap dicek di sini agar tidak ada celah kalau rule diubah.
        if (! $user->canEditEmail()) {
            abort(403, 'Role Anda tidak memiliki akses untuk mengubah email.');
        }

        $owner = $user->profileOwner();

        if (! $owner) {
            abort(403, 'Data profil tidak ditemukan.');
        }

        $newEmail = $request->validated()['email'];
        $oldEmail = $owner->email;

        // Skip update kalau email tidak berubah (hindari query tidak perlu)
        if ($oldEmail === $newEmail) {
            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        }

        // Update email di table master sesuai role
        $owner->update(['email' => $newEmail]);

        // Audit log
        Log::info('User mengubah email profile', [
            'user_id'   => $user->id,
            'username'  => $user->username,
            'role'      => $user->role,
            'table'     => $user->profileEmailTable(),
            'owner_id'  => $owner->id,
            'old_email' => $oldEmail,
            'new_email' => $newEmail,
            'ip'        => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}