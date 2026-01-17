<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;

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
| AUTHENTICATED
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // SINGLE DASHBOARD ENTRY POINT
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Breeze Profile
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
Route::middleware(['auth', 'role:admin'])
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
Route::middleware(['auth', 'role:dosen'])
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
Route::middleware(['auth', 'role:mahasiswa'])
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
Route::middleware(['auth', 'role:staff_tu'])
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
Route::middleware(['auth', 'role:kaprodi'])
    ->prefix('kaprodi')
    ->name('kaprodi.')
    ->group(function () {

        Route::view('/dashboard', 'kaprodi.dashboard')
            ->name('dashboard');
    });

require __DIR__.'/auth.php';