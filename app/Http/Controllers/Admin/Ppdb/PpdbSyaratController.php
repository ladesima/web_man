<?php

namespace App\Http\Controllers\Admin\Ppdb;

use App\Models\PpdbSyarat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PpdbSyaratController extends Controller
{
    /**
     * STORE (Tambah Syarat)
     */
    public function store(Request $request)
    {
        $request->validate([
            'master_id' => 'required|exists:master_ppdb,id',
            'nama' => 'required|string|max:255',
            'tipe' => 'required|string',
            'format' => 'nullable|string',
            'ukuran' => 'nullable|string',
            'kebutuhan' => 'required|in:wajib,opsional',
        ]);

        PpdbSyarat::create($request->all());

        return redirect()->back()->with('success', 'Syarat berhasil ditambahkan');
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|string',
            'format' => 'nullable|string',
            'ukuran' => 'nullable|string',
            'kebutuhan' => 'required|in:wajib,opsional',
        ]);

        $syarat = PpdbSyarat::findOrFail($id);

        $syarat->update($request->all());

        return redirect()->back()->with('success', 'Syarat berhasil diupdate');
    }

    /**
     * DELETE
     */
    public function destroy($id)
    {
        $syarat = PpdbSyarat::findOrFail($id);
        $syarat->delete();

        return redirect()->back()->with('success', 'Syarat berhasil dihapus');
    }
}