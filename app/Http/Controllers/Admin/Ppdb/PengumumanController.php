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
    $pendaftaran = Pendaftaran::latest()->get();

    $rows = $pendaftaran->map(function ($item) {

        // 🔥 MAP STATUS DB → UI
        $status_verifikasi = match ($item->status) {
            'form_selesai' => 'Menunggu',
            'berkas_selesai' => 'Siap Seleksi',
            'perbaikan' => 'Perlu Perbaikan',
            'lulus' => 'Berkas Valid',
            default => 'Menunggu',
        };

        // 🔥 MAP KE HASIL SELEKSI
        $hasil = match ($item->status) {
    'lulus' => 'Lulus',

    // 🔥 semua selain lulus = tidak lulus
    'perbaikan' => 'Tidak Lulus',
    'form_selesai' => 'Tidak Lulus',
    'berkas_selesai' => 'Tidak Lulus',

    default => 'Tidak Lulus',
};

        return [
            'id' => $item->id,
            'nama' => $item->nama_lengkap ?? '-',
            'no' => $item->id ?? '-',
            'jalur' => $item->jalur ?? '-',

            'status_verifikasi' => $status_verifikasi,
            'hasil' => $hasil,

            'status_pub' => $item->is_publish ? 'publish' : 'belum',
            'status_email' => $item->email_status ?? 'belum_terkirim',

            'tgl' => $item->updated_at
                ? \Carbon\Carbon::parse($item->updated_at)->format('d/m/y - H:i') . ' WITA'
                : '-',

            'checked' => false
        ];
    });

    // =========================
    // 📊 STATISTIK
    // =========================
    $total = $rows->count();
    $lulus = $rows->where('hasil', 'Lulus')->count();
    $tidak_lulus = $rows->where('hasil', 'Tidak Lulus')->count();
    $perbaikan = $rows->where('hasil', 'Perbaikan')->count();

    // 🔥 INI YANG KAMU BUTUHKAN
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
                'email_status' => 'terkirim'
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