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
        ->latest()
        ->first();

    // 🔒 VALIDASI JALUR
    if ($pendaftaran && $pendaftaran->jalur !== $jalur) {
        return redirect()->route(
            'siswa.pendaftaran',
            $pendaftaran->jalur
        );
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

    $pendaftaran = Pendaftaran::where('user_id', $user->id)
        ->latest()
        ->first();

    // 🔒 VALIDASI JALUR
    if ($pendaftaran && $pendaftaran->jalur !== $jalur) {
        return redirect()->route('siswa.pendaftaran', $pendaftaran->jalur);
    }

    // 🔒 LOCK DATA
    if ($pendaftaran && !$pendaftaran->is_revisi && $pendaftaran->status !== 'belum') {
        return redirect()->route('siswa.upload.berkas', $pendaftaran->jalur)
            ->with('error', 'Data sudah dikunci dan tidak bisa diubah');
    }

    // ✅ VALIDASI
    $request->validate([
        'ttl' => 'required',
        'asal_sekolah' => 'required',
        'alamat' => 'required',
        'nama_ortu' => 'required',
        'pekerjaan_ortu' => 'required',
        'penghasilan_ortu' => 'required',
        'alamat_ortu' => 'required',
        'jumlah_saudara' => 'required|integer',
        'foto' => 'required|nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);
    

    // 🔥 HANDLE FOTO (SIMPAN KE PENDAFTARAN)
    $fotoPath = $pendaftaran ? $pendaftaran->foto : null;
    if ($request->hasFile('foto')) {

        $file = $request->file('foto');

        // hapus foto lama
        if ($pendaftaran && $pendaftaran->foto) {
            Storage::disk('public')->delete($pendaftaran->foto);
        }

        $fotoPath = $file->store('foto_ppdb', 'public');
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

        // 🔥 FIX UTAMA
        'foto' => $fotoPath,

        'status' => 'form_selesai',
        'last_step' => 'berkas'
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

    $pendaftaran = Pendaftaran::where('user_id', $user->id)
        ->latest()
        ->first();

    // =========================================
    // 🔥 MAPPING STATUS ADMIN → STATUS UI SISWA
    // =========================================
    $status = 'menunggu';

    if ($pendaftaran) {

        if (!$pendaftaran->is_publish) {
            $status = 'menunggu';
        } else {

            switch ($pendaftaran->status) {

                case 'lulus':
                    $status = 'diterima';
                    break;

                case 'perbaikan':
                    $status = 'perbaikan'; // ❗ penting
                    break;

                case 'berkas_selesai':
                case 'form_selesai':
                    $status = 'menunggu';
                    break;

                default:
                    $status = 'tidaklolos';
                    break;
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
    $user = \Auth::guard('ppdb')->user();

    // 🔥 ambil data sesuai jalur + yang SUDAH publish
    $pendaftaran = \App\Models\Pendaftaran::where('user_id', $user->id)
        ->where('jalur', $jalur)
        ->latest()
        ->first();

    $status = 'menunggu';

    if ($pendaftaran) {

        // DEBUG (opsional, bisa kamu cek dulu)
        // dd($pendaftaran->status, $pendaftaran->is_publish);

        if ($pendaftaran->is_publish == 1) {

            if ($pendaftaran->status === 'lulus') {
                $status = 'diterima';

            } elseif ($pendaftaran->status === 'perbaikan') {
                $status = 'perbaikan';

            } else {
                $status = 'tidaklolos';
            }

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
}