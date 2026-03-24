<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// 🔥 MODEL
use App\Models\Pendaftaran;
use App\Models\MasterPpdb;
use App\Models\DetailPendaftaran;

class PendaftaranController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | FORM PENDAFTARAN
    |--------------------------------------------------------------------------
    */
    public function index($jalur)
    {
        $user = Auth::guard('ppdb')->user();

        if (!$user) {
            return redirect()->route('ppdb.login');
        }

        $pendaftaran = Pendaftaran::where('user_id', $user->id)->first();

        // 🔥 FLOW PROTECTION
        if ($pendaftaran) {

            if ($pendaftaran->status === 'form_selesai') {
                return redirect()->route('siswa.upload.berkas', $pendaftaran->jalur);
            }

            if ($pendaftaran->status === 'berkas_selesai') {
                return redirect()->route('siswa.verifikasi', $pendaftaran->jalur);
            }

            if ($pendaftaran->status === 'verifikasi') {
                return redirect()->route('siswa.verifikasi', $pendaftaran->jalur);
            }

            if ($pendaftaran->status === 'pengumuman') {
                return redirect()->route('siswa.pengumuman', $pendaftaran->jalur);
            }
        }

        // 🔥 ambil syarat dari admin
        $ppdb = MasterPpdb::aktifWithRelasi();
        $syarats = $ppdb?->syarats ?? collect();

        return view('ppdb.pendaftaran.index', compact(
            'jalur',
            'user',
            'syarats'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN DATA FORM + SYARAT
    |--------------------------------------------------------------------------
    */
    public function store(Request $request, $jalur)
    {
        $user = Auth::guard('ppdb')->user();

        if (!$user) {
            return redirect()->route('ppdb.login');
        }

        $pendaftaran = Pendaftaran::where('user_id', $user->id)->first();

        // 🔒 LOCK
        if ($pendaftaran && !$pendaftaran->is_revisi) {
            return redirect()->route('siswa.upload.berkas', $pendaftaran->jalur)
                ->with('error', 'Data sudah dikunci dan tidak bisa diubah');
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI UTAMA
        |--------------------------------------------------------------------------
        */
        $request->validate([
            'ttl' => 'required',
            'asal_sekolah' => 'required',
            'alamat' => 'required',
            'nama_ortu' => 'required',
            'pekerjaan_ortu' => 'required',
            'penghasilan_ortu' => 'required',
            'alamat_ortu' => 'required',
            'jumlah_saudara' => 'required|integer'
        ]);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATA PENDAFTARAN
        |--------------------------------------------------------------------------
        */
        $data = [
            'user_id' => $user->id,
            'jalur' => $jalur,

            'nama_lengkap' => $user->nama,
            'nisn' => $user->nisn,

            'ttl' => $request->ttl,
            'asal_sekolah' => $request->asal_sekolah,
            'alamat' => $request->alamat,
            'nama_ortu' => $request->nama_ortu,
            'pekerjaan_ortu' => $request->pekerjaan_ortu,
            'penghasilan_ortu' => $request->penghasilan_ortu,
            'alamat_ortu' => $request->alamat_ortu,
            'jumlah_saudara' => $request->jumlah_saudara,

            'status' => 'form_selesai',
            'last_step' => 'berkas'
        ];

        if ($pendaftaran) {
            $pendaftaran->update($data);
        } else {
            $pendaftaran = Pendaftaran::create($data);
        }

        /*
        |--------------------------------------------------------------------------
        | 🔥 SIMPAN SYARAT DINAMIS
        |--------------------------------------------------------------------------
        */
        $ppdb = MasterPpdb::aktifWithRelasi();
        $syarats = $ppdb?->syarats ?? [];

        foreach ($syarats as $syarat) {

            // 🔥 ambil value dari input
            $value = $request->input('syarat_' . $syarat->id);

            // 🔥 skip kalau kosong
            if (!$value) continue;

            DetailPendaftaran::updateOrCreate(
                [
                    'pendaftaran_id' => $pendaftaran->id,
                    'syarat_id' => $syarat->id,
                ],
                [
                    'value' => $syarat->tipe === 'teks' ? $value : null,
                    'file' => null, // upload nanti di step berkas
                ]
            );
        }

        return redirect()->route('siswa.upload.berkas', $jalur)
            ->with('success', 'Data formulir berhasil disimpan');
    }


    /*
    |--------------------------------------------------------------------------
    | FORM (ALT METHOD)
    |--------------------------------------------------------------------------
    */
    public function create($jalur)
    {
        $ppdb = MasterPpdb::aktifWithRelasi();

        return view('ppdb.pendaftaran.isi-formulir', [
            'jalur' => $jalur,
            'syarats' => $ppdb?->syarats ?? collect()
        ]);
    }
}