<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\PpdbUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
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

        // ✅ SIMPAN USER
        $user = PpdbUser::create([
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 🔥 KIRIM EMAIL VERIFIKASI
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
        // ✅ VALIDASI
        $request->validate([
            'nisn' => 'required|digits:10',
            'password' => 'required'
        ]);

        // 🔍 CEK USER
        $user = PpdbUser::where('nisn', $request->nisn)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'nisn' => 'NISN atau password salah'
            ]);
        }

        // ❗ CEK VERIFIKASI EMAIL
        if (!$user->hasVerifiedEmail()) {
            return back()->withErrors([
                'email' => 'Silakan verifikasi email terlebih dahulu'
            ]);
        }

        // 🔐 LOGIN
        Auth::guard('ppdb')->login($user);
        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | CEK DATA PENDAFTARAN
        |--------------------------------------------------------------------------
        */
        $pendaftaran = Pendaftaran::where('user_id', $user->id)->latest()->first();

        /*
        |--------------------------------------------------------------------------
        | JIKA BELUM PERNAH DAFTAR
        |--------------------------------------------------------------------------
        */
        if (!$pendaftaran) {

            $jalur = session('jalur_daftar');

            if ($jalur) {
                session()->forget('jalur_daftar');
                return redirect()->route('siswa.pendaftaran', $jalur);
            }

            return redirect()->route('ppdb.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | PRIORITAS LAST STEP (PALING PENTING)
        |--------------------------------------------------------------------------
        */
        if (!empty($pendaftaran->last_step)) {

            switch ($pendaftaran->last_step) {

                case 'form':
                    return redirect()->route('siswa.pendaftaran', $pendaftaran->jalur);

                case 'berkas':
                    return redirect()->route('siswa.upload.berkas', $pendaftaran->jalur);

                case 'verifikasi':
                    return redirect()->route('siswa.verifikasi', $pendaftaran->jalur);

                case 'pengumuman':
                    return redirect()->route('siswa.pengumuman', $pendaftaran->jalur);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FALLBACK BERDASARKAN STATUS
        |--------------------------------------------------------------------------
        */
        switch ($pendaftaran->status) {

            case 'belum':
                return redirect()->route('siswa.pendaftaran', $pendaftaran->jalur);

            case 'form_selesai':
                return redirect()->route('siswa.upload.berkas', $pendaftaran->jalur);

            case 'berkas_selesai':
                return redirect()->route('siswa.verifikasi', $pendaftaran->jalur);

            case 'perbaikan':
                return redirect()->route('siswa.pendaftaran', $pendaftaran->jalur);

            case 'lulus':
                return redirect()->route('siswa.pengumuman', $pendaftaran->jalur);

            case 'tidak_lulus':
                return redirect()->route('ppdb.dashboard')
                    ->with('error', 'Anda tidak lulus, silakan daftar jalur lain');

            default:
                return redirect()->route('ppdb.dashboard');
        }
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

    /*
    |--------------------------------------------------------------------------
    | LUPA PASSWORD - kirim OTP ke email
    |--------------------------------------------------------------------------
    */
    public function lupaPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:ppdb_users,email',
        ], [
            'email.exists' => 'Email tidak ditemukan.',
        ]);

        // Generate OTP 4 digit
        $otp = rand(1000, 9999);

        // Simpan OTP di cache selama 2 menit
        Cache::put('ppdb_otp_' . $request->email, $otp, now()->addMinutes(2));

        // Kirim OTP via email
        Mail::raw("Kode OTP reset kata sandi Anda: $otp\n\nKode berlaku selama 2 menit.", function ($message) use ($request, $otp) {
            $message->to($request->email)
                    ->subject('Kode OTP Reset Kata Sandi PPDB');
        });

        // Simpan email di session untuk dipakai di halaman OTP
        session(['ppdb_reset_email' => $request->email]);

        return redirect()->route('ppdb.verify-otp');
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFY OTP
    |--------------------------------------------------------------------------
    */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|array|min:4',
            'otp.*' => 'required|digits:1',
        ]);

        $email = session('ppdb_reset_email');

        if (!$email) {
            return redirect()->route('ppdb.lupa-password')
                ->withErrors(['otp' => 'Sesi habis, ulangi proses.']);
        }

        $inputOtp = implode('', $request->otp);
        $cachedOtp = Cache::get('ppdb_otp_' . $email);

        if (!$cachedOtp || $inputOtp != $cachedOtp) {
            return back()->withErrors(['otp' => 'Kode OTP salah atau sudah expired.']);
        }

        // OTP valid, hapus dari cache & tandai boleh reset
        Cache::forget('ppdb_otp_' . $email);
        session(['ppdb_otp_verified' => true]);

        return redirect()->route('ppdb.reset-password');
    }

    /*
    |--------------------------------------------------------------------------
    | RESET PASSWORD - simpan password baru
    |--------------------------------------------------------------------------
    */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ], [
            'password.min' => 'Minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $email = session('ppdb_reset_email');
        $verified = session('ppdb_otp_verified');

        if (!$email || !$verified) {
            return redirect()->route('ppdb.lupa-password')
                ->withErrors(['password' => 'Sesi tidak valid, ulangi proses.']);
        }

        $user = PpdbUser::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('ppdb.lupa-password');
        }

        // Cek tidak boleh sama dengan password lama
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Kata sandi baru tidak boleh sama dengan kata sandi lama.'
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Hapus session reset
        session()->forget(['ppdb_reset_email', 'ppdb_otp_verified']);

        return redirect()->route('ppdb.login')
            ->with('success', 'Kata sandi berhasil diubah, silakan login.');
    }
}