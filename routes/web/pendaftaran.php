<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ppdb\PendaftaranController;

/*
|--------------------------------------------------------------------------
| PENDAFTARAN (FORM)
|--------------------------------------------------------------------------
*/
Route::post('/siswa/pendaftaran/{jalur}', [
    PendaftaranController::class,
    'store'
])->name('siswa.pendaftaran.post');