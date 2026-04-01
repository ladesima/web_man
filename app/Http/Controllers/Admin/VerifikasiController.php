<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use function logAktivitas;

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

        return view('admin.ppdb.operasional.verifikasi.index', compact('pendaftar', 'counts'));
    }

    public function show($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        return view('admin.ppdb.operasional.verifikasi.detail', compact('pendaftaran'));
    }

    public function validasi($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        return view('admin.ppdb.operasional.verifikasi.validasi', compact('pendaftaran'));
    }

    /*
    |------------------------------------------------------------------
    | 🔥 SIMPAN VALIDASI FINAL (LOGIC BARU)
    |------------------------------------------------------------------
    */
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
            ->route('admin.operasional.verifikasi')
            ->with('success', 'Verifikasi berhasil disimpan');
    }

    /*
    |------------------------------------------------------------------
    | ACTION MANUAL
    |------------------------------------------------------------------
    */
    public function lulus($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $pendaftaran->update([
            'status' => 'lulus',
            'last_step' => 'pengumuman',
            'is_revisi' => false,
            'catatan_revisi' => null
        ]);

        logAktivitas('Meluluskan siswa: ' . $pendaftaran->nama_lengkap);

        return back()->with('success', 'Peserta dinyatakan LULUS');
    }

    public function tidakLulus($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $pendaftaran->update([
            'status' => 'tidak_lulus',
            'last_step' => 'pengumuman',
            'is_revisi' => false
        ]);

        logAktivitas('Menolak siswa: ' . $pendaftaran->nama_lengkap);

        return back()->with('error', 'Peserta dinyatakan TIDAK LULUS');
    }

    public function reset($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $pendaftaran->update([
            'status' => 'belum',
            'last_step' => 'form',
            'is_revisi' => false,
            'catatan_revisi' => null,
            'verifikasi_dokumen' => null
        ]);

        logAktivitas('Reset data pendaftar: ' . $pendaftaran->nama_lengkap);

        return back()->with('info', 'Data berhasil direset');
    }
}