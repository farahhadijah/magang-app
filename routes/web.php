<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\FirstLoginController;

// ADMIN
use App\Http\Controllers\Admin\UserController;
/*
|--------------------------------------------------------------------------
| ROOT & AUTH
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
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

    // FIRST LOGIN
    Route::get('/first-login', [FirstLoginController::class, 'show'])
        ->name('password.first');

    Route::post('/first-login', [FirstLoginController::class, 'update'])
        ->name('password.first.update');

    // SINGLE ENTRY DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('first.login')
        ->name('dashboard');

    // PROFILE (ALL ROLES)
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| MAHASISWA AREA
|--------------------------------------------------------------------------
*/
// MAHASISWA
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\PengajuanPklController;
use App\Http\Controllers\Mahasiswa\LogbookController;
Route::middleware(['auth', 'first.login', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->name('mahasiswa.')
    ->group(function () {

        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])
            ->name('dashboard');
        // PENGAJUAN PKL
        Route::get('/pengajuan-pkl', [PengajuanPklController::class, 'create'])
            ->name('pengajuan.create');
        Route::post('/pengajuan-pkl', [PengajuanPklController::class, 'store'])
            ->name('pengajuan.store');
        Route::get('/status-pengajuan', [PengajuanPklController::class, 'status'])
            ->name('pengajuan.status');
        // UPLOAD ULANG DOKUMEN INVALID
        Route::post(
            '/pengajuan-pkl/dokumen/{id}/upload-ulang',
            [PengajuanPklController::class, 'uploadUlangDokumen']
            )->name('pengajuan.dokumen.upload-ulang');
        // LOGBOOK
        Route::get('/logbook', [LogbookController::class, 'index'])
            ->name('logbook.index');
        Route::get('/logbook/create', [LogbookController::class, 'create'])
            ->name('logbook.create');
        Route::post('/logbook', [LogbookController::class, 'store'])
            ->name('logbook.store');
        // edit
        Route::get('/logbook/{logbook}/edit', [LogbookController::class, 'edit'])
            ->name('logbook.edit');

        Route::put('/logbook/{logbook}', [LogbookController::class, 'update'])
            ->name('logbook.update');

        Route::delete('/logbook/{logbook}', [LogbookController::class, 'destroy'])
            ->name('logbook.destroy');
    });

/*
|--------------------------------------------------------------------------
| DOSEN AREA
|--------------------------------------------------------------------------
*/
// DOSEN
use App\Http\Controllers\Dosen\MahasiswaBimbinganController;
use App\Http\Controllers\Dosen\ReviewLogbookController;
use App\Http\Controllers\Dosen\PenilaianPKLController;
Route::middleware(['auth', 'first.login', 'role:dosen'])
    ->prefix('dosen')
    ->name('dosen.')
    ->group(function () {

        Route::view('/dashboard', 'dosen.dashboard')
            ->name('dashboard');
        // MAHASISWA BIMBINGAN
        Route::get('/mahasiswa-bimbingan', [MahasiswaBimbinganController::class, 'index'])
            ->name('mahasiswa.bimbingan');
        Route::get('logbook', [ReviewLogbookController::class, 'index'])
            ->name('logbook.index');

        Route::put('logbook/{logbook}/review',
            [ReviewLogbookController::class, 'review'])
            ->name('logbook.review');

        Route::put('logbook/{logbook}/review-ajax',
            [ReviewLogbookController::class, 'reviewAjax'])
            ->name('logbook.review-ajax');

        // PENILAIAN PKL
        Route::get('/penilaian', [PenilaianPKLController::class, 'index'])
            ->name('penilaian.index');
        Route::get('/penilaian/{mahasiswa}/create', [PenilaianPKLController::class, 'create'])
            ->name('penilaian.create');
        Route::post('/penilaian/{mahasiswa}', [PenilaianPKLController::class, 'store'])
            ->name('penilaian.store');
    });


    // MITRA/PJ
//     use App\Http\Controllers\Mitra\DashboardController as MitraDashboardController;
// use App\Http\Controllers\Mitra\LogbookController as MitraLogbookController;
    
// Route::middleware(['auth', 'first.login', 'role:mitra'])
//     ->prefix('mitra')
//     ->name('mitra.')
//     ->group(function () {

//         Route::get('/dashboard', [MitraDashboardController::class, 'index'])
//             ->name('dashboard');

//         Route::get('/logbook', [MitraLogbookController::class, 'index'])
//             ->name('logbook.index');

//         Route::post('/logbook/{logbook}/catatan', 
//             [MitraLogbookController::class, 'beriCatatan'])
//             ->name('logbook.catatan');
// });

/*
|--------------------------------------------------------------------------
| STAFF TU AREA
|--------------------------------------------------------------------------
*/
// STAFF TU
use App\Http\Controllers\Staff\PengajuanPKLController as StaffPengajuanPKLController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\DokumenPengajuanController;
Route::middleware(['auth', 'first.login', 'role:staff_tu'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {

        Route::get('/dashboard', [StaffDashboardController::class, 'index'])
            ->name('dashboard');
        // Histori pengajuan ditolak → harus DI ATAS {id}
        Route::get('/pengajuan/histori-ditolak', [StaffPengajuanPKLController::class, 'historiDitolak'])
            ->name('pengajuan.histori_ditolak');
        Route::post(
            '/dokumen/{id}/valid',
            [DokumenPengajuanController::class, 'valid']
        )->name('dokumen.valid');

        Route::post(
            '/dokumen/{id}/invalid',
            [DokumenPengajuanController::class, 'invalid']
        )->name('dokumen.invalid');
        // Daftar pengajuan TU
        Route::get('/pengajuan', [StaffPengajuanPKLController::class, 'index'])
            ->name('pengajuan.index');
        // Detail pengajuan → parameter {id}
        Route::get('/pengajuan/{id}', [StaffPengajuanPKLController::class, 'show'])
            ->name('pengajuan.show');
        // Approve / Reject
        Route::post('/pengajuan/{id}/approve', [StaffPengajuanPKLController::class, 'approve'])
            ->name('pengajuan.approve');
        Route::post('/pengajuan/{id}/reject', [StaffPengajuanPKLController::class, 'reject'])
            ->name('pengajuan.reject');
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
| KAPRODI AREA
|--------------------------------------------------------------------------
*/
// KAPRODI
use App\Http\Controllers\Kaprodi\PengajuanPKLController as KaprodiPengajuanPKLController;
use App\Http\Controllers\Kaprodi\MahasiswaController;
use App\Http\Controllers\Kaprodi\NilaiController;
Route::middleware(['auth','role:kaprodi'])
    ->prefix('kaprodi')
    ->name('kaprodi.')
    ->group(function(){
        Route::get('/dashboard',[KaprodiPengajuanPKLController::class,'dashboard'])->name('dashboard');
        Route::get('/mahasiswa', [MahasiswaController::class, 'index'])
            ->name('mahasiswa.index');
        Route::get('/pengajuan',[KaprodiPengajuanPKLController::class,'index'])->name('pengajuan.index');
        Route::get('/nilai', [NilaiController::class, 'index'])
            ->name('nilai.index');
        Route::get('/pengajuan/{id}',[KaprodiPengajuanPKLController::class,'show'])->name('pengajuan.show');
        Route::post('/pengajuan/{id}/approve',[KaprodiPengajuanPKLController::class,'approve'])->name('pengajuan.approve');
        Route::post('/pengajuan/{id}/reject',[KaprodiPengajuanPKLController::class,'reject'])->name('pengajuan.reject');
        Route::get('/histori-ditolak',[KaprodiPengajuanPKLController::class,'historiDitolak'])->name('pengajuan.histori_ditolak');
    });

/*
|--------------------------------------------------------------------------
| DEFAULT AUTH ROUTES (BREEZE)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';