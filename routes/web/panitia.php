<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PANITIA
|--------------------------------------------------------------------------
*/
Route::prefix('panitia')->group(function () {
    Route::view('/dashboard', 'panitia.dashboard')->name('panitia.dashboard');
});