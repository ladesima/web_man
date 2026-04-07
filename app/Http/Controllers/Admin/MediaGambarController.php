<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MediaGambar;
use Illuminate\Support\Facades\Storage;

class MediaGambarController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN INDEX UTAMA
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return view('admin.ppdb.manajemen.media-gambar.index');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    public function indexAdmin()
    {
        $data = MediaGambar::pluck('file', 'key');

        return view('admin.ppdb.manajemen.media-gambar.admin', compact('data'));
    }

    /*
    |--------------------------------------------------------------------------
    | PANITIA
    |--------------------------------------------------------------------------
    */
    public function indexPanitia()
    {
        $data = MediaGambar::pluck('file', 'key');

        return view('admin.ppdb.manajemen.media-gambar.panitia', compact('data'));
    }

    /*
    |--------------------------------------------------------------------------
    | SISWA
    |--------------------------------------------------------------------------
    */
    public function indexSiswa()
    {
        $data = MediaGambar::pluck('file', 'key');

        return view('admin.ppdb.manajemen.media-gambar.siswa', compact('data'));
    }

    public function indexSistemInformasi()
{
    $data = MediaGambar::pluck('file', 'key');

    return view('admin.ppdb.manajemen.media-gambar.sistem-informasi', compact('data'));
}

    /*
    |--------------------------------------------------------------------------
    | STORE / UPDATE MEDIA
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI (WAJIB)
        |--------------------------------------------------------------------------
        */
        $request->validate([
            'media.*' => 'nullable|image|mimes:jpg,jpeg,png|max:5120'
        ]);

        $files = $request->file('media', []);

        if (!$files) {
            return back()->with('error', 'Tidak ada file yang diupload');
        }

        foreach ($files as $key => $file) {

            if (!$file) continue;

            $old = MediaGambar::where('key', $key)->first();

            /*
            |--------------------------------------------------------------------------
            | HAPUS FILE LAMA
            |--------------------------------------------------------------------------
            */
            if ($old && $old->file && Storage::disk('public')->exists($old->file)) {
                Storage::disk('public')->delete($old->file);
            }

            /*
            |--------------------------------------------------------------------------
            | SIMPAN FILE BARU
            |--------------------------------------------------------------------------
            */
            $path = $file->store('media_ppdb', 'public');

            /*
            |--------------------------------------------------------------------------
            | UPDATE / CREATE
            |--------------------------------------------------------------------------
            */
            MediaGambar::updateOrCreate(
                ['key' => $key],
                ['file' => $path]
            );
        }

        return back()->with('success', 'Media berhasil disimpan');
    }
}