<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VerifikasiController;
use App\Http\Controllers\CertificateTemplateController;
use App\Http\Controllers\EcdsaController;
// ECDSA integer
Route::get('/ecdsa-real-output',  [EcdsaController::class, "signRealOutput"]);
Route::get('/ecdsa-same-message',  [EcdsaController::class, "signSameMessage"]);
Route::get('/ecdsa-real-implement',  [EcdsaController::class, "signRealImplement"]);


Route::get('/',  [HomeController::class, 'index']);
Route::get('/scan', [HomeController::class, 'scannerHome']);

Route::post('/v/verify-qr', [HomeController::class, 'verifyQr']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::prefix('template')->name('template.')->group(function () {
        Route::get('/active',  [CertificateTemplateController::class, 'getActive'])->name('active');
        Route::get('/data',    [CertificateTemplateController::class, 'data'])->name('data');
        Route::get('/{id}',    [CertificateTemplateController::class, 'show'])->name('show');
        Route::put('/{id}',    [CertificateTemplateController::class, 'update'])->name('update');
        Route::delete('/{id}', [CertificateTemplateController::class, 'destroy'])->name('destroy');
        Route::post('/',       [CertificateTemplateController::class, 'store'])->name('store');
        });
        
        Route::prefix('verifikasi')->name('verifikasi.')->group(function (){
            Route::get('/data',           [VerifikasiController::class, 'data'])->name('data');
            });
            
    Route::prefix('sertifikat')->name('sertifikat.')->group(function () {
        Route::get('/{id}/print', [SertifikatController::class, 'print'])->name('sertifikat.print');
        Route::post('/bulk-update', [SertifikatController::class, 'bulkUpdateStatus'])->name('update.bulk');
        Route::delete('/bulk-destroy', [SertifikatController::class, 'bulkDestroy'])->name('destroy.bulk');
        Route::get('/data',           [SertifikatController::class, 'data'])->name('data');
        Route::get('/{id}',           [SertifikatController::class, 'show'])->name('show');
        Route::put('/{id}',           [SertifikatController::class, 'update'])->name('update');
        Route::delete('/{id}',        [SertifikatController::class, 'destroy'])->name('destroy');
        Route::post('/',              [SertifikatController::class, 'store'])->name('store');
    });
    Route::get('/dashboard',  [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/sertifikat', [DashboardController::class, 'sertifikat'])->name('sertifikat');
    Route::get('/verifikasi', [DashboardController::class, 'verifikasi'])->name('verifikasi');
    Route::get('/template',   [DashboardController::class, 'template'])->name('template');
});
