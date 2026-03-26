<?php

use Illuminate\Support\Facades\Route;

Route::prefix('panitia')->name('panitia.')->group(function () {

    Route::view('/dashboard', 'panitia.dashboard')->name('dashboard');
    Route::view('/data-pendaftar', 'panitia.data-pendaftar.index')->name('data-pendaftar');

    // ── OPERASIONAL ──────────────────────────────────────────────

    // Verifikasi Berkas
    Route::view('/operasional/verifikasi',            'panitia.operasional.verifikasi.index')       ->name('operasional.verifikasi');
    Route::view('/operasional/verifikasi/detail',     'panitia.operasional.verifikasi.detail')      ->name('operasional.verifikasi.detail');
    Route::view('/operasional/verifikasi/validasi',   'panitia.operasional.verifikasi.validasi')    ->name('operasional.verifikasi.validasi');

    // Pengumuman (Operasional)
    Route::view('/operasional/pengumuman',            'panitia.operasional.pengumuman.index')       ->name('operasional.pengumuman');
    Route::view('/operasional/pengumuman/review',     'panitia.operasional.pengumuman.review')      ->name('operasional.pengumuman.review');
    Route::view('/operasional/pengumuman/tambah',     'panitia.operasional.pengumuman.tambah')      ->name('operasional.pengumuman.tambah');
    Route::view('/operasional/pengumuman/{id}/pesan', 'panitia.operasional.pengumuman.detail-pesan')->name('operasional.pengumuman.pesan');

    // FAQ & Bantuan
    Route::view('/operasional/faq',                   'panitia.operasional.faq')                   ->name('operasional.faq');
    Route::view('/operasional/faq/tambah',            'panitia.operasional.faq-tambah')             ->name('operasional.faq.tambah');

    // ── SELEKSI ───────────────────────────────────────────────────
    Route::view('/seleksi', 'panitia.seleksi.index')->name('seleksi');

    // ── PENGUMUMAN (menu utama, bawah Seleksi Nilai) ──────────────
    Route::view('/pengumuman', 'panitia.pengumuman.index')->name('pengumuman');

    // ── AUTH ──────────────────────────────────────────────────────
    Route::post('/logout', function () {
        auth()->logout();
        return redirect('/');
    })->name('logout');

});