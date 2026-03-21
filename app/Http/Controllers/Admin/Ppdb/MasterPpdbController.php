<?php

namespace App\Http\Controllers\Admin\Ppdb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// 🔥 MODEL
use App\Models\MasterPpdb;
use App\Models\PpdbJalur;
use App\Models\PpdbTahapan;
use App\Models\PpdbSyarat;

class MasterPpdbController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST DATA (HALAMAN MASTER)
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $data = MasterPpdb::orderBy('tahun_ajaran', 'desc')->get();

        return view('admin.ppdb.master.index', compact('data'));
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN TAHUN AJAR
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|unique:master_ppdb,tahun_ajaran',
            'gelombang' => 'required'
        ]);

        // 🔥 jika aktif → nonaktifkan semua
        if ($request->is_active == 1) {
            MasterPpdb::query()->update(['is_active' => false]);
        }

        MasterPpdb::create([
            'tahun_ajaran' => $request->tahun_ajaran,
            'is_active' => $request->is_active ?? 0,
            'gelombang' => $request->gelombang
        ]);

        return back()->with('success', 'Data berhasil disimpan');
    }

    /*
    |--------------------------------------------------------------------------
    | AKTIFKAN TAHUN AJAR
    |--------------------------------------------------------------------------
    */
    public function activate($id)
    {
        // 🔥 hanya boleh 1 aktif
        MasterPpdb::query()->update(['is_active' => false]);

        MasterPpdb::where('id', $id)->update([
            'is_active' => true
        ]);

        return back()->with('success', 'Tahun ajar berhasil diaktifkan');
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL MASTER → LIST JALUR
    |--------------------------------------------------------------------------
    */
   public function detail($id)
{
    $master = MasterPpdb::with([
        'jalurs.tahapans',
        'syarats' // 🔥 TAMBAH INI
    ])->findOrFail($id);

    $jalurs = $master->jalurs;

    return view('admin.ppdb.master.detail', compact('master', 'jalurs'));
}
/*
    |--------------------------------------------------------------------------
    | UPDATE JALUR
    |--------------------------------------------------------------------------
    */
public function updateJalur(Request $request, $id)
{
    $request->validate([
        'jalur' => 'required',
        'gelombang' => 'required',
        'kuota' => 'required|integer'
    ]);

    $jalur = PpdbJalur::findOrFail($id);

    $isActive = $request->status == 'aktif';

    // 🔥 FIX LOGIC
    if ($isActive) {
        PpdbJalur::where('master_ppdb_id', $jalur->master_ppdb_id)
            ->where('jalur', $request->jalur) // ✅ hanya jalur yang sama
            ->where('id', '!=', $id) // ✅ kecuali dirinya
            ->update(['is_active' => false]);
    }

    $jalur->update([
        'jalur' => $request->jalur,
        'gelombang' => $request->gelombang,
        'kuota' => $request->kuota,
        'tanggal_mulai' => $request->tanggal_mulai,
        'tanggal_selesai' => $request->tanggal_selesai,
        'is_active' => $isActive
    ]);

    return back()->with('success', 'Jalur berhasil diupdate');
}

    /*
    |--------------------------------------------------------------------------
    | SIMPAN JALUR
    |--------------------------------------------------------------------------
    */
    public function storeJalur(Request $request)
{
    $request->validate([
        'master_id' => 'required',
        'jalur' => 'required',
        'gelombang' => 'required',
        'kuota' => 'required|integer'
    ]);

    $isActive = $request->status == 'aktif';

    // 🔥 FIX LOGIC
    if ($isActive) {
        PpdbJalur::where('master_ppdb_id', $request->master_id)
            ->where('jalur', $request->jalur) // ✅ hanya jalur yang sama
            ->update(['is_active' => false]);
    }

    PpdbJalur::create([
        'master_ppdb_id' => $request->master_id,
        'jalur' => $request->jalur,
        'gelombang' => $request->gelombang,
        'kuota' => $request->kuota,

        'tanggal_mulai' => $request->tanggal_mulai 
            ? \Carbon\Carbon::parse($request->tanggal_mulai)->format('Y-m-d')
            : null,

        'tanggal_selesai' => $request->tanggal_selesai 
            ? \Carbon\Carbon::parse($request->tanggal_selesai)->format('Y-m-d')
            : null,

        'is_active' => $isActive
    ]);

    return back()->with('success', 'Jalur berhasil ditambahkan');
}

    /*
    |--------------------------------------------------------------------------
    | SIMPAN TAHAPAN (JADWAL)
    |--------------------------------------------------------------------------
    */
    public function storeTahapan(Request $request)
    {
        $request->validate([
            'jalur_id' => 'required',
            'nama_tahapan' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date'
        ]);

        PpdbTahapan::create([
            'jalur_id' => $request->jalur_id,
            'nama_tahapan' => $request->nama_tahapan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        return back()->with('success', 'Tahapan berhasil ditambahkan');
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS JALUR
    |--------------------------------------------------------------------------
    */
    public function deleteJalur($id)
    {
        PpdbJalur::findOrFail($id)->delete();

        return back()->with('success', 'Jalur berhasil dihapus');
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS TAHAPAN
    |--------------------------------------------------------------------------
    */
    public function deleteTahapan($id)
    {
        PpdbTahapan::findOrFail($id)->delete();

        return back()->with('success', 'Tahapan berhasil dihapus');
    }
}