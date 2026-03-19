<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\PpdbUser;
use Illuminate\Support\Facades\Auth;

class PendaftaranController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | FORM PENDAFTARAN
    |--------------------------------------------------------------------------
    */
    public function index($jalur)
    {
        // 🔐 ambil user dari guard (FIX)
        $user = Auth::guard('ppdb')->user();

        if (!$user) {
            return redirect()->route('ppdb.login');
        }

        // 🔐 cek pendaftaran
        $pendaftaran = Pendaftaran::where('user_id', $user->id)->first();

        /*
        |--------------------------------------------------------------------------
        | FLOW PROTECTION
        |--------------------------------------------------------------------------
        */

        // 🔥 SUDAH ISI FORM → KE UPLOAD
        if ($pendaftaran && $pendaftaran->status === 'form_selesai') {
            return redirect()->route('siswa.upload.berkas', $pendaftaran->jalur);
        }

        // 🔥 SUDAH UPLOAD → KE VERIFIKASI
        if ($pendaftaran && $pendaftaran->status === 'berkas_selesai') {
            return redirect()->route('siswa.verifikasi', $pendaftaran->jalur);
        }

        // 🔥 SUDAH VERIFIKASI
        if ($pendaftaran && $pendaftaran->status === 'verifikasi') {
            return redirect()->route('siswa.verifikasi', $pendaftaran->jalur);
        }

        // 🔥 SUDAH PENGUMUMAN
        if ($pendaftaran && $pendaftaran->status === 'pengumuman') {
            return redirect()->route('siswa.pengumuman', $pendaftaran->jalur);
        }

        return view('ppdb.pendaftaran.index', compact('jalur', 'user'));
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN DATA FORM
    |--------------------------------------------------------------------------
    */
    public function store(Request $request, $jalur)
    {
        // 🔐 ambil user dari guard (FIX BESAR)
        $user = Auth::guard('ppdb')->user();

        if (!$user) {
            return redirect()->route('ppdb.login');
        }

        // 🔐 cek apakah sudah pernah daftar
        $pendaftaran = Pendaftaran::where('user_id', $user->id)->first();

        /*
        |--------------------------------------------------------------------------
        | LOCK SYSTEM (TIDAK BISA EDIT)
        |--------------------------------------------------------------------------
        */
        if ($pendaftaran && !$pendaftaran->is_revisi) {
            return redirect()->route('siswa.upload.berkas', $pendaftaran->jalur)
                ->with('error', 'Data sudah dikunci dan tidak bisa diubah');
        }

        // ✅ VALIDASI
        $request->validate([
            'ttl' => 'required',
            'asal_sekolah' => 'required',
            'alamat' => 'required',
            'nama_ortu' => 'required',
            'pekerjaan_ortu' => 'required',
            'penghasilan_ortu' => 'required',
            'alamat_ortu' => 'required',
            'jumlah_saudara' => 'required|integer'
        ], [
            'required' => 'Semua field wajib diisi!',
            'jumlah_saudara.integer' => 'Jumlah saudara harus berupa angka'
        ]);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN / UPDATE DATA
        |--------------------------------------------------------------------------
        */

        $data = [
            'user_id' => $user->id,
            'jalur' => $jalur,

            // 🔥 AUTO DARI AKUN
            'nama_lengkap' => $user->nama,
            'nisn' => $user->nisn,

            // 🔽 DARI FORM
            'ttl' => $request->ttl,
            'asal_sekolah' => $request->asal_sekolah,
            'alamat' => $request->alamat,
            'nama_ortu' => $request->nama_ortu,
            'pekerjaan_ortu' => $request->pekerjaan_ortu,
            'penghasilan_ortu' => $request->penghasilan_ortu,
            'alamat_ortu' => $request->alamat_ortu,
            'jumlah_saudara' => $request->jumlah_saudara,

            // 🔥 STATUS SYSTEM
            'status' => 'form_selesai',
            'last_step' => 'berkas'
        ];

       if ($pendaftaran) {

    // 🔥 UPDATE MANUAL (ANTI FAIL)
    foreach ($data as $key => $value) {
        $pendaftaran->$key = $value;
    }

    $pendaftaran->save();

} else {

    // 🆕 CREATE + PASTIKAN TERISI
    $pendaftaran = new Pendaftaran();

    foreach ($data as $key => $value) {
        $pendaftaran->$key = $value;
    }

    $pendaftaran->save();
}

        return redirect()->route('siswa.upload.berkas', $jalur)
            ->with('success', 'Data formulir berhasil disimpan');
    }
}