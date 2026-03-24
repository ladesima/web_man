<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PILIH JALUR
|--------------------------------------------------------------------------
*/
Route::get('/ppdb/pilih/{jalur}', function ($jalur) {

    $allowed = ['prestasi', 'reguler', 'afirmasi'];
    if (!in_array($jalur, $allowed)) abort(404);

    $user = auth('ppdb')->user();

    if ($user) {
        $pendaftaran = \App\Models\Pendaftaran::where('user_id', $user->id)
            ->where('status', '!=', 'tidak_lulus')
            ->latest()
            ->first();

        if ($pendaftaran) {
            return redirect()->route('ppdb.dashboard')
                ->with('error', 'Anda masih memiliki pendaftaran aktif');
        }

        session(['jalur_daftar' => $jalur]);
        session()->save();
        return redirect()->route('siswa.pendaftaran', $jalur);
    }

    session(['jalur_daftar' => $jalur]);
    return redirect()->route('ppdb.daftar');

})->name('ppdb.pilih_jalur');