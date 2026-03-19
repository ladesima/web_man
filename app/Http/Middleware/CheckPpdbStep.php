<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Auth;

class CheckPpdbStep
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('ppdb')->user();

        // kalau belum login → biarkan middleware auth handle
        if (!$user) {
            return redirect()->route('ppdb.login');
        }

        $pendaftaran = Pendaftaran::where('user_id', $user->id)->first();

        // ❌ BELUM ADA DATA PENDAFTARAN
        if (!$pendaftaran) {
            return $next($request);
        }

        $currentRoute = $request->route()->getName();

        /*
        |--------------------------------------------------------------------------
        | RULE FLOW
        |--------------------------------------------------------------------------
        */

        switch ($pendaftaran->status) {

            case 'belum':
                if ($currentRoute !== 'siswa.pendaftaran') {
                    return redirect()->route('siswa.pendaftaran', $pendaftaran->jalur);
                }
                break;

            case 'form_selesai':
                if ($currentRoute !== 'siswa.upload.berkas') {
                    return redirect()->route('siswa.upload.berkas', $pendaftaran->jalur);
                }
                break;

            case 'berkas_selesai':
                if ($currentRoute !== 'siswa.verifikasi') {
                    return redirect()->route('siswa.verifikasi', $pendaftaran->jalur);
                }
                break;

            case 'verifikasi':
                if ($currentRoute !== 'siswa.verifikasi') {
                    return redirect()->route('siswa.verifikasi', $pendaftaran->jalur);
                }
                break;

            case 'pengumuman':
                if ($currentRoute !== 'siswa.pengumuman') {
                    return redirect()->route('siswa.pengumuman', $pendaftaran->jalur);
                }
                break;
        }

        return $next($request);
    }
}