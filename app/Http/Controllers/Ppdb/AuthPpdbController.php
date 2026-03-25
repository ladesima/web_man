<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\PpdbUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Pendaftaran;

class AuthPpdbController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */
   public function register(Request $request)
{
    $request->validate([
        'nisn' => 'required|digits:10|unique:ppdb_users,nisn',
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:ppdb_users,email',
        'password' => 'required|min:6|confirmed'
    ]);

    $user = PpdbUser::create([
        'nisn' => $request->nisn,
        'nama' => $request->nama,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    $user->sendEmailVerificationNotification();

    return redirect()
        ->route('verification.notice')
        ->with('success', 'Akun berhasil dibuat, silakan cek email untuk verifikasi.');
}

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */
    public function login(Request $request)
{
    /*
    |------------------------------------------------------------------
    | VALIDASI INPUT
    |------------------------------------------------------------------
    */
    $request->validate([
        'nisn' => 'required|digits:10',
        'email' => 'required|email',
        'password' => 'required'
    ]);

    /*
    |------------------------------------------------------------------
    | 🔍 CEK USER BERDASARKAN NISN + EMAIL
    |------------------------------------------------------------------
    */
    $user = PpdbUser::where('nisn', $request->nisn)
        ->where('email', $request->email)
        ->first();

    /*
    |------------------------------------------------------------------
    | ❌ USER TIDAK DITEMUKAN
    |------------------------------------------------------------------
    */
    if (!$user) {
        return back()->withErrors([
            'nisn' => 'NISN atau email tidak sesuai'
        ])->withInput();
    }

    /*
    |------------------------------------------------------------------
    | ❌ PASSWORD SALAH
    |------------------------------------------------------------------
    */
    if (!Hash::check($request->password, $user->password)) {
        return back()->withErrors([
            'password' => 'Password salah'
        ])->withInput();
    }

    /*
    |------------------------------------------------------------------
    | ❗ CEK VERIFIKASI EMAIL
    |------------------------------------------------------------------
    */
    if (!$user->hasVerifiedEmail()) {
        return back()->withErrors([
            'email' => 'Silakan verifikasi email terlebih dahulu'
        ]);
    }

    /*
    |------------------------------------------------------------------
    | 🔐 LOGIN
    |------------------------------------------------------------------
    */
    Auth::guard('ppdb')->login($user);
    $request->session()->regenerate();

    /*
    |------------------------------------------------------------------
    | 🔥 AMBIL PENDAFTARAN
    |------------------------------------------------------------------
    */
    $pendaftaran = Pendaftaran::where('user_id', $user->id)
        ->latest()
        ->first();

    $routes = [
        'form' => 'siswa.pendaftaran',
        'berkas' => 'siswa.upload.berkas',
        'verifikasi' => 'siswa.verifikasi',
        'pengumuman' => 'siswa.pengumuman',
    ];

    /*
    |------------------------------------------------------------------
    | 🔥 PRIORITAS 1: LAST STEP
    |------------------------------------------------------------------
    */
    if ($pendaftaran) {

        if (empty($pendaftaran->last_step)) {
            $pendaftaran->update(['last_step' => 'form']);
        }

        if (isset($routes[$pendaftaran->last_step])) {
            return redirect()->route(
                $routes[$pendaftaran->last_step],
                $pendaftaran->jalur
            );
        }
    }

    /*
    |------------------------------------------------------------------
    | 🔁 PRIORITAS 2: STATUS
    |------------------------------------------------------------------
    */
    if ($pendaftaran) {

        return match ($pendaftaran->status) {
            'belum' => redirect()->route('siswa.pendaftaran', $pendaftaran->jalur),
            'form_selesai' => redirect()->route('siswa.upload.berkas', $pendaftaran->jalur),
            'berkas_selesai' => redirect()->route('siswa.verifikasi', $pendaftaran->jalur),
            'perbaikan' => redirect()->route('siswa.pendaftaran', $pendaftaran->jalur),
            'lulus' => redirect()->route('siswa.pengumuman', $pendaftaran->jalur),
            'tidak_lulus' => redirect()->route('ppdb.dashboard')
                ->with('error', 'Anda tidak lulus, silakan daftar jalur lain'),
            default => redirect()->route('ppdb.dashboard'),
        };
    }

    /*
    |------------------------------------------------------------------
    | 🔥 PRIORITAS 3: DARI JALUR
    |------------------------------------------------------------------
    */
    if (session()->has('redirect_jalur')) {
        $jalur = session()->pull('redirect_jalur');
        return redirect()->route('siswa.pendaftaran', $jalur);
    }

    /*
    |------------------------------------------------------------------
    | 🔥 PRIORITAS 4: LANDING
    |------------------------------------------------------------------
    */
    if (session()->has('redirect_after_login')) {
        session()->pull('redirect_after_login');
        return redirect()->route('ppdb.dashboard');
    }

    /*
    |------------------------------------------------------------------
    | DEFAULT
    |------------------------------------------------------------------
    */
    return redirect()->route('ppdb.dashboard');
}
public function lupaPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email'
    ]);

    $user = PpdbUser::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors([
            'email' => 'Email tidak ditemukan'
        ]);
    }

    $otp = rand(1000, 9999);

    // 🔥 FORCE SAVE (SUDAH BENAR)
    $user->otp = $otp;
    $user->otp_expired_at = now()->addMinutes(5);
    $user->save();

    \Log::info('OTP GENERATED', [
        'email' => $user->email,
        'otp' => $otp
    ]);

    \Mail::raw("Kode OTP kamu: $otp", function ($message) use ($user) {
        $message->to($user->email)
            ->subject('Kode OTP Reset Password');
    });

    session(['ppdb_reset_email' => $user->email]);

    return redirect()->route('ppdb.verify-otp');
}

public function verifyOtp(Request $request)
{
    $request->validate([
        'otp' => 'required|array|size:4',
        'otp.*' => 'digits:1'
    ]);

    $otpInput = implode('', $request->otp);

    $email = session('ppdb_reset_email');

    if (!$email) {
        return redirect()->route('ppdb.login');
    }

    $user = PpdbUser::where('email', $email)->first();

    if (!$user || $user->otp != $otpInput) {
        return back()->withErrors([
            'otp' => 'Kode OTP salah'
        ]);
    }

    if (now()->gt($user->otp_expired_at)) {
        return back()->withErrors([
            'otp' => 'OTP sudah kadaluarsa'
        ]);
    }

    session(['otp_verified' => true]);

    return redirect()->route('ppdb.reset-password');
}

public function resetPassword(Request $request)
{
    $request->validate([
        'password' => 'required|min:6|confirmed'
    ]);

    $email = session('ppdb_reset_email');

    if (!$email || !session('otp_verified')) {
        return redirect()->route('ppdb.login');
    }

    $user = PpdbUser::where('email', $email)->first();

    if (!$user) {
        return redirect()->route('ppdb.login');
    }

    $user->update([
        'password' => \Hash::make($request->password),
        'otp' => null,
        'otp_expired_at' => null
    ]);

    session()->forget(['ppdb_reset_email', 'otp_verified']);

    return redirect()->route('ppdb.login')
        ->with('success', 'Password berhasil direset');
}

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    public function logout(Request $request)
    {
        Auth::guard('ppdb')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('ppdb.login');
    }
}