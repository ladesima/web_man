<?php

namespace App\Http\Controllers\Admin\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\PengumumanMail;

class PengumumanController extends Controller
{
public function index()
{
    $pendaftaran = Pendaftaran::with('user')->latest()->get();
 
        $rows = $pendaftaran->map(function ($item) {
 
    // =========================
    // 🎯 HITUNG NILAI (FIX ALL JALUR)
    // =========================
    $nilaiTotal = null;
    $statusNilai = null;
 
    if ($item->nilai_rapor !== null) {
 
        // 🔥 beda per jalur
        if ($item->jalur == 'prestasi') {
            if ($item->nilai_prestasi !== null) {
                $nilaiTotal = round(($item->nilai_rapor + $item->nilai_prestasi) / 2);
            }
        } else {
            // ✅ reguler & afirmasi
            $nilaiTotal = $item->nilai_rapor;
        }
 
        // 🔥 tentukan status nilai
        if ($nilaiTotal !== null) {
            if ($nilaiTotal >= 80) {
                $statusNilai = 'valid';
            } elseif ($nilaiTotal >= 75) {
                $statusNilai = 'memenuhi';
            } else {
                $statusNilai = 'kurang';
            }
        }
    }
 
    // =========================
    // 📂 STATUS BERKAS (AMBIL DARI VERIFIKASI)
    // =========================
    // =========================
// 📂 STATUS BERKAS (REAL DARI JSON)
// =========================
$statusBerkas = 'tidak_valid';
 
if ($item->verifikasi_dokumen) {
 
    $dok = is_array($item->verifikasi_dokumen)
        ? $item->verifikasi_dokumen
        : json_decode($item->verifikasi_dokumen, true);
 
    if (is_array($dok)) {
 
        $statuses = collect($dok)->pluck('status');
 
        $allOk = $statuses->every(fn($s) => $s === 'ok');
        $allNo = $statuses->every(fn($s) => $s === 'no');
 
        if ($allOk) {
            $statusBerkas = 'valid';
        } elseif ($allNo) {
            $statusBerkas = 'tidak_valid';
        } else {
            $statusBerkas = 'perbaikan';
        }
    }
}
 
    // =========================
    // 🧠 HASIL AKHIR (FIX LOGIKA)
    // =========================
    $hasil = '-';
 
    if ($statusBerkas == 'valid' && in_array($statusNilai, ['valid', 'memenuhi'])) {
        $hasil = 'Lulus';
    }
    elseif ($statusBerkas == 'perbaikan') {
        $hasil = 'Perbaikan';
    }
    elseif ($statusBerkas == 'tidak_valid' && in_array($statusNilai, ['valid', 'memenuhi'])) {
        $hasil = 'Perbaikan';
    }
    elseif ($statusNilai == 'kurang') {
        $hasil = 'Tidak Lulus';
    }
    elseif ($statusBerkas == 'tidak_valid' && $statusNilai === null) {
        $hasil = 'Tidak Lulus';
    }
 
 
// =========================
// 📊 STATUS VERIFIKASI (SYNC DENGAN VERIFIKASI BERKAS)
// =========================
 
// 🔥 LANGSUNG DARI STATUS DB — sama persis dengan VerifikasiController
$status_verifikasi = match ($item->status) {
    'belum', 'form_selesai'  => 'Menunggu',
    'berkas_selesai'          => 'Siap Seleksi',
    'perbaikan'               => 'Perlu Perbaikan',
    'lulus'                   => 'Berkas Valid',
    'tidak_lulus'             => 'Berkas Ditolak',
    default                   => 'Menunggu',  // ✅ tidak pernah null
};
 
// 🔥 FALLBACK: jika status DB belum/null, pakai hasil verifikasi dokumen
if (in_array($item->status, ['belum', null]) && $item->verifikasi_dokumen) {
 
    $status_verifikasi = match ($statusBerkas) {
        'valid' => 'Berkas Valid',
        'perbaikan' => 'Perlu Perbaikan',
        'tidak_valid' => 'Berkas Ditolak',
        default => 'Menunggu',
    };
}
 
    return [
        'id' => $item->id,
        'nama' => $item->nama_lengkap ?? '-',
        'no' => $item->id ?? '-',
        'jalur' => $item->jalur ?? '-',
 
        'nilai_total' => $nilaiTotal,
        'status_nilai' => $statusNilai,
        'status_berkas' => $statusBerkas,
 
        'status_verifikasi' => $status_verifikasi,
        'hasil' => $hasil,
 
        'status_pub' => $item->is_publish ? 'publish' : 'belum',
        'status_email' => $item->email_status ?? 'belum_terkirim',
 
        'tgl' => $item->updated_at
            ? Carbon::parse($item->updated_at)->format('d/m/y - H:i') . ' WITA'
            : '-',
 
        'checked' => false
    ];
});
$rows = $rows->sortByDesc('nilai_total')->values();
 
        $total = $rows->count();
        $lulus = $rows->where('hasil', 'Lulus')->count();
        $tidak_lulus = $rows->where('hasil', 'Tidak Lulus')->count();
        $perbaikan = $rows->where('hasil', 'Perbaikan')->count();
 
        $siap_diumumkan = $rows
            ->where('status_email', 'belum_terkirim')
            ->count();
 
    return view('admin.ppdb.operasional.pengumuman.index', compact(
        'rows',
        'total',
        'lulus',
        'tidak_lulus',
        'perbaikan',
        'siap_diumumkan' // 👈 WAJIB TAMBAH
    ));
}

    public function publishMassal(Request $request)
{
    $ids = $request->ids ?? [];

    $data = Pendaftaran::with('user')
    ->whereIn('id', $ids)
    ->where('email_status', 'belum_terkirim')
    ->get();


    $success = 0;
    $failed = 0;
    $invalidEmails = [];

    foreach ($data as $item) {

    $email = optional($item->user)->email;

    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $failed++;
        $invalidEmails[] = $email;
        continue;
    }

    $template = EmailTemplate::where('penerima', match ($item->status) {
        'lulus' => 'lulus',
        'perbaikan', 'form_selesai' => 'perlu_perbaikan',
        'berkas_selesai' => 'tidak_lulus',
        default => 'semua'
    })->first();

    if (!$template) {
        $failed++;
        continue;
    }

    try {
        $isi = str_replace('{nama}', $item->nama_lengkap, $template->isi);

        Mail::send([], [], function ($message) use ($email, $template, $isi) {
            $message->to($email)
                    ->subject($template->judul)
                    ->html($isi);
        });

        $item->update([
            'is_publish' => 1,
            'email_status' => 'terkirim',
            'last_step' => 'pengumuman'
        ]);

        $success++;

    } catch (\Exception $e) {
        \Log::error('EMAIL GAGAL: '.$e->getMessage());
        $failed++;
    }
}

    return response()->json([
        'success' => true,
        'success_count' => $success,
        'failed_count' => $failed,
        'invalid' => $invalidEmails
    ]);
}
    public function review()
{
    $pesanList = EmailTemplate::get()->map(function ($item) {
        return [
            'id' => $item->id,
            'judul' => $item->judul,
            'preview' => substr(strip_tags($item->isi), 0, 80),
            'isi' => $item->isi
        ];
    });

    return view('admin.ppdb.operasional.pengumuman.review', compact('pesanList'));
}
public function publish(Request $request)
{
    $ids = $request->ids ?? [];

    $data = Pendaftaran::with('user')
        ->whereIn('id', $ids)
        ->get();

    $success = 0;
    $failed = 0;
    $invalidEmails = [];

    foreach ($data as $item) {

        // ✅ ambil email dari tabel ppdb_users
        $email = optional($item->user)->email;

        // ✅ validasi email
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $failed++;
            $invalidEmails[] = $email;
            continue;
        }

        // ✅ mapping status → template
        $penerima = match ($item->status) {
            'lulus' => 'lulus',
            'perbaikan', 'form_selesai' => 'perlu_perbaikan',
            'berkas_selesai' => 'tidak_lulus',
            default => 'semua'
        };

        // ✅ ambil template
        $template = EmailTemplate::where('penerima', $penerima)->first();

        if (!$template) {
            $failed++;
            continue;
        }

        try {

            // ✅ inject nama ke template
            $isi = str_replace('{nama}', $item->nama_lengkap, $template->isi);

            // ✅ kirim email
            Mail::send([], [], function ($message) use ($email, $template, $isi) {
                $message->to($email)
                        ->subject($template->judul)
                        ->html($isi);
            });

            // ✅ update status
            $item->update([
                'is_publish' => 1,
                'email_status' => 'terkirim',
                'last_step' => 'pengumuman'
            ]);

            $success++;

        } catch (\Exception $e) {
            \Log::error('EMAIL GAGAL: '.$e->getMessage());
            $failed++;
        }
    }

    return response()->json([
        'success' => true,
        'total' => $success,
        'failed' => $failed,
        'invalid' => $invalidEmails
    ]);
}
public function store(Request $request)
{
    $request->validate([
        'judul' => 'required|string|max:255',
        'isi' => 'required',
        'penerima' => 'required|in:lulus,tidak_lulus,perlu_perbaikan,semua',
    ]);

    EmailTemplate::create([
        'judul' => $request->judul,
        'isi' => $request->isi,
        'penerima' => $request->penerima,
    ]);

    return redirect()
        ->route('admin.operasional.pengumuman.review')
        ->with('success', 'Pesan berhasil disimpan');
}
public function editTemplate($id)
{
    $template = \App\Models\EmailTemplate::findOrFail($id);

    return view('admin.ppdb.operasional.pengumuman.edit-template', compact('template'));
}
public function updateTemplate(Request $request, $id)
{
    \DB::table('email_templates')->where('id', $id)->update([
        'judul' => $request->judul,
        'isi' => $request->isi,
        'penerima' => $request->penerima, // 🔥 WAJIB
        'updated_at' => now()
    ]);

    return redirect()
        ->route('admin.operasional.pengumuman.review')
        ->with('success', 'Template berhasil diupdate');
}

public function destroyTemplate($id)
{
    try {
        $template = \App\Models\EmailTemplate::find($id);

        if (!$template) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $template->delete();

        return response()->json([
            'success' => true
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}
}