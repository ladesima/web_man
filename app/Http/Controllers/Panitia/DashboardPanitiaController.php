<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Carbon\Carbon;

class DashboardPanitiaController extends Controller
{
    public function index()
    {
        // ===================== TOTAL =====================
        $total = Pendaftaran::count();

        // ===================== HARI INI =====================
        $hariIni = Pendaftaran::whereDate('created_at', today())->count();

        // ===================== KEMARIN =====================
        $kemarin = Pendaftaran::whereDate('created_at', today()->subDay())->count();

        // ===================== SELISIH =====================
        $selisih = $hariIni - $kemarin;

        if ($selisih > 0) {
            $labelSelisih = '+' . $selisih;
        } elseif ($selisih < 0) {
            $labelSelisih = (string)$selisih;
        } else {
            $labelSelisih = '0';
        }

        // ===================== MENUNGGU VERIFIKASI =====================
        $perluVerifikasi = Pendaftaran::whereIn('status', ['belum', 'form_selesai'])->count();

        // ===================== 🔥 BERKAS VALID =====================
        $berkasValid = Pendaftaran::where('status', 'lulus')->count();

        // ===================== 🔥 PERLU PERBAIKAN =====================
        $perluPerbaikan = Pendaftaran::where('status', 'perbaikan')->count();

        // ===================== STAT JALUR =====================
        $prestasi = Pendaftaran::where('jalur', 'prestasi')->count();
        $reguler  = Pendaftaran::where('jalur', 'reguler')->count();
        $afirmasi = Pendaftaran::where('jalur', 'afirmasi')->count();

        // ===================== JALUR AKTIF =====================
        $jalurAktif = \App\Models\PpdbJalur::where('is_active', 1)->latest()->first();

        $namaJalur = '-';
        $gelombang = '-';
        $tanggal   = '-';

        if ($jalurAktif) {
            $namaJalur = ucfirst($jalurAktif->jalur);
            $gelombang = 'Gel ' . ($jalurAktif->gelombang ?? '-');

            if ($jalurAktif->tanggal_mulai && $jalurAktif->tanggal_selesai) {
                $tanggal = \Carbon\Carbon::parse($jalurAktif->tanggal_mulai)->translatedFormat('d F') .
                    ' - ' .
                    \Carbon\Carbon::parse($jalurAktif->tanggal_selesai)->translatedFormat('d F Y');
            }
        }

        // ===================== GRAFIK 7 HARI =====================
        $labels = [];
        $dataPrestasi = [];
        $dataReguler = [];
        $dataAfirmasi = [];

        for ($i = 6; $i >= 0; $i--) {
            $tanggalLoop = Carbon::today()->subDays($i);

            $labels[] = $tanggalLoop->translatedFormat('l');

            $dataPrestasi[] = Pendaftaran::where('jalur', 'prestasi')
                ->whereDate('created_at', $tanggalLoop)
                ->count();

            $dataReguler[] = Pendaftaran::where('jalur', 'reguler')
                ->whereDate('created_at', $tanggalLoop)
                ->count();

            $dataAfirmasi[] = Pendaftaran::where('jalur', 'afirmasi')
                ->whereDate('created_at', $tanggalLoop)
                ->count();
        }

        return view('panitia.dashboard', compact(
            'total',
            'hariIni',
            'labelSelisih',
            'perluVerifikasi',
            'berkasValid',        // 🔥 NEW
            'perluPerbaikan',     // 🔥 NEW
            'prestasi',
            'reguler',
            'afirmasi',
            'labels',
            'dataPrestasi',
            'dataReguler',
            'dataAfirmasi',
            'namaJalur',
            'gelombang',
            'tanggal'
        ));
    }
}