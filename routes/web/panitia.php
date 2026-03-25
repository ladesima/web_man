<?php

use Illuminate\Support\Facades\Route;

Route::prefix('panitia')->name('panitia.')->group(function () {

    Route::view('/dashboard', 'panitia.dashboard')->name('dashboard');
    Route::view('/data-pendaftar', 'panitia.data-pendaftar.index')->name('data-pendaftar');
    Route::view('/verifikasi', 'panitia.verifikasi.index')->name('verifikasi');
    Route::view('/verifikasi/detail', 'panitia.verifikasi.detail')->name('verifikasi.detail');
    Route::view('/seleksi', 'panitia.seleksi.index')->name('seleksi');
    Route::view('/pengumuman', 'panitia.pengumuman.index')->name('pengumuman');

    // Dummy logout untuk preview UI
    Route::post('/logout', function () {
        return redirect('/panitia/dashboard');
    })->name('logout');

});