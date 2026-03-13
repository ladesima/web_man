<?php

namespace App\Http\Controllers\Ppdb;

use Illuminate\Support\Facades\Auth;
use App\Models\PpdbUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

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

        return redirect()->route('ppdb.login')
            ->with('success','Akun berhasil dibuat, silakan login');
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

    session([
        'ppdb_user_id' => $user->id
    ]);

    return redirect()->route('siswa.dashboard');
}
}