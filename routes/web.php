<?php


use App\Http\Controllers\Auth\FirstLoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Monitoring\SertifikatController;

use App\Http\Controllers\ProfileController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\LaporanAkhirController as MahasiswaLaporanAkhirController;
use App\Http\Controllers\Mahasiswa\LogbookController as MahasiswaLogbookController;
use App\Http\Controllers\Mahasiswa\NilaiPklController as MahasiswaNilaiPklController;
use App\Http\Controllers\Mahasiswa\PengajuanPklController as MahasiswaPengajuanController;
use App\Http\Controllers\Mahasiswa\TugasController as TugasMahasiswaController;
use App\Http\Controllers\Mahasiswa\PengajuanSertifikatController;
Route::middleware(['auth', 'first.login', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->name('mahasiswa.')
    ->group(function () {
        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
        // formulir
        Route::get('/formulir', 
            [\App\Http\Controllers\Mahasiswa\FormulirController::class,'index']
        )->name('formulir.index');

        Route::get('/formulir/download/{id}', 
            [\App\Http\Controllers\Mahasiswa\FormulirController::class,'download']
        )->name('formulir.download');
        // Pengajuan PKL
        Route::get('/pengajuan-pkl', [MahasiswaPengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan-pkl', [MahasiswaPengajuanController::class, 'store'])->name('pengajuan.store');
        Route::get('/status-pengajuan', [MahasiswaPengajuanController::class, 'status'])->name('pengajuan.status');
        Route::post('/pengajuan-pkl/dokumen/{id}/upload-ulang', [MahasiswaPengajuanController::class, 'uploadUlangDokumen'])
            ->name('pengajuan.dokumen.upload-ulang');
        Route::get('/surat-pengantar/{id}/download',[MahasiswaPengajuanController::class, 'downloadSuratPengantar'])->name('surat-pengantar.download');
        
        Route::get('/sertifikat', [PengajuanSertifikatController::class,'index'])
        ->name('sertifikat.index');

        Route::post('/sertifikat', [PengajuanSertifikatController::class,'store'])
        ->name('sertifikat.store');

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
        Route::post('/cek-kemiripan-tempat', [MahasiswaPengajuanController::class, 'cekKemiripanAjax'] )->name('pengajuan.cek-kemiripan');
        // nilai
        Route::get('/nilai-pkl', [MahasiswaNilaiPklController::class, 'index'])->name('nilai.index');
        Route::get('/sertifikat/{pkl}', function ($pklId) {return view('mahasiswa.nilai.sertifikat-dummy'); })->name('sertifikat.dummy');
        // tugas
        Route::get('/tugas', [TugasMahasiswaController::class, 'index'])
        ->name('tugas');
        Route::get('/tugas/{id}', [TugasMahasiswaController::class,'show'])
        ->name('tugas.show');
        Route::post('/tugas/{id}/submit', [TugasMahasiswaController::class,'submit'])
        ->name('tugas.submit');

    });

/*
|--------------------------------------------------------------------------
| DOSEN AREA
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\LaporanAkhirController as DosenLaporanAkhirController;
use App\Http\Controllers\Dosen\MahasiswaBimbinganController;
use App\Http\Controllers\Dosen\NilaiPklController as DosenNilaiPklController;
use App\Http\Controllers\Dosen\ReviewLogbookController;
Route::middleware(['auth', 'first.login', 'role:dosen'])
    ->prefix('dosen')
    ->name('dosen.')
    ->group(function () {
        // Sertifikat Mahasiswa
        Route::get('/sertifikat', [SertifikatController::class, 'indexDosen'])
            ->name('sertifikat.index');

        Route::get('/dashboard', [DosenDashboardController::class, 'index'])
        ->name('dashboard');

        // Mahasiswa Bimbingan
        Route::get('/mahasiswa-bimbingan',[MahasiswaBimbinganController::class, 'index']
        )->name('mahasiswa.bimbingan');

        // Logbook
        Route::get('/logbook',[ReviewLogbookController::class, 'index']
        )->name('logbook.index');
        Route::put('/logbook/{logbook}/review',[ReviewLogbookController::class, 'review']
        )->name('logbook.review');
        Route::post('/logbook/{logbook}/review-ajax',[ReviewLogbookController::class, 'reviewAjax']
        )->name('logbook.review-ajax');
        // bulk approve
        Route::post('/logbook/bulk-approve', [ReviewLogbookController::class, 'bulkApprove']
        )->name('logbook.bulk-approve');
        // Nilai
        Route::get('/nilai',[DosenNilaiPklController::class, 'index']
        )->name('nilai.index');
        Route::get('/nilai/{pkl}/create',[DosenNilaiPklController::class, 'create']
        )->name('nilai.create');
        Route::post('/nilai/{pkl}',[DosenNilaiPklController::class, 'store']
        )->name('nilai.store');
        Route::get('/nilai/daftar',[DosenNilaiPklController::class, 'daftar']
        )->name('nilai.daftar');

        // Laporan Akhir
        Route::get('/laporan',[DosenLaporanAkhirController::class, 'index']
        )->name('laporan.index');
        Route::get('/laporan/{pkl}',[DosenLaporanAkhirController::class, 'show']
        )->name('laporan.show');
        Route::post('/laporan/{pkl}/approve',[DosenLaporanAkhirController::class, 'approve']
        )->name('laporan.approve');
        Route::post('/laporan/{pkl}/reject',[DosenLaporanAkhirController::class, 'reject']
        )->name('laporan.reject');

        
    });

/*
|--------------------------------------------------------------------------
| KAPRODI AREA
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Kaprodi\MahasiswaController as KaprodiMahasiswaController;
use App\Http\Controllers\Kaprodi\PengajuanPklController as KaprodiPengajuanController;
Route::middleware(['auth', 'kaprodi'])
    ->prefix('kaprodi')
    ->name('kaprodi.')
    ->group(function () {
        // Sertifikat Mahasiswa
         Route::get('/sertifikat', [SertifikatController::class, 'indexKaprodi'])
            ->name('sertifikat.index');
    Route::get('/mahasiswa', [KaprodiMahasiswaController::class, 'index'])->name('mahasiswa.index');
    Route::get('/pengajuan', [KaprodiPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/{id}', [KaprodiPengajuanController::class, 'show'])->name('pengajuan.show');
        Route::post('/pengajuan/{id}/approve', [KaprodiPengajuanController::class, 'approve'])->name('pengajuan.approve');
        Route::post('/pengajuan/{id}/reject', [KaprodiPengajuanController::class, 'reject'])->name('pengajuan.reject');
        Route::get('/histori',[KaprodiPengajuanController::class, 'histori'])->name('pengajuan.histori');
       Route::get('/mahasiswa/belum',[KaprodiMahasiswaController::class, 'belumMengajukan'])->name('mahasiswa.belum');
    });
/*
|--------------------------------------------------------------------------
| STAFF TU AREA
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\DokumenPengajuanController as StaffDokumenController;
use App\Http\Controllers\Staff\PengajuanPklController as StaffPengajuanController;
use App\Http\Controllers\Staff\MitraController as StaffMitraController;
Route::middleware(['auth', 'first.login', 'role:staff_tu'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {
        Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
        Route::get('/pengajuan/histori', [StaffPengajuanController::class, 'histori']
        )->name('pengajuan.histori');

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

        Route::get('/manajemen-mitra', [StaffMitraController::class, 'index']
        )->name('manajemen-mitra.index');
        Route::get('/manajemen-mitra/{id}', [StaffMitraController::class, 'show']
        )->name('manajemen-mitra.show');
        Route::post('/mitra/{id}/regenerate', [StaffMitraController::class, 'regenerate'])->name('mitra.regenerate');
    });

/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\FakultasController;
use App\Http\Controllers\Admin\FormulirController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\ProdiController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

Route::middleware(['auth', 'first.login', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard',[AdminDashboardController::class,'index'])
            ->name('dashboard');

        Route::resource('users', UserController::class)->only(['create', 'store']);

        Route::resource('formulir', FormulirController::class);

        Route::resource('prodi', ProdiController::class);
        Route::post('prodi/import', [ProdiController::class, 'import'])->name('prodi.import');

        Route::resource('mahasiswa', MahasiswaController::class);
        Route::post('mahasiswa/import', [MahasiswaController::class, 'import'])->name('mahasiswa.import');
        Route::post('mahasiswa/{mahasiswa}/reset-password',[MahasiswaController::class, 'resetPassword'])->name('mahasiswa.reset-password');

        Route::resource('dosen', DosenController::class);
        Route::post('dosen/{dosen}/reset-password',[DosenController::class,'resetPassword'])->name('dosen.reset-password');
        Route::post('dosen/import',[DosenController::class,'import'])->name('dosen.import');

        Route::resource('staff', StaffController::class);
        Route::post('staff/{id}/reset', [StaffController::class,'reset'])->name('staff.reset');
        Route::post('staff/import',[StaffController::class, 'import'])->name('staff.import');

        Route::delete('fakultas/bulk-delete',[ FakultasController::class, 'bulkDelete'])
        ->name('fakultas.bulkDelete');
        Route::resource('fakultas', FakultasController::class)->parameters([
        'fakultas' => 'fakultas']);

        Route::post('fakultas/import',[FakultasController::class, 'import']
        )->name('fakultas.import');
    });

use App\Http\Controllers\Pimpinan\PimpinanController;

Route::prefix('pimpinan')->middleware('auth')->group(function () {

    Route::get('/', [PimpinanController::class, 'index']);

    Route::get('/fakultas/{id}', [PimpinanController::class, 'prodi'])
        ->name('pimpinan.prodi');

    Route::get('/prodi/{prodi_id}/angkatan/{angkatan}', [PimpinanController::class, 'mahasiswa'])
        ->name('pimpinan.mahasiswa');
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
        
        Route::get('/sertifikat', [App\Http\Controllers\Mitra\PengajuanSertifikatController::class,'index'])
        ->name('sertifikat.index');

        Route::post('/sertifikat/upload/{id}', [App\Http\Controllers\Mitra\PengajuanSertifikatController::class,'upload'])
        ->name('sertifikat.upload');
        Route::get('/tugas', [App\Http\Controllers\Mitra\TugasMitraController::class, 'index'])
            ->name('tugas.index');
        Route::get('/tugas/create', [App\Http\Controllers\Mitra\TugasMitraController::class, 'create'])
            ->name('tugas.create');
        Route::post('/tugas', [App\Http\Controllers\Mitra\TugasMitraController::class, 'store'])
            ->name('tugas.store');
        Route::get('/tugas/{tugas}', [App\Http\Controllers\Mitra\TugasMitraController::class, 'show'])
            ->name('tugas.show');
        Route::get('/tugas/{tugas}/edit', [App\Http\Controllers\Mitra\TugasMitraController::class, 'edit'])->name('tugas.edit');
        Route::put('/tugas/{tugas}', [App\Http\Controllers\Mitra\TugasMitraController::class, 'update'])->name('tugas.update');
        Route::delete('/tugas/{tugas}', [App\Http\Controllers\Mitra\TugasMitraController::class, 'destroy'])->name('tugas.destroy');
        Route::post('/tugas/verifikasi/{id}',[App\Http\Controllers\Mitra\TugasMitraController::class,'verifikasi'])->name('tugas.verifikasi');
    });


/*
|--------------------------------------------------------------------------
| DEFAULT AUTH ROUTES (BREEZE)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';