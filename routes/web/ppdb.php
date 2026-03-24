<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ppdb\AuthPpdbController;
use App\Http\Controllers\Ppdb\CekNisnController;
use App\Http\Controllers\Ppdb\LandingPpdbController;

/*
|--------------------------------------------------------------------------
| PPDB (PUBLIC + AUTH)
|--------------------------------------------------------------------------
*/
Route::prefix('ppdb')->group(function () {

    Route::get('/', [LandingPpdbController::class, 'landing'])->name('ppdb.landing');
    Route::view('/informasi', 'website.ppdb.informasi')->name('ppdb.informasi');
    Route::view('/alur', 'website.ppdb.alur')->name('ppdb.alur');
    Route::view('/persyaratan', 'website.ppdb.persyaratan')->name('ppdb.persyaratan');
    Route::view('/jadwal', 'website.ppdb.jadwal')->name('ppdb.jadwal');
    Route::view('/tutorial', 'website.ppdb.tutorial')->name('ppdb.tutorial');
    Route::view('/pengumuman', 'website.ppdb.pengumuman')->name('ppdb.pengumuman');

    Route::get('/jalur/{slug}', [LandingPpdbController::class, 'jalur'])
    ->name('ppdb.jalur');

    Route::get('/login', fn() => view('ppdb.auth.login'))->name('ppdb.login');

    Route::post('/login', [
        AuthPpdbController::class,
        'login'
    ])->name('ppdb.login.post');

    Route::view('/daftar', 'ppdb.auth.registrasi')->name('ppdb.daftar');
    Route::view('/daftar/step2', 'ppdb.auth.registrasi2')->name('ppdb.daftar.step2');

    // ===== LUPA PASSWORD =====
    Route::view('/lupa-password', 'ppdb.auth.lupa-password')->name('ppdb.lupa-password');
    Route::post('/lupa-password', [AuthPpdbController::class, 'lupaPassword'])->name('ppdb.lupa-password.post');

    Route::get('/verify-otp', fn() => view('ppdb.auth.otp'))->name('ppdb.verify-otp');
    Route::post('/verify-otp', [AuthPpdbController::class, 'verifyOtp'])->name('ppdb.verify-otp.post');

    Route::get('/reset-password', fn() => view('ppdb.auth.reset-password'))->name('ppdb.reset-password');
    Route::post('/reset-password', [AuthPpdbController::class, 'resetPassword'])->name('ppdb.reset-password.post');
    // ===== END LUPA PASSWORD =====

    Route::post('/register', [
        AuthPpdbController::class,
        'register'
    ])->name('ppdb.register');

    Route::post('/cek-nisn', [
        CekNisnController::class,
        'cek'
    ])->name('ppdb.cek.nisn');
});