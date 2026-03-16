<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;

class UploadBerkasController extends Controller
{

    public function index($jalur)
    {
        return view('ppdb.siswa.upload-berkas', compact('jalur'));
    }


    public function store(Request $request, $jalur)
    {

        $request->validate([
            'akta_lahir' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'kartu_keluarga' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'rapor' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'verifikasi_pd' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'sertifikat_prestasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'sk_sekolah' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);


        $data = [];

        $files = [
            'akta_lahir',
            'kartu_keluarga',
            'rapor',
            'verifikasi_pd',
            'sertifikat_prestasi',
            'sk_sekolah'
        ];


        foreach ($files as $file) {

            if ($request->hasFile($file)) {

                $path = $request->file($file)->store('berkas_ppdb', 'public');

                $data[$file] = $path;
            }
        }


        Pendaftaran::where('user_id', session('ppdb_user_id'))
            ->where('jalur', $jalur)
            ->update($data);


        return redirect()
            ->route('siswa.verifikasi', $jalur)
            ->with('success', 'Berkas berhasil diupload');
    }
}