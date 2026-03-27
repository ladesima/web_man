<?php

namespace App\Http\Controllers\Admin\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class KelolaPengumumanPpdbController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendaftaran::with('user');

        // ===================== SEARCH =====================
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nisn', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($qq) use ($request) {
                      $qq->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // ===================== FILTER JALUR =====================
        if ($request->jalur) {
            $query->where('jalur', $request->jalur);
        }

        // ===================== FILTER STATUS =====================
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // ===================== DATA (FIXED) =====================
        $data = $query->latest()->get();

       $rows = $data->map(function ($item) {
    return [
        'id' => (int) $item->id,
        'nama' => (string) (optional($item->user)->name ?? '-'),
        'no' => (string) ($item->nisn ?? '-'),
        'jalur' => (string) ucfirst($item->jalur ?? '-'),
        'hasil' => (string) (
            $item->status === 'diterima'
                ? 'Lulus'
                : ($item->status === 'perbaikan' ? 'Perbaikan' : 'Tidak Lulus')
        ),
        'status_pub' => $item->is_publish ? 'publish' : 'belum',
        'status_email' => (string) ($item->email_status ?? 'belum_terkirim'),
        'tgl' => $item->updated_at
            ? $item->updated_at->format('d-m-Y H:i') . ' WITA'
            : '-',
        'checked' => false,
    ];
})->values()->toArray(); // 🔥 WAJIB

        return view('admin.ppdb.operasional.pengumuman.index', [
            'rows' => $rows
        ]);
    }

    // ===================== PUBLISH MASSAL =====================
    public function publish(Request $request)
    {
        $ids = $request->ids ?? [];

        if (count($ids) > 0) {
            Pendaftaran::whereIn('id', $ids)->update([
                'is_publish' => 1
            ]);
        }

        return back()->with('success', 'Pengumuman berhasil dipublish');
    }

    // ===================== KIRIM EMAIL MASSAL =====================
    public function kirimEmail(Request $request)
    {
        $ids = $request->ids ?? [];

        if (count($ids) > 0) {
            Pendaftaran::whereIn('id', $ids)->update([
                'email_status' => 'terkirim'
            ]);
        }

        return back()->with('success', 'Email berhasil dikirim');
    }
}