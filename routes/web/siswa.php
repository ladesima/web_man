<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ppdb\PendaftaranController;
use App\Http\Controllers\Ppdb\UploadBerkasController;
use App\Http\Controllers\Ppdb\LandingPpdbController;

/*
|--------------------------------------------------------------------------
| PENDAFTARAN (FORM SUBMIT)
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

    $allowed = ['prestasi', 'reguler', 'afirmasi'];
    if (!in_array($jalur, $allowed)) abort(404);

    // 🔥 SIMPAN JALUR DULU
    session(['jalur_daftar' => $jalur]);
    session()->save();

    // 🔥 FORCE LOGOUT (KUNCI SOLUSI)
    if (auth('ppdb')->check()) {
        auth('ppdb')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    // 🔥 ARAHKAN KE REGISTRASI
    return redirect()->route('ppdb.daftar');

})->name('ppdb.pilih_jalur');

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/ppdb/dashboard', [
    LandingPpdbController::class,
    'index'
])->middleware(['auth:ppdb'])->name('ppdb.dashboard');

/*
|--------------------------------------------------------------------------
| 🔥 CENTRAL REDIRECT (PALING PENTING)
|--------------------------------------------------------------------------
*/
Route::get('/ppdb/redirect', function () {

    $user = auth('ppdb')->user();

    if (!$user) {
        return redirect()->route('ppdb.login');
    }

    $pendaftaran = \App\Models\Pendaftaran::where('user_id', $user->id)
        ->latest()
        ->first();

    /*
    |--------------------------------------------------------------------------
    | PRIORITAS 1: SUDAH PERNAH DAFTAR
    |--------------------------------------------------------------------------
    */
    if ($pendaftaran) {

        $routes = [
            'form' => 'siswa.pendaftaran',
            'berkas' => 'siswa.upload.berkas',
            'verifikasi' => 'siswa.verifikasi',
            'pengumuman' => 'siswa.pengumuman',
        ];

        $step = $pendaftaran->last_step ?? 'form';

        if (isset($routes[$step])) {
            return redirect()->route($routes[$step], $pendaftaran->jalur);
        }

        return redirect()->route('ppdb.dashboard');
    }

    /*
    |--------------------------------------------------------------------------
    | PRIORITAS 2: BELUM DAFTAR → CEK JALUR
    |--------------------------------------------------------------------------
    */
    $jalur = session('jalur_daftar');

    if ($jalur) {
        session()->forget('jalur_daftar');
        return redirect()->route('siswa.pendaftaran', $jalur);
    }

    /*
    |--------------------------------------------------------------------------
    | DEFAULT
    |--------------------------------------------------------------------------
    */
    return redirect()->route('ppdb.dashboard');

})->name('ppdb.auto.redirect');

/*
|--------------------------------------------------------------------------
| FORM PENDAFTARAN
|--------------------------------------------------------------------------
*/
Route::get('/siswa/pendaftaran/{jalur}', function ($jalur) {

    $allowed = ['prestasi', 'reguler', 'afirmasi'];
    if (!in_array($jalur, $allowed)) abort(404);

    return view('ppdb.pendaftaran.isi-formulir', compact('jalur'));

})->middleware(['auth:ppdb'])->name('siswa.pendaftaran');

/*
|--------------------------------------------------------------------------
| UPLOAD BERKAS
|--------------------------------------------------------------------------
*/
Route::get('/siswa/upload-berkas/{jalur}', [
    UploadBerkasController::class,
    'index'
])->middleware(['auth:ppdb', 'ppdb.step'])
  ->name('siswa.upload.berkas');

Route::post('/siswa/upload-berkas/{jalur}', [
    UploadBerkasController::class,
    'store'
])->middleware(['auth:ppdb', 'ppdb.step'])
  ->name('siswa.upload.berkas.post');

/*
|--------------------------------------------------------------------------
| SISWA (PROTECTED)
|--------------------------------------------------------------------------
*/
Route::prefix('siswa')
    ->middleware(['auth:ppdb', 'ppdb.step'])
    ->group(function () {

    Route::view('/dashboard', 'ppdb.dashboard.beranda')->name('siswa.dashboard');

    Route::get('/verifikasi/{jalur}', function ($jalur) {
        return view('ppdb.dashboard.status', compact('jalur'));
    })->name('siswa.verifikasi');

    Route::get('/pengumuman/{jalur}', function ($jalur) {
        return view('ppdb.pengumuman.index', compact('jalur'));
    })->name('siswa.pengumuman');

    Route::get('/daftar-ulang/{jalur}', function ($jalur) {
        return view('ppdb.daftar-ulang.index', compact('jalur'));
    })->name('siswa.daftar-ulang');

    Route::post('/daftar-ulang/{jalur}', function ($jalur) {
        return redirect()->route('siswa.dashboard');
    })->name('siswa.daftar-ulang.post');

});