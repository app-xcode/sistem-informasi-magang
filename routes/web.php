<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\InstansiController;
use App\Http\Controllers\Admin\MagangController;
use App\Http\Controllers\Admin\DataMagangController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\LogbookController as DosenLogbookController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
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
    Route::view('/monitoring', 'shared.module', ['pageTitle' => 'Monitoring Magang', 'pageDescription' => 'Pantau aktivitas dan status magang mahasiswa.'])->name('monitoring.index');
    Route::view('/laporan', 'shared.module', ['pageTitle' => 'Laporan', 'pageDescription' => 'Lihat dan kelola laporan sistem magang.'])->name('laporan.index');
    Route::view('/pengaturan', 'shared.module', ['pageTitle' => 'Pengaturan', 'pageDescription' => 'Kelola pengaturan sistem informasi magang.'])->name('pengaturan.index');
});

Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', MahasiswaDashboardController::class)->name('dashboard');
    Route::view('/profil', 'shared.module', ['pageTitle' => 'Profil', 'pageDescription' => 'Kelola informasi profil mahasiswa.'])->name('profil');
    Route::view('/pengajuan', 'shared.module', ['pageTitle' => 'Pengajuan Magang', 'pageDescription' => 'Ajukan dan pantau proses pengajuan magang.'])->name('pengajuan');
    Route::view('/tempat-magang', 'shared.module', ['pageTitle' => 'Tempat Magang', 'pageDescription' => 'Lihat dan pilih informasi tempat magang.'])->name('tempat-magang');
    Route::view('/status-magang', 'shared.module', ['pageTitle' => 'Status Magang', 'pageDescription' => 'Lihat status pelaksanaan magang Anda.'])->name('status-magang');
    Route::get('/logbook', [MahasiswaLogbookController::class, 'index'])->name('logbook');
    Route::post('/logbook', [MahasiswaLogbookController::class, 'store'])->name('logbook.store');
    Route::delete('/logbook/{logbook}', [MahasiswaLogbookController::class, 'destroy'])->name('logbook.destroy');
    Route::view('/laporan', 'shared.module', ['pageTitle' => 'Laporan Magang', 'pageDescription' => 'Kelola dan kirim laporan magang.'])->name('laporan');
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
