<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::prefix('mahasiswa')->group(function () {
        Route::get('/dashboard', fn () => view('dashboard.mahasiswa'))
            ->name('mahasiswa.dashboard');
    });

    Route::prefix('staff')->group(function () {
        Route::get('/dashboard', fn () => view('dashboard.staff'))
            ->name('staff_tu.dashboard');
    });

    Route::prefix('kaprodi')->group(function () {
        Route::get('/dashboard', fn () => view('dashboard.kaprodi'))
            ->name('kaprodi.dashboard');
    });

    Route::prefix('dosen')->group(function () {
        Route::get('/dashboard', fn () => view('dashboard.dosen'))
            ->name('dosen.dashboard');
    });

});



require __DIR__.'/auth.php';