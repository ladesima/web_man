<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ppdb\LandingPpdbController;

/*
|--------------------------------------------------------------------------
| LANDING
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingPpdbController::class, 'landing'])
    ->name('beranda');