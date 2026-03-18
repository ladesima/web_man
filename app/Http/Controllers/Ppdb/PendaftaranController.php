<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\PpdbUser;

class PendaftaranController extends Controller
{
    public function index($jalur)
    {
        $user = PpdbUser::where('id', $userId)->firstOrFail();

        // 🔐 Ambil data user

     

        // 🔐 Cek pendaftaran
        $pendaftaran = Pendaftaran::where('user_id', $userId)->first();

        // 🔥 SUDAH ISI FORM → JANGAN KEMBALI
        if ($pendaftaran && $pendaftaran->status === 'form_selesai') {
            return redirect()->route('ppdb.dashboard');
        }

        // 🔥 SUDAH VERIFIKASI
        if ($pendaftaran && $pendaftaran->status === 'verifikasi') {
            return redirect()->route('siswa.verifikasi', $pendaftaran->jalur);
        }
dd($user);
        return view('ppdb.pendaftaran.index', compact('jalur', 'user'));
    }


    public function store(Request $request, $jalur)
    {
        $userId = session('ppdb_user_id');

        // 🔐 Ambil user (WAJIB untuk keamanan)
        $user = PpdbUser::find($userId);

        // 🔐 CEK apakah sudah pernah daftar
        $cek = Pendaftaran::where('user_id', $userId)->first();

        if ($cek) {
            return redirect()->route('siswa.upload.berkas', $cek->jalur)
                ->with('error', 'Anda sudah mengisi data dan tidak bisa mengubahnya');
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

        // 💾 SIMPAN DATA (AMAN - TIDAK DARI INPUT USER)
        Pendaftaran::create([
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

            // 🔥 STATUS
            'status' => 'form_selesai'
        ]);

        return redirect()->route('siswa.upload.berkas', $jalur)
            ->with('success', 'Data formulir berhasil disimpan');
    }
}