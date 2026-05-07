<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VerifikasiController;


Route::get('/',  [HomeController::class, 'index']);
Route::get('/scan', [HomeController::class, 'scannerHome']);

Route::post('/v/verify-qr', [HomeController::class, 'verifyQr']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])
    ->group(function () {
        Route::get('/verifikasi', [DashboardController::class, 'verifikasi'])->name('verifikasi');
    });
Route::middleware(['auth'])->group(function () {

    Route::prefix('verifikasi')->name('verifikasi.')->group(function (){
        Route::get('/data',           [VerifikasiController::class, 'data'])->name('data');
    });
    
    Route::prefix('sertifikat')->name('sertifikat.')->group(function () {
        Route::post('/bulk-update', [SertifikatController::class, 'bulkUpdateStatus'])->name('update.bulk');
        Route::delete('/bulk-destroy', [SertifikatController::class, 'bulkDestroy'])->name('destroy.bulk');
        
        // ✅ yang FIX dulu (bukan param)
        Route::get('/data',           [SertifikatController::class, 'data'])->name('data');
        Route::get('/{id}/print',     [SertifikatController::class, 'print'])->name('print');

        // ✅ baru yang param
        Route::get('/{id}',           [SertifikatController::class, 'show'])->name('show');
        Route::put('/{id}',           [SertifikatController::class, 'update'])->name('update');
        Route::delete('/{id}',        [SertifikatController::class, 'destroy'])->name('destroy');

        // POST tetap aman
        Route::post('/',              [SertifikatController::class, 'store'])->name('store');
    });

    // route halaman
    Route::get('/dashboard',  [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/sertifikat', [DashboardController::class, 'sertifikat'])->name('sertifikat');

});
