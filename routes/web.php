<?php

use App\Models\PpdbUser;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Ppdb\CekNisnController;
use App\Http\Controllers\Ppdb\AuthPpdbController;
use App\Http\Controllers\Ppdb\LandingPpdbController;
use App\Http\Controllers\Ppdb\PendaftaranController;
use App\Http\Controllers\Ppdb\UploadBerkasController;
use App\Http\Controllers\Auth\AdminAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Ppdb\MasterPpdbController;
use App\Http\Controllers\Admin\Ppdb\TahapanController;
use App\Http\Controllers\Admin\Ppdb\PpdbSyaratController;

/*
|--------------------------------------------------------------------------
| LANDING
|--------------------------------------------------------------------------
*/
Route::view('/', 'website.ppdb.landing')->name('beranda');

/*
|--------------------------------------------------------------------------
| EMAIL VERIFICATION (PPDB)
|--------------------------------------------------------------------------
*/
Route::get('/email/verify', function () {
    return view('ppdb.auth.verify-email');
})->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {

    $user = PpdbUser::findOrFail($id);

    if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
        abort(403);
    }

    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    Auth::guard('ppdb')->login($user);

    return redirect()->route('ppdb.dashboard');

})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user('ppdb')->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi dikirim ulang!');
})->middleware(['auth:ppdb', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| PENDAFTARAN (FORM)
|--------------------------------------------------------------------------
*/
Route::post('/siswa/pendaftaran/{jalur}', [
    PendaftaranController::class,
    'store'
])->name('siswa.pendaftaran.post');

/*
|--------------------------------------------------------------------------
| PILIH JALUR
| - Belum login  → simpan jalur di session → ke registrasi
| - Sudah login  → simpan jalur di session → langsung ke isi formulir
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

/*
|--------------------------------------------------------------------------
| DASHBOARD PPDB
|--------------------------------------------------------------------------
*/
Route::get('/ppdb/dashboard', [
    LandingPpdbController::class,
    'index'
])->middleware(['auth:ppdb'])->name('ppdb.dashboard');

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

/*
|--------------------------------------------------------------------------
| PPDB (PUBLIC + AUTH)
|--------------------------------------------------------------------------
*/
Route::prefix('ppdb')->group(function () {

    Route::view('/', 'website.ppdb.landing')->name('ppdb.landing');
    Route::view('/informasi', 'website.ppdb.informasi')->name('ppdb.informasi');
    Route::view('/alur', 'website.ppdb.alur')->name('ppdb.alur');
    Route::view('/persyaratan', 'website.ppdb.persyaratan')->name('ppdb.persyaratan');
    Route::view('/jadwal', 'website.ppdb.jadwal')->name('ppdb.jadwal');
    Route::view('/tutorial', 'website.ppdb.tutorial')->name('ppdb.tutorial');
    Route::view('/pengumuman', 'website.ppdb.pengumuman')->name('ppdb.pengumuman');

    Route::get('/jalur/{slug}', function ($slug) {
        $allowed = ['prestasi', 'reguler', 'afirmasi'];
        if (!in_array($slug, $allowed)) abort(404);
        return view('website.ppdb.jalur', compact('slug'));
    })->name('ppdb.jalur');

    Route::get('/login', fn() => view('ppdb.auth.login'))->name('ppdb.login');

    Route::post('/login', [
        AuthPpdbController::class,
        'login'
    ])->name('ppdb.login.post');

    Route::view('/daftar', 'ppdb.auth.registrasi')->name('ppdb.daftar');
    Route::view('/daftar/step2', 'ppdb.auth.registrasi2')->name('ppdb.daftar.step2');

    // ===== LUPA PASSWORD =====
    Route::view('/lupa-password', 'ppdb.auth.lupa-password')->name('ppdb.lupa-password');
    Route::post('/lupa-password', [AuthPpdbController::class, 'lupaPassword'])->name('ppdb.lupa-password.post');

    Route::get('/verify-otp', fn() => view('ppdb.auth.otp'))->name('ppdb.verify-otp');
    Route::post('/verify-otp', [AuthPpdbController::class, 'verifyOtp'])->name('ppdb.verify-otp.post');

    Route::get('/reset-password', fn() => view('ppdb.auth.reset-password'))->name('ppdb.reset-password');
    Route::post('/reset-password', [AuthPpdbController::class, 'resetPassword'])->name('ppdb.reset-password.post');
    // ===== END LUPA PASSWORD =====

    Route::post('/register', [
        AuthPpdbController::class,
        'register'
    ])->name('ppdb.register');

    Route::post('/cek-nisn', [
        CekNisnController::class,
        'cek'
    ])->name('ppdb.cek.nisn');
});

/*
|--------------------------------------------------------------------------
| SISWA - PENDAFTARAN (TANPA ppdb.step — boleh akses meski belum ada data)
|--------------------------------------------------------------------------
*/
Route::get('/siswa/pendaftaran/{jalur}', function ($jalur) {
    $allowed = ['prestasi', 'reguler', 'afirmasi'];
    if (!in_array($jalur, $allowed)) abort(404);
    return view('ppdb.pendaftaran.isi-formulir', compact('jalur'));
})->middleware(['auth:ppdb'])->name('siswa.pendaftaran');

/*
|--------------------------------------------------------------------------
| SISWA (PROTECTED — dengan ppdb.step)
|--------------------------------------------------------------------------
*/
Route::prefix('siswa')
    ->middleware(['auth:ppdb', 'ppdb.step'])
    ->group(function () {

    Route::view('/dashboard', 'ppdb.dashboard.beranda')->name('siswa.dashboard');

    Route::get('/redirect', function () {

        $user = auth('ppdb')->user();

        $pendaftaran = \App\Models\Pendaftaran::where('user_id', $user->id)
            ->latest()
            ->first();

        if (!$pendaftaran) {
            return redirect()->route('ppdb.dashboard');
        }

        switch ($pendaftaran->status) {
            case 'perbaikan':
                return redirect()->route('siswa.pendaftaran', $pendaftaran->jalur);
            case 'lulus':
                return redirect()->route('siswa.pengumuman', $pendaftaran->jalur);
            case 'tidak_lulus':
                return redirect()->route('ppdb.dashboard');
        }

        return redirect()->route('ppdb.dashboard');
    });

    Route::get('/upload-berkas/{jalur}', function ($jalur) {
        return view('ppdb.berkas.index', compact('jalur'));
    })->name('siswa.upload.berkas');

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

/*
|--------------------------------------------------------------------------
| ADMIN AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth'])->group(function () {

    Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');

    Route::get('/master-ppdb', [MasterPpdbController::class, 'index'])
        ->name('admin.master');

    Route::post('/master-ppdb/store', [MasterPpdbController::class, 'store'])
        ->name('admin.master.store');

    Route::post('/master-ppdb/activate/{id}', [MasterPpdbController::class, 'activate'])
        ->name('admin.master.activate');

    Route::get('/master-ppdb/{id}', [MasterPpdbController::class, 'detail'])
        ->name('admin.master.detail');

    Route::post('/jalur/store', [MasterPpdbController::class, 'storeJalur'])
        ->name('admin.jalur.store');
    Route::put('/jalur/{id}', [MasterPpdbController::class, 'updateJalur'])->name('admin.jalur.update');
    Route::delete('/jalur/{id}', [MasterPpdbController::class, 'deleteJalur'])->name('admin.jalur.delete');

    Route::post('/tahapan', [TahapanController::class, 'store'])->name('admin.tahapan.store');
    Route::put('/tahapan/{id}', [TahapanController::class, 'update'])->name('admin.tahapan.update');
    Route::delete('/tahapan/{id}', [TahapanController::class, 'destroy'])->name('admin.tahapan.delete');

    Route::get('/master-ppdb/{tahun}/tambah-syarat', fn($tahun) =>
        view('admin.ppdb.master.tambah-syarat', compact('tahun'))
    )->name('admin.master.tambah-syarat');
    Route::post('/syarat', [PpdbSyaratController::class, 'store'])->name('admin.syarat.store');
    Route::put('/syarat/{id}', [PpdbSyaratController::class, 'update'])->name('admin.syarat.update');
    Route::delete('/syarat/{id}', [PpdbSyaratController::class, 'destroy'])->name('admin.syarat.delete');

    Route::view('/operasional/verifikasi', 'admin.ppdb.operasional.verifikasi-berkas')->name('admin.operasional.verifikasi');
    Route::view('/operasional/pengumuman', 'admin.ppdb.operasional.pengumuman')->name('admin.operasional.pengumuman');
    Route::view('/operasional/faq', 'admin.ppdb.operasional.faq')->name('admin.operasional.faq');
    Route::view('/manajemen/akun', 'admin.ppdb.manajemen.akun-panitia')->name('admin.manajemen.akun');
    Route::view('/manajemen/riwayat', 'admin.ppdb.manajemen.riwayat')->name('admin.manajemen.riwayat');

    Route::get('/verifikasi', [\App\Http\Controllers\Admin\VerifikasiController::class, 'index'])->name('admin.verifikasi');
    Route::get('/verifikasi/{id}', [\App\Http\Controllers\Admin\VerifikasiController::class, 'show'])->name('admin.verifikasi.detail');
    Route::post('/verifikasi/lulus/{id}', [\App\Http\Controllers\Admin\VerifikasiController::class, 'lulus'])->name('admin.verifikasi.lulus');
    Route::post('/verifikasi/tidak-lulus/{id}', [\App\Http\Controllers\Admin\VerifikasiController::class, 'tidakLulus'])->name('admin.verifikasi.tidak_lulus');
    Route::post('/verifikasi/perbaikan/{id}', [\App\Http\Controllers\Admin\VerifikasiController::class, 'perbaikan'])->name('admin.verifikasi.perbaikan');

});

/*
|--------------------------------------------------------------------------
| PANITIA
|--------------------------------------------------------------------------
*/
Route::prefix('panitia')->group(function () {
    Route::view('/dashboard', 'panitia.dashboard')->name('panitia.dashboard');
});