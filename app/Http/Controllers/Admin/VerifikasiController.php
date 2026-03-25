<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;

class VerifikasiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST DATA VERIFIKASI (AMBIL DARI TABEL PENDAFTARAN)
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        // 🔥 ambil dari tabel pendaftaran
        $data = Pendaftaran::latest()->get();

        // 🔥 mapping ke format frontend (TIDAK UBAH UI)
        $pendaftar = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama_lengkap ?? '-', // 🔥 dari pendaftaran
                'no' => $item->nisn,
                'jalur' => ucfirst($item->jalur),

                // 🔥 mapping status ke UI
                'status' => match ($item->status) {
                    'belum' => 'menunggu',
                    'form_selesai' => 'menunggu',
                    'berkas_selesai' => 'siap_seleksi',
                    'perbaikan' => 'perlu_perbaikan',
                    'lulus' => 'berkas_valid',
                    'tidak_lulus' => 'berkas_ditolak',
                    default => 'menunggu',
                },

                'catatan' => $item->catatan_revisi ?? '-',
                'checked' => false,
            ];
        });

        // 🔥 HITUNG CARD (SESUAI STATUS PENDAFTARAN)
        $counts = [
            'menunggu' => $data->whereIn('status', ['belum', 'form_selesai'])->count(),
            'perlu_perbaikan' => $data->where('status', 'perbaikan')->count(),
            'berkas_valid' => $data->where('status', 'lulus')->count(),
            'berkas_ditolak' => $data->where('status', 'tidak_lulus')->count(),
        ];

        return view('admin.ppdb.operasional.verifikasi.index', [
            'pendaftar' => $pendaftar,
            'counts' => $counts
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL PESERTA (AMBIL DARI PENDAFTARAN)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        return view('admin.ppdb.operasional.verifikasi.detail', [
            'pendaftaran' => $pendaftaran
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDASI
    |--------------------------------------------------------------------------
    */
    public function validasi($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        return view('admin.ppdb.operasional.verifikasi.validasi', compact('pendaftaran'));
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
    | PERBAIKAN
    |--------------------------------------------------------------------------
    */
    public function perbaikan(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string|max:1000'
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
    | RESET
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
    public function simpanValidasi(Request $request, $id)
{
    $pendaftaran = Pendaftaran::findOrFail($id);

    $data = $request->verifikasi;

    $finalStatus = 'lulus'; // default

    foreach ($data as $key => $item) {
        if ($item['status'] === 'no') {

            if (empty($item['catatan'])) {
                return back()->with('error', 'Catatan wajib diisi jika ditolak');
            }

            $finalStatus = 'perbaikan';
        }
    }

    $pendaftaran->verifikasi_dokumen = json_encode($data);
    $pendaftaran->status = $finalStatus;
    $pendaftaran->save();

    return back()->with('success', 'Verifikasi berhasil disimpan');
}
}