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
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\ProfileController as MahasiswaProfileController;
use App\Http\Controllers\Mahasiswa\PengajuanController as MahasiswaPengajuanController;
use App\Http\Controllers\Mahasiswa\TempatMagangController as MahasiswaTempatMagangController;
use App\Http\Controllers\Mahasiswa\StatusMagangController as MahasiswaStatusMagangController;
use App\Http\Controllers\Mahasiswa\LaporanController as MahasiswaLaporanController;
use App\Http\Controllers\Mahasiswa\LogbookController as MahasiswaLogbookController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
    Route::resource('mahasiswa', MahasiswaController::class)->except(['show']);
    Route::resource('dosen', DosenController::class)->except(['show']);
    Route::resource('perusahaan', InstansiController::class)->except(['show']);
    Route::resource('pengajuan', MagangController::class)->except(['show']);
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
    Route::view('/profil', 'shared.module', ['pageTitle' => 'Profil', 'pageDescription' => 'Kelola informasi profil dosen.'])->name('profil');
    Route::view('/mahasiswa-bimbingan', 'shared.module', ['pageTitle' => 'Mahasiswa Bimbingan', 'pageDescription' => 'Lihat mahasiswa yang berada dalam bimbingan Anda.'])->name('mahasiswa-bimbingan');
    Route::view('/pengajuan', 'shared.module', ['pageTitle' => 'Pengajuan Magang', 'pageDescription' => 'Tinjau pengajuan magang mahasiswa bimbingan.'])->name('pengajuan');
    Route::view('/monitoring', 'shared.module', ['pageTitle' => 'Monitoring Magang', 'pageDescription' => 'Pantau perkembangan mahasiswa selama magang.'])->name('monitoring');
    Route::get('/logbook', [DosenLogbookController::class, 'index'])->name('logbook');
    Route::patch('/logbook/{logbook}/validasi', [DosenLogbookController::class, 'validateLogbook'])->name('logbook.validate');
    Route::view('/penilaian', 'shared.module', ['pageTitle' => 'Penilaian', 'pageDescription' => 'Kelola penilaian mahasiswa magang.'])->name('penilaian');
    Route::view('/laporan', 'shared.module', ['pageTitle' => 'Laporan', 'pageDescription' => 'Tinjau laporan magang mahasiswa bimbingan.'])->name('laporan');
});
