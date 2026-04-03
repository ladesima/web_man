@extends('layouts.panitia')
@section('title', 'Review Email')
@section('content')
<style>
.card-shadow { box-shadow: 0px 4px 4px 0px rgba(161, 209, 251, 0.25); }
.pesan-card { border: 0.75px solid #E2E8F0; border-radius: 12px; background: white; transition: box-shadow 0.2s; }
.pesan-card:hover { box-shadow: 0px 4px 14px 0px rgba(39, 194, 222, 0.15); }
.btn-detail { display:inline-flex; align-items:center; gap:5px; padding:5px 14px; font-size:11px; font-weight:600; color:#27C2DE; border:1px solid #27C2DE; border-radius:8px; background:#E0FAFB; cursor:pointer; transition:all 0.15s; text-decoration:none; }
.btn-detail:hover { background:#27C2DE; color:white; }
.btn-detail:hover img { filter: brightness(0) invert(1); }
</style>

<div x-data="{
    showPopup: false,
    selectedPesan: {},

    openPopup(pesan) {
        this.selectedPesan = pesan;
        this.showPopup = true;
    },

    gunakanTemplate() {
        localStorage.setItem('selected_template', this.selectedPesan.id);
        this.showPopup = false;
        window.location.href = '/panitia/operasional/pengumuman';
    },

    hapusTemplate(id) {
        if (!confirm('Yakin ingin menghapus template ini?')) return;

        const token = document.querySelector('meta[name=csrf-token]')?.content;

        if (!token) {
            alert('CSRF token tidak ditemukan');
            return;
        }

        fetch('/panitia/operasional/pengumuman/template/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                alert('Template berhasil dihapus');
                location.reload();
            } else {
                alert('Gagal menghapus');
            }
        })
        .catch(() => alert('Terjadi error'));
    }
}">
{{-- TABS + ACTION --}}
<div class="flex items-center justify-between mb-5">

    {{-- Tabs --}}
    <div class="flex gap-1 p-1 bg-white"
         style="border-radius:14px; box-shadow:0px 2px 8px rgba(0,0,0,0.06); border:1px solid #F0F0F0;">

        {{-- HOME --}}
        <a href="{{ route('panitia.operasional.pengumuman') }}"
           class="px-5 py-1.5 text-[13px] transition-all"
           style="{{ request()->routeIs('panitia.operasional.pengumuman')
                ? 'background:#C4F4FD; color:#00758A; font-weight:700; border:1px solid #C4F4FD; border-radius:10px;'
                : 'background:transparent; color:#94A3B8; font-weight:400; border:1px solid transparent; border-radius:10px;' }}">
            Home
        </a>

        {{-- REVIEW EMAIL --}}
        <a href="{{ route('panitia.operasional.pengumuman.review') }}"
           class="px-5 py-1.5 text-[13px] transition-all"
           style="{{ request()->routeIs('panitia.operasional.pengumuman.review')
                ? 'background:#C4F4FD; color:#00758A; font-weight:700; border:1px solid #C4F4FD; border-radius:10px;'
                : 'background:transparent; color:#94A3B8; font-weight:400; border:1px solid transparent; border-radius:10px;' }}">
            Review Email
        </a>

    </div>

    {{-- Action --}}
    <a href="{{ route('panitia.operasional.pengumuman.tambah') }}"
       class="inline-flex items-center gap-2 px-4 py-2 text-white text-[12px] font-semibold hover:opacity-90 transition-all"
       style="background:#27C2DE; border-radius:8px;">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Pesan
    </a>

</div>

    {{-- LIST PESAN --}}
<div class="flex flex-col gap-3">

    @foreach($pesanList as $pesan)
    <div class="pesan-card px-5 py-4">

        {{-- JUDUL --}}
        <h3 class="text-[13px] font-bold text-[#2B2A28] mb-1">
            {{ $pesan['judul'] }}
        </h3>

        {{-- PREVIEW --}}
        <p class="text-[12px] text-[#94A3B8] leading-relaxed mb-3 line-clamp-2">
            {{ $pesan['preview'] ?? Str::limit(strip_tags($pesan['isi']), 80) }}
        </p>

        <div class="flex gap-2">

            {{-- DETAIL --}}
            <button @click="openPopup(@js($pesan))" class="btn-detail">
                <img src="{{ asset('ppdb/admin/operasional/detail2.png') }}" class="w-3.5 h-3.5">
                Detail
            </button>

            {{-- EDIT --}}
            <a href="{{ route('panitia.operasional.pengumuman.template.edit', $pesan['id']) }}"
               class="btn-detail"
               style="background:#FFF7E6; border-color:#F59E0B; color:#F59E0B;">
                Edit
            </a>

            {{-- HAPUS --}}
            <button @click="hapusTemplate({{ $pesan['id'] }})"
                class="btn-detail"
                style="background:#FFE4E6; border-color:#EF4444; color:#EF4444;">
                Hapus
            </button>

        </div>
    </div>
    @endforeach

</div>


  {{-- POPUP PREVIEW --}}
<template x-teleport="body">
    <div x-show="showPopup" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center"
        style="background:rgba(0,0,0,0.45); backdrop-filter:blur(2px);">

        <div @click.outside="showPopup = false"
            class="bg-white w-full shadow-2xl"
            style="max-width:520px; border-radius:16px;"
            x-transition>

            <div class="p-8">

                {{-- Judul --}}
                <h2 class="text-[14px] font-bold text-[#2B2A28] text-center mb-6"
                    x-text="selectedPesan.judul">
                </h2>

                {{-- Isi --}}
                <div class="text-[12px] text-[#575551] leading-relaxed mb-8"
                    style="min-height:240px;"
                    x-html="selectedPesan.isi">
                </div>

                {{-- ACTION BUTTON --}}
                <div class="flex gap-3 justify-center">

                    {{-- Kembali --}}
                    <button @click="showPopup = false"
                        class="text-[12px] font-semibold text-[#2B2A28] hover:bg-slate-50 transition-all"
                        style="border-radius:4px; border:1px solid #D4D4D4; padding:8px 32px;">
                        Kembali
                    </button>

                    {{-- Gunakan Template --}}
                    <button @click="gunakanTemplate()"
                        class="text-white text-[12px] font-semibold hover:opacity-90 transition-all"
                        style="background:#27C2DE; border-radius:4px; padding:8px 32px;">
                        Gunakan Template
                    </button>

                    {{-- Edit --}}
                    <a :href="`/panitia/operasional/pengumuman/template/${selectedPesan.id}/edit`"
                        class="text-white text-[12px] font-semibold hover:opacity-90 transition-all"
                        style="background:#41D1EA; border-radius:4px; padding:8px 32px;">
                        Edit
                    </a>

                </div>

            </div>
        </div>
    </div>
</template>
</div>
@endsection