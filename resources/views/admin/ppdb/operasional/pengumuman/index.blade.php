@extends('layouts.admin')

@section('title', 'Pengumuman')

@section('content')
<style>
.custom-check { appearance:none; -webkit-appearance:none; width:20px; height:20px; border:none; border-radius:4px; background:white; cursor:pointer; position:relative; box-shadow:0 0 0 0.75px #2B2A28; }
.custom-check:checked { background:white; border:none; box-shadow:0 0 0 0.75px #2B2A28; }
.custom-check:checked::after { content:""; position:absolute; left:5px; top:2px; width:7px; height:11px; border:2px solid #27C2DE; border-top:none; border-left:none; transform:rotate(45deg); }

/* ===== HIDE SCROLLBAR tapi tetap bisa scroll ===== */
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

/* ===== DROP SHADOW dari Figma: X=0 Y=4 Blur=4 Spread=0 #A1D1FB 25% ===== */
.card-shadow {
    box-shadow: 0px 4px 4px 0px rgba(161, 209, 251, 0.25);
}
</style>

<div x-data="{
    tab: 'home',
    showPublish: false,
    showBerhasil: false,
    checkedCount: 0,
    checkAll: false,
    rows: [
        { id: 1, nama: 'Muhammad Naufal', no: '121731871', jalur: 'Prestasi', hasil: 'Perbaikan',   status_pub: 'belum',   status_email: 'belum_terkirim', tgl: '23/3/26 - 12:23 WITA', checked: false },
        { id: 2, nama: 'Zahara Liberty',  no: '121731871', jalur: 'Prestasi', hasil: 'Lulus',       status_pub: 'belum',   status_email: 'belum_terkirim', tgl: '23/3/26 - 12:23 WITA', checked: false },
        { id: 3, nama: 'Ahmad Sahroni',   no: '121731871', jalur: 'Prestasi', hasil: 'Lulus',       status_pub: 'publish', status_email: 'terkirim',       tgl: '23/3/26 - 12:23 WITA', checked: false },
        { id: 4, nama: 'Zony Erikson',    no: '121731871', jalur: 'Prestasi', hasil: 'Tidak Lulus', status_pub: 'publish', status_email: 'terkirim',       tgl: '23/3/26 - 12:23 WITA', checked: false },
    ],
    toggleAll() {
        this.checkAll = !this.checkAll;
        this.rows.forEach(r => r.checked = this.checkAll);
        this.updateCount();
    },
    updateCount() {
        this.checkedCount = this.rows.filter(r => r.checked).length;
    }
}">

    {{-- ===== TABS + ACTION BUTTONS ===== --}}
    <div class="flex items-center justify-between mb-5">

        {{-- Tab --}}
       <div class="flex gap-1 p-1 bg-white" style="border-radius:14px; box-shadow: 0px 2px 8px rgba(0,0,0,0.06); border: 1px solid #F0F0F0;">

    <button @click="tab = 'home'"
            :style="tab === 'home'
                ? 'background:#C4F4FD; color:#00758A; font-weight:700; border: 1px solid #C4F4FD; border-radius:10px;'
                : 'background:transparent; color:#94A3B8; font-weight:400; border: 1px solid transparent; border-radius:10px;'"
            class="px-5 py-1.5 text-[13px] transition-all">
        Home
    </button>

    <a href="{{ route('admin.operasional.pengumuman.review') }}"
    :style="window.location.pathname.includes('review')
            ? 'background:#C4F4FD; color:#00758A; font-weight:700; border: 1px solid #C4F4FD; border-radius:10px;'
            : 'background:transparent; color:#94A3B8; font-weight:400; border: 1px solid #E2E8F0; border-radius:10px;'"
    class="px-5 py-1.5 text-[13px] transition-all">
        Review Email
    </a>
</div>

        {{-- Action Buttons --}}
        <div class="flex gap-3">
            <button @click="showPublish = true"
                    class="inline-flex items-center gap-2 px-4 py-2 text-white text-[12px] font-semibold hover:opacity-90 transition-all"
                    style="background:#27C2DE; border-radius:8px;">
                <img src="{{ asset('ppdb/admin/operasional/publish.png') }}" alt="" class="w-4 h-4 object-contain brightness-0 invert">
                Publish Massal
            </button>
            <button class="inline-flex items-center gap-2 px-4 py-2 text-[12px] font-semibold hover:opacity-90 transition-all"
                    style="background: white; border: 1.5px solid #00758A; color:#00758A; border-radius:8px;">
                <img src="{{ asset('ppdb/admin/operasional/kirim.png') }}" alt="" class="w-4 h-4 object-contain">
                Kirim Yang Ditandai
            </button>
        </div>
    </div>

    {{-- ===== TAB HOME ===== --}}
    <div x-show="tab === 'home'">

        {{-- Stat Cards --}}
        <div class="grid grid-cols-4 gap-4 mb-5">
            @php
            $stats = [
                ['label' => 'Siap Diumumkan',  'icon' => 'siapseleksi.png',   'from' => '#FAFEFF', 'to' => '#FF91FB', 'color' => '#9333EA'],
                ['label' => 'Total Lulus',      'icon' => 'berkasvalid.png',   'from' => '#FAFEFF', 'to' => '#88FFC4', 'color' => '#15803D'],
                ['label' => 'Tidak Lulus',      'icon' => 'berkasditolak.png', 'from' => '#FAFEFF', 'to' => '#FF9696', 'color' => '#DC2626'],
                ['label' => 'Perlu Perbaikan',  'icon' => 'perluperbaikan.png','from' => '#FAFEFF', 'to' => '#7AB2FF', 'color' => '#2563EB'],
            ];
            @endphp
            @foreach($stats as $s)
            <div class="relative rounded-2xl px-4 py-4 overflow-hidden cursor-pointer transition-all duration-200"
                 style="background: linear-gradient(to bottom left, {{ $s['from'] }} 0%, {{ $s['to'] }} 100%);
                        border: 0.66px solid #F3F3F3;
                        box-shadow: 0px 2.62px 2.62px 0px rgba(161,209,251,0.25);
                        filter: saturate(0.45) brightness(1.08);"
                 onmouseenter="this.style.filter='saturate(1) brightness(1)'; this.style.boxShadow='0px 6px 14px rgba(0,0,0,0.10)'"
                 onmouseleave="this.style.filter='saturate(0.45) brightness(1.08)'; this.style.boxShadow='0px 2.62px 2.62px 0px rgba(161,209,251,0.25)'">
                <div class="flex items-start justify-between mb-2">
                    <p class="text-[11px] font-semibold" style="color:{{ $s['color'] }}">{{ $s['label'] }}</p>
                    <img src="{{ asset('ppdb/admin/operasional/' . $s['icon']) }}" alt="" class="w-8 h-8 object-contain">
                </div>
                <p class="text-[26px] font-bold text-[#2B2A28]">1298</p>
            </div>
            @endforeach
        </div>

        {{-- ===== Filter & Search — dengan drop shadow Figma ===== --}}
        <div class="flex gap-3 mb-4 items-center w-full">
            {{-- Search --}}
            <div class="relative flex-[2]">
                <input type="text" placeholder="Cari"
                       class="w-full pl-9 pr-4 py-2.5 text-[12px] border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#27C2DE] card-shadow"
                       style="border-radius:8px;">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            {{-- Dropdowns --}}
            @foreach(['Jalur', 'Gelombang', 'Hasil Seleksi', 'Status Publish', 'Email'] as $f)
            <div class="relative flex-1">
                <select class="appearance-none w-full pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none card-shadow"
                        style="border-radius:8px;">
                    <option>{{ $f }}</option>
                </select>
                <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            @endforeach
        </div>

        {{-- ===== TABEL — dengan drop shadow Figma ===== --}}
        <div class="bg-white rounded-2xl overflow-hidden card-shadow">
            <div class="w-full overflow-x-auto no-scrollbar">
                <table class="w-full min-w-[1100px] border-separate border-spacing-0">
                    <thead>
                        <tr style="background:#C4F4FD;">
                            <th class="py-3 px-4 w-10 sticky left-0 z-20 bg-[#C4F4FD]">
                                <input type="checkbox" @change="toggleAll()" x-model="checkAll" class="custom-check">
                            </th>
                            <th class="text-center py-3 px-3 text-[12px] font-semibold text-[#2B2A28] sticky left-10 z-20 bg-[#C4F4FD]"
                                style="min-width:40px;">No</th>
                            <th class="text-left py-3 px-3 text-[12px] font-semibold text-[#2B2A28] sticky left-[98px] z-20 bg-[#C4F4FD]"
                                style="min-width:150px; box-shadow: 4px 0 4px 0 rgba(161,209,251,0.25);">Nama Peserta</th>
                            <th class="text-center py-3 px-3 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:120px;">No Pendaftaran</th>
                            <th class="text-center py-3 px-3 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:90px;">Jalur</th>
                            <th class="text-center py-3 px-3 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:130px;">Status Verifikasi</th>
                            <th class="text-center py-3 px-3 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:110px;">Hasil Seleksi</th>
                            <th class="text-center py-3 px-3 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:110px;">Status Publish</th>
                            <th class="text-center py-3 px-3 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:120px;">Status Email</th>
                            <th class="text-center py-3 px-3 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:150px;">Tgl Verifikasi</th>
                            <th class="text-center py-3 px-3 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(row, i) in rows" :key="row.id">
                            <tr class="hover:bg-slate-50 transition-all">
                                <td class="py-3 px-4 sticky left-0 z-10 bg-white">
                                    <input type="checkbox" x-model="row.checked" @change="updateCount()" class="custom-check">
                                </td>
                                <td class="text-center py-3 px-3 text-[12px] text-[#2B2A28] sticky left-10 z-10 bg-white"
                                    x-text="i+1"></td>
                                <td class="py-3 px-3 text-[12px] font-medium text-[#2B2A28] sticky left-[98px] z-10 bg-white"
                                    style="box-shadow: 4px 0 4px 0 rgba(161,209,251,0.25);"
                                    x-text="row.nama"></td>
                                <td class="text-center py-3 px-3 text-[12px] text-[#2B2A28] whitespace-nowrap" x-text="row.no"></td>
                                <td class="text-center py-3 px-3 text-[12px] text-[#2B2A28] whitespace-nowrap" x-text="row.jalur"></td>
                                <td class="text-center py-3 px-3 whitespace-nowrap">
                                    <span class="px-3 py-1 text-[11px] font-medium"
                                          style="background: rgba(255,154,220,0.20); border: 1px solid #F80ECD; border-radius:4px; color:#F80ECD;">
                                        Siap umumkan
                                    </span>
                                </td>
                                <td class="text-center py-3 px-3 text-[12px] text-[#2B2A28] whitespace-nowrap" x-text="row.hasil"></td>
                                <td class="text-center py-3 px-3 whitespace-nowrap">
                                    <span x-show="row.status_pub === 'publish'"
                                          class="px-3 py-1 text-[11px] font-medium"
                                          style="background:#27C2DE; color:white; border-radius:4px;">Publish</span>
                                    <span x-show="row.status_pub === 'belum'"
                                          class="px-3 py-1 text-[11px] font-medium"
                                          style="background:#FEE2E2; color:#EF4444; border:1px solid #EF4444; border-radius:4px;">Belum</span>
                                </td>
                                <td class="text-center py-3 px-3 whitespace-nowrap">
                                    <span x-show="row.status_email === 'terkirim'"
                                          class="px-3 py-1 text-[11px] font-medium"
                                          style="background:#DCFCE7; color:#16A34A; border:1px solid #16A34A; border-radius:4px;">Terkirim</span>
                                    <span x-show="row.status_email === 'belum_terkirim'"
                                          class="px-3 py-1 text-[11px] font-medium"
                                          style="background:#F1F5F9; color:#64748B; border:1px solid #CBD5E1; border-radius:4px;">Belum Terkirim</span>
                                </td>
                                <td class="text-center py-3 px-3 text-[12px] text-[#575551] whitespace-nowrap" x-text="row.tgl"></td>
                                <td class="text-center py-3 px-3 whitespace-nowrap">
                                    <a :href="'/admin/operasional/pengumuman/' + row.id"
                                       class="inline-flex items-center px-4 py-1.5 text-white text-[12px] font-semibold transition-all hover:opacity-90 active:scale-95"
                                       style="background:#27C2DE; border-radius:4px;">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===== PAGINATION DI LUAR CARD — layout Gambar 2, tombol Gambar 3 ===== --}}
        <div class="flex items-center gap-3 px-1 py-3">

            {{-- Kiri: info --}}
            <span class="text-[12px] text-slate-400 whitespace-nowrap">1 - 4 of 450</span>

            {{-- Spacer --}}
            <div class="flex-1"></div>

            {{-- Rows per page --}}
            <div class="flex items-center gap-2">
                <span class="text-[12px] text-slate-400 whitespace-nowrap">Rows per page:</span>
                <div class="relative">
                    <select class="appearance-none pl-3 pr-7 py-1.5 text-[12px] text-white font-semibold focus:outline-none"
                            style="background:#27C2DE; border-radius:6px; min-width:52px;">
                        <option>15</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                    <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>

         <div class="flex gap-2">
            <button class="flex items-center justify-center hover:opacity-80 transition-all active:scale-95"
                    style="width:29px; height:32px; background:#C8E6FD; border-radius:8px;
                        box-shadow: 0px 4px 12px 0px rgba(0,0,0,0.20);">
                <img src="{{ asset('ppdb/admin/operasional/arrowleft.png') }}" alt="prev" class="w-3.5 h-3.5 object-contain">
            </button>
            <button class="flex items-center justify-center hover:opacity-80 transition-all active:scale-95"
                    style="width:29px; height:32px; background:#005C6B; border-radius:8px;
                        box-shadow: 0px 4px 12px 0px rgba(0,0,0,0.20);">
                <img src="{{ asset('ppdb/admin/operasional/arrowright.png') }}" alt="next" class="w-3.5 h-3.5 object-contain brightness-0 invert">
            </button>
        </div>
        </div>

    </div>

    {{-- ===== TAB REVIEW EMAIL ===== --}}
    <div x-show="tab === 'review'" x-cloak>
        <div class="bg-white rounded-2xl p-8 text-center card-shadow">
            <p class="text-[14px] text-slate-400">Halaman Review Email</p>
        </div>
    </div>

    {{-- ===== POPUP PUBLISH ===== --}}
<template x-teleport="body">
    <div x-show="showPublish" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center"
         style="background:rgba(0,0,0,0.45); backdrop-filter:blur(2px);">
        <div @click.outside="showPublish = false"
             class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-2xl" x-transition>

            {{-- Title --}}
            <h2 class="text-[15px] font-bold text-[#2B2A28] text-center mb-4">Publish Pengumuman</h2>

            {{-- Subtitle --}}
            <p class="text-[13px] font-bold text-[#2B2A28] mb-3">Publikasikan Pengumuman Untuk 2 Peserta?</p>

            {{-- Ceklis items --}}
            <div class="space-y-2 mb-2">
                @foreach(['Sudah Divalidasi : 2 Peserta', 'Lulus : 1 Peserta', 'Perbaikan : 1 Peserta'] as $item)
                <div class="flex items-center gap-2">
                    <img src="{{ asset('ppdb/admin/operasional/ceklis2.png') }}" alt="" class="w-4 h-4 object-contain flex-shrink-0">
                    <span class="text-[12px] text-[#575551]">{{ $item }}</span>
                </div>
                @endforeach
            </div>

            {{-- Warning items --}}
            <div class="space-y-2 mb-5">
                @foreach(['Semua Peserta Akan Menerima Pengumuman Via Email', 'Pastikan Data Sudah Benar Sebelum Dipublikasikan'] as $item)
                <div class="flex items-center gap-2">
                    <img src="{{ asset('ppdb/admin/operasional/tandaseru.png') }}" alt="" class="w-4 h-4 object-contain flex-shrink-0">
                    <span class="text-[12px] text-[#575551]">{{ $item }}</span>
                </div>
                @endforeach
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 justify-center">
                <button @click="showPublish = false"
                        class="px-8 py-2 text-[12px] font-semibold text-[#2B2A28] hover:bg-slate-50 transition-all"
                        style="border-radius:8px; border: 1px solid #D4D4D4;">
                    Batal
                </button>
                <button @click="showPublish = false; showBerhasil = true"
                        class="px-8 py-2 text-white text-[12px] font-semibold hover:opacity-90 transition-all"
                        style="background:#27C2DE; border-radius:8px;">
                    Publish
                </button>
            </div>
        </div>
    </div>
</template>

{{-- ===== POPUP BERHASIL ===== --}}
<template x-teleport="body">
    <div x-show="showBerhasil" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center"
         style="background:rgba(0,0,0,0.45); backdrop-filter:blur(2px);">
        <div @click.outside="showBerhasil = false"
             class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-2xl" x-transition>

            {{-- Title --}}
            <div class="flex items-center gap-2 mb-3">
                <img src="{{ asset('ppdb/admin/operasional/ceklispengumuman.png') }}" alt="" class="w-6 h-6 object-contain flex-shrink-0">
                <h2 class="text-[15px] font-bold text-[#2B2A28]">Pengumuman Berhasil!</h2>
            </div>

            {{-- Desc --}}
            <p class="text-[12px] text-[#575551] mb-4">
                <span class="font-bold text-[#2B2A28]">Pengumuman Berhasil</span> Dipublikasikan Kepada Seluruh Peserta PPDB!
            </p>

          {{-- Green box --}}
            <div class="px-3 py-2.5 mb-3"
                style="background: rgba(181,255,190,0.20); border: 0.5px solid #D4D4D4; height:42px; display:flex; align-items:center;">
                <span class="text-[12px] font-semibold text-[#2B2A28]">1 Pengumuman Terkirim Via Email</span>
            </div>
            {{-- Kirim items --}}
            <div class="space-y-2 mb-3">
                @foreach(['Berhasil Kirim : 1 Email', 'Gagal Terkirim : 1 Email'] as $item)
                <div class="flex items-center gap-2">
                    <img src="{{ asset('ppdb/admin/operasional/ceklis2.png') }}" alt="" class="w-4 h-4 object-contain flex-shrink-0">
                    <span class="text-[12px] text-[#575551]">{{ $item }}</span>
                </div>
                @endforeach
            </div>

            {{-- Email tidak valid --}}
            <div class="px-3 py-2 mb-5"
                style="border: 0.5px solid #D4D4D4;">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('ppdb/admin/operasional/ceklis2.png') }}" alt="" class="w-4 h-4 object-contain flex-shrink-0">
                    <span class="text-[12px] text-[#575551]">Zahra@Gimel.Com</span>
                    <span class="text-[12px] font-semibold" style="color:#27C2DE;">- Email Tidak Valid</span>
                </div>
            </div>

            {{-- Tutup --}}
            <div class="flex justify-center">
                <button @click="showBerhasil = false"
                        class="px-10 py-2 text-white text-[12px] font-semibold hover:opacity-90 transition-all"
                        style="background:#27C2DE; border-radius:8px;">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</template>

</div>
@endsection