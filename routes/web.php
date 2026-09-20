<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\InstansiController;
use App\Http\Controllers\Admin\MagangController;
use App\Http\Controllers\Admin\DataMagangController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\LogbookController as DosenLogbookController;
use App\Http\Controllers\Dosen\ProfileController as DosenProfileController;
use App\Http\Controllers\Dosen\MahasiswaBimbinganController as DosenMahasiswaBimbinganController;
use App\Http\Controllers\Dosen\PengajuanController as DosenPengajuanController;
use App\Http\Controllers\Dosen\MonitoringController as DosenMonitoringController;
use App\Http\Controllers\Dosen\LaporanController as DosenLaporanController;
use App\Http\Controllers\Dosen\PenilaianController as DosenPenilaianController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\ProfileController as MahasiswaProfileController;
use App\Http\Controllers\Mahasiswa\PengajuanController as MahasiswaPengajuanController;
use App\Http\Controllers\Mahasiswa\TempatMagangController as MahasiswaTempatMagangController;
use App\Http\Controllers\Mahasiswa\StatusMagangController as MahasiswaStatusMagangController;
use App\Http\Controllers\Mahasiswa\LaporanController as MahasiswaLaporanController;
use App\Http\Controllers\Mahasiswa\LogbookController as MahasiswaLogbookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    return match (auth()->user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
        'dosen' => redirect()->route('dosen.dashboard'),
        default => redirect()->route('login'),
    };
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
    Route::resource('mahasiswa', MahasiswaController::class)->except(['show']);
    Route::resource('dosen', DosenController::class)->except(['show']);
    Route::resource('perusahaan', InstansiController::class)->except(['show']);
    Route::resource('pengajuan', MagangController::class)->except(['show']);
    Route::patch('/pengajuan/{pengajuan}/review', [MagangController::class, 'review'])->name('pengajuan.review');
    Route::get('/magang', [DataMagangController::class, 'index'])->name('magang.index');
    Route::get('/magang/{magang}', [DataMagangController::class, 'show'])->name('magang.show');
    Route::patch('/magang/{magang}/status', [DataMagangController::class, 'updateStatus'])->name('magang.status');
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/{laporan}/download', [LaporanController::class, 'download'])->name('laporan.download');
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::patch('/pengaturan/profile', [PengaturanController::class, 'updateProfile'])->name('pengaturan.profile');
    Route::patch('/pengaturan/password', [PengaturanController::class, 'updatePassword'])->name('pengaturan.password');
});

Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', MahasiswaDashboardController::class)->name('dashboard');
    Route::get('/profil', [MahasiswaProfileController::class, 'index'])->name('profil');
    Route::patch('/profil', [MahasiswaProfileController::class, 'update'])->name('profil.update');
    Route::patch('/profil/password', [MahasiswaProfileController::class, 'updatePassword'])->name('profil.password');
    Route::get('/pengajuan', [MahasiswaPengajuanController::class, 'index'])->name('pengajuan');
    Route::get('/pengajuan/create', [MahasiswaPengajuanController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan', [MahasiswaPengajuanController::class, 'store'])->name('pengajuan.store');
    Route::get('/tempat-magang', [MahasiswaTempatMagangController::class, 'index'])->name('tempat-magang');
    Route::get('/status-magang', [MahasiswaStatusMagangController::class, 'index'])->name('status-magang');
    Route::get('/logbook', [MahasiswaLogbookController::class, 'index'])->name('logbook');
    Route::post('/logbook', [MahasiswaLogbookController::class, 'store'])->name('logbook.store');
    Route::get('/logbook/{logbook}/bukti', [MahasiswaLogbookController::class, 'bukti'])->name('logbook.bukti');
    Route::get('/logbook/{logbook}/edit', [MahasiswaLogbookController::class, 'edit'])->name('logbook.edit');
    Route::put('/logbook/{logbook}', [MahasiswaLogbookController::class, 'update'])->name('logbook.update');
    Route::delete('/logbook/{logbook}', [MahasiswaLogbookController::class, 'destroy'])->name('logbook.destroy');
    Route::get('/laporan', [MahasiswaLaporanController::class, 'index'])->name('laporan');
    Route::post('/laporan', [MahasiswaLaporanController::class, 'store'])->name('laporan.store');
    Route::get('/laporan/{laporan}/download', [MahasiswaLaporanController::class, 'download'])->name('laporan.download');
    Route::delete('/laporan/{laporan}', [MahasiswaLaporanController::class, 'destroy'])->name('laporan.destroy');
});

Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', DosenDashboardController::class)->name('dashboard');
    Route::get('/profil', [DosenProfileController::class, 'index'])->name('profil');
    Route::patch('/profil', [DosenProfileController::class, 'update'])->name('profil.update');
    Route::patch('/profil/password', [DosenProfileController::class, 'updatePassword'])->name('profil.password');
    Route::get('/mahasiswa-bimbingan', [DosenMahasiswaBimbinganController::class, 'index'])->name('mahasiswa-bimbingan');
    Route::get('/mahasiswa-bimbingan/{magang}', [DosenMahasiswaBimbinganController::class, 'show'])->name('mahasiswa-bimbingan.show');
    Route::get('/pengajuan', [DosenPengajuanController::class, 'index'])->name('pengajuan');
    Route::get('/monitoring', [DosenMonitoringController::class, 'index'])->name('monitoring');
    Route::get('/logbook', [DosenLogbookController::class, 'index'])->name('logbook');
    Route::get('/logbook/{logbook}/bukti', [DosenLogbookController::class, 'bukti'])->name('logbook.bukti');
    Route::patch('/logbook/{logbook}/validasi', [DosenLogbookController::class, 'validateLogbook'])->name('logbook.validate');
    Route::get('/penilaian', [DosenPenilaianController::class, 'index'])->name('penilaian');
    Route::get('/penilaian/{magang}/edit', [DosenPenilaianController::class, 'edit'])->name('penilaian.edit');
    Route::post('/penilaian/{magang}', [DosenPenilaianController::class, 'store'])->name('penilaian.store');
    Route::get('/laporan', [DosenLaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/{laporan}/download', [DosenLaporanController::class, 'download'])->name('laporan.download');
    Route::patch('/laporan/{laporan}/validasi', [DosenLaporanController::class, 'validateReport'])->name('laporan.validate');
});
