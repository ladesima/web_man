<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function index()
    {
        $data = Pendaftaran::latest()->get();

        return view('admin.ppdb.operasional.verifikasi-berkas', compact('data'));
    }

    public function show($id)
    {
        $data = Pendaftaran::findOrFail($id);

        return view('admin.ppdb.operasional.detail-verifikasi', compact('data'));
    }

    public function lulus($id)
    {
        $data = Pendaftaran::findOrFail($id);

        $data->update([
            'status' => 'lulus',
            'last_step' => 'pengumuman'
        ]);

        return back()->with('success', 'Siswa dinyatakan LULUS');
    }

    public function tidakLulus($id)
    {
        $data = Pendaftaran::findOrFail($id);

        $data->update([
            'status' => 'tidak_lulus',
            'last_step' => 'pengumuman'
        ]);

        return back()->with('success', 'Siswa TIDAK LULUS');
    }

    public function perbaikan(Request $request, $id)
    {
        $data = Pendaftaran::findOrFail($id);

        $data->update([
            'status' => 'perbaikan',
            'catatan_revisi' => $request->catatan,
            'last_step' => 'form'
        ]);

        return back()->with('success', 'Siswa diminta PERBAIKAN');
    }
}