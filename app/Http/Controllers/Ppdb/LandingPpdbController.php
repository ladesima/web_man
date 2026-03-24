<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\MasterPpdb;
use App\Models\Pendaftaran;

class LandingPpdbController extends Controller
{
    public function index()
    {
        // ✅ Ambil user (pakai guard saja, clean)
        $user = auth('ppdb')->user();

        // ✅ Ambil PPDB aktif (pakai helper method)
        $ppdb = MasterPpdb::aktifWithRelasi();

        // ✅ Ambil pendaftaran (safe)
        $pendaftaran = null;

        if ($user) {
            $pendaftaran = Pendaftaran::where('user_id', $user->id)
                ->latest()
                ->first();
        }

        return view('ppdb.dashboard.beranda', [
            'user' => $user,
            'ppdb' => $ppdb,
            'pendaftaran' => $pendaftaran,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LANDING PAGE (PUBLIC)
    |--------------------------------------------------------------------------
    */
  public function landing()
{
    $ppdb = MasterPpdb::with([
        'jalurs.tahapans' => function ($q) {
            $q->orderBy('tanggal_mulai', 'asc');
        }
    ])->where('is_active', 1)->first();

    if ($ppdb) {
        // 🔥 URUTAN CUSTOM
        $urutan = ['prestasi', 'reguler', 'afirmasi'];

        $ppdb->jalurs = $ppdb->jalurs
            ->sortBy(function ($item) use ($urutan) {
                return array_search($item->jalur, $urutan);
            })
            ->values();
    }

    return view('website.ppdb.landing', compact('ppdb'));
}
public function jalur($slug)
{
    $ppdb = MasterPpdb::aktifWithRelasi();

    return view('website.ppdb.jalur', [
        'slug' => $slug,
        'ppdb' => $ppdb
    ]);
}
}