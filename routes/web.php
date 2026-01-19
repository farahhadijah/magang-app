<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\FirstLoginController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED (NO ROLE)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // FIRST LOGIN (WAJIB SEBELUM DASHBOARD)
    Route::get('/first-login', [FirstLoginController::class, 'show'])
        ->name('password.first');

    Route::post('/first-login', [FirstLoginController::class, 'update'])
        ->name('password.first.update');

    // SINGLE ENTRY POINT DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('first.login')
        ->name('dashboard');

    // PROFILE (BOLEH DIAKSES SEMUA ROLE)
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'first.login', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::view('/dashboard', 'admin.dashboard')
            ->name('dashboard');

        Route::resource('users', UserController::class)
            ->only(['create', 'store']);
    });

/*
|--------------------------------------------------------------------------
| DOSEN AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'first.login', 'role:dosen'])
    ->prefix('dosen')
    ->name('dosen.')
    ->group(function () {
        Route::view('/dashboard', 'dosen.dashboard')
            ->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| MAHASISWA AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'first.login', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->name('mahasiswa.')
    ->group(function () {
        Route::view('/dashboard', 'mahasiswa.dashboard')
            ->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| STAFF TU AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'first.login', 'role:staff_tu'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {
        Route::view('/dashboard', 'staff.dashboard')
            ->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| KAPRODI AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'first.login', 'role:kaprodi'])
    ->prefix('kaprodi')
    ->name('kaprodi.')
    ->group(function () {
        Route::view('/dashboard', 'kaprodi.dashboard')
            ->name('dashboard');
    });

require __DIR__.'/auth.php';