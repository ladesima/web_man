@extends('layouts.admin')

@section('title', 'Tambah FAQ')

@section('content')

<div x-data="{
    showPopup: false,

    confirmSubmit() {
        this.showPopup = false;
        document.getElementById('faqForm').submit();
    }
}">

<form id="faqForm">
<div class="bg-white rounded-2xl p-8 card-shadow">
    <div class="grid grid-cols-2 gap-8">

        {{-- Kiri --}}
        <div class="space-y-5">
            <div>
                <label class="block text-[13px] font-medium text-[#2B2A28] mb-2">Pertanyaan</label>
                <textarea id="pertanyaan" rows="4"
                    class="w-full rounded-xl px-4 py-3 text-[12px] border bg-[#F5F7FF] border-[#DFEAF2] focus:outline-none focus:ring-2 focus:ring-[#006E87] resize-none"></textarea>
            </div>

            <div>
                <label class="block text-[13px] font-medium text-[#2B2A28] mb-2">Jawaban</label>
                <textarea id="jawaban" rows="6"
                    class="w-full rounded-xl px-4 py-3 text-[12px] border bg-[#F5F7FF] border-[#DFEAF2] focus:outline-none focus:ring-2 focus:ring-[#006E87] resize-none"></textarea>
            </div>
        </div>

        {{-- Kanan --}}
        <div class="space-y-5">

            <div>
                <label class="block text-[13px] font-medium text-[#2B2A28] mb-2">Status</label>
                <select id="status"
                    class="w-full rounded-xl px-4 py-3 text-[12px] border bg-[#F5F7FF] border-[#DFEAF2] focus:outline-none focus:ring-2 focus:ring-[#006E87]">
                    <option value=""></option>
                    <option>Aktif</option>
                    <option>Tidak Aktif</option>
                </select>
            </div>

            <div>
                <label class="block text-[13px] font-medium text-[#2B2A28] mb-2">Kategori</label>
                <select id="kategori"
                    class="w-full rounded-xl px-4 py-3 text-[12px] border bg-[#F5F7FF] border-[#DFEAF2] focus:outline-none focus:ring-2 focus:ring-[#006E87]">
                    <option value=""></option>
                    <option>Pendaftaran</option>
                    <option>Berkas</option>
                    <option>Jalur Seleksi</option>
                    <option>Jadwal</option>
                </select>
            </div>

            <div>
                <label class="block text-[13px] font-medium text-[#2B2A28] mb-2">Urutan</label>
                <select id="urutan"
                    class="w-full rounded-xl px-4 py-3 text-[12px] border bg-[#F5F7FF] border-[#DFEAF2] focus:outline-none focus:ring-2 focus:ring-[#006E87]">
                    <option value=""></option>
                    @for ($u = 1; $u <= 20; $u++)
                        <option value="{{ $u }}">{{ $u }}</option>
                    @endfor
                </select>
            </div>

        </div>
    </div>

    {{-- Tombol --}}
    <div class="flex justify-center mt-8">
        <button type="button"
            id="btnSubmit"
            @click="
                if (!isValid()) {
                    alert('Harap lengkapi semua data terlebih dahulu!');
                } else {
                    showPopup = true;
                }
            "
            class="px-16 py-2.5 text-white text-[13px] rounded-md font-normal transition-all
                   bg-[#91E9F9] hover:opacity-90">
            Tambah
        </button>
    </div>
</div>
</form>

{{-- ===== POPUP ===== --}}
<template x-teleport="body">
    <div x-show="showPopup" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center"
         style="background:rgba(0,0,0,0.35);">

        <div @click.outside="showPopup = false"
             class="relative w-full max-w-sm"
             x-transition.scale>

            <img src="{{ asset('ppdb/admin/operasional/popup.png') }}" class="w-full">

            <div class="absolute bottom-0 left-0 right-0 pb-8 px-8 text-center">
                <h3 class="text-[15px] font-bold text-[#2B2A28] mb-2">
                    Yakin ingin menambah FAQ?
                </h3>

                <p class="text-[12px] text-slate-400 mb-5">
                    Data FAQ akan disimpan
                </p>

                <div class="flex gap-3 justify-center">
                    
                    <button @click="showPopup = false"
                        class="px-6 py-2 rounded-xl border border-slate-300 text-[13px] font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                        Batal
                    </button>

                    <button @click="confirmSubmit"
                        class="px-6 py-2 rounded-xl text-white text-[13px] font-semibold transition-all"
                        style="background:#27C2DE;">
                        Yakin
                    </button>

                </div>
            </div>

        </div>
    </div>
</template>

{{-- SCRIPT --}}
<script>
    const pertanyaan = document.getElementById('pertanyaan');
    const jawaban = document.getElementById('jawaban');
    const status = document.getElementById('status');
    const kategori = document.getElementById('kategori');
    const urutan = document.getElementById('urutan');
    const btn = document.getElementById('btnSubmit');

    function isValid() {
        return (
            pertanyaan.value.trim() !== '' &&
            jawaban.value.trim() !== '' &&
            status.value !== '' &&
            kategori.value !== '' &&
            urutan.value !== ''
        );
    }

    function updateButton() {
        if (isValid()) {
            btn.style.backgroundColor = '#006E87';
        } else {
            btn.style.backgroundColor = '#91E9F9';
        }
    }

    document.querySelectorAll('#faqForm textarea, #faqForm select').forEach(el => {
        el.addEventListener('input', updateButton);
        el.addEventListener('change', updateButton);
    });
</script>

</div>

@endsection