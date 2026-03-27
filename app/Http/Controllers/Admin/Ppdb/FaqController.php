<?php

namespace App\Http\Controllers\Admin\Ppdb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
{
    $faqs = Faq::orderBy('urutan')->get()->map(function ($item) {
        return [
            'id' => $item->id,
            'pertanyaan' => $item->pertanyaan,
            'status' => strtolower(str_replace(' ', '_', $item->status)),
            'kategori' => $item->kategori,
            'urutan' => $item->urutan,
            'terakhir' => $item->updated_at
    ? $item->updated_at->format('d-m-y H:i') . ' WITA'
    : '-',
        ];
    })->values();

    return view('admin.ppdb.operasional.faq', compact('faqs'));
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

}