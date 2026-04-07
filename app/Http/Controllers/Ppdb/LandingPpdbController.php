<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\MasterPpdb;
use App\Models\Pendaftaran;
use App\Models\Faq;
use App\Models\PpdbJalur;
use App\Models\MediaGambar;

class LandingPpdbController extends Controller
{
   public function index()
{
    $user = auth('ppdb')->user();

    // ✅ PPDB tetap ambil yang aktif
    $ppdb = MasterPpdb::where('is_active', 1)->first();

    // 🔥 AMBIL SEMUA JALUR (TANPA FILTER)
    $jalurs = PpdbJalur::with([
        'tahapans' => function ($q) {
            $q->orderBy('tanggal_mulai', 'asc');
        }
    ])->get();

    // 🔥 URUTAN CUSTOM
    $urutan = ['prestasi', 'reguler', 'afirmasi'];

    $jalurs = $jalurs->sortBy(function ($item) use ($urutan) {
        return array_search($item->jalur, $urutan);
    })->values();

    // ✅ pendaftaran
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
        'jalurs' => $jalurs
    ]);
}

    /*
    |--------------------------------------------------------------------------
    | LANDING PAGE (PUBLIC)
    |--------------------------------------------------------------------------
    */
 public function landing()
{
    $media = MediaGambar::pluck('file', 'key');
    $ppdb = MasterPpdb::where('is_active', 1)->first();

    // 🔥 AMBIL SEMUA JALUR
    $jalurs = PpdbJalur::with([
        'tahapans' => function ($q) {
            $q->orderBy('tanggal_mulai', 'asc');
        }
    ])->get();

    // 🔥 URUTAN
    $urutan = ['prestasi', 'reguler', 'afirmasi'];

    $jalurs = $jalurs->sortBy(function ($item) use ($urutan) {
        return array_search($item->jalur, $urutan);
    })->values();

    $faqs = Faq::where('status', 'aktif')
        ->orderBy('urutan')
        ->get(['id','pertanyaan','jawaban','kategori']);

    return view('website.ppdb.landing', [
        'ppdb' => $ppdb,
        'jalurs' => $jalurs, // 🔥 TAMBAHAN PENTING
        'faqs' => $faqs,
        'media' => $media
    ]);
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