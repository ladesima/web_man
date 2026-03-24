<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ppdb\LandingPpdbController;

/*
|--------------------------------------------------------------------------
| DASHBOARD PPDB
|--------------------------------------------------------------------------
*/
Route::get('/ppdb/dashboard', [
    LandingPpdbController::class,
    'index'
])->middleware(['auth:ppdb'])->name('ppdb.dashboard');