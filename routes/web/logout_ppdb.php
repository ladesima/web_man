<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| LOGOUT PPDB (FIX GUARD)
|--------------------------------------------------------------------------
*/
Route::post('/ppdb/logout', function () {
    auth('ppdb')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('ppdb.login');
})->name('ppdb.logout');