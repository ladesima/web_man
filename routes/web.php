<?php

use App\Models\PpdbUser;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Ppdb\CekNisnController;
use App\Http\Controllers\Ppdb\AuthPpdbController;
use App\Http\Controllers\Ppdb\LandingPpdbController;
use App\Http\Controllers\Ppdb\PendaftaranController;
use App\Http\Controllers\Ppdb\UploadBerkasController;
use App\Http\Controllers\Auth\AdminAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| LANDING
|--------------------------------------------------------------------------
*/
Route::view('/', 'website.ppdb.landing')->name('beranda');

/*
|--------------------------------------------------------------------------
| EMAIL VERIFICATION (PPDB)
|--------------------------------------------------------------------------
*/
Route::get('/email/verify', function () {
    return view('ppdb.auth.verify-email');
})->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {

    $user = PpdbUser::findOrFail($id);

    if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
        abort(403);
    }

    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    Auth::guard('ppdb')->login($user);

    return redirect()->route('ppdb.dashboard');

})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user('ppdb')->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi dikirim ulang!');
})->middleware(['auth:ppdb', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| PENDAFTARAN (FORM)
|--------------------------------------------------------------------------
*/
Route::post('/siswa/pendaftaran/{jalur}', [
    PendaftaranController::class,
    'store'
])->name('siswa.pendaftaran.post');

/*
|--------------------------------------------------------------------------
| PILIH JALUR
|--------------------------------------------------------------------------
*/
Route::get('/ppdb/pilih/{jalur}', function ($jalur) {
    session(['jalur_daftar' => $jalur]);
    return redirect()->route('ppdb.daftar');
})->name('ppdb.pilih_jalur');

/*
|--------------------------------------------------------------------------
| DASHBOARD PPDB
|--------------------------------------------------------------------------
*/
Route::get('/ppdb/dashboard', [
    LandingPpdbController::class,
    'index'
])->middleware(['auth:ppdb'])->name('ppdb.dashboard');

/*
|--------------------------------------------------------------------------
| UPLOAD BERKAS
|--------------------------------------------------------------------------
*/
Route::get('/siswa/upload-berkas/{jalur}', [
    UploadBerkasController::class,
    'index'
])->name('siswa.upload.berkas');

Route::post('/siswa/upload-berkas/{jalur}', [
    UploadBerkasController::class,
    'store'
])->name('siswa.upload.berkas.post');

/*
|--------------------------------------------------------------------------
| PPDB (PUBLIC + AUTH)
|--------------------------------------------------------------------------
*/
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

    // AUTH SISWA
    Route::get('/login', fn() => view('ppdb.auth.login'))->name('ppdb.login');

    Route::post('/login', [
        AuthPpdbController::class,
        'login'
    ])->name('ppdb.login.post');

    Route::view('/daftar', 'ppdb.auth.registrasi')->name('ppdb.daftar');
    Route::view('/daftar/step2', 'ppdb.auth.registrasi2')->name('ppdb.daftar.step2');
    Route::view('/lupa-password', 'ppdb.auth.lupa-password')->name('ppdb.lupa-password');

    Route::post('/register', [
        AuthPpdbController::class,
        'register'
    ])->name('ppdb.register');

    Route::post('/cek-nisn', [
        CekNisnController::class,
        'cek'
    ])->name('ppdb.cek.nisn');
});

/*
|--------------------------------------------------------------------------
| SISWA (PROTECTED)
|--------------------------------------------------------------------------
*/
Route::prefix('siswa')
    ->middleware(['auth:ppdb', 'ppdb.step'])
    ->group(function () {

    // Dashboard
    Route::view('/dashboard', 'ppdb.dashboard.beranda')->name('siswa.dashboard');

    // ========================
    // FORM PENDAFTARAN
    // ========================
    Route::get('/pendaftaran/{jalur}', function ($jalur) {

        $allowed = ['prestasi', 'reguler', 'afirmasi'];
        if (!in_array($jalur, $allowed)) abort(404);

        return view('ppdb.pendaftaran.isi-formulir', compact('jalur'));

    })->name('siswa.pendaftaran');

    // ========================
    // UPLOAD BERKAS
    // ========================
    Route::get('/upload-berkas/{jalur}', function ($jalur) {

        $allowed = ['prestasi', 'reguler', 'afirmasi'];
        if (!in_array($jalur, $allowed)) abort(404);

        return view('ppdb.berkas.index', compact('jalur'));

    })->name('siswa.upload.berkas');

    // ========================
    // VERIFIKASI
    // ========================
    Route::get('/verifikasi/{jalur}', function ($jalur) {

        $allowed = ['prestasi', 'reguler', 'afirmasi'];
        if (!in_array($jalur, $allowed)) abort(404);

        return view('ppdb.dashboard.status', compact('jalur'));

    })->name('siswa.verifikasi');

    // ========================
    // PENGUMUMAN
    // ========================
    Route::get('/pengumuman/{jalur}', function ($jalur) {

        $allowed = ['prestasi', 'reguler', 'afirmasi'];
        if (!in_array($jalur, $allowed)) abort(404);

        return view('ppdb.pengumuman.index', compact('jalur'));

    })->name('siswa.pengumuman');

    // ========================
    // DAFTAR ULANG
    // ========================
    Route::get('/daftar-ulang/{jalur}', function ($jalur) {

        $allowed = ['prestasi', 'reguler', 'afirmasi'];
        if (!in_array($jalur, $allowed)) abort(404);

        return view('ppdb.daftar-ulang.index', compact('jalur'));

    })->name('siswa.daftar-ulang');

    Route::post('/daftar-ulang/{jalur}', function ($jalur) {
        return redirect()->route('siswa.dashboard');
    })->name('siswa.daftar-ulang.post');

});

/*
|--------------------------------------------------------------------------
| ADMIN AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth'])->group(function () {

    Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');

    Route::view('/master-ppdb', 'admin.ppdb.master.index')->name('admin.master');

    Route::get('/master-ppdb/{tahun}', fn($tahun) =>
        view('admin.ppdb.master.detail', compact('tahun'))
    )->name('admin.master.detail');

    Route::get('/master-ppdb/{tahun}/tambah-syarat', fn($tahun) =>
        view('admin.ppdb.master.tambah-syarat', compact('tahun'))
    )->name('admin.master.tambah-syarat');

    Route::view('/operasional/verifikasi', 'admin.ppdb.operasional.verifikasi-berkas')->name('admin.operasional.verifikasi');
    Route::view('/operasional/pengumuman', 'admin.ppdb.operasional.pengumuman')->name('admin.operasional.pengumuman');
    Route::view('/operasional/faq', 'admin.ppdb.operasional.faq')->name('admin.operasional.faq');

    Route::view('/manajemen/akun', 'admin.ppdb.manajemen.akun-panitia')->name('admin.manajemen.akun');
    Route::view('/manajemen/riwayat', 'admin.ppdb.manajemen.riwayat-aktivitas')->name('admin.manajemen.riwayat');
});

/*
|--------------------------------------------------------------------------
| PANITIA
|--------------------------------------------------------------------------
*/
Route::prefix('panitia')->group(function () {
    Route::view('/dashboard', 'panitia.dashboard')->name('panitia.dashboard');
});