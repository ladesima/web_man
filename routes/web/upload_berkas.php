<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ppdb\UploadBerkasController;

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