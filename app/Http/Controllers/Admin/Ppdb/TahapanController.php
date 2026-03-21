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
            'nama_tahapan' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        PpdbTahapan::create([
    'jalur_id' => $request->jalur_id,
    'nama_tahapan' => $request->nama_tahapan,
    'tanggal_mulai' => $request->tanggal_mulai,
    'tanggal_selesai' => $request->tanggal_selesai,
]);

   return redirect()
    ->route('admin.master.detail', $request->jalur_id)
    ->with('success', 'Data berhasil ditambahkan');
    }
    /**
     * UPDATE (Edit Timeline)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_tahapan' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $tahapan = PpdbTahapan::findOrFail($id);

        $tahapan->update([
            'nama_tahapan' => $request->nama_tahapan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

       return redirect()
    ->route('admin.master.detail', $request->jalur_id)
    ->with('success', 'Data berhasil diupdate');
    }

    /**
     * DELETE (Hapus Timeline)
     */
    public function destroy($id)
    {
        $tahapan = PpdbTahapan::findOrFail($id);
        $tahapan->delete();

       return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}