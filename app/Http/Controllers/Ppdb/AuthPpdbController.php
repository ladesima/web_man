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
    | AMBIL DATA PENDAFTARAN
    |--------------------------------------------------------------------------
    */
    $pendaftaran = Pendaftaran::where('user_id', $user->id)
        ->latest()
        ->first();

    /*
    |--------------------------------------------------------------------------
    | 🔥 PRIORITAS 1: SUDAH PERNAH DAFTAR → LAST STEP
    |--------------------------------------------------------------------------
    */
    if ($pendaftaran) {

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
        | FALLBACK STATUS
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
    | 🔥 PRIORITAS 2: BELUM PERNAH DAFTAR → CEK JALUR
    |--------------------------------------------------------------------------
    */
    $jalur = session('jalur_daftar');

    if ($jalur) {
        session()->forget('jalur_daftar');
        return redirect()->route('siswa.pendaftaran', $jalur);
    }

    /*
    |--------------------------------------------------------------------------
    | 🔥 PRIORITAS 3: DEFAULT
    |--------------------------------------------------------------------------
    */
    return redirect()->route('ppdb.dashboard');
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