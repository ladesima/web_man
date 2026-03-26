<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;

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
    |--------------------------------------------------------------------------|
    | 🔥 SIMPAN VALIDASI (FIX CATATAN)
    |--------------------------------------------------------------------------|
    */
    public function simpanValidasi(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $data = $request->verifikasi ?? [];

        $finalStatus = 'lulus';
        $catatanGlobal = [];

        foreach ($data as $key => $item) {

            $status = $item['status'] ?? null;
            $catatan = $item['catatan'] ?? null;

            // 🔥 VALIDASI jika ditolak wajib isi catatan
            if ($status === 'no') {

                if (empty($catatan)) {
                    return back()->with('error', 'Catatan wajib diisi jika ada dokumen ditolak');
                }

                $finalStatus = 'perbaikan';
            }

            // 🔥 KUMPULKAN CATATAN GLOBAL
            if (!empty($catatan)) {
                $catatanGlobal[] =
                    strtoupper(str_replace('_', ' ', $key)) . ' : ' . $catatan;
            }
        }

        // 🔥 SIMPAN JSON DETAIL
        $pendaftaran->verifikasi_dokumen = json_encode($data);

        // 🔥 STATUS OTOMATIS
        $pendaftaran->status = $finalStatus;

        // 🔥 INI YANG PENTING (SYNC KE DATABASE)
        $pendaftaran->catatan_revisi = count($catatanGlobal)
            ? implode(' | ', $catatanGlobal)
            : null;

        $pendaftaran->last_step = 'verifikasi';

        $pendaftaran->save();

        return redirect()
            ->route('admin.operasional.verifikasi')
            ->with('success', 'Verifikasi berhasil disimpan');
    }

    public function lulus($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $pendaftaran->update([
            'status' => 'lulus',
            'last_step' => 'pengumuman',
            'is_revisi' => false,
            'catatan_revisi' => null
        ]);

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

        return back()->with('info', 'Data berhasil direset');
    }
    public function simpan(Request $request, $id)
{
    $pendaftaran = Pendaftaran::findOrFail($id);

    $data = $request->verifikasi;

    // VALIDASI: jika ada "no" wajib catatan
    foreach ($data as $key => $item) {
        if (($item['status'] ?? null) === 'no' && empty($item['catatan'])) {
            return back()->withErrors([
                'error' => 'Catatan wajib diisi jika tidak approve'
            ]);
        }
    }

    // SIMPAN JSON
    $pendaftaran->verifikasi_dokumen = json_encode($data);

    // LOGIKA STATUS
    $allOk = collect($data)->every(fn($d) => ($d['status'] ?? '') === 'ok');

    if ($allOk) {
        $pendaftaran->status = 'lulus';
    } else {
        $pendaftaran->status = 'perbaikan';
    }

    $pendaftaran->save();

    return redirect()
        ->route('admin.operasional.verifikasi.show', $id)
        ->with('success', 'Verifikasi berhasil disimpan');
}
}