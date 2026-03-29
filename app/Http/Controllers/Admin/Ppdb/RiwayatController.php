<?php

namespace App\Http\Controllers\Admin\Ppdb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aktivitas;
use Carbon\Carbon;

class RiwayatController extends Controller
{
    public function index()
    {
        $rows = Aktivitas::with('panitia')
            ->orderByDesc('waktu') // 🔥 lebih tepat dari latest()
            ->get()
            ->map(function ($item) {

                return [
                    'id' => $item->id,

                    // 🔥 aman kalau relasi null
                    'nama' => optional($item->panitia)->nama ?? '-',
                    'email' => optional($item->panitia)->email ?? '-',

                    'aktivitas' => $item->aktivitas ?? '-',

                    // 🔥 pakai cast (lebih clean)
                    'waktu' => $item->waktu
    ? Carbon::parse($item->waktu)->format('d-m-Y H:i') . ' WITA'
    : '-',
                ];
            })
            ->values();

        return view('admin.ppdb.manajemen.riwayat-aktivitas', compact('rows'));
    }

    public function destroy($id)
    {
        $data = Aktivitas::find($id);

        // 🔥 prevent error kalau data tidak ada
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}