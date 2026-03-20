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

        // ❌ belum login
        if (!$user) {
            return redirect()->route('ppdb.login');
        }

        $pendaftaran = Pendaftaran::where('user_id', $user->id)->latest()->first();

        // ❌ belum daftar sama sekali
        if (!$pendaftaran) {
            return $next($request);
        }

        $currentRoute = $request->route()->getName();

        /*
        |--------------------------------------------------------------------------
        | PRIORITAS LAST STEP (PALING AKURAT)
        |--------------------------------------------------------------------------
        */
        if (!empty($pendaftaran->last_step)) {

            $routes = [
                'form' => 'siswa.pendaftaran',
                'berkas' => 'siswa.upload.berkas',
                'verifikasi' => 'siswa.verifikasi',
                'pengumuman' => 'siswa.pengumuman',
            ];

            $targetRoute = $routes[$pendaftaran->last_step] ?? null;

            if ($targetRoute && $currentRoute !== $targetRoute) {
                return redirect()->route($targetRoute, $pendaftaran->jalur);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FALLBACK STATUS (JIKA LAST_STEP KOSONG)
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
            case 'verifikasi':
                if ($currentRoute !== 'siswa.verifikasi') {
                    return redirect()->route('siswa.verifikasi', $pendaftaran->jalur);
                }
                break;

            case 'pengumuman':
            case 'lulus':
                if ($currentRoute !== 'siswa.pengumuman') {
                    return redirect()->route('siswa.pengumuman', $pendaftaran->jalur);
                }
                break;

            case 'perbaikan':
                if ($currentRoute !== 'siswa.pendaftaran') {
                    return redirect()->route('siswa.pendaftaran', $pendaftaran->jalur);
                }
                break;

            case 'tidak_lulus':
                return redirect()->route('ppdb.dashboard')
                    ->with('error', 'Silakan daftar jalur lain');
        }

        return $next($request);
    }
}