<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;

class VerifikasiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST DATA VERIFIKASI
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $data = Pendaftaran::latest()->get();

        return view('admin.ppdb.operasional.verifikasi-berkas', compact('data'));
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL PESERTA
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        return view('admin.ppdb.operasional.detail', compact('pendaftaran'));
    }

    /*
    |--------------------------------------------------------------------------
    | LULUS
    |--------------------------------------------------------------------------
    */
    public function lulus($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $pendaftaran->status = 'lulus';
        $pendaftaran->last_step = 'pengumuman';
        $pendaftaran->is_revisi = false;
        $pendaftaran->catatan_revisi = null;

        $pendaftaran->save();

        return back()->with('success', 'Peserta dinyatakan LULUS');
    }

    /*
    |--------------------------------------------------------------------------
    | TIDAK LULUS
    |--------------------------------------------------------------------------
    */
    public function tidakLulus($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $pendaftaran->status = 'tidak_lulus';
        $pendaftaran->last_step = 'pengumuman';
        $pendaftaran->is_revisi = false;

        $pendaftaran->save();

        return back()->with('error', 'Peserta dinyatakan TIDAK LULUS');
    }

    /*
    |--------------------------------------------------------------------------
    | PERBAIKAN (REVISI)
    |--------------------------------------------------------------------------
    */
    public function perbaikan(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string|max:1000'
        ], [
            'catatan.required' => 'Catatan revisi wajib diisi'
        ]);

        $pendaftaran = Pendaftaran::findOrFail($id);

        $pendaftaran->status = 'perbaikan';
        $pendaftaran->is_revisi = true;
        $pendaftaran->catatan_revisi = $request->catatan;
        $pendaftaran->last_step = 'form';

        $pendaftaran->save();

        return back()->with('warning', 'Peserta diminta melakukan perbaikan');
    }

    /*
    |--------------------------------------------------------------------------
    | RESET (OPSIONAL - DEBUG / ADMIN)
    |--------------------------------------------------------------------------
    */
    public function reset($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $pendaftaran->status = 'belum';
        $pendaftaran->last_step = 'form';
        $pendaftaran->is_revisi = false;
        $pendaftaran->catatan_revisi = null;

        $pendaftaran->save();

        return back()->with('info', 'Data berhasil direset');
    }
}