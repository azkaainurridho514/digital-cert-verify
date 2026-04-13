<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboard;

// ===== AUTH =====
// Route::get('/', fn() => redirect()->route('login'));
Route::get('/', function(){
    return view("index");
});
Route::get('/scan', function(){
    return view("scanner");
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ===== ADMIN ROUTES =====
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::get('/siswa', fn() => view('admin.siswa'))->name('siswa');
        Route::get('/sertifikat', fn() => view('admin.sertifikat'))->name('sertifikat');
        Route::get('/verifikasi', fn() => view('admin.verifikasi'))->name('verifikasi');
    });

// ===== SISWA ROUTES =====
Route::prefix('siswa')
    ->name('siswa.')
    ->middleware(['auth', 'role:siswa'])
    ->group(function () {
        Route::get('/dashboard', [SiswaDashboard::class, 'index'])->name('dashboard');
        Route::get('/sertifikat', fn() => view('siswa.sertifikat'))->name('sertifikat');
        Route::get('/verifikasi', fn() => view('siswa.verifikasi'))->name('verifikasi');
    });