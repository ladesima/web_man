<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ppdb\AuthPpdbController;
use App\Http\Controllers\Ppdb\CekNisnController;
use App\Http\Controllers\Ppdb\LandingPpdbController;
use App\Http\Controllers\Ppdb\PendaftaranController;
use App\Http\Controllers\Ppdb\UploadBerkasController;
use App\Http\Controllers\Admin\Ppdb\FaqController;
use App\Models\PpdbUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| LANDING & DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingPpdbController::class, 'landing'])->name('beranda');

Route::get('/ppdb/dashboard', [LandingPpdbController::class, 'index'])
    ->middleware(['auth:ppdb'])->name('ppdb.dashboard');

/*
|--------------------------------------------------------------------------
| PPDB PUBLIC + AUTH
|--------------------------------------------------------------------------
*/
Route::prefix('ppdb')->group(function () {

    Route::get('/', [LandingPpdbController::class, 'landing'])->name('ppdb.landing');
    Route::view('/informasi', 'website.ppdb.informasi')->name('ppdb.informasi');
    Route::view('/alur', 'website.ppdb.alur')->name('ppdb.alur');
    Route::view('/persyaratan', 'website.ppdb.persyaratan')->name('ppdb.persyaratan');
    Route::view('/jadwal', 'website.ppdb.jadwal')->name('ppdb.jadwal');
    Route::view('/tutorial', 'website.ppdb.tutorial')->name('ppdb.tutorial');
    Route::view('/pengumuman', 'website.ppdb.pengumuman')->name('ppdb.pengumuman');

    Route::get('/jalur/{slug}', [LandingPpdbController::class, 'jalur'])->name('ppdb.jalur');

    Route::get('/login', fn() => view('ppdb.auth.login'))->name('ppdb.login');
    Route::post('/login', [AuthPpdbController::class, 'login'])->name('ppdb.login.post');

    Route::view('/daftar', 'ppdb.auth.registrasi')->name('ppdb.daftar');
    Route::view('/daftar/step2', 'ppdb.auth.registrasi2')->name('ppdb.daftar.step2');

    Route::post('/register', [AuthPpdbController::class, 'register'])->name('ppdb.register');

    Route::post('/cek-nisn', [CekNisnController::class, 'cek'])->name('ppdb.cek.nisn');

    // LUPA PASSWORD
    Route::view('/lupa-password', 'ppdb.auth.lupa-password')->name('ppdb.lupa-password');
    Route::post('/lupa-password', [AuthPpdbController::class, 'lupaPassword'])->name('ppdb.lupa-password.post');

    Route::get('/verify-otp', fn() => view('ppdb.auth.otp'))->name('ppdb.verify-otp');
    Route::post('/verify-otp', [AuthPpdbController::class, 'verifyOtp'])->name('ppdb.verify-otp.post');

    Route::get('/reset-password', fn() => view('ppdb.auth.reset-password'))->name('ppdb.reset-password');
    Route::post('/reset-password', [AuthPpdbController::class, 'resetPassword'])->name('ppdb.reset-password.post');
});

Route::post('/ppdb/pertanyaan', [FaqController::class, 'kirimPertanyaan'])
    ->name('ppdb.pertanyaan.kirim');

/*
|--------------------------------------------------------------------------
| PILIH JALUR
|--------------------------------------------------------------------------
*/
Route::get('/ppdb/pilih/{jalur}', function ($jalur) {

    $allowed = ['prestasi', 'reguler', 'afirmasi'];
    if (!in_array($jalur, $allowed)) abort(404);

    // 🔥 simpan tujuan jalur
    session(['redirect_jalur' => $jalur]);

    // 🔥 jika belum login → login dulu
    if (!auth('ppdb')->check()) {
        return redirect()->route('ppdb.login');
    }

    // 🔥 jika sudah login → langsung ke form
    return redirect()->route('siswa.pendaftaran', $jalur);

})->name('ppdb.pilih_jalur');

/*
|--------------------------------------------------------------------------
| PENDAFTARAN
|--------------------------------------------------------------------------
*/
Route::get('/siswa/pendaftaran/{jalur}', function ($jalur) {
    $allowed = ['prestasi', 'reguler', 'afirmasi'];
    if (!in_array($jalur, $allowed)) abort(404);

    return view('ppdb.pendaftaran.isi-formulir', compact('jalur'));
})->middleware(['auth:ppdb'])->name('siswa.pendaftaran');

Route::post('/siswa/pendaftaran/{jalur}', [PendaftaranController::class, 'store'])
    ->name('siswa.pendaftaran.post');
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
| SISWA AREA
|--------------------------------------------------------------------------
*/
Route::prefix('siswa')->middleware(['auth:ppdb', 'ppdb.step'])->group(function () {

    Route::view('/dashboard', 'ppdb.dashboard.beranda')->name('siswa.dashboard');

    Route::get('/verifikasi/{jalur}', fn($jalur) =>
        view('ppdb.dashboard.status', compact('jalur'))
    )->name('siswa.verifikasi');

    Route::get('/pengumuman/{jalur}', [PendaftaranController::class, 'pengumuman'])
    ->name('siswa.pengumuman');

    Route::get('/daftar-ulang/{jalur}', fn($jalur) =>
        view('ppdb.daftar-ulang.index', compact('jalur'))
    )->name('siswa.daftar-ulang');

    Route::post('/daftar-ulang/{jalur}', fn($jalur) =>
        redirect()->route('siswa.dashboard')
    )->name('siswa.daftar-ulang.post');
});

/*
|--------------------------------------------------------------------------
| REDIRECT STEP
|--------------------------------------------------------------------------
*/
Route::get('/ppdb/redirect', function () {

    $user = auth('ppdb')->user();

    /*
    |--------------------------------------------------------------------------
    | 🔥 BELUM LOGIN → SIMPAN INTENT
    |--------------------------------------------------------------------------
    */
    if (!$user) {
        session(['redirect_after_login' => true]);
        return redirect()->route('ppdb.login');
    }

    /*
    |--------------------------------------------------------------------------
    | 🔥 SUDAH LOGIN → CEK PENDAFTARAN
    |--------------------------------------------------------------------------
    */
    $pendaftaran = \App\Models\Pendaftaran::where('user_id', $user->id)
        ->latest()
        ->first();

    if ($pendaftaran) {

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
    }

    /*
    |--------------------------------------------------------------------------
    | 🔥 BELUM PERNAH DAFTAR → DEFAULT DASHBOARD
    |--------------------------------------------------------------------------
    */
    return redirect()->route('ppdb.dashboard');

})->name('ppdb.redirect');

/*
|--------------------------------------------------------------------------
| LOGOUT
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
| EMAIL VERIFICATION
|--------------------------------------------------------------------------
*/
Route::get('/email/verify', fn() => view('ppdb.auth.verify-email'))
    ->name('verification.notice');

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