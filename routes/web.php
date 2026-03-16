<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Ppdb\CekNisnController;
use App\Http\Controllers\Ppdb\AuthPpdbController;
use App\Http\Controllers\Ppdb\LandingPpdbController;

Route::view('/', 'website.ppdb.landing')->name('beranda');

Route::get('/ppdb/pilih/{jalur}', function ($jalur) {

    session(['jalur_daftar' => $jalur]);

    return redirect()->route('ppdb.daftar');

})->name('ppdb.pilih_jalur');

Route::get('/ppdb/dashboard', [LandingPpdbController::class, 'index'])
    ->name('ppdb.dashboard');

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

    // Auth
    Route::get('/login', function () {
        return view('ppdb.auth.login');
    })->name('ppdb.login');
    Route::post('/login', [AuthPpdbController::class, 'login'])->name('ppdb.login.post');

    Route::view('/daftar', 'ppdb.auth.registrasi')->name('ppdb.daftar');
    Route::view('/daftar/step2', 'ppdb.auth.registrasi2')->name('ppdb.daftar.step2');
    Route::view('/lupa-password', 'ppdb.auth.lupa-password')->name('ppdb.lupa-password');

    Route::post('/daftar/step2', function () {
        return redirect()->route('siswa.dashboard');
    })->name('ppdb.daftar.step2.post');

    Route::post('/register', [AuthPpdbController::class, 'register'])->name('ppdb.register');
    Route::post('/cek-nisn', [CekNisnController::class, 'cek'])->name('ppdb.cek.nisn');
});


Route::prefix('siswa')->group(function () {
    Route::view('/dashboard', 'ppdb.dashboard.beranda')->name('siswa.dashboard');

    // Isi Formulir
    Route::get('/pendaftaran/{jalur}', function ($jalur) {
        $allowed = ['prestasi', 'reguler', 'afirmasi'];
        if (!in_array($jalur, $allowed)) abort(404);
        return view('ppdb.pendaftaran.isi-formulir', compact('jalur'));
    })->name('siswa.pendaftaran');

    Route::post('/pendaftaran/{jalur}', function ($jalur) {
        // simpan data formulir nanti pakai controller
        return redirect()->route('siswa.berkas', $jalur);
    })->name('siswa.pendaftaran.post');

    // Upload Berkas
    Route::get('/berkas/{jalur}', function ($jalur) {
        return view('ppdb.berkas.index', compact('jalur'));
    })->name('siswa.berkas');

    Route::post('/berkas/{jalur}', function ($jalur) {
        // simpan berkas nanti pakai controller
        return redirect()->route('siswa.verifikasi', $jalur);
    })->name('siswa.berkas.post');

    // Verifikasi
    Route::get('/verifikasi/{jalur}', function ($jalur) {
        return view('ppdb.dashboard.status', compact('jalur'));
    })->name('siswa.verifikasi');

    // Pengumuman
    Route::get('/pengumuman/{jalur}', function ($jalur) {
        return view('ppdb.pengumuman.index', compact('jalur'));
    })->name('siswa.pengumuman');

    // Daftar Ulang
    Route::get('/daftar-ulang/{jalur}', function ($jalur) {
        return view('ppdb.daftar-ulang.index', compact('jalur'));
    })->name('siswa.daftar-ulang');

    Route::post('/daftar-ulang/{jalur}', function ($jalur) {
        return redirect()->route('siswa.dashboard');
    })->name('siswa.daftar-ulang.post');
});

// =====================
// AUTH ADMIN & PANITIA
// =====================
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    // logic login nanti diisi di controller
})->name('login.post');

// Admin
Route::prefix('admin')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');
});

// Panitia
Route::prefix('panitia')->group(function () {
    Route::view('/dashboard', 'panitia.dashboard')->name('panitia.dashboard');
});

// Logout
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');