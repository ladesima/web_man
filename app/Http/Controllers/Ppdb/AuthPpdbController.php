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
    |----------------------------------------------------------------------
    | REGISTER
    |----------------------------------------------------------------------
    */
    public function register(Request $request)
    {
        $request->validate([
            'nisn' => 'required|digits:10|unique:ppdb_users,nisn',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:ppdb_users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        // ✅ SIMPAN USER (BELUM VERIFIED)
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
    |----------------------------------------------------------------------
    | LOGIN
    |----------------------------------------------------------------------
    */
    public function login(Request $request)
    {
        // ✅ VALIDASI
        $credentials = $request->validate([
            'nisn' => 'required|digits:10',
            'password' => 'required'
        ]);

        // 🔍 CEK USER DULU (LEBIH AMAN)
        $user = PpdbUser::where('nisn', $request->nisn)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'nisn' => 'NISN atau password salah'
            ]);
        }

        // ❗ CEK EMAIL VERIFICATION (SEBELUM LOGIN)
        if (!$user->hasVerifiedEmail()) {
            return back()->withErrors([
                'email' => 'Silakan verifikasi email terlebih dahulu'
            ]);
        }

        // 🔐 LOGIN SETELAH VALID
        Auth::guard('ppdb')->login($user);
        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------
        | FLOW PENDAFTARAN (FINAL & TIDAK LOMPAT)
        |--------------------------------------------------------------
        */

        $pendaftaran = Pendaftaran::where('user_id', $user->id)->first();

        // ❌ BELUM ADA DATA PENDAFTARAN
        if (!$pendaftaran) {

            $jalur = session('jalur_daftar');

            if ($jalur) {
                session()->forget('jalur_daftar');
                return redirect()->route('siswa.pendaftaran', $jalur);
            }

            return redirect()->route('ppdb.dashboard');
        }

        /*
        |--------------------------------------------------------------
        | PRIORITAS LAST STEP (AGAR TIDAK RESET FLOW)
        |--------------------------------------------------------------
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
        |--------------------------------------------------------------
        | FALLBACK KE STATUS (JIKA LAST_STEP BELUM ADA)
        |--------------------------------------------------------------
        */
        switch ($pendaftaran->status) {

            case 'belum':
                return redirect()->route('siswa.pendaftaran', $pendaftaran->jalur);

            case 'form_selesai':
                return redirect()->route('siswa.upload.berkas', $pendaftaran->jalur);

            case 'berkas_selesai':
                return redirect()->route('siswa.verifikasi', $pendaftaran->jalur);

            case 'verifikasi':
                return redirect()->route('siswa.verifikasi', $pendaftaran->jalur);

            case 'pengumuman':
                return redirect()->route('siswa.pengumuman', $pendaftaran->jalur);

            default:
                return redirect()->route('ppdb.dashboard');
        }
    }

    /*
    |----------------------------------------------------------------------
    | LOGOUT
    |----------------------------------------------------------------------
    */
    public function logout(Request $request)
    {
        Auth::guard('ppdb')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('ppdb.login');
    }
}