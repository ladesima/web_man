<?php

namespace App\Http\Controllers\Admin\Ppdb;

use App\Models\PpdbTahapan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TahapanController extends Controller
{
    /**
     * STORE (Tambah Timeline)
     */
    public function store(Request $request)
{
    $request->validate([
        'jalur_id' => 'required|exists:ppdb_jalurs,id',
        'nama_tahapan' => 'required',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date'
    ]);

    \App\Models\PpdbTahapan::create([
        'jalur_id' => $request->jalur_id,
        'nama_tahapan' => $request->nama_tahapan,
        'tanggal_mulai' => $request->tanggal_mulai,
        'tanggal_selesai' => $request->tanggal_selesai,
    ]);

    // 🔥 AMBIL MASTER ID
    $jalur = \App\Models\PpdbJalur::findOrFail($request->jalur_id);

    return redirect('/admin/master-ppdb/' . $jalur->master_ppdb_id)
        ->with('success', 'Tahapan berhasil ditambahkan');
}
    /**
     * UPDATE (Edit Timeline)
     */
    public function update(Request $request, $id)
{
    $tahapan = \App\Models\PpdbTahapan::findOrFail($id);

    $tahapan->update([
        'nama_tahapan' => $request->nama_tahapan,
        'tanggal_mulai' => $request->tanggal_mulai,
        'tanggal_selesai' => $request->tanggal_selesai,
    ]);

    $jalur = \App\Models\PpdbJalur::findOrFail($tahapan->jalur_id);

    return redirect('/admin/master-ppdb/' . $jalur->master_ppdb_id)
        ->with('success', 'Tahapan berhasil diupdate');
}

    /**
     * DELETE (Hapus Timeline)
     */
    public function destroy($id)
{
    $tahapan = \App\Models\PpdbTahapan::findOrFail($id);
    $jalur = \App\Models\PpdbJalur::findOrFail($tahapan->jalur_id);

    $tahapan->delete();

    return redirect('/admin/master-ppdb/' . $jalur->master_ppdb_id)
        ->with('success', 'Tahapan berhasil dihapus');
}
}