<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DosenController as AdminDosenController;
use App\Http\Controllers\Admin\FakultasController as AdminFakultasController;
use App\Http\Controllers\Admin\InformasiController as AdminInformasiController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\PimpinanController as AdminPimpinanController;
use App\Http\Controllers\Admin\ProdiController as AdminProdiController;
use App\Http\Controllers\Admin\StaffController as AdminStaffController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\FirstLoginController;
use App\Http\Controllers\Auth\SiakadDosenFirstLoginController;
use App\Http\Controllers\Auth\SiakadFirstLoginController;
use App\Http\Controllers\Auth\SiakadStaffFirstLoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\LaporanAkhirController as DosenLaporanAkhirController;
use App\Http\Controllers\Dosen\MahasiswaBimbinganController as DosenMahasiswaBimbinganController;
use App\Http\Controllers\Dosen\NilaiPklController as DosenNilaiPklController;
use App\Http\Controllers\Dosen\ResumePklController as DosenResumePklController;
use App\Http\Controllers\Dosen\ReviewLogbookController as DosenReviewLogbookController;
use App\Http\Controllers\Kaprodi\MahasiswaController as KaprodiMahasiswaController;
use App\Http\Controllers\Kaprodi\PengajuanPklController as KaprodiPengajuanController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\LaporanAkhirController as MahasiswaLaporanAkhirController;
use App\Http\Controllers\Mahasiswa\LogbookController as MahasiswaLogbookController;
use App\Http\Controllers\Mahasiswa\NilaiMitraMahasiswaController as MahasiswaNilaiMitraController;
use App\Http\Controllers\Mahasiswa\NilaiPklController as MahasiswaNilaiPklController;
use App\Http\Controllers\Mahasiswa\PengajuanPklController as MahasiswaPengajuanController;
use App\Http\Controllers\Mahasiswa\StatusAkademikController as MahasiswaStatusAkademikController;
use App\Http\Controllers\Mahasiswa\TugasController as MahasiswaTugasController;
use App\Http\Controllers\Mitra\MitraController as MitraController;
use App\Http\Controllers\Mitra\PenilaianMitraController as MitraPenilaianMitraController;
use App\Http\Controllers\Mitra\ResumeController as MitraResumeController;
use App\Http\Controllers\Mitra\SuratBalasanController as MitraSuratBalasanController;
use App\Http\Controllers\Mitra\TugasMitraController as MitraTugasMitraController;
use App\Http\Controllers\Pimpinan\PimpinanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Staff\ApprovalNilaiController as StaffApprovalNilaiController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\DokumenPengajuanController as StaffDokumenController;
use App\Http\Controllers\Staff\FormulirRemedialController as StaffFormulirRemedialController;
use App\Http\Controllers\Staff\KredensialMitraController as StaffKredensialMitraController;
use App\Http\Controllers\Staff\MitraController as StaffMitraController;
use App\Http\Controllers\Staff\PengajuanPklController as StaffPengajuanController;
use App\Http\Controllers\Staff\ResumeController as StaffResumeController;
use App\Http\Controllers\Staff\StaffSuratController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ROOT & AUTH
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'first.login'])->group(function () {
    Route::get('/siakad/first-login', [SiakadFirstLoginController::class, 'show'])->name('siakad.first-login');
    Route::post('/siakad/first-login', [SiakadFirstLoginController::class, 'store'])->name('siakad.first-login.store');
    Route::get('/siakad/dosen/first-login', [SiakadDosenFirstLoginController::class, 'show'])->name('siakad.dosen.first-login');
    Route::post('/siakad/dosen/first-login', [SiakadDosenFirstLoginController::class, 'store'])->name('siakad.dosen.first-login.store');
    Route::get('/siakad/staff/first-login', [SiakadStaffFirstLoginController::class, 'show'])->name('siakad.staff.first-login');
    Route::post('/siakad/staff/first-login', [SiakadStaffFirstLoginController::class, 'store'])->name('siakad.staff.first-login.store');
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

        Route::get('/status-akademik', [MahasiswaStatusAkademikController::class, 'index'])
            ->name('status-akademik.index');

        Route::middleware(['bebas.nilai.de', 'validasi.krs.siakad'])->group(function () {
            Route::get('/pengajuan-pkl', [MahasiswaPengajuanController::class, 'create'])->name('pengajuan.create');
            Route::post('/pengajuan-pkl', [MahasiswaPengajuanController::class, 'store'])->name('pengajuan.store');
            Route::post('/pengajuan-pkl/dokumen/{id}/upload-ulang', [MahasiswaPengajuanController::class, 'uploadUlangDokumen'])->name('pengajuan.dokumen.upload-ulang');
        });

        Route::get('/status-pengajuan', [MahasiswaPengajuanController::class, 'status'])->name('pengajuan.status');
        Route::post('/cek-kemiripan-tempat', [MahasiswaPengajuanController::class, 'cekKemiripanAjax'])->name('pengajuan.cek-kemiripan');

        // Dokumentasi Google Drive
        Route::get('/logbook/dokumentasi/edit', [MahasiswaLogbookController::class, 'editDokumentasi'])
            ->name('logbook.dokumentasi.edit');

        Route::put('/logbook/dokumentasi', [MahasiswaLogbookController::class, 'updateDokumentasi'])
            ->name('logbook.dokumentasi.update');

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

        // Nilai
        Route::get('/nilai-pkl', [MahasiswaNilaiPklController::class, 'index'])->name('nilai.index');

        // Tugas
        Route::get('/tugas', [MahasiswaTugasController::class, 'index'])->name('tugas');
        Route::get('/tugas/{id}', [MahasiswaTugasController::class, 'show'])->name('tugas.show');
        Route::post('/tugas/{id}/submit', [MahasiswaTugasController::class, 'submit'])->name('tugas.submit');

        // Penilaian Mitra
        Route::get('/penilaian-mitra', [MahasiswaNilaiMitraController::class, 'index'])->name('penilaianMitra.index');
    });

/*
|--------------------------------------------------------------------------
| AUTHENTICATED (GLOBAL)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // First login flow
    Route::get('/first-login', [FirstLoginController::class, 'show'])
        ->middleware('first.login')
        ->name('password.first');
    Route::post('/first-login', [FirstLoginController::class, 'update'])
        ->middleware('first.login')
        ->name('password.first.update');

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
| DOSEN AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'first.login', 'role:dosen'])
    ->prefix('dosen')
    ->name('dosen.')
    ->group(function () {

        Route::get('/dashboard', [DosenDashboardController::class, 'index'])
            ->name('dashboard');

        // Mahasiswa Bimbingan
        Route::get('/mahasiswa-bimbingan', [DosenMahasiswaBimbinganController::class, 'index'])
            ->name('mahasiswa.bimbingan');

        // Logbook
        Route::get('/logbook', [DosenReviewLogbookController::class, 'index'])
            ->name('logbook.index');

        Route::post('/logbook/{logbook}/review-ajax', [DosenReviewLogbookController::class, 'reviewAjax'])
            ->name('logbook.review-ajax');

        Route::put('/logbook/{logbook}/review', [DosenReviewLogbookController::class, 'review'])
            ->name('logbook.review');

        Route::post('/logbook/bulk-approve', [DosenReviewLogbookController::class, 'bulkApprove'])
            ->name('logbook.bulk-approve');

        Route::get('/logbook/{pkl}', [DosenReviewLogbookController::class, 'detail'])
            ->name('logbook.detail');

        // Nilai
        Route::get('/nilai', [DosenNilaiPklController::class, 'index'])
            ->name('nilai.index');
        Route::get('/nilai/{pkl}/create', [DosenNilaiPklController::class, 'create'])
            ->name('nilai.create');
        Route::post('/nilai/{pkl}', [DosenNilaiPklController::class, 'store'])
            ->name('nilai.store');

        // Route '/nilai/daftar' redirects to resume page
        Route::get('/nilai/daftar', function () {
            return redirect()->route('dosen.resume.index');
        })->name('nilai.daftar');

        // Laporan Akhir
        Route::get('/laporan', [DosenLaporanAkhirController::class, 'index'])
            ->name('laporan.index');
        Route::get('/laporan/{pkl}', [DosenLaporanAkhirController::class, 'show'])
            ->name('laporan.show');
        Route::post('/laporan/{pkl}/approve', [DosenLaporanAkhirController::class, 'approve'])
            ->name('laporan.approve');
        Route::post('/laporan/{pkl}/reject', [DosenLaporanAkhirController::class, 'reject'])
            ->name('laporan.reject');

        // 1. Halaman utama resume → daftar ANGKATAN
        Route::get('/resume-pkl', [DosenResumePklController::class, 'index'])
            ->name('resume.index');

        // 2. Daftar mahasiswa berdasarkan ANGKATAN
        Route::get('/resume-pkl/angkatan/{tahun}', [DosenResumePklController::class, 'mahasiswa'])
            ->name('resume.mahasiswa');

        // 3. Detail resume satu mahasiswa
        Route::get('/resume-pkl/{pkl}', [DosenResumePklController::class, 'show'])
            ->name('resume.show');

        // 4. Detail logbook dari resume
        Route::get('/resume-pkl/{pkl}/logbook', [DosenResumePklController::class, 'logbook'])
            ->name('resume.logbook');
    });

/*
|--------------------------------------------------------------------------
| KAPRODI AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'kaprodi'])
    ->prefix('kaprodi')
    ->name('kaprodi.')
    ->group(function () {
        Route::get('/mahasiswa', [KaprodiMahasiswaController::class, 'index'])->name('mahasiswa.index');
        Route::get('/pengajuan', [KaprodiPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/{id}', [KaprodiPengajuanController::class, 'show'])->name('pengajuan.show');
        Route::post('/pengajuan/{id}/approve', [KaprodiPengajuanController::class, 'approve'])->name('pengajuan.approve');
        Route::post('/pengajuan/{id}/reject', [KaprodiPengajuanController::class, 'reject'])->name('pengajuan.reject');
        Route::get('/histori', [KaprodiPengajuanController::class, 'histori'])->name('pengajuan.histori');
        Route::get('/mahasiswa/belum-pkl', [KaprodiMahasiswaController::class, 'daftarAngkatan'])->name('mahasiswa.angkatan');
        Route::get('/mahasiswa/belum-pkl/{angkatan}', [KaprodiMahasiswaController::class, 'belumMengajukan'])->name('mahasiswa.belum');
        Route::get('/mahasiswa/{mahasiswa}', [KaprodiMahasiswaController::class, 'detail'])->name('mahasiswa.detail');
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
        Route::get('/pengajuan/histori', [StaffPengajuanController::class, 'histori'])
            ->name('pengajuan.histori');
        Route::post('/dokumen/{id}/valid', [StaffDokumenController::class, 'valid'])->name('dokumen.valid');
        Route::post('/dokumen/{id}/invalid', [StaffDokumenController::class, 'invalid'])->name('dokumen.invalid');
        Route::get('/pengajuan', [StaffPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/{id}', [StaffPengajuanController::class, 'show'])->name('pengajuan.show');
        Route::post('/pengajuan/{id}/approve', [StaffPengajuanController::class, 'approve'])->name('pengajuan.approve');
        Route::post('/pengajuan/{id}/reject', [StaffPengajuanController::class, 'reject'])->name('pengajuan.reject');
        Route::get('/mitra/{id}/angkatan/{tahun}', [StaffMitraController::class, 'mahasiswaByAngkatan'])->name('mitra.mahasiswa');
        Route::get('/mitra', [StaffMitraController::class, 'index'])->name('mitra.index');

        // Manajemen Mitra (list mitra + kelola kredensial)
        Route::get('/manajemen-mitra', [StaffMitraController::class, 'index'])->name('manajemen-mitra.index');
        Route::get('/manajemen-mitra/{id}', [StaffMitraController::class, 'show'])->name('manajemen-mitra.show');
        Route::prefix('mitra/{mitra}/kredensial')
            ->name('mitra.kredensial.')
            ->group(function () {
                Route::get('/', [StaffKredensialMitraController::class, 'show'])->name('show');
                Route::post('/resend', [StaffKredensialMitraController::class, 'resend'])->name('resend');
                Route::post('/update-email', [StaffKredensialMitraController::class, 'updateEmail'])->name('update-email');
                Route::post('/reset-password', [StaffKredensialMitraController::class, 'resetPassword'])->name('reset-password');
            });

        Route::get('/surat-pengantar', [StaffSuratController::class, 'index'])->name('surat.index');
        Route::get('/surat-pengantar/{id}/cetak', [StaffSuratController::class, 'cetak'])->name('surat.cetak');
        Route::post('/surat-pengantar/{id}/validasi', [StaffSuratController::class, 'validasi'])->name('surat.validasi');
        Route::get('/surat-pengantar/{id}/preview', [StaffSuratController::class, 'preview'])->name('surat.preview');
        Route::post('/surat-pengantar/bulk-print', [StaffSuratController::class, 'bulkPrint'])->name('surat.bulk');
        Route::post('/surat-pengantar/bulk-preview', [StaffSuratController::class, 'bulkPreview'])->name('surat.bulk-preview');
        Route::post('/surat-pengantar/bulk-validasi', [StaffSuratController::class, 'bulkValidasi'])->name('surat.bulk-validasi');
        Route::post('/surat-pengantar/{id}/tandai-dicetak', [StaffSuratController::class, 'tandaiDicetak'])->name('surat.tandai-dicetak');
        Route::post('/surat-pengantar/bulk-tandai-dicetak', [StaffSuratController::class, 'bulkTandaiDicetak'])->name('surat.bulk-tandai-dicetak');

        // ================= FORMULIR REMEDIAL =================
        Route::get('/formulir-remedial', [StaffFormulirRemedialController::class, 'index'])->name('formulir-remedial.index');
        Route::post('/formulir-remedial', [StaffFormulirRemedialController::class, 'store'])->name('formulir-remedial.store');
        Route::put('/formulir-remedial/{formulirRemedial}', [StaffFormulirRemedialController::class, 'update'])->name('formulir-remedial.update');
        Route::delete('/formulir-remedial/{formulirRemedial}', [StaffFormulirRemedialController::class, 'destroy'])->name('formulir-remedial.destroy');

        // ================= APPROVAL NILAI =================
        Route::get('/nilai', [StaffApprovalNilaiController::class, 'index'])->name('nilai.index');
        Route::post('/nilai/{pkl}/approve', [StaffApprovalNilaiController::class, 'approve'])->name('nilai.approve');
        Route::post('/nilai/bulk-approve', [StaffApprovalNilaiController::class, 'bulkApprove'])->name('nilai.bulk-approve');
        Route::get('/resume', [StaffResumeController::class, 'index'])->name('resume.index');
        Route::get('/resume/angkatan/{angkatan}', [StaffResumeController::class, 'showByAngkatan'])->where('angkatan', '[0-9]{4}')->name('resume.angkatan');
    });

/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'first.login', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/', [AdminMahasiswaController::class, 'indexProdi'])->name('index');
        Route::get('/prodi/{prodiId}/angkatan', [AdminMahasiswaController::class, 'angkatanByProdi'])
            ->name('angkatan')
            ->whereNumber('prodiId');

        Route::get('/prodi/{prodiId}/angkatan/{angkatan}', [AdminMahasiswaController::class, 'byAngkatan'])
            ->name('by-angkatan')
            ->whereNumber('prodiId')
            ->where('angkatan', '[0-9]{4}');

        Route::get('/create', [AdminMahasiswaController::class, 'create'])->name('create');
        Route::post('/', [AdminMahasiswaController::class, 'store'])->name('store');
        Route::post('/import', [AdminMahasiswaController::class, 'import'])->name('import');
        Route::post('/sync', [AdminMahasiswaController::class, 'syncSiakad'])->name('sync');

        // ---- ROUTE WILDCARD {mahasiswa} (paling akhir) ----
        Route::get('/{mahasiswa}', [AdminMahasiswaController::class, 'show'])->name('show');
        Route::get('/{mahasiswa}/edit', [AdminMahasiswaController::class, 'edit'])->name('edit');
        Route::put('/{mahasiswa}', [AdminMahasiswaController::class, 'update'])->name('update');
        Route::delete('/{mahasiswa}', [AdminMahasiswaController::class, 'destroy'])->name('destroy');
        Route::post('/{mahasiswa}/reset-password', [AdminMahasiswaController::class, 'resetPassword'])->name('reset-password');
        Route::post('/{id}/activate', [AdminMahasiswaController::class, 'activate'])->name('activate');
    });

    Route::resource('dosen', AdminDosenController::class)->except(['show']);
    Route::post('dosen/{dosen}/reset-password', [AdminDosenController::class, 'resetPassword'])->name('dosen.reset-password');
    Route::post('dosen/{id}/activate', [AdminDosenController::class, 'activate'])->name('dosen.activate');
    Route::post('dosen/sync', [AdminDosenController::class, 'syncSiakad'])->name('dosen.sync');

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', AdminUserController::class)->only(['create', 'store']);

    Route::resource('prodi', AdminProdiController::class)->except(['show']);
    Route::post('prodi/import', [AdminProdiController::class, 'import'])->name('prodi.import');
    Route::post('prodi/sync', [AdminProdiController::class, 'syncSiakad'])->name('prodi.sync');

    Route::resource('pimpinan', AdminPimpinanController::class)->except(['show']);
    Route::post('pimpinan/{id}/reset', [AdminPimpinanController::class, 'reset'])->name('pimpinan.reset');
    Route::post('pimpinan/{id}/activate', [AdminPimpinanController::class, 'activate'])->name('pimpinan.activate');

    Route::resource('staff', AdminStaffController::class)->except(['show']);
    Route::post('staff/{id}/reset', [AdminStaffController::class, 'reset'])->name('staff.reset');
    Route::post('staff/import', [AdminStaffController::class, 'import'])->name('staff.import');
    Route::post('staff/{id}/activate', [AdminStaffController::class, 'activate'])->name('staff.activate');

    Route::delete('fakultas/bulk-delete', [AdminFakultasController::class, 'bulkDelete'])
        ->name('fakultas.bulkDelete');
    Route::resource('fakultas', AdminFakultasController::class)->except(['show'])->parameters([
        'fakultas' => 'fakultas',
    ]);
    Route::post('fakultas/import', [AdminFakultasController::class, 'import'])->name('fakultas.import');
    Route::post('fakultas/sync', [AdminFakultasController::class, 'syncSiakad'])->name('fakultas.sync');

    Route::resource('informasi', AdminInformasiController::class)->except(['show']);
});
/*
|--------------------------------------------------------------------------
| PIMPINAN AREA
|--------------------------------------------------------------------------
*/
Route::prefix('pimpinan')->middleware(['auth', 'first.login', 'role:pimpinan'])->group(function () {
    Route::get('/', [PimpinanController::class, 'index']);
    Route::get('/fakultas/{id}', [PimpinanController::class, 'prodi'])->name('pimpinan.prodi');
    Route::get('/prodi/{prodi_id}/angkatan/{angkatan}', [PimpinanController::class, 'mahasiswa'])->name('pimpinan.mahasiswa');
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

        Route::get('/dashboard', [MitraController::class, 'dashboard'])->name('dashboard');
        Route::get('/mahasiswa', [MitraController::class, 'mahasiswa'])->name('mahasiswa');

        // Daftar mahasiswa yang sudah mengisi logbook (index untuk mitra)
        Route::get('/logbook', [MitraController::class, 'logbookList'])->name('logbook.index');
        Route::get('/logbook/{pkl}', [MitraController::class, 'logbook'])->name('logbook');

        // Tugas
        Route::get('/tugas', [MitraTugasMitraController::class, 'index'])->name('tugas.index');
        Route::get('/tugas/create', [MitraTugasMitraController::class, 'create'])->name('tugas.create');
        Route::post('/tugas', [MitraTugasMitraController::class, 'store'])->name('tugas.store');
        Route::get('/tugas/{tugas}', [MitraTugasMitraController::class, 'show'])->name('tugas.show');
        Route::get('/tugas/{tugas}/edit', [MitraTugasMitraController::class, 'edit'])->name('tugas.edit');
        Route::put('/tugas/{tugas}', [MitraTugasMitraController::class, 'update'])->name('tugas.update');
        Route::delete('/tugas/{tugas}', [MitraTugasMitraController::class, 'destroy'])->name('tugas.destroy');
        Route::post('/tugas/verifikasi/{id}', [MitraTugasMitraController::class, 'verifikasi'])->name('tugas.verifikasi');

        // Penilaian
        Route::get('/penilaian', [MitraPenilaianMitraController::class, 'index'])->name('penilaian');
        Route::get('/penilaian/{id}', [MitraPenilaianMitraController::class, 'form'])->name('penilaian.form');
        Route::post('/penilaian/{id}', [MitraPenilaianMitraController::class, 'store'])->name('penilaian.store');

        // Surat Balasan
        Route::get('/surat-balasan', [MitraSuratBalasanController::class, 'index'])->name('surat-balasan.index');
        Route::post('/surat-balasan/{pkl}', [MitraSuratBalasanController::class, 'store'])->name('surat-balasan.store');

        // Resume PKL
        Route::get('/resume', [MitraResumeController::class, 'index'])->name('resume.index');
        Route::get('/resume/angkatan/{tahun}', [MitraResumeController::class, 'mahasiswa'])->name('resume.mahasiswa');
        Route::get('/resume/{pkl}/logbook', [MitraResumeController::class, 'logbook'])->name('resume.logbook');
        Route::get('/resume/{pkl}', [MitraResumeController::class, 'show'])->name('resume.show');
    });

/*
|--------------------------------------------------------------------------
| DEFAULT AUTH ROUTES (BREEZE)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
