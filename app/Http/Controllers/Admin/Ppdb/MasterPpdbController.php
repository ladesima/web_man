<?php

namespace App\Http\Controllers\Admin\Ppdb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MasterPpdb;
use App\Models\PpdbJalur;
use App\Models\PpdbTahapan;
use App\Models\PpdbSyarat;

class MasterPpdbController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST DATA
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $data = MasterPpdb::latest()->get();
        return view('admin.ppdb.master.index', compact('data'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE MASTER
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|unique:master_ppdb,tahun_ajaran',
            'gelombang' => 'required'
        ]);

        if ($request->is_active == 1) {
            MasterPpdb::query()->update(['is_active' => false]);
        }

        MasterPpdb::create([
            'tahun_ajaran' => $request->tahun_ajaran,
            'gelombang' => $request->gelombang,
            'is_active' => $request->is_active ?? 0
        ]);

        return redirect()->route('admin.master')
            ->with('success', 'Data berhasil disimpan');
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVATE
    |--------------------------------------------------------------------------
    */
    public function activate($id)
    {
        MasterPpdb::query()->update(['is_active' => false]);

        MasterPpdb::where('id', $id)->update([
            'is_active' => true
        ]);

        return redirect()->route('admin.master')
            ->with('success', 'Tahun ajar berhasil diaktifkan');
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL
    |--------------------------------------------------------------------------
    */
    public function detail($id)
    {
        $master = MasterPpdb::with([
            'jalurs.tahapans',
            'syarats'
        ])->findOrFail($id);

        $jalurs = $master->jalurs;

        return view('admin.ppdb.master.detail', compact('master', 'jalurs'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE JALUR
    |--------------------------------------------------------------------------
    */
    public function storeJalur(Request $request)
    {
        $request->validate([
            'master_id' => 'required|exists:master_ppdb,id',
            'jalur' => 'required',
            'gelombang' => 'required',
            'kuota' => 'required|integer'
        ]);

        $isActive = $request->status == 'aktif';

        if ($isActive) {
            PpdbJalur::where('master_ppdb_id', $request->master_id)
                ->where('jalur', $request->jalur)
                ->update(['is_active' => false]);
        }

        PpdbJalur::create([
            'master_ppdb_id' => $request->master_id,
            'jalur' => $request->jalur,
            'gelombang' => $request->gelombang,
            'kuota' => $request->kuota,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'is_active' => $isActive
        ]);

        return redirect('/admin/master-ppdb/' . $request->master_id)
            ->with('success', 'Jalur berhasil ditambahkan');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE JALUR
    |--------------------------------------------------------------------------
    */
    public function updateJalur(Request $request, $id)
    {
        $jalur = PpdbJalur::findOrFail($id);

        $request->validate([
            'jalur' => 'required',
            'gelombang' => 'required',
            'kuota' => 'required|integer'
        ]);

        $isActive = $request->status == 'aktif';

        if ($isActive) {
            PpdbJalur::where('master_ppdb_id', $jalur->master_ppdb_id)
                ->where('jalur', $request->jalur)
                ->where('id', '!=', $id)
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

        return redirect('/admin/master-ppdb/' . $jalur->master_ppdb_id)
            ->with('success', 'Jalur berhasil diupdate');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE TAHAPAN (FIX BUG 404)
    |--------------------------------------------------------------------------
    */
    public function storeTahapan(Request $request)
    {
        $request->validate([
            'jalur_id' => 'required|exists:ppdb_jalurs,id',
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

        // 🔥 AMBIL MASTER ID DENGAN AMAN
        $jalur = PpdbJalur::findOrFail($request->jalur_id);

        return redirect('/admin/master-ppdb/' . $jalur->master_ppdb_id)
            ->with('success', 'Tahapan berhasil ditambahkan');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE JALUR
    |--------------------------------------------------------------------------
    */
    public function deleteJalur($id)
    {
        $jalur = PpdbJalur::findOrFail($id);
        $masterId = $jalur->master_ppdb_id;

        $jalur->delete();

        return redirect('/admin/master-ppdb/' . $masterId)
            ->with('success', 'Jalur berhasil dihapus');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE TAHAPAN
    |--------------------------------------------------------------------------
    */
    public function deleteTahapan($id)
    {
        $tahapan = PpdbTahapan::findOrFail($id);
        $jalur = PpdbJalur::findOrFail($tahapan->jalur_id);

        $tahapan->delete();

        return redirect('/admin/master-ppdb/' . $jalur->master_ppdb_id)
            ->with('success', 'Tahapan berhasil dihapus');
    }
}