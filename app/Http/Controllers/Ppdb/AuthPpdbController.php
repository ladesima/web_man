<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\PpdbUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pendaftaran;

class AuthPpdbController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nisn' => 'required|digits:10|unique:ppdb_users,nisn',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:ppdb_users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        PpdbUser::create([
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('ppdb.login')
            ->with('success', 'Akun berhasil dibuat, silakan login');
    }


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

        // 🔐 REGENERATE SESSION
        $request->session()->regenerate();

        // 💾 SIMPAN SESSION
        session([
            'ppdb_user_id' => $user->id,
            'nama' => $user->nama
        ]);

        // 🔥 CEK DATA PENDAFTARAN
        $pendaftaran = Pendaftaran::where('user_id', $user->id)->first();

        if ($pendaftaran) {

            // 🔒 SUDAH FINAL → VERIFIKASI
            if ($pendaftaran->status === 'verifikasi') {
                return redirect()->route('siswa.verifikasi', $pendaftaran->jalur);
            }

            // 🟡 SUDAH ISI FORM → LANJUT UPLOAD
            if ($pendaftaran->status === 'form_selesai') {
                return redirect()->route('siswa.upload.berkas', $pendaftaran->jalur);
            }

            // 🔄 fallback aman
            return redirect()->route('ppdb.dashboard');
        }

        // 🔥 JIKA BELUM PERNAH DAFTAR → CEK JALUR
        $jalur = session('jalur_daftar');

        if ($jalur) {
            session()->forget('jalur_daftar');
            return redirect("/siswa/pendaftaran/$jalur");
        }

        // 🔵 DEFAULT
        return redirect()->route('ppdb.dashboard');
    }
}