<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Ppdb\MasterPpdbController;
use App\Http\Controllers\Admin\Ppdb\TahapanController;
use App\Http\Controllers\Admin\Ppdb\PpdbSyaratController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\VerifikasiController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

Route::get('/preview-dokumen', function (Illuminate\Http\Request $request) {

    $file = $request->file;

    $path = storage_path('app/public/' . $file);

    if (!file_exists($path)) {
        abort(404, 'File tidak ditemukan');
    }

    return response()->file($path);

})->name('preview.dokumen');

Route::prefix('admin')->middleware(['auth'])->group(function () {

    Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');

    Route::get('/master-ppdb', [MasterPpdbController::class, 'index'])->name('admin.master');
    Route::post('/master-ppdb/store', [MasterPpdbController::class, 'store'])->name('admin.master.store');
    Route::post('/master-ppdb/activate/{id}', [MasterPpdbController::class, 'activate'])->name('admin.master.activate');

    Route::get('/master-ppdb/{tahun}/tambah-syarat', fn($tahun) =>
        view('admin.ppdb.master.tambah-syarat', compact('tahun'))
    )->name('admin.master.tambah-syarat');

    Route::get('/master-ppdb/{id}', [MasterPpdbController::class, 'detail'])->name('admin.master.detail');

    Route::post('/jalur/store', [MasterPpdbController::class, 'storeJalur'])->name('admin.jalur.store');
    Route::put('/jalur/{id}', [MasterPpdbController::class, 'updateJalur'])->name('admin.jalur.update');
    Route::delete('/jalur/{id}', [MasterPpdbController::class, 'deleteJalur'])->name('admin.jalur.delete');

    Route::post('/tahapan', [TahapanController::class, 'store'])->name('admin.tahapan.store');
    Route::put('/tahapan/{id}', [TahapanController::class, 'update'])->name('admin.tahapan.update');
    Route::delete('/tahapan/{id}', [TahapanController::class, 'destroy'])->name('admin.tahapan.delete');

    Route::get('/data-pendaftar', function () {
        $pendaftaran = [];
        return view('admin.ppdb.data-pendaftar.index', compact('pendaftaran'));
    })->name('admin.data-pendaftar');

    Route::post('/syarat', [PpdbSyaratController::class, 'store'])->name('admin.syarat.store');
    Route::put('/syarat/{id}', [PpdbSyaratController::class, 'update'])->name('admin.syarat.update');
    Route::delete('/syarat/{id}', [PpdbSyaratController::class, 'destroy'])->name('admin.syarat.delete');

    // VERIFIKASI
    // Route::view('/operasional/verifikasi', 'admin.ppdb.operasional.verifikasi.index')->name('admin.operasional.verifikasi');
    // Route::view('/operasional/verifikasi/{id}', 'admin.ppdb.operasional.verifikasi.detail')->name('admin.operasional.verifikasi.detail');
    // Route::view('/operasional/verifikasi/{id}/validasi', 'admin.ppdb.operasional.verifikasi.validasi')->name('admin.operasional.verifikasi.validasi');

     // MEDIA GAMBAR
    Route::view('/manajemen/media-gambar', 'admin.ppdb.manajemen.media-gambar.index')
->name('admin.manajemen.media-gambar');
    Route::view('/manajemen/media-gambar/sistem-informasi', 'admin.ppdb.manajemen.media-gambar.sistem-informasi')
        ->name('admin.manajemen.media-gambar.sistem-informasi');
    Route::view('/manajemen/media-gambar/siswa', 'admin.ppdb.manajemen.media-gambar.siswa')
        ->name('admin.manajemen.media-gambar.siswa');
    Route::view('/manajemen/media-gambar/admin', 'admin.ppdb.manajemen.media-gambar.admin')
        ->name('admin.manajemen.media-gambar.admin');
    Route::view('/manajemen/media-gambar/panitia', 'admin.ppdb.manajemen.media-gambar.panitia')
        ->name('admin.manajemen.media-gambar.panitia');
        
    // PENGUMUMAN
    Route::view('/operasional/pengumuman', 'admin.ppdb.operasional.pengumuman.index')->name('admin.operasional.pengumuman');
    Route::view('/operasional/pengumuman/review', 'admin.ppdb.operasional.pengumuman.review')->name('admin.operasional.pengumuman.review');
    Route::view('/operasional/pengumuman/tambah', 'admin.ppdb.operasional.pengumuman.tambah')->name('admin.operasional.pengumuman.tambah');
    Route::view('/operasional/pengumuman/{id}/pesan', 'admin.ppdb.operasional.pengumuman.detail-pesan')->name('admin.operasional.pengumuman.pesan');

    Route::view('/operasional/faq', 'admin.ppdb.operasional.faq')
        ->name('admin.operasional.faq');

    Route::view('/operasional/faq/tambah', 'admin.ppdb.operasional.faq-tambah')
        ->name('admin.operasional.faq.tambah');

    Route::view('/manajemen/akun', 'admin.ppdb.manajemen.akun-panitia')->name('admin.manajemen.akun');
    Route::view('/manajemen/riwayat', 'admin.ppdb.manajemen.riwayat-aktivitas')->name('admin.manajemen.riwayat');
   

   Route::get('/operasional/verifikasi', [VerifikasiController::class, 'index'])
    ->name('admin.operasional.verifikasi');

   // DETAIL
    Route::get('/operasional/verifikasi/{id}', [\App\Http\Controllers\Admin\VerifikasiController::class, 'show'])->name('admin.verifikasi.detail');
Route::get('/operasional/verifikasi/{id}/validasi', [\App\Http\Controllers\Admin\VerifikasiController::class, 'validasi'])->name('admin.verifikasi.validasi');

    Route::post('/operasional/verifikasi/lulus/{id}', [VerifikasiController::class, 'lulus']);
    Route::post('/operasional/verifikasi/tidak-lulus/{id}', [VerifikasiController::class, 'tidakLulus']);
    Route::post('/operasional/verifikasi/perbaikan/{id}', [VerifikasiController::class, 'perbaikan']);
    Route::post('/operasional/verifikasi/{id}/simpan', [VerifikasiController::class, 'simpanValidasi'])->name('admin.verifikasi.simpan');
});

// AUTH ADMIN
Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');