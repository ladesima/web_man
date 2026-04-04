<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;

class NilaiSeleksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendaftaran::query();

        // 🔍 SEARCH
        if ($request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nisn', 'like', '%' . $request->search . '%');
        }

        // 🔽 FILTER
        if ($request->jalur) {
            $query->where('jalur', $request->jalur);
        }

        if ($request->gelombang) {
            $query->where('gelombang', $request->gelombang);
        }

        // 🔽 AMBIL DATA
        $pesertas = $query->get()->map(function ($item) {

    $nilaiRapor = $item->nilai_rapor;
    $nilaiPrestasi = $item->nilai_prestasi;

    $nilaiTotal = null;
    if ($nilaiRapor !== null && $nilaiPrestasi !== null) {
        $nilaiTotal = round(($nilaiRapor + $nilaiPrestasi) / 2);
    }

    return [
        'id' => $item->id,
        'nama' => $item->nama_lengkap, // ✅ FIX
        'nisn' => $item->nisn,
        'sekolah' => $item->asal_sekolah,
        'jalur' => $item->jalur,
        'nilaiRapor' => $nilaiRapor,
        'nilaiPrestasi' => $nilaiPrestasi,
        'nilaiTotal' => $nilaiTotal,
    ];
});

        return view('panitia.seleksi.index', compact('pesertas'));
    }
    public function updateNilai(Request $request, $id)
{
    $request->validate([
        'nilai_rapor' => 'required|numeric|min:0|max:100',
        'nilai_prestasi' => 'required|numeric|min:0|max:100',
    ]);

    $pendaftaran = Pendaftaran::findOrFail($id);

    // simpan ke database
    $pendaftaran->update([
        'nilai_rapor' => $request->nilai_rapor,
        'nilai_prestasi' => $request->nilai_prestasi,
    ]);

    // hitung total
    $nilaiTotal = round(($request->nilai_rapor + $request->nilai_prestasi) / 2);

    return response()->json([
        'success' => true,
        'data' => [
            'nilai_rapor' => $request->nilai_rapor,
            'nilai_prestasi' => $request->nilai_prestasi,
            'nilai_total' => $nilaiTotal,
        ]
    ]);
}
}