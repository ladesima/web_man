<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panitia\AuthController;
use App\Http\Controllers\Panitia\DashboardPanitiaController;
use App\Http\Middleware\PanitiaMiddleware;
use App\Http\Controllers\Panitia\DataPendaftarController;
use App\Http\Controllers\Panitia\VerifikasiController;
use App\Http\Controllers\Panitia\PengumumanController;
use App\Http\Controllers\Panitia\PengumumanNilaiController;
use App\Http\Controllers\Panitia\PertanyaanController;
use App\Http\Controllers\Panitia\NilaiSeleksiController;

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

            Route::get('/verifikasi/{id}', 
    [VerifikasiController::class, 'show']
)->name('verifikasi.detail');
            // Pengumuman
            Route::get('/pengumuman', [PengumumanController::class, 'index'])
    ->name('pengumuman');

            Route::get('/pengumuman/review',
        [PengumumanController::class, 'review']
    )->name('pengumuman.review');

            Route::delete('/pengumuman/template/{id}',
    [PengumumanController::class, 'destroyTemplate']);

            Route::get('/pengumuman/template/{id}/edit',
    [PengumumanController::class, 'editTemplate']
)->name('pengumuman.template.edit');

            Route::post('/pengumuman/publish-massal',
    [PengumumanController::class, 'publishMassal']
)->name('pengumuman.publish.massal');

            Route::post('/pengumuman/publish-selected',
    [PengumumanController::class, 'publishSelected']
)->name('pengumuman.publish.selected');

            Route::post('/pengumuman/store',
    [PengumumanController::class, 'store']
)->name('pengumuman.store');

            Route::put('/pengumuman/template/{id}',
    [PengumumanController::class, 'updateTemplate']
)->name('pengumuman.template.update');

            

            
            Route::view('/pengumuman/tambah', 'panitia.operasional.pengumuman.tambah')->name('pengumuman.tambah');
            Route::view('/pengumuman/{id}/pesan', 'panitia.operasional.pengumuman.detail-pesan')->name('pengumuman.pesan');
            // FAQ
            Route::get('/faq', [PertanyaanController::class, 'index'])->name('faq');
            Route::patch('/pertanyaan/{id}/jawab', [PertanyaanController::class, 'jawab']);
            Route::delete('/faq/{id}', [PertanyaanController::class, 'destroy']);
            Route::get('/faq/tambah', [PertanyaanController::class, 'create'])->name('faq.tambah');
            Route::post('/faq', [PertanyaanController::class, 'store'])->name('faq.store');

            Route::get('/faq/{id}/edit', [PertanyaanController::class, 'edit'])->name('faq.edit');
            Route::put('/faq/{id}', [PertanyaanController::class, 'update'])->name('faq.update');
        
            
            });

        // ── SELEKSI ─────────────────────────────
        Route::get('/seleksi', [NilaiSeleksiController::class, 'index'])
        ->name('seleksi');

        Route::patch('/seleksi/{id}/nilai', [NilaiSeleksiController::class, 'updateNilai'])
        ->name('seleksi.nilai');

    // optional
        Route::get('/seleksi/{id}', [NilaiSeleksiController::class, 'show']);
        Route::patch('/seleksi/{id}/reset', [NilaiSeleksiController::class, 'resetNilai']);

        // ── PENGUMUMAN ──────────────────────────
        Route::get('/pengumuman_nilai', function () {
            return view('panitia.pengumuman_nilai.index');
                })->name('pengumuman_nilai');
        

        // ── DATA PENDAFTAR ──────────────────────
        Route::get('/data-pendaftar', [DataPendaftarController::class, 'index'])
        ->name('data-pendaftar');
        });



});