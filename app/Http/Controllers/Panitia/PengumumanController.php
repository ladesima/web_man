<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Carbon\Carbon;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PengumumanController extends Controller
{
    public function index()
    {
        $pendaftaran = Pendaftaran::with('user')->latest()->get();

        $rows = $pendaftaran->map(function ($item) {

    // =========================
    // 🎯 HITUNG NILAI
    // =========================
    $nilaiTotal = null;
    $statusNilai = null;

    if ($item->nilai_rapor !== null && $item->nilai_prestasi !== null) {
        $nilaiTotal = round(($item->nilai_rapor + $item->nilai_prestasi) / 2);

        if ($nilaiTotal >= 80) {
    $statusNilai = 'valid';
} elseif ($nilaiTotal >= 75) {
    $statusNilai = 'memenuhi';
} else {
    $statusNilai = 'kurang';
}
    }

    // =========================
    // 📂 STATUS BERKAS
    // =========================
    $statusBerkas = 'tidak_valid';

    if ($item->status == 'lulus') {
        $statusBerkas = 'valid';
    }

    // =========================
    // 🧠 HASIL AKHIR
    // =========================
    $hasil = '-';

if ($statusBerkas == 'valid' && $statusNilai == 'valid') {
    $hasil = 'Lulus';
}
elseif ($statusBerkas == 'valid' && $statusNilai == 'memenuhi') {
    $hasil = 'Lulus';
}
elseif ($statusBerkas == 'tidak_valid' && $statusNilai == 'valid') {
    $hasil = 'Perbaikan';
}
elseif ($statusBerkas == 'tidak_valid' && $statusNilai == 'memenuhi') {
    $hasil = 'Perbaikan';
}
elseif ($statusNilai == 'kurang') {
    $hasil = 'Tidak Lulus';
}

    // =========================
    // 📊 STATUS VERIFIKASI UI
    // =========================
    $status_verifikasi = match ($statusBerkas) {
        'valid' => 'Berkas Valid',
        'tidak_valid' => 'Perlu Perbaikan',
        default => 'Menunggu',
    };

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

        $total = $rows->count();
        $lulus = $rows->where('hasil', 'Lulus')->count();
        $tidak_lulus = $rows->where('hasil', 'Tidak Lulus')->count();
        $perbaikan = $rows->where('hasil', 'Perlu Perbaikan')->count();

        $siap_diumumkan = $rows
            ->where('status_email', 'belum_terkirim')
            ->count();

        return view('panitia.operasional.pengumuman.index', compact(
            'rows',
            'total',
            'lulus',
            'tidak_lulus',
            'perbaikan',
            'siap_diumumkan'
        ));
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

        return view('panitia.operasional.pengumuman.review', compact('pesanList'));
    }

    public function destroyTemplate($id)
    {
        $template = EmailTemplate::find($id);

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
    }

    // =========================
    // 🔥 CORE FUNCTION EMAIL
    // =========================
    private function kirimEmail($data)
    {
        $success = 0;
        $failed = 0;
        $invalidEmails = [];

        foreach ($data as $item) {

            $email = optional($item->user)->email;

            // VALIDASI EMAIL
            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $failed++;
                $invalidEmails[] = $email ?? 'Email kosong';
                continue;
            }

            // MAPPING STATUS
            if ($item->status === 'lulus') {
                $penerima = 'lulus';
            } elseif ($item->status === 'perbaikan') {
                $penerima = 'perlu_perbaikan';
            } else {
                $penerima = 'tidak_lulus';
            }

            // AMBIL TEMPLATE + FALLBACK
            $template = EmailTemplate::where('penerima', $penerima)->first()
                ?? EmailTemplate::where('penerima', 'semua')->first();

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

                // UPDATE DB
                $item->update([
                    'is_publish' => 1,
                    'email_status' => 'terkirim'
                ]);

                \Log::info("EMAIL TERKIRIM KE: " . $email);

                $success++;

            } catch (\Exception $e) {
                \Log::error('EMAIL GAGAL: ' . $e->getMessage());
                $failed++;
            }
        }

        return [
            'success' => $success,
            'failed' => $failed,
            'invalid' => $invalidEmails
        ];
    }

    public function publishMassal()
    {
        $data = Pendaftaran::with('user')
            ->where('email_status', 'belum_terkirim')
            ->get();

        $result = $this->kirimEmail($data);

        return response()->json([
            'success' => true,
            'success_count' => $result['success'],
            'failed_count' => $result['failed'],
            'invalid' => $result['invalid']
        ]);
    }

    public function publishSelected(Request $request)
    {
        $ids = $request->ids ?? [];

        $data = Pendaftaran::with('user')
            ->whereIn('id', $ids)
            ->get();

        $result = $this->kirimEmail($data);

        return response()->json([
            'success' => true,
            'success_count' => $result['success'],
            'failed_count' => $result['failed'],
            'invalid' => $result['invalid']
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'penerima' => 'required'
        ]);

        EmailTemplate::create($request->only('judul', 'isi', 'penerima'));

        return redirect()
            ->route('panitia.operasional.pengumuman.review')
            ->with('success', 'Template berhasil disimpan');
    }

    public function updateTemplate(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required',
            'penerima' => 'required'
        ]);

        $template = EmailTemplate::findOrFail($id);

        $template->update($request->only('judul', 'isi', 'penerima'));

        return redirect()
            ->route('panitia.operasional.pengumuman.review')
            ->with('success', 'Template berhasil diupdate');
    }

    public function editTemplate($id)
    {
        $template = EmailTemplate::findOrFail($id);

        return view('panitia.operasional.pengumuman.edit', compact('template'));
    }
}