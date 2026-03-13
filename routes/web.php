<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ppdb\CekNisnController;
use App\Http\Controllers\Ppdb\AuthPpdbController;


Route::view('/', 'website.ppdb.landing')->name('beranda');

Route::prefix('ppdb')->group(function () {
    Route::view('/', 'website.ppdb.landing')->name('ppdb.landing');
    Route::view('/informasi', 'website.ppdb.informasi')->name('ppdb.informasi');
    Route::view('/alur', 'website.ppdb.alur')->name('ppdb.alur');
    Route::view('/persyaratan', 'website.ppdb.persyaratan')->name('ppdb.persyaratan');
    Route::view('/jadwal', 'website.ppdb.jadwal')->name('ppdb.jadwal');
    Route::view('/tutorial', 'website.ppdb.tutorial')->name('ppdb.tutorial');
    Route::view('/pengumuman', 'website.ppdb.pengumuman')->name('ppdb.pengumuman');

    Route::get('/jalur/{slug}', function ($slug) {
        $allowed = ['prestasi', 'reguler', 'afirmasi'];
        if (!in_array($slug, $allowed)) abort(404);
        return view('website.ppdb.jalur', compact('slug'));
    })->name('ppdb.jalur');

    // halaman login
    Route::get('/login', function () {
        return view('ppdb.auth.login');
    })->name('ppdb.login');

    // proses login
    Route::post('/login', [AuthPpdbController::class, 'login'])
        ->name('ppdb.login.post');


    Route::view('/login', 'ppdb.auth.login')->name('ppdb.login');
    Route::view('/daftar', 'ppdb.auth.registrasi')->name('ppdb.daftar');
    Route::view('/daftar/step2', 'ppdb.auth.registrasi2')->name('ppdb.daftar.step2');
    Route::view('/lupa-password', 'ppdb.auth.lupa-password')->name('ppdb.lupa-password');
    Route::post('/daftar/step2', function () {
        return redirect()->route('siswa.dashboard');
    })->name('ppdb.daftar.step2.post');

    Route::post('/register', [AuthPpdbController::class, 'register'])
    ->name('ppdb.register');

Route::post('/cek-nisn', [CekNisnController::class, 'cek'])
    ->name('ppdb.cek.nisn');

});


Route::prefix('siswa')->group(function () {
    Route::view('/dashboard', 'ppdb.dashboard.beranda')->name('siswa.dashboard');

    Route::get('/pendaftaran/{jalur}', function ($jalur) {
        $allowed = ['prestasi', 'reguler', 'afirmasi'];
        if (!in_array($jalur, $allowed)) abort(404);
        return view('ppdb.pendaftaran.isi-formulir', compact('jalur'));
    })->name('siswa.pendaftaran');

    Route::get('/berkas/{jalur}', function ($jalur) {
        return view('ppdb.berkas.index', compact('jalur'));
    })->name('siswa.berkas');

    Route::get('/verifikasi/{jalur}', function ($jalur) {
        return view('ppdb.dashboard.status', compact('jalur'));
    })->name('siswa.verifikasi');

    Route::get('/pengumuman/{jalur}', function ($jalur) {
        return view('ppdb.pengumuman.index', compact('jalur'));
    })->name('siswa.pengumuman');

    Route::get('/daftar-ulang/{jalur}', function ($jalur) {
        return view('ppdb.daftar-ulang.index', compact('jalur'));
    })->name('siswa.daftar-ulang');
});