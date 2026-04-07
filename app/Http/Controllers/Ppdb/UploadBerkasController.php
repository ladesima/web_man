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
        $user = Auth::guard('ppdb')->user();

        if (!$user) {
            return redirect()->route('ppdb.login');
        }

        $pendaftaran = Pendaftaran::where('user_id', $user->id)
    ->where('jalur', $jalur)
    ->latest()
    ->first();

        /*
        |------------------------------------------------------------------
        | ❌ BELUM ISI FORM
        |------------------------------------------------------------------
        */
        if (!$pendaftaran) {
            return redirect()->route('siswa.pendaftaran', $jalur)
                ->with('error', 'Silakan isi formulir terlebih dahulu');
        }

        /*
        |------------------------------------------------------------------
        | 🔒 VALIDASI JALUR
        |------------------------------------------------------------------
        */
        if ($pendaftaran->jalur !== $jalur) {
            return redirect()->route(
                'siswa.upload.berkas',
                $pendaftaran->jalur
            );
        }

        /*
        |------------------------------------------------------------------
        | 🔥 SET LAST STEP (JIKA BELUM ADA)
        |------------------------------------------------------------------
        */
        if (empty($pendaftaran->last_step)) {
            $pendaftaran->update([
                'last_step' => 'berkas'
            ]);
        }

        // ❗ TIDAK PERLU FLOW PROTECTION DI SINI
        // karena sudah ditangani middleware

        return view('ppdb.berkas.index', compact('jalur', 'pendaftaran'));
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN BERKAS
    |--------------------------------------------------------------------------
    */
    public function store(Request $request, $jalur)
{
    $user = Auth::guard('ppdb')->user();

    if (!$user) {
        return redirect()->route('ppdb.login');
    }

    $pendaftaran = Pendaftaran::where('user_id', $user->id)
        ->where('jalur', $jalur)
        ->latest()
        ->first();

    if (!$pendaftaran) {
        return redirect()->route('siswa.pendaftaran', $jalur)
            ->with('error', 'Isi formulir terlebih dahulu');
    }

    // 🔒 LOCK SYSTEM
    if ($pendaftaran->status !== 'form_selesai' && !$pendaftaran->is_revisi) {
        return redirect()->route('siswa.verifikasi', $jalur)
            ->with('error', 'Berkas sudah dikunci');
    }

    /*
    |--------------------------------------------------------------------------
    | 🔥 FILE MAPPING (SATU KALI SAJA)
    |--------------------------------------------------------------------------
    */
    $files = [
        'akta' => 'akta_lahir',
        'kk' => 'kartu_keluarga',
        'rapor' => 'rapor',
        'bukti_pd' => 'verifikasi_pd',
        'skl' => 'sk_sekolah',
    ];

    if ($jalur === 'prestasi') {
        $files['sertifikat'] = 'sertifikat_prestasi';
    }

    if ($jalur === 'afirmasi') {
        $files['kip'] = 'kip';
    }

    /*
    |--------------------------------------------------------------------------
    | 🔥 VALIDASI DINAMIS (INI YANG BENAR)
    |--------------------------------------------------------------------------
    */
    $rules = [];

    foreach ($files as $input => $column) {

        if (empty($pendaftaran->$column)) {
            $rules[$input] = 'required|file|mimes:jpg,jpeg,png,pdf|max:5120';
        } else {
            $rules[$input] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120';
        }
    }

    $request->validate($rules);

    /*
    |--------------------------------------------------------------------------
    | 🔥 UPLOAD FILE (TIDAK HAPUS JIKA TIDAK DIUPLOAD)
    |--------------------------------------------------------------------------
    */
    $data = [];

    foreach ($files as $input => $column) {

        if ($request->hasFile($input)) {

            // hapus file lama jika ada
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
    | 🔥 UPDATE STATUS
    |--------------------------------------------------------------------------
    */
   $pendaftaran->update(array_merge($data, [
    'status' => 'berkas_selesai',
    'last_step' => 'verifikasi',

    // 🔥 RESET WAJIB
    'is_publish' => 0,
    'email_status' => null,

    // opsional
    'catatan' => null,
]));
$pendaftaran->refresh();

    return redirect()
        ->route('siswa.verifikasi', $jalur)
        ->with('success', 'Berkas berhasil diupload');
}
}