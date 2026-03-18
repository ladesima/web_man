<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Storage;

class UploadBerkasController extends Controller
{

    public function index($jalur)
    {
    $userId = session('ppdb_user_id');  
    // 🔐 CEK DATA PENDAFTARAN
      $pendaftaran = Pendaftaran::where('user_id', $userId)
    ->where('jalur', $jalur)
    ->first();

        if (!$pendaftaran) {
            return redirect()->route('siswa.pendaftaran', $jalur)
                ->with('error', 'Silakan isi formulir terlebih dahulu');
        }

        // 🔐 JIKA SUDAH VERIFIKASI → KUNCI
        if ($pendaftaran->status == 'verifikasi') {
            return redirect()->route('siswa.verifikasi', $jalur);
        }

        return view('ppdb.berkas.index', compact('jalur'));
    }


    public function store(Request $request, $jalur)
{

    $userId = session('ppdb_user_id');

    // 🔐 CEK DATA (FIXED)
    $pendaftaran = Pendaftaran::where('user_id', $userId)
        ->where('jalur', $jalur)
        ->first();

    if (!$pendaftaran) {
        return redirect()->route('siswa.pendaftaran', $jalur)
            ->with('error', 'Isi formulir terlebih dahulu');
    }

    if ($pendaftaran->status == 'verifikasi') {
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

            $file = $request->file($input);

            $filename = time() . '_' . $file->getClientOriginalName();

            $path = $file->storeAs('berkas_ppdb', $filename, 'public');

            $data[$column] = $path;
        }
    }

    // 🔥 DEBUG (WAJIB COBA SEKALI)
    // dd($data);

    // 🔥 UPDATE (FIXED)
    $pendaftaran->update(array_merge($data, [
        'status' => 'verifikasi'
    ]));

    return redirect()
        ->route('siswa.verifikasi', $jalur)
        ->with('success', 'Berkas berhasil diupload');
}
}