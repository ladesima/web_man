<?php

namespace App\Http\Controllers\Admin\Ppdb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\Pertanyaan;
use Illuminate\Support\Facades\Mail;
use App\Mail\JawabanPertanyaanMail;

class FaqController extends Controller
{
    public function index()
    {
        // ✅ FAQ
        $faqs = Faq::orderBy('urutan')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'pertanyaan' => $item->pertanyaan,
                'status' => strtolower(str_replace(' ', '_', $item->status)),
                'kategori' => ucwords(str_replace('_', ' ', $item->kategori)), // 🔥 FIX
                'urutan' => $item->urutan,
                'terakhir' => $item->updated_at
                    ? $item->updated_at->format('d-m-y H:i') . ' WITA'
                    : '-',
            ];
        })->values();

        // ✅ PERTANYAAN
        $pertanyaans = Pertanyaan::latest()->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'pertanyaan' => $item->pertanyaan,
                'pengirim' => $item->pengirim,
                'email' => $item->email,
                'kategori' => ucwords(str_replace('_', ' ', $item->kategori)), // 🔥 FIX
                'status' => $item->status,
                'jawaban' => $item->jawaban,
            ];
        })->values();

        // ✅ AMBIL LIST KATEGORI (UNTUK FILTER / FORM)
        $kategoris = Faq::select('kategori')
            ->distinct()
            ->pluck('kategori')
            ->map(function ($item) {
                return strtolower(str_replace(' ', '_', $item));
            });

        return view('admin.ppdb.operasional.faq', compact('faqs', 'pertanyaans', 'kategoris'));
    }


    public function create()
    {
        return view('admin.ppdb.operasional.faq-tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pertanyaan' => 'required',
            'jawaban' => 'required',
            'status' => 'required',
            'kategori' => 'required',
            'urutan' => 'required|integer'
        ]);

        Faq::create([
            'pertanyaan' => $request->pertanyaan,
            'jawaban' => $request->jawaban,
            'status' => $request->status,
            'kategori' => $request->kategori,
            'urutan' => $request->urutan,
        ]);

        return redirect()
            ->route('admin.operasional.faq')
            ->with('success', 'FAQ berhasil ditambahkan');
    }
    public function edit($id)
{
    $faq = Faq::findOrFail($id);

    return view('admin.ppdb.operasional.faq-tambah', compact('faq'));
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'pertanyaan' => 'required',
            'jawaban' => 'required',
            'status' => 'required',
            'kategori' => 'required',
            'urutan' => 'required|integer'
        ]);

        $faq = Faq::findOrFail($id);
        $faq->update([
            'pertanyaan' => $request->pertanyaan,
            'jawaban' => $request->jawaban,
            'status' => strtolower(str_replace(' ', '_', $request->status)),
            'kategori' => $request->kategori,
            'urutan' => $request->urutan,
        ]);

        return redirect()
            ->route('admin.operasional.faq')
            ->with('success', 'FAQ berhasil diperbarui');
    }

    public function destroy($id)
{
    $faq = Faq::findOrFail($id);
    $faq->delete();

    return response()->json([
        'success' => true
    ]);
}
    public function toggleStatus($id)
{
    $faq = Faq::findOrFail($id);

    $faq->status = $faq->status === 'aktif' ? 'tidak_aktif' : 'aktif';
    $faq->save();

    return response()->json([
        'success' => true,
        'status' => $faq->status
    ]);
}
public function kirimPertanyaan(Request $request)
{
    $request->validate([
        'pertanyaan' => 'required',
        'pengirim' => 'required',
        'email' => 'required|email',
        'kategori' => 'required'
    ]);

    Pertanyaan::create([
        'pertanyaan' => $request->pertanyaan,
        'pengirim' => $request->pengirim,
        'email' => $request->email,
        'kategori' => ucwords(str_replace('_', ' ', $request->kategori)),
        'status' => 'belum_dijawab'
    ]);

    return response()->json([
        'success' => true
    ]);
}
public function jawabPertanyaan(Request $request, $id)
{
    $request->validate([
        'jawaban' => 'required'
    ]);

    // ambil data pertanyaan
    $pertanyaan = Pertanyaan::findOrFail($id);

    // 🔥 update pertanyaan
    $pertanyaan->update([
        'jawaban' => $request->jawaban,
        'status' => 'sudah_dijawab'
    ]);

    // 🔥 simpan ke FAQ
    Faq::create([
        'pertanyaan' => $pertanyaan->pertanyaan,
        'jawaban' => $request->jawaban,
        'status' => 'aktif',
        'kategori' => $pertanyaan->kategori,
        'urutan' => (Faq::max('urutan') ?? 0) + 1
    ]);

    // 🔥 KIRIM EMAIL (INI YANG BELUM ADA DI KODE KAMU)
    try {
        Mail::to($pertanyaan->email)
            ->send(new JawabanPertanyaanMail($pertanyaan));
    } catch (\Exception $e) {
        \Log::error('Gagal kirim email: ' . $e->getMessage());
    }

    return response()->json([
        'success' => true
    ]);
}

}