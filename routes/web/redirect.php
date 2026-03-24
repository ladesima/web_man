<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTO REDIRECT LAST STEP
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

    if (!$pendaftaran) {
        return redirect()->route('ppdb.dashboard');
    }

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

})->name('ppdb.auto.redirect');