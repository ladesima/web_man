<?php
namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Pertanyaan;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PertanyaanController extends Controller
{
    public function index()
    {
        // =========================
        // DATA PERTANYAAN
        // =========================
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

        // =========================
        // RETURN VIEW
        // =========================
        return view('panitia.operasional.faq', [
        'kategoris' => $kategoris,    
        'pertanyaans' => $pertanyaans,
            'faqs' => $faqs,
        ]);
    }
    public function jawab(Request $request, $id)
{
    $request->validate([
        'jawaban' => 'required|string'
    ]);

    $pertanyaan = Pertanyaan::findOrFail($id);

    // update DB
    $pertanyaan->update([
        'jawaban' => $request->jawaban,
        'status' => 'sudah_dijawab'
    ]);

    // kirim email
    try {
        Mail::raw(
            "Pertanyaan:\n" . $pertanyaan->pertanyaan .
            "\n\nJawaban:\n" . $request->jawaban,
            function ($message) use ($pertanyaan) {
                $message->to($pertanyaan->email)
                        ->subject('Jawaban Pertanyaan PPDB');
            }
        );
    } catch (\Exception $e) {
        \Log::error($e->getMessage());
    }

    return response()->json([
        'success' => true
    ]);
}
    public function create()
    {
        return view('panitia.operasional.faq-tambah');
    }
    public function edit($id)
{
    $faq = Faq::findOrFail($id);

    $kategoris = Faq::select('kategori')
        ->distinct()
        ->pluck('kategori');

    return view('panitia.operasional.faq-tambah', [
        'faq' => $faq,
        'kategoris' => $kategoris
    ]);
}
    public function store(Request $request)
    {
        $request->validate([
            'pertanyaan' => 'required',
            'jawaban' => 'required',
            'status' => 'required|in:aktif,tidak_aktif',
            'kategori' => 'required',
            'urutan' => 'required|integer'
        ]);

        Faq::create([
            'pertanyaan' => $request->pertanyaan,
            'jawaban' => $request->jawaban,
            'status' => $request->status,
            'kategori' => strtolower(str_replace(' ', '_', $request->kategori)),
            'urutan' => $request->urutan,
        ]);

        return redirect()
            ->route('panitia.operasional.faq')
            ->with('success', 'FAQ berhasil ditambahkan');
    }
    public function destroy($id)
{
    $faq = Faq::findOrFail($id);
    $faq->delete();

    return response()->json([
        'success' => true
    ]);
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
        'status' => strtolower($request->status),
        'kategori' => strtolower(str_replace(' ', '_', $request->kategori)),
        'urutan' => $request->urutan,
    ]);

    return redirect()->route('panitia.operasional.faq');
}
}