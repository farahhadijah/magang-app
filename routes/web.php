<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\FirstLoginController;

use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\LogbookController as MahasiswaLogbookController;
use App\Http\Controllers\Mahasiswa\PengajuanPklController as MahasiswaPengajuanController;
use App\Http\Controllers\Mahasiswa\LaporanAkhirController as MahasiswaLaporanAkhirController;

use App\Http\Controllers\Dosen\MahasiswaBimbinganController;
use App\Http\Controllers\Dosen\ReviewLogbookController;
use App\Http\Controllers\Dosen\LaporanAkhirController as DosenLaporanAkhirController;
use App\Http\Controllers\Dosen\NilaiPklController as DosenNilaiPklController;

use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\DokumenPengajuanController as StaffDokumenController;
use App\Http\Controllers\Staff\PengajuanPKLController as StaffPengajuanController;

use App\Http\Controllers\Kaprodi\PengajuanPKLController as KaprodiPengajuanController;
use App\Http\Controllers\Kaprodi\MahasiswaController as KaprodiMahasiswaController;
use App\Http\Controllers\Kaprodi\NilaiController as KaprodiNilaiController;

/*
|--------------------------------------------------------------------------
| ROOT & AUTH
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

/*
|--------------------------------------------------------------------------
| DB TEST (OPTIONAL)
|--------------------------------------------------------------------------
*/
Route::get('/db-test', function () {
    return DB::select('SELECT DATABASE() as db');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED (GLOBAL)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // First login flow
    Route::get('/first-login', [FirstLoginController::class, 'show'])->name('password.first');
    Route::post('/first-login', [FirstLoginController::class, 'update'])->name('password.first.update');

    // Dashboard (single entry)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('first.login')
        ->name('dashboard');

    // Profile (all roles)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');

        // Pengajuan PKL
        Route::get('/pengajuan-pkl', [MahasiswaPengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan-pkl', [MahasiswaPengajuanController::class, 'store'])->name('pengajuan.store');
        Route::get('/status-pengajuan', [MahasiswaPengajuanController::class, 'status'])->name('pengajuan.status');
        Route::post('/pengajuan-pkl/dokumen/{id}/upload-ulang', [MahasiswaPengajuanController::class, 'uploadUlangDokumen'])
            ->name('pengajuan.dokumen.upload-ulang');

        // Logbook
        Route::get('/logbook', [MahasiswaLogbookController::class, 'index'])->name('logbook.index');
        Route::get('/logbook/create', [MahasiswaLogbookController::class, 'create'])->name('logbook.create');
        Route::post('/logbook', [MahasiswaLogbookController::class, 'store'])->name('logbook.store');
        Route::get('/logbook/{logbook}/edit', [MahasiswaLogbookController::class, 'edit'])->name('logbook.edit');
        Route::put('/logbook/{logbook}', [MahasiswaLogbookController::class, 'update'])->name('logbook.update');
        Route::delete('/logbook/{logbook}', [MahasiswaLogbookController::class, 'destroy'])->name('logbook.destroy');
        // Laporan Akhir
        Route::get('/laporan', [MahasiswaLaporanAkhirController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/create', [MahasiswaLaporanAkhirController::class, 'create'])->name('laporan.create');
        Route::post('/laporan', [MahasiswaLaporanAkhirController::class, 'store'])->name('laporan.store');
        Route::post('/cek-kemiripan-tempat', 
    [MahasiswaPengajuanController::class, 'cekKemiripanAjax']
            )->name('pengajuan.cek-kemiripan');

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
        Route::view('/dashboard', 'dosen.dashboard')->name('dashboard');
        Route::get('/mahasiswa-bimbingan', [MahasiswaBimbinganController::class, 'index'])->name('mahasiswa.bimbingan');
        Route::get('/logbook', [ReviewLogbookController::class, 'index'])->name('logbook.index');

        Route::put('/logbook/{logbook}/review', [ReviewLogbookController::class, 'review'])->name('logbook.review');
        Route::put('/logbook/{logbook}/review-ajax', [ReviewLogbookController::class, 'reviewAjax'])->name('logbook.review-ajax');

        // Nilai PKL
        Route::get('/nilai', [DosenNilaiPklController::class, 'index'])->name('nilai.index');
        Route::get('/nilai/{pkl}/create', [DosenNilaiPklController::class, 'create'])->name('nilai.create');
        Route::post('/nilai/{pkl}', [DosenNilaiPklController::class, 'store'])->name('nilai.store');


        // Laporan Akhir
        Route::get('/laporan', [DosenLaporanAkhirController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/{pkl}', [DosenLaporanAkhirController::class, 'show'])->name('laporan.show');
        Route::post('/laporan/{pkl}/approve', [DosenLaporanAkhirController::class, 'approve'])->name('laporan.approve');
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
        Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
        Route::get('/pengajuan/histori-ditolak', [StaffPengajuanController::class, 'historiDitolak'])->name('pengajuan.histori_ditolak');

        Route::post('/dokumen/{id}/valid', [StaffDokumenController::class, 'valid'])->name('dokumen.valid');
        Route::post('/dokumen/{id}/invalid', [StaffDokumenController::class, 'invalid'])->name('dokumen.invalid');

        Route::get('/pengajuan', [StaffPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/{id}', [StaffPengajuanController::class, 'show'])->name('pengajuan.show');
        Route::post('/pengajuan/{id}/approve', [StaffPengajuanController::class, 'approve'])->name('pengajuan.approve');
        Route::post('/pengajuan/{id}/reject', [StaffPengajuanController::class, 'reject'])->name('pengajuan.reject');
        
        Route::get('/mitra', 
        [StaffPengajuanController::class, 'manajemenMitra']
        )->name('mitra.index');
        
        Route::get('/mitra/{id}/akun',
            [StaffPengajuanController::class, 'showAkunMitra']
        )->name('mitra.akun');

        Route::post('/mitra/{id}',
            [StaffPengajuanController::class, 'storeMitra']
        )->name('mitra.store');


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
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
        Route::resource('users', UserController::class)->only(['create', 'store']);
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
        Route::get('/dashboard', [KaprodiPengajuanController::class, 'dashboard'])->name('dashboard');
        Route::get('/mahasiswa', [KaprodiMahasiswaController::class, 'index'])->name('mahasiswa.index');
        Route::get('/pengajuan', [KaprodiPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/nilai', [KaprodiNilaiController::class, 'index'])->name('nilai.index');
        Route::get('/pengajuan/{id}', [KaprodiPengajuanController::class, 'show'])->name('pengajuan.show');
        Route::post('/pengajuan/{id}/approve', [KaprodiPengajuanController::class, 'approve'])->name('pengajuan.approve');
        Route::post('/pengajuan/{id}/reject', [KaprodiPengajuanController::class, 'reject'])->name('pengajuan.reject');
        Route::get('/histori-ditolak', [KaprodiPengajuanController::class, 'historiDitolak'])->name('pengajuan.histori_ditolak');
    });

/*
|--------------------------------------------------------------------------
| MITRA AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'first.login', 'role:mitra'])
    ->prefix('mitra')
    ->name('mitra.')
    ->group(function () {

        Route::get('/dashboard', 
            [App\Http\Controllers\Mitra\MitraController::class, 'dashboard']
        )->name('dashboard');

        Route::get('/mahasiswa', [App\Http\Controllers\Mitra\MitraController::class, 'mahasiswa'])
            ->name('mahasiswa');

        // Daftar mahasiswa yang sudah mengisi logbook (index untuk mitra)
        Route::get('/logbook', [App\Http\Controllers\Mitra\MitraController::class, 'logbookList'])
            ->name('logbook.index');

        Route::get('/logbook/{pkl}', [App\Http\Controllers\Mitra\MitraController::class, 'logbook'])
            ->name('logbook');
    });


/*
|--------------------------------------------------------------------------
| DEFAULT AUTH ROUTES (BREEZE)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';