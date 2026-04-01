<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Panitia;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('panitia.auth.login'); // jangan ubah blade
    }

    public function login(Request $request)
    {
        // ✅ Validasi input
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // ✅ Cari panitia berdasarkan username
        $panitia = Panitia::where('username', $request->username)
            ->where('status', 'aktif')
            ->first();

        if (!$panitia) {
            return back()->withErrors([
                'username' => 'Akun tidak ditemukan atau tidak aktif'
            ]);
        }

        // ✅ Cek password (hash)
        if (!Hash::check($request->password, $panitia->password)) {
            return back()->withErrors([
                'password' => 'Password salah'
            ]);
        }

        // ✅ Simpan session panitia
        Session::put('panitia_id', $panitia->id);
        Session::put('panitia_nama', $panitia->nama);
        Session::put('panitia_login', true);

        // ✅ Update last login
        $panitia->update([
            'last_login' => now()
        ]);

        return redirect()->route('panitia.dashboard');
    }

    public function logout()
    {
        Session::forget('panitia_id');
        Session::forget('panitia_nama');
        Session::forget('panitia_login');

        return redirect()->route('panitia.login');
    }
}