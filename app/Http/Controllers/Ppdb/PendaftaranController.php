<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// 🔥 MODEL
use App\Models\Pendaftaran;
use App\Models\MasterPpdb;
use App\Models\DetailPendaftaran;

class PendaftaranController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | FORM PENDAFTARAN
    |--------------------------------------------------------------------------
    */
    public function index($jalur)
{
    $user = Auth::guard('ppdb')->user();

    if (!$user) {
        return redirect()->route('ppdb.login');
    }

    $pendaftaran = Pendaftaran::where('user_id', $user->id)
    ->where('jalur', $jalur)
    ->latest()
    ->first();

    // 🔒 VALIDASI JALUR
    if ($pendaftaran && $pendaftaran->jalur !== $jalur) {
    // biarkan lanjut (multi jalur)
    $pendaftaran = null;
}

    // 🔥 SET LAST STEP
    if ($pendaftaran && empty($pendaftaran->last_step)) {
        $pendaftaran->update([
            'last_step' => 'form'
        ]);
    }

    // 🔥 AMBIL SYARAT
    $ppdb = MasterPpdb::aktifWithRelasi();
    $syarats = $ppdb?->syarats ?? collect();

    // =====================================================
    // 🔥🔥🔥 LOGIKA HASIL PENGUMUMAN (INI YANG PENTING)
    // =====================================================
    $hasil = 'belum';

    if ($pendaftaran) {

        if (!$pendaftaran->is_publish) {
            $hasil = 'proses'; // belum diumumkan
        } else {

            if ($pendaftaran->status === 'lulus') {
                $hasil = 'lulus';
            } elseif ($pendaftaran->status === 'perbaikan') {
                $hasil = 'perbaikan';
            } else {
                $hasil = 'tidak_lulus'; // optional kalau nanti dipakai
            }
        }
    }

    return view('ppdb.pendaftaran.index', compact(
        'jalur',
        'user',
        'syarats',
        'pendaftaran',
        'hasil'
    ));
}

    /*
    |--------------------------------------------------------------------------
    | SIMPAN DATA FORM + SYARAT
    |--------------------------------------------------------------------------
    */
    public function store(Request $request, $jalur)
{
    $user = Auth::guard('ppdb')->user();

    if (!$user) {
        return redirect()->route('ppdb.login');
    }

    // 🔥 ambil data lama
    $pendaftaran = Pendaftaran::where('user_id', $user->id)
        ->where('jalur', $jalur)
        ->latest()
        ->first();

    // 🔒 CEK DUPLIKAT (FIX BUG)
    $cek = Pendaftaran::where('user_id', $user->id)
        ->where('jalur', $jalur)
        ->whereNotIn('status', ['tidak_lulus'])
        ->exists();

    if ($cek && !$pendaftaran) {
        return back()->with('error', 'Anda sudah mendaftar di jalur ini');
    }

    // 🔒 LOCK DATA (kecuali revisi)
    if ($pendaftaran && !$pendaftaran->is_revisi && $pendaftaran->status !== 'belum') {
        return redirect()->route('siswa.upload.berkas', $pendaftaran->jalur)
            ->with('error', 'Data sudah dikunci dan tidak bisa diubah');
    }

    // ✅ VALIDASI (FOTO DINAMIS)
    $request->validate([
        'ttl' => 'required',
        'asal_sekolah' => 'required',
        'alamat' => 'required',
        'nama_ortu' => 'required',
        'pekerjaan_ortu' => 'required',
        'penghasilan_ortu' => 'required',
        'alamat_ortu' => 'required',
        'jumlah_saudara' => 'required|integer',
        'foto' => $pendaftaran
            ? 'nullable|image|mimes:jpg,jpeg,png|max:2048'
            : 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // 🔥 HANDLE FOTO (AMAN)
    $fotoPath = $pendaftaran->foto ?? null;

    if ($request->hasFile('foto')) {

        if ($pendaftaran && $pendaftaran->foto) {
            Storage::disk('public')->delete($pendaftaran->foto);
        }

        $fotoPath = $request->file('foto')->store('foto_ppdb', 'public');
    }

    // 🔥 DATA
    $data = [
        'user_id' => $user->id,
        'jalur' => $jalur,

        'nama_lengkap' => $user->nama,
        'nisn' => $user->nisn,

        'ttl' => $request->ttl,
        'asal_sekolah' => $request->asal_sekolah,
        'alamat' => $request->alamat,
        'nama_ortu' => $request->nama_ortu,
        'pekerjaan_ortu' => $request->pekerjaan_ortu,
        'penghasilan_ortu' => $request->penghasilan_ortu,
        'alamat_ortu' => $request->alamat_ortu,
        'jumlah_saudara' => $request->jumlah_saudara,

        'foto' => $fotoPath,

        // 🔥 RESET SETELAH PERBAIKAN
        'status' => 'form_selesai',
        'last_step' => 'berkas',
        'catatan' => null,
        'is_revisi' => false,
    ];

    // 🔥 SIMPAN
    if ($pendaftaran) {
        $pendaftaran->update($data);
    } else {
        $pendaftaran = Pendaftaran::create($data);
    }

    // 🔥 SYARAT DINAMIS
    $ppdb = MasterPpdb::aktifWithRelasi();
    $syarats = $ppdb?->syarats ?? [];

    foreach ($syarats as $syarat) {

        $value = $request->input('syarat_' . $syarat->id);

        if (!$value) continue;

        DetailPendaftaran::updateOrCreate(
            [
                'pendaftaran_id' => $pendaftaran->id,
                'syarat_id' => $syarat->id,
            ],
            [
                'value' => $syarat->tipe === 'teks' ? $value : null,
                'file' => null,
            ]
        );
    }

    return redirect()->route('siswa.upload.berkas', $jalur)
        ->with('success', 'Data formulir berhasil disimpan');
}


    /*
    |--------------------------------------------------------------------------
    | FORM (ALT METHOD - OPTIONAL)
    |--------------------------------------------------------------------------
    */
    public function create($jalur)
    {
        $ppdb = MasterPpdb::aktifWithRelasi();

        return view('ppdb.pendaftaran.isi-formulir', [
            'jalur' => $jalur,
            'syarats' => $ppdb?->syarats ?? collect()
        ]);
    }
   public function verifikasi($jalur)
{
    $user = Auth::guard('ppdb')->user();

    if (!$user) {
        return redirect()->route('ppdb.login');
    }

    $pendaftaran = Pendaftaran::where('user_id', $user->id)
        ->where('jalur', $jalur)
        ->latest()
        ->first();

    $status = 'menunggu';

    if ($pendaftaran) {

        // ❌ BELUM DIPUBLISH
        if (!$pendaftaran->is_publish) {
            $status = 'menunggu';
        } else {

            // =====================================
            // 🔥 CEK VERIFIKASI BERKAS
            // =====================================
            $verifikasi = $pendaftaran->verifikasi_dokumen;

            $berkas_valid = true;

            if ($verifikasi) {
                $verifikasi = is_array($verifikasi) ? $verifikasi : json_decode($verifikasi, true);

                if (in_array('tidak_valid', $verifikasi)) {
                    $berkas_valid = false;
                }
            } else {
                $berkas_valid = false;
            }

            // =====================================
            // 🔥 CEK NILAI
            // =====================================
            $nilaiRapor = $pendaftaran->nilai_rapor;
            $nilaiPrestasi = $pendaftaran->nilai_prestasi;

            $nilaiTotal = null;
            if ($nilaiRapor !== null && $nilaiPrestasi !== null) {
                $nilaiTotal = round(($nilaiRapor + $nilaiPrestasi) / 2);
            }

            $nilai_status = 'belum';

            if ($nilaiTotal !== null) {
                if ($nilaiTotal > 80) {
                    $nilai_status = 'lulus';
                } elseif ($nilaiTotal >= 75) {
                    $nilai_status = 'memenuhi';
                } else {
                    $nilai_status = 'tidak';
                }
            }

            // =====================================
            // 🔥 FINAL LOGIKA (SESUAI RULE KAMU)
            // =====================================
            if ($berkas_valid && in_array($nilai_status, ['lulus', 'memenuhi'])) {
                $status = 'diterima';
            } elseif (!$berkas_valid && in_array($nilai_status, ['lulus', 'memenuhi'])) {
                $status = 'perbaikan';
            } elseif ($nilai_status === 'tidak') {
                $status = 'tidaklolos';
            } else {
                $status = 'menunggu';
            }
        }
    }

    return view('ppdb.verifikasi.index', compact(
        'pendaftaran',
        'jalur',
        'status'
    ));
}
public function pengumuman($jalur)
{
    
    $user = Auth::guard('ppdb')->user();

    if (!$user) {
        return redirect()->route('ppdb.login');
    }

    $pendaftaran = Pendaftaran::where('user_id', $user->id)
        ->where('jalur', $jalur)
        ->latest()
        ->first();

    $status = 'menunggu';

    if ($pendaftaran) {

        if ($pendaftaran->is_publish == 1) {

            $status = match ($pendaftaran->status) {
                'lulus' => 'diterima',
                'perbaikan' => 'perbaikan',
                default => 'tidaklolos',
            };

        } else {
            $status = 'menunggu';
        }
    }
    return view('ppdb.pengumuman.index', compact(
        'jalur',
        'status',
        'pendaftaran'
    ));
}
public function daftarUlang($jalur)
{
    $user = Auth::guard('ppdb')->user();

    if (!$user) {
        return redirect()->route('ppdb.login');
    }

    $pendaftaran = Pendaftaran::where('user_id', $user->id)
        ->where('jalur', $jalur)
        ->latest()
        ->first();

    // 🔒 VALIDASI
    if (!$pendaftaran) {
        abort(404, 'Data tidak ditemukan');
    }

    if (!$pendaftaran->is_publish) {
        abort(403, 'Pengumuman belum dibuka');
    }

    if ($pendaftaran->status !== 'lulus') {
        abort(403, 'Hanya siswa yang lulus yang bisa daftar ulang');
    }

    return view('ppdb.daftar-ulang.index', compact(
        'jalur',
        'pendaftaran'
    ));
}
public function dashboard()
{
    $user = Auth::guard('ppdb')->user();

    if (!$user) {
        return redirect()->route('ppdb.login');
    }

    // 🔥 ambil master ppdb + relasi jalur
    $ppdb = MasterPpdb::aktifWithRelasi();

    // 🔥 ambil semua pendaftaran user (multi jalur support)
    $pendaftaran = Pendaftaran::where('user_id', $user->id)
        ->get();

    return view('ppdb.dashboard.beranda', compact(
        'user',
        'ppdb',
        'pendaftaran'
    ));
}
public function perbaikan($jalur)
{
    $user = Auth::guard('ppdb')->user();

    if (!$user) {
        return redirect()->route('ppdb.login');
    }

    $pendaftaran = Pendaftaran::where('user_id', $user->id)
        ->where('jalur', $jalur)
        ->latest()
        ->first();

    if (!$pendaftaran) {
        abort(404);
    }

    // 🔥 MODE REVISI
    $pendaftaran->update([
    'status' => 'form_selesai',
    'last_step' => 'berkas',

    // 🔥 RESET SYSTEM
    'is_publish' => 0,
    'email_status' => null,

    // opsional tapi bagus
    'catatan' => null,
]);

    return redirect()->route('siswa.pendaftaran', $jalur);
}
public function form($jalur)
{
    $user = Auth::guard('ppdb')->user();

    $pendaftaran = Pendaftaran::where('user_id', $user->id)
        ->where('jalur', $jalur)
        ->latest()
        ->first();

    return view('ppdb.pendaftaran.isi-formulir', compact(
        'jalur',
        'pendaftaran'
    ));
}
}