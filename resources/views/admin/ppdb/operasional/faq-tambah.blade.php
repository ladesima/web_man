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

<script>
const fields = [
    document.getElementById('pertanyaan'),
    document.getElementById('jawaban'),
    document.getElementById('status'),
    document.getElementById('kategori'),
    document.getElementById('urutan')
];

const btn       = document.getElementById('btnSubmit');
const errorText = document.getElementById('errorText');

const COLOR_INVALID = '#91E9F9';
const COLOR_VALID   = '#27C2DE'; // warna jika data lengkap
const COLOR_HOVER   = '#27C2DE'; // warna hover selalu sama

function isValid() {
    return fields.every(el => el.value.trim() !== '');
}

function updateBaseColor() {
    btn.style.backgroundColor = isValid() ? COLOR_VALID : COLOR_INVALID;
    if (isValid()) errorText.classList.add('hidden');
}

// Hover selalu #27C2DE
btn.addEventListener('mouseenter', () => {
    btn.style.backgroundColor = COLOR_HOVER;
});

btn.addEventListener('mouseleave', () => {
    updateBaseColor();
});

// Update otomatis saat field berubah
fields.forEach(el => {
    el.addEventListener('input',  updateBaseColor);
    el.addEventListener('change', updateBaseColor);
});

// Klik tombol
btn.addEventListener('click', () => {
    if (!isValid()) {
        errorText.classList.remove('hidden');
    } else {
        errorText.classList.add('hidden');
        document.querySelector('[x-data]').__x.$data.showPopup = true;
    }
});
</script>

</div>

@endsection