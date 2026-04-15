<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboard;
use App\Http\Controllers\Siswa\SertifikatController as SertifikatSiswaController;
use App\Http\Controllers\Admin\SertifikatController as SertifikatAdminController;
use App\Http\Controllers\Admin\SiswaController;

Route::get('/', function(){
    return view("index");
});
Route::get('/scan', function(){
    return view("scanner");
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::prefix('sertifikat')->name('sertifikat.')->group(function () {
            Route::get('/',               [SertifikatAdminController::class, 'index'])->name('index');
            Route::get('/data',           [SertifikatAdminController::class, 'data'])->name('data');
            Route::get('/students',       [SertifikatAdminController::class, 'searchStudents'])->name('students');
            Route::get('/programs',       [SertifikatAdminController::class, 'searchPrograms'])->name('programs');
            Route::post('/',              [SertifikatAdminController::class, 'store'])->name('store');
            Route::get('/{id}/print',     [SertifikatAdminController::class, 'print'])->name('print'); 
            Route::get('/{id}',           [SertifikatAdminController::class, 'show'])->name('show');
            Route::put('/{id}',           [SertifikatAdminController::class, 'update'])->name('update');
            Route::delete('/{id}',        [SertifikatAdminController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/generate-cert-number',           [SertifikatAdminController::class, 'generateCertNumber'])->name('generate-cert-number');
        });
        
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::get('/siswa', [AdminDashboard::class, 'allStudents'])->name('siswa');
        Route::get('/sertifikat', [AdminDashboard::class, 'sertifikat'])->name('sertifikat');
        Route::get('/verifikasi', [AdminDashboard::class, 'verifikasi'])->name('verifikasi');


        Route::prefix('siswa')->name('siswa.')->group(function () {
            Route::get('/data', [SiswaController::class, 'data'])->name('data');
            Route::post('/', [SiswaController::class, 'store'])->name('store');
            Route::get('/{id}', [SiswaController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [SiswaController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SiswaController::class, 'update'])->name('update');
            Route::delete('/{id}', [SiswaController::class, 'destroy'])->name('destroy');
        });

    });

Route::prefix('siswa')
    ->name('siswa.')
    ->middleware(['auth', 'role:siswa'])
    ->group(function () {
        Route::get('/dashboard',  [SiswaDashboard::class, 'index'])->name('dashboard');
        Route::get('/sertifikat', [SiswaDashboard::class, 'sertifikat'])->name('sertifikat');
        Route::get('/verifikasi', [SiswaDashboard::class, 'verifikasi'])->name('verifikasi');
 
        Route::prefix('sertifikat')->name('sertifikat.')->group(function () {
            Route::get('/data',          [SertifikatSiswaController::class, 'data'])->name('data');
            Route::post('/',             [SertifikatSiswaController::class, 'store'])->name('store');
            Route::get('/{id}/download', [SertifikatSiswaController::class, 'download'])->name('download'); // ← tambahan
            Route::get('/{id}',          [SertifikatSiswaController::class, 'show'])->name('show');
            Route::get('/{id}/edit',     [SertifikatSiswaController::class, 'edit'])->name('edit');
            Route::put('/{id}',          [SertifikatSiswaController::class, 'update'])->name('update');
            Route::delete('/{id}',       [SertifikatSiswaController::class, 'destroy'])->name('destroy');
        });
    });

