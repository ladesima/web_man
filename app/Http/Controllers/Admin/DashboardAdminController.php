<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 🔥 TOTAL
        $total = Pendaftaran::count();

        // 🔥 HARI INI
        $hariIni = Pendaftaran::whereDate('created_at', Carbon::today())->count();

        // 🔥 PERLU VERIFIKASI
        $perluVerifikasi = Pendaftaran::where('status', 'berkas_selesai')->count();

        // 🔥 STAT JALUR
        $prestasi = Pendaftaran::where('jalur', 'prestasi')->count();
        $reguler = Pendaftaran::where('jalur', 'reguler')->count();
        $afirmasi = Pendaftaran::where('jalur', 'afirmasi')->count();

        // 🔥 DATA GRAFIK (7 hari terakhir)
        $grafik = collect(range(6, 0))->map(function ($i) {
            return [
                'tanggal' => now()->subDays($i)->format('Y-m-d'),
                'label' => now()->subDays($i)->translatedFormat('l'),
                'prestasi' => Pendaftaran::where('jalur', 'prestasi')
                    ->whereDate('created_at', now()->subDays($i))
                    ->count(),
                'reguler' => Pendaftaran::where('jalur', 'reguler')
                    ->whereDate('created_at', now()->subDays($i))
                    ->count(),
                'afirmasi' => Pendaftaran::where('jalur', 'afirmasi')
                    ->whereDate('created_at', now()->subDays($i))
                    ->count(),
            ];
        });

        return view('admin.dashboard', compact(
            'total',
            'hariIni',
            'perluVerifikasi',
            'prestasi',
            'reguler',
            'afirmasi',
            'grafik'
        ));
    }
}