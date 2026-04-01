<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function index()
    {
        $data = Pendaftaran::latest()->get();

        $pendaftar = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama_lengkap ?? '-',
                'no' => $item->nisn ?? '-',
                'jalur' => ucfirst($item->jalur),

                'status' => match ($item->status) {
                    'belum', 'form_selesai' => 'menunggu',
                    'perbaikan' => 'perlu_perbaikan',
                    'berkas_selesai' => 'siap_seleksi',
                    'lulus' => 'berkas_valid',
                    'tidak_lulus' => 'berkas_ditolak',
                    default => 'menunggu',
                },

                'catatan' => $item->catatan_revisi ?? '-',
                'checked' => false,
            ];
        });

        $counts = [
            'menunggu' => $data->whereIn('status', ['belum', 'form_selesai'])->count(),
            'perlu_perbaikan' => $data->where('status', 'perbaikan')->count(),
            'berkas_valid' => $data->where('status', 'lulus')->count(),
            'berkas_ditolak' => $data->where('status', 'tidak_lulus')->count(),
        ];

        return view('panitia.operasional.verifikasi.index', compact('pendaftar', 'counts'));
    }
    public function show($id)
{
    $pendaftaran = \App\Models\Pendaftaran::with('user')->findOrFail($id);

    return view('panitia.operasional.verifikasi.detail', compact('pendaftaran'));
}
public function validasi($id)
{
    $pendaftaran = \App\Models\Pendaftaran::findOrFail($id);

    // 🔥 DATA VERIFIKASI (JSON)
    $verifikasi = json_decode($pendaftaran->verifikasi_dokumen ?? '{}', true);

    // 🔥 DAFTAR DOKUMEN (WAJIB ADA)
    $dokumen = [
        [
            'nama' => 'akta_lahir',
            'label' => 'Akta Kelahiran',
            'file' => $pendaftaran->akta_lahir
        ],
        [
            'nama' => 'kartu_keluarga',
            'label' => 'Kartu Keluarga',
            'file' => $pendaftaran->kartu_keluarga
        ],
        [
            'nama' => 'rapor',
            'label' => 'Rapor',
            'file' => $pendaftaran->rapor
        ],
        [
            'nama' => 'verifikasi_pd',
            'label' => 'Verifikasi PD',
            'file' => $pendaftaran->verifikasi_pd
        ],
        [
            'nama' => 'sertifikat_prestasi',
            'label' => 'Sertifikat Prestasi',
            'file' => $pendaftaran->sertifikat_prestasi
        ],
        [
            'nama' => 'sk_sekolah',
            'label' => 'SK Sekolah',
            'file' => $pendaftaran->sk_sekolah
        ],
    ];

    return view('panitia.operasional.verifikasi.validasi', compact(
        'pendaftaran',
        'dokumen',
        'verifikasi'
    ));
}
public function simpanValidasi(Request $request, $id)
{
    $pendaftaran = \App\Models\Pendaftaran::findOrFail($id);

    $input = $request->verifikasi ?? [];
    $hasil = [];

    $total = count($input);
    $valid = 0;
    $ditolak = 0;
    $perbaikan = 0;

    foreach ($input as $key => $dok) {

        $status = $dok['status'] ?? null;
        $catatan = $dok['catatan'] ?? null;

        // 🔥 RULE UTAMA
        if ($status === 'ok') {
            // ✅ kalau approved → catatan DIHAPUS
            $hasil[$key] = [
                'status' => 'ok',
                'catatan' => null
            ];
            $valid++;
        }

        elseif ($status === 'no') {

            if (!empty($catatan)) {
                // ✅ ada catatan → perbaikan
                $hasil[$key] = [
                    'status' => 'no',
                    'catatan' => $catatan
                ];
                $perbaikan++;
            } else {
                // ❌ tidak ada catatan → ditolak
                $hasil[$key] = [
                    'status' => 'no',
                    'catatan' => null
                ];
                $ditolak++;
            }
        }
    }

    // 🔥 SIMPAN JSON
    $pendaftaran->verifikasi_dokumen = json_encode($hasil);

    // 🔥 STATUS GLOBAL
    if ($valid === $total) {
        $pendaftaran->status = 'lulus';
    } elseif ($ditolak === $total) {
        $pendaftaran->status = 'tidak_lulus';
    } else {
        $pendaftaran->status = 'perbaikan';
    }

    $pendaftaran->save();

    return redirect()
        ->route('panitia.operasional.verifikasi', $id)
        ->with('success', 'Verifikasi berhasil disimpan');
}
}