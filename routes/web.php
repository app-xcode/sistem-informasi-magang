<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')
    ->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
        Route::resource('mahasiswa', MahasiswaController::class)->except(['show']);
        Route::resource('dosen', DosenController::class)->except(['show']);
        Route::view('/perusahaan', 'shared.module', ['pageTitle' => 'Data Perusahaan', 'pageDescription' => 'Kelola data instansi atau perusahaan tempat magang.'])->name('perusahaan.index');
        Route::view('/pengajuan', 'shared.module', ['pageTitle' => 'Pengajuan Magang', 'pageDescription' => 'Kelola dan proses pengajuan magang mahasiswa.'])->name('pengajuan.index');
        Route::view('/magang', 'shared.module', ['pageTitle' => 'Data Magang', 'pageDescription' => 'Kelola data pelaksanaan magang mahasiswa.'])->name('magang.index');
        Route::view('/monitoring', 'shared.module', ['pageTitle' => 'Monitoring Magang', 'pageDescription' => 'Pantau aktivitas dan status magang mahasiswa.'])->name('monitoring.index');
        Route::view('/laporan', 'shared.module', ['pageTitle' => 'Laporan', 'pageDescription' => 'Lihat dan kelola laporan sistem magang.'])->name('laporan.index');
        Route::view('/pengaturan', 'shared.module', ['pageTitle' => 'Pengaturan', 'pageDescription' => 'Kelola pengaturan sistem informasi magang.'])->name('pengaturan.index');
    });

Route::middleware(['auth', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->name('mahasiswa.')
    ->group(function () {
        Route::get('/dashboard', MahasiswaDashboardController::class)->name('dashboard');
        Route::view('/profil', 'shared.module', ['pageTitle' => 'Profil', 'pageDescription' => 'Kelola informasi profil mahasiswa.'])->name('profil');
        Route::view('/pengajuan', 'shared.module', ['pageTitle' => 'Pengajuan Magang', 'pageDescription' => 'Ajukan dan pantau proses pengajuan magang.'])->name('pengajuan');
        Route::view('/tempat-magang', 'shared.module', ['pageTitle' => 'Tempat Magang', 'pageDescription' => 'Lihat dan pilih informasi tempat magang.'])->name('tempat-magang');
        Route::view('/status-magang', 'shared.module', ['pageTitle' => 'Status Magang', 'pageDescription' => 'Lihat status pelaksanaan magang Anda.'])->name('status-magang');
        Route::view('/logbook', 'shared.module', ['pageTitle' => 'Logbook', 'pageDescription' => 'Catat dan kelola kegiatan magang harian.'])->name('logbook');
        Route::view('/laporan', 'shared.module', ['pageTitle' => 'Laporan Magang', 'pageDescription' => 'Kelola dan kirim laporan magang.'])->name('laporan');
    });

Route::middleware(['auth', 'role:dosen'])
    ->prefix('dosen')
    ->name('dosen.')
    ->group(function () {
        Route::get('/dashboard', DosenDashboardController::class)->name('dashboard');
        Route::view('/profil', 'shared.module', ['pageTitle' => 'Profil', 'pageDescription' => 'Kelola informasi profil dosen.'])->name('profil');
        Route::view('/mahasiswa-bimbingan', 'shared.module', ['pageTitle' => 'Mahasiswa Bimbingan', 'pageDescription' => 'Lihat mahasiswa yang berada dalam bimbingan Anda.'])->name('mahasiswa-bimbingan');
        Route::view('/pengajuan', 'shared.module', ['pageTitle' => 'Pengajuan Magang', 'pageDescription' => 'Tinjau pengajuan magang mahasiswa bimbingan.'])->name('pengajuan');
        Route::view('/monitoring', 'shared.module', ['pageTitle' => 'Monitoring Magang', 'pageDescription' => 'Pantau perkembangan mahasiswa selama magang.'])->name('monitoring');
        Route::view('/logbook', 'shared.module', ['pageTitle' => 'Logbook', 'pageDescription' => 'Tinjau aktivitas logbook mahasiswa bimbingan.'])->name('logbook');
        Route::view('/penilaian', 'shared.module', ['pageTitle' => 'Penilaian', 'pageDescription' => 'Kelola penilaian mahasiswa magang.'])->name('penilaian');
        Route::view('/laporan', 'shared.module', ['pageTitle' => 'Laporan', 'pageDescription' => 'Tinjau laporan magang mahasiswa bimbingan.'])->name('laporan');
    });
