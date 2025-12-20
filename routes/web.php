<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengajuanPklController;
use App\Http\Controllers\DokumenPengajuanController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\VerifikasiTuController;
use App\Http\Controllers\VerifikasiKaprodiController;
use App\Http\Controllers\PklController;
use App\Http\Controllers\LaporanAkhirController;
use App\Http\Controllers\NilaiPklController;

// Auth::routes();

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/db-test', function () {
    return DB::select('SELECT DATABASE() as db');
});

Route::middleware(['auth','role:mahasiswa'])->prefix('mahasiswa')->group(function () {

    Route::get('/dashboard', function () {
        return view('mahasiswa.dashboard');
    });

    Route::resource('pengajuan-pkl', PengajuanPklController::class);

    Route::post('pengajuan-pkl/{id}/upload-dokumen',
        [DokumenPengajuanController::class, 'store']
    );

    Route::resource('logbook', LogbookController::class)
        ->only(['index','create','store']);
});

Route::middleware(['auth','role:staff_tu'])->prefix('tu')->group(function () {

    Route::get('/dashboard', function () {
        return view('tu.dashboard');
    });

    Route::get('/pengajuan',
        [VerifikasiTuController::class, 'index']
    );

    Route::post('/pengajuan/{id}/approve',
        [VerifikasiTuController::class, 'approve']
    );

    Route::post('/pengajuan/{id}/reject',
        [VerifikasiTuController::class, 'reject']
    );
});

Route::middleware(['auth','role:kaprodi'])->prefix('kaprodi')->group(function () {

    Route::get('/dashboard', function () {
        return view('kaprodi.dashboard');
    });

    Route::get('/pengajuan',
        [VerifikasiKaprodiController::class, 'index']
    );

    Route::post('/pengajuan/{id}/approve',
        [VerifikasiKaprodiController::class, 'approve']
    );

    Route::post('/pengajuan/{id}/reject',
        [VerifikasiKaprodiController::class, 'reject']
    );
});

Route::middleware(['auth','role:dosen'])->prefix('dosen')->group(function () {

    Route::get('/dashboard', function () {
        return view('dosen.dashboard');
    });

    Route::get('/pkl',
        [PklController::class, 'index']
    );

    Route::post('/logbook/{id}/approve',
        [LogbookController::class, 'approve']
    );

    Route::post('/laporan/{id}/approve',
        [LaporanAkhirController::class, 'approve']
    );

    Route::resource('nilai-pkl', NilaiPklController::class)
        ->only(['store']);
});

?>