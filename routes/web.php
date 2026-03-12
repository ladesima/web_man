<?php

use Illuminate\Support\Facades\Route;

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
        if (!in_array($slug, $allowed)) {
            abort(404);
        }
        return view('website.ppdb.jalur', compact('slug'));
    })->name('ppdb.jalur');
});

Route::prefix('siswa')->group(function () {
    Route::view('/dashboard', 'ppdb.dashboard.beranda')->name('siswa.dashboard');
    Route::view('/pendaftaran', 'ppdb.pendaftaran.isi-formulir')->name('siswa.pendaftaran');
    Route::view('/berkas', 'ppdb.berkas.index')->name('siswa.berkas');
    Route::view('/status', 'ppdb.dashboard.status')->name('siswa.status');
    Route::view('/pengumuman', 'ppdb.pengumuman.index')->name('siswa.pengumuman');
    Route::view('/daftar-ulang', 'ppdb.daftar-ulang.index')->name('siswa.daftar-ulang');
});