<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UploadBerkasController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN UPLOAD
    |--------------------------------------------------------------------------
    */
    public function index($jalur)
    {
        // 🔐 ambil user dari guard (FIX)
        $user = Auth::guard('ppdb')->user();

        if (!$user) {
            return redirect()->route('ppdb.login');
        }

        // 🔐 ambil data pendaftaran
        $pendaftaran = Pendaftaran::where('user_id', $user->id)
            ->where('jalur', $jalur)
            ->first();

        // ❌ BELUM ISI FORM
        if (!$pendaftaran) {
            return redirect()->route('siswa.pendaftaran', $jalur)
                ->with('error', 'Silakan isi formulir terlebih dahulu');
        }

        /*
        |--------------------------------------------------------------------------
        | FLOW PROTECTION
        |--------------------------------------------------------------------------
        */

        // 🔥 SUDAH UPLOAD → KE VERIFIKASI
        if ($pendaftaran->status === 'berkas_selesai') {
            return redirect()->route('siswa.verifikasi', $jalur);
        }

        // 🔥 SUDAH VERIFIKASI
        if ($pendaftaran->status === 'verifikasi') {
            return redirect()->route('siswa.verifikasi', $jalur);
        }

        // 🔥 SUDAH PENGUMUMAN
        if ($pendaftaran->status === 'pengumuman') {
            return redirect()->route('siswa.pengumuman', $jalur);
        }

        return view('ppdb.berkas.index', compact('jalur', 'pendaftaran'));
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN BERKAS
    |--------------------------------------------------------------------------
    */
    public function store(Request $request, $jalur)
    {
        // 🔐 ambil user dari guard (FIX BESAR)
        $user = Auth::guard('ppdb')->user();

        if (!$user) {
            return redirect()->route('ppdb.login');
        }

        // 🔐 ambil data pendaftaran
        $pendaftaran = Pendaftaran::where('user_id', $user->id)
            ->where('jalur', $jalur)
            ->first();

        if (!$pendaftaran) {
            return redirect()->route('siswa.pendaftaran', $jalur)
                ->with('error', 'Isi formulir terlebih dahulu');
        }

        /*
        |--------------------------------------------------------------------------
        | LOCK SYSTEM (TIDAK BISA EDIT)
        |--------------------------------------------------------------------------
        */
        if ($pendaftaran->status !== 'form_selesai' && !$pendaftaran->is_revisi) {
            return redirect()->route('siswa.verifikasi', $jalur)
                ->with('error', 'Berkas sudah dikunci');
        }

        // ✅ VALIDASI
        $request->validate([
            'akta' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'kk' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'rapor' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'bukti_pd' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'sertifikat' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'skl' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $data = [];

        $files = [
            'akta' => 'akta_lahir',
            'kk' => 'kartu_keluarga',
            'rapor' => 'rapor',
            'bukti_pd' => 'verifikasi_pd',
            'sertifikat' => 'sertifikat_prestasi',
            'skl' => 'sk_sekolah'
        ];

        foreach ($files as $input => $column) {

            if ($request->hasFile($input)) {

                // 🔥 HAPUS FILE LAMA (JIKA ADA)
                if (!empty($pendaftaran->$column)) {
                    Storage::disk('public')->delete($pendaftaran->$column);
                }

                $file = $request->file($input);

                $filename = time() . '_' . $file->getClientOriginalName();

                $path = $file->storeAs('berkas_ppdb', $filename, 'public');

                $data[$column] = $path;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA
        |--------------------------------------------------------------------------
        */
        $pendaftaran->update(array_merge($data, [
            'status' => 'berkas_selesai',
            'last_step' => 'verifikasi'
        ]));

        return redirect()
            ->route('siswa.verifikasi', $jalur)
            ->with('success', 'Berkas berhasil diupload');
    }
}