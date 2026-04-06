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
                'no' => $item->nisn,
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
    $pendaftaran = Pendaftaran::findOrFail($id);

        $data = $request->verifikasi ?? [];

        $catatanGlobal = [];

        foreach ($data as $key => $item) {

            $status = $item['status'] ?? null;
            $catatan = $item['catatan'] ?? null;

            // ❗ wajib isi catatan jika ditolak
            if ($status === 'no' && empty($catatan)) {
                return back()->with('error', 'Catatan wajib diisi jika ada dokumen ditolak');
            }

            if (!empty($catatan)) {
                $catatanGlobal[] =
                    strtoupper(str_replace('_', ' ', $key)) . ' : ' . $catatan;
            }
        }

        // 🔥 SIMPAN JSON
        $pendaftaran->verifikasi_dokumen = json_encode($data);

        // 🔥 LOGIC STATUS BARU
        $statuses = collect($data)->pluck('status');

        $allOk = $statuses->every(fn($s) => $s === 'ok');
        $allNo = $statuses->every(fn($s) => $s === 'no');

        if ($allOk) {
            $finalStatus = 'lulus';

        } elseif ($allNo) {
            $finalStatus = 'tidak_lulus';

        } else {
            $finalStatus = 'perbaikan';
        }

        $pendaftaran->status = $finalStatus;

        // 🔥 CATATAN GLOBAL
        $pendaftaran->catatan_revisi = count($catatanGlobal)
            ? implode(' | ', $catatanGlobal)
            : null;

        $pendaftaran->last_step = 'verifikasi';

        $pendaftaran->save();

        logAktivitas('Verifikasi data pendaftar: ' . $pendaftaran->nama_lengkap);

    return redirect()
        ->route('panitia.operasional.verifikasi', $id)
        ->with('success', 'Verifikasi berhasil disimpan');
}
}