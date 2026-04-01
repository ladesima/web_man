<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class DataPendaftarController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendaftaran::query();

        // 🔍 SEARCH
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('nisn', 'like', '%' . $request->search . '%');
            });
        }

        // FILTER JALUR
        if ($request->jalur) {
            $query->where('jalur', $request->jalur);
        }

        // FILTER GELOMBANG
        if ($request->gelombang) {
            $query->where('gelombang', $request->gelombang);
        }

        // FILTER STATUS
        if ($request->status) {
            if ($request->status == 'belum') {
                $query->whereIn('status', ['belum', 'form_selesai']);
            } else {
                $query->where('status', $request->status);
            }
        }

        // FILTER WAKTU
        if ($request->waktu) {
            if ($request->waktu == 'hari_ini') {
                $query->whereDate('created_at', today());
            } elseif ($request->waktu == 'minggu_ini') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($request->waktu == 'bulan_ini') {
                $query->whereMonth('created_at', now()->month);
            }
        }

        $pendaftaran = $query->latest()->get();

        return view('panitia.data-pendaftar.index', compact('pendaftaran'));
    }
}