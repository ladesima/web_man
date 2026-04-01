<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panitia\AuthController;
use App\Http\Controllers\Panitia\DashboardPanitiaController;
use App\Http\Middleware\PanitiaMiddleware;
use App\Http\Controllers\Panitia\DataPendaftarController;
use App\Http\Controllers\Panitia\VerifikasiController;
Route::get('/preview/{file}', function ($file) {
    return response()->file(storage_path('app/public/' . $file));
})->name('preview.dokumen');
Route::prefix('panitia')->name('panitia.')->group(function () {
    
    
    // ================================
    // 🔐 AUTH (TANPA LOGIN)
    // ================================
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');

    // ================================
    // 🔒 PROTECTED (WAJIB LOGIN)
    // ================================
    Route::middleware(['panitia.auth'])->group(function () {

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/dashboard', [DashboardPanitiaController::class, 'index'])
        ->name('dashboard');
        // ── OPERASIONAL ─────────────────────────

        Route::prefix('operasional')->name('operasional.')->group(function () {

            // Verifikasi
            Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi');
            Route::get('/verifikasi/{id}', [VerifikasiController::class, 'show'])
    ->name('verifikasi.detail');
            Route::get('/verifikasi/{id}/validasi', 
    [VerifikasiController::class, 'validasi']
)->name('verifikasi.validasi');
            Route::post('/verifikasi/{id}/simpan',
    [VerifikasiController::class, 'simpanValidasi']
)->name('verifikasi.simpan');
            // Pengumuman
            Route::view('/pengumuman', 'panitia.operasional.pengumuman.index')->name('pengumuman');
            Route::view('/pengumuman/review', 'panitia.operasional.pengumuman.review')->name('pengumuman.review');
            Route::view('/pengumuman/tambah', 'panitia.operasional.pengumuman.tambah')->name('pengumuman.tambah');
            Route::view('/pengumuman/{id}/pesan', 'panitia.operasional.pengumuman.detail-pesan')->name('pengumuman.pesan');

            // FAQ
            Route::view('/faq', 'panitia.operasional.faq')->name('faq');
            Route::view('/faq/tambah', 'panitia.operasional.faq-tambah')->name('faq.tambah');
        });

        // ── SELEKSI ─────────────────────────────
        Route::view('/seleksi', 'panitia.seleksi.index')->name('seleksi');

        // ── PENGUMUMAN ──────────────────────────
        Route::view('/pengumuman', 'panitia.pengumuman.index')->name('pengumuman');

        // ── DATA PENDAFTAR ──────────────────────
        Route::get('/data-pendaftar', [DataPendaftarController::class, 'index'])
        ->name('data-pendaftar');
        });



});