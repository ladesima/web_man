<?php

namespace App\Http\Controllers\Admin\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class DataPendaftarController extends Controller
{

public function index(Request $request)
{
    $query = Pendaftaran::with('user');

    // SEARCH
    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('nama_lengkap', 'like', '%'.$request->search.'%')
              ->orWhere('nisn', 'like', '%'.$request->search.'%');
        });
    }

    // JALUR
    if ($request->jalur) {
        $query->where('jalur', $request->jalur);
    }

    // ❗ GELombang (AMAN)
    if ($request->gelombang && \Schema::hasColumn('pendaftaran', 'gelombang')) {
        $query->where('gelombang', $request->gelombang);
    }

    // STATUS
    if ($request->status) {
        $query->where('status', $request->status);
    }

    // WAKTU
    if ($request->waktu) {
        if ($request->waktu == 'hari_ini') {
            $query->whereDate('created_at', now());
        } elseif ($request->waktu == 'minggu_ini') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($request->waktu == 'bulan_ini') {
            $query->whereMonth('created_at', now()->month);
        }
    }

    $pendaftaran = $query->latest()->get();

    return view('admin.ppdb.data-pendaftar.index', compact('pendaftaran'));
}

    public function show($id)
{
    $pendaftaran = Pendaftaran::with('user')->findOrFail($id);

    $verifikasi = $pendaftaran->verifikasi_dokumen 
        ? json_decode($pendaftaran->verifikasi_dokumen, true) 
        : [];

    return view('admin.ppdb.operasional.verifikasi.detail', compact('pendaftaran', 'verifikasi'));
}
}