<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\PpdbUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        $request->validate([
            'nisn' => 'required|digits:10',
            'password' => 'required'
        ]);

        $user = PpdbUser::where('nisn', $request->nisn)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'nisn' => 'NISN atau password salah'
            ]);
        }

        // Simpan session login
        $request->session()->regenerate();

        session([
            'ppdb_user_id' => $user->id,
            'nama' => $user->nama
        ]);

        // Ambil jalur yang dipilih sebelumnya
        $jalur = session('jalur_daftar');

        if ($jalur) {

            // Hapus session jalur setelah digunakan
            session()->forget('jalur_daftar');

            return redirect("/siswa/pendaftaran/$jalur");
        }

        // Jika login tanpa memilih jalur
        return redirect()->route('ppdb.dashboard');
    }
}