<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;

class PendaftaranController extends Controller
{

    public function store(Request $request, $jalur)
{
    $request->validate([
        'nama_lengkap' => 'required',
        'ttl' => 'required',
        'nisn' => 'required',
        'asal_sekolah' => 'required',
        'alamat' => 'required',
        'nama_ortu' => 'required',
        'pekerjaan_ortu' => 'required',
        'penghasilan_ortu' => 'required',
        'alamat_ortu' => 'required',
        'jumlah_saudara' => 'required|integer',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ]);
    dd($request->file('foto'));
    $fotoPath = null;

    // 🔥 WAJIB ADA INI
    if ($request->hasFile('foto')) {
        $file = $request->file('foto');

        $fotoPath = $file->store('foto_ppdb', 'public');
    }

    Pendaftaran::create([
        'user_id' => session('ppdb_user_id'),
        'jalur' => $jalur,

        'nama_lengkap' => $request->nama_lengkap,
        'ttl' => $request->ttl,
        'nisn' => $request->nisn,
        'asal_sekolah' => $request->asal_sekolah,
        'alamat' => $request->alamat,

        'nama_ortu' => $request->nama_ortu,
        'pekerjaan_ortu' => $request->pekerjaan_ortu,
        'penghasilan_ortu' => $request->penghasilan_ortu,
        'alamat_ortu' => $request->alamat_ortu,
        'jumlah_saudara' => $request->jumlah_saudara,

        'foto' => $fotoPath // 🔥 SIMPAN KE DB
    ]);

    return redirect()->route('siswa.upload.berkas', $jalur)
        ->with('success','Data formulir berhasil disimpan');
}

}