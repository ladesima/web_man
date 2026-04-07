<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Panitia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\MediaGambar;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        $media = MediaGambar::pluck('file', 'key');
        return view('auth.login', compact('media'));
    }

    public function login(Request $request)
{
    $request->validate([
        'role' => 'required',
        'password' => 'required'
    ]);

    // ================= ADMIN =================
    if ($request->role === 'admin') {

        $request->validate([
            'email' => 'required|email'
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::guard('web')->attempt($credentials)) {

            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password admin salah'
        ]);
    }

    // ================= PANITIA =================
    if ($request->role === 'panitia') {

        $request->validate([
            'username' => 'required'
        ]);

        $panitia = Panitia::where('username', $request->username)
            ->where('status', 'aktif')
            ->first();

        if (!$panitia) {
            return back()->withErrors([
                'username' => 'Akun panitia tidak ditemukan'
            ]);
        }

        if (!Hash::check($request->password, $panitia->password)) {
            return back()->withErrors([
                'password' => 'Password panitia salah'
            ]);
        }

        // session panitia
        Session::put('panitia_id', $panitia->id);
        Session::put('panitia_nama', $panitia->nama);
        Session::put('panitia_login', true);

        $panitia->update([
            'last_login' => now()
        ]);

        return redirect()->route('panitia.dashboard');
    }

    return back()->withErrors([
        'role' => 'Role tidak valid'
    ]);
}
}
