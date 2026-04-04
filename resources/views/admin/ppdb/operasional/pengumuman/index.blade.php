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

<div x-data="pengumumanData()">

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
            <button @click="kirimDitandai()" class="inline-flex items-center gap-2 px-4 py-2 text-[12px] font-semibold hover:opacity-90 transition-all"
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
                <p class="text-[26px] font-bold text-[#2B2A28]">@if($s['label'] == 'Siap Diumumkan')
        {{ $siap_diumumkan }}
    @elseif($s['label'] == 'Total Lulus')
        {{ $lulus }}
    @elseif($s['label'] == 'Tidak Lulus')
        {{ $tidak_lulus }}
    @elseif($s['label'] == 'Perlu Perbaikan')
        {{ $perbaikan }}
    @endif</p>
            </div>
            @endforeach
        </div>
{{-- ===== Filter & Search — FIX FLEX ===== --}}
<div class="flex gap-3 mb-4 items-center w-full">

    {{-- SEARCH --}}
    <div class="relative flex-[2] min-w-0">
        <input type="text" placeholder="Cari" x-model="search"
            class="w-full pl-9 pr-4 py-2.5 text-[12px] border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#27C2DE] card-shadow"
            style="border-radius:8px;">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </div>

    {{-- DROPDOWNS WRAPPER --}}
    <div class="flex gap-3 flex-[5] min-w-0">

        {{-- JALUR --}}
        <div class="relative flex-1 min-w-0">
            <select x-model="filterJalur"
                class="appearance-none w-full pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none card-shadow"
                style="border-radius:8px;">
                <option value="">Jalur</option>
                <option value="prestasi">Prestasi</option>
                <option value="reguler">Reguler</option>
                <option value="afirmasi">Afirmasi</option>
            </select>
            <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

    

        {{-- HASIL --}}
        <div class="relative flex-1 min-w-0">
            <select x-model="filterHasil"
                class="appearance-none w-full pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none card-shadow"
                style="border-radius:8px;">
                <option value="">Hasil Seleksi</option>
                <option value="Lulus">Lulus</option>
                <option value="Tidak Lulus">Tidak Lulus</option>
                <option value="Perbaikan">Perbaikan</option>
            </select>
            <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        {{-- PUBLISH --}}
        <div class="relative flex-1 min-w-0">
            <select x-model="filterPublish"
                class="appearance-none w-full pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none card-shadow"
                style="border-radius:8px;">
                <option value="">Status Publish</option>
                <option value="publish">Publish</option>
                <option value="belum">Belum</option>
            </select>
            <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        {{-- EMAIL --}}
        <div class="relative flex-1 min-w-0">
            <select x-model="filterEmail"
                class="appearance-none w-full pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none card-shadow"
                style="border-radius:8px;">
                <option value="">Email</option>
                <option value="terkirim">Terkirim</option>
                <option value="belum_terkirim">Belum Terkirim</option>
            </select>
            <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

    </div>
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
                        <template x-for="(row, i) in paginatedRows" :key="row.id">
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
    <span x-show="row.status_verifikasi === 'Menunggu'"
          class="px-3 py-1 text-[11px]"
          style="background:#FEF3C7; border:1px solid #F59E0B; color:#F59E0B; border-radius:4px;">
        Menunggu
    </span>

    <span x-show="row.status_verifikasi === 'Berkas Valid'"
          class="px-3 py-1 text-[11px]"
          style="background:#DCFCE7; border:1px solid #16A34A; color:#16A34A; border-radius:4px;">
        Berkas Valid
    </span>

    <span x-show="row.status_verifikasi === 'Perlu Perbaikan'"
          class="px-3 py-1 text-[11px]"
          style="background:#DBEAFE; border:1px solid #2563EB; color:#2563EB; border-radius:4px;">
        Perlu Perbaikan
    </span>

    <span x-show="row.status_verifikasi === 'Siap Seleksi'"
          class="px-3 py-1 text-[11px]"
          style="background:#FCE7F3; border:1px solid #F80ECD; color:#F80ECD; border-radius:4px;">
        Siap Seleksi
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
                                    <a :href="'/admin/operasional/verifikasi/' + row.id"
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
            <span class="text-[12px] text-slate-400 whitespace-nowrap">
    <span x-text="totalData === 0 ? 0 : startItem"></span>
    -
    <span x-text="endItem"></span>
    of
    <span x-text="totalData"></span>
</span>

            {{-- Spacer --}}
            <div class="flex-1"></div>

            {{-- Rows per page --}}
            <div class="flex items-center gap-2">
                <span class="text-[12px] text-slate-400 whitespace-nowrap">Rows per page:</span>
                <div class="relative">
                    <select class="appearance-none pl-3 pr-7 py-1.5 text-[12px] text-white font-semibold focus:outline-none" x-model="perPage"
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

    {{-- PREV --}}
    <button 
        @click="if(currentPage > 1) currentPage--"
        :class="currentPage === 1 ? 'opacity-40 cursor-not-allowed' : ''"
        class="flex items-center justify-center hover:opacity-80 transition-all active:scale-95"
        style="width:29px; height:32px; background:#C8E6FD; border-radius:8px;
            box-shadow: 0px 4px 12px 0px rgba(0,0,0,0.20);">
        <img src="{{ asset('ppdb/admin/operasional/arrowleft.png') }}" 
             class="w-3.5 h-3.5 object-contain">
    </button>

    {{-- NEXT --}}
    <button 
        @click="if(currentPage < totalPages) currentPage++"
        :class="currentPage === totalPages ? 'opacity-40 cursor-not-allowed' : ''"
        class="flex items-center justify-center hover:opacity-80 transition-all active:scale-95"
        style="width:29px; height:32px; background:#005C6B; border-radius:8px;
            box-shadow: 0px 4px 12px 0px rgba(0,0,0,0.20);">
        <img src="{{ asset('ppdb/admin/operasional/arrowright.png') }}" 
             class="w-3.5 h-3.5 object-contain brightness-0 invert">
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
            <p class="text-[13px] font-bold text-[#2B2A28] mb-3"> Publikasikan Pengumuman Untuk <span x-text="totalSiap"></span> Peserta?</p>

            {{-- Ceklis items --}}
            <div class="space-y-2 mb-2">

    <div class="flex items-center gap-2">
        <img src="{{ asset('ppdb/admin/operasional/ceklis2.png') }}" class="w-4 h-4">
        <span class="text-[12px] text-[#575551]">
            Sudah Divalidasi : <span x-text="totalValid"></span> Peserta
        </span>
    </div>

    <div class="flex items-center gap-2">
        <img src="{{ asset('ppdb/admin/operasional/ceklis2.png') }}" class="w-4 h-4">
        <span class="text-[12px] text-[#575551]">
            Lulus : <span x-text="totalLulus"></span> Peserta
        </span>
    </div>

    <div class="flex items-center gap-2">
        <img src="{{ asset('ppdb/admin/operasional/ceklis2.png') }}" class="w-4 h-4">
        <span class="text-[12px] text-[#575551]">
            Perbaikan : <span x-text="totalPerbaikan"></span> Peserta
        </span>
    </div>

</div>

            {{-- Buttons --}}
            <div class="flex gap-3 justify-center">
                <button @click="showPublish = false"
                        class="px-8 py-2 text-[12px] font-semibold text-[#2B2A28] hover:bg-slate-50 transition-all"
                        style="border-radius:8px; border: 1px solid #D4D4D4;">
                    Batal
                </button>
                <button @click="publishMassal()"
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
                <img src="{{ asset('ppdb/admin/operasional/ceklispengumuman.png') }}" class="w-6 h-6">
                <h2 class="text-[15px] font-bold text-[#2B2A28]">Pengumuman Berhasil!</h2>
            </div>

            {{-- Desc --}}
            <p class="text-[12px] text-[#575551] mb-4">
                <span class="font-bold text-[#2B2A28]"
                      x-text="successCount + ' Pengumuman Terkirim Via Email'"></span>
                Dipublikasikan Kepada Seluruh Peserta PPDB!
            </p>

            {{-- Green box --}}
            <div class="px-3 py-2.5 mb-3"
                style="background: rgba(181,255,190,0.20); border: 0.5px solid #D4D4D4; height:42px; display:flex; align-items:center;">
                <span class="text-[12px] font-semibold text-[#2B2A28]"
                      x-text="'Berhasil Kirim : ' + successCount + ' Email'"></span>
            </div>

            {{-- Statistik --}}
            <div class="space-y-2 mb-3">

                {{-- Berhasil --}}
                <div class="flex items-center gap-2">
                    <img src="{{ asset('ppdb/admin/operasional/ceklis2.png') }}" class="w-4 h-4">
                    <span class="text-[12px] text-[#575551]"
                          x-text="'Berhasil Kirim : ' + successCount + ' Email'"></span>
                </div>

                {{-- Gagal --}}
                <div class="flex items-center gap-2" x-show="failedCount > 0">
                    <img src="{{ asset('ppdb/admin/operasional/ceklis2.png') }}" class="w-4 h-4">
                    <span class="text-[12px] text-[#575551]"
                          x-text="'Gagal Terkirim : ' + failedCount + ' Email'"></span>
                </div>

            </div>

            {{-- LIST EMAIL INVALID --}}
            <div class="px-3 py-2 mb-5"
                style="border: 0.5px solid #D4D4D4;"
                x-show="invalidEmails.length > 0">

                <template x-for="email in invalidEmails" :key="email">
                    <div class="flex items-center gap-2 mb-1">
                        <img src="{{ asset('ppdb/admin/operasional/ceklis2.png') }}" class="w-4 h-4">
                        <span class="text-[12px] text-[#575551]" x-text="email"></span>
                        <span class="text-[12px] font-semibold text-[#27C2DE]">
                            - Email Tidak Valid
                        </span>
                    </div>
                </template>

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
<script>
function pengumumanData() {
    return {
        tab: "home",
        showPublish: false,
        showBerhasil: false,
        checkedCount: 0,
        checkAll: false,

        currentPage: 1,
        perPage: 15,
        loadingPublish: false,

        rows: @json($rows),
        allRows: @json($rows),

        search: "",
        filterHasil: "",
        filterPublish: "",
        filterEmail: "",
        filterJalur: "",
        successCount: 0,
failedCount: 0,
invalidEmails: [],

        // =========================
        // PAGINATION
        // =========================
        get totalData() {
            return this.filteredRows.length;
        },

        get totalPages() {
            return Math.ceil(this.totalData / this.perPage) || 1;
        },

        get paginatedRows() {
            let start = (this.currentPage - 1) * this.perPage;
            return this.filteredRows.slice(start, start + this.perPage);
        },

        get startItem() {
            if (this.totalData === 0) return 0;
            return (this.currentPage - 1) * this.perPage + 1;
        },

        get endItem() {
            return Math.min(this.currentPage * this.perPage, this.totalData);
        },

        // =========================
        // FILTER
        // =========================
        get filteredRows() {
            return this.allRows.filter(r => {

                let nama = (r.nama || "").toLowerCase();
                let jalur = (r.jalur || "").toLowerCase();

                return (
                    (this.search === "" || nama.includes(this.search.toLowerCase())) &&
                    (this.filterHasil === "" || r.hasil === this.filterHasil) &&
                    (this.filterPublish === "" || r.status_pub === this.filterPublish) &&
                    (this.filterEmail === "" || r.status_email === this.filterEmail) &&
                    (this.filterJalur === "" || jalur === this.filterJalur.toLowerCase())
                );
            });
        },

        // =========================
        // CHECKBOX
        // =========================
        toggleAll() {
            this.checkAll = !this.checkAll;

            this.filteredRows.forEach(r => {
                r.checked = this.checkAll;
            });

            this.updateCount();
        },

        updateCount() {
            this.checkedCount = this.allRows.filter(r => r.checked).length;
        },

        // =========================
        // 🚀 PUBLISH MASSAL
        // =========================
       publishMassal() {
    console.log('CLICK MASUK');

    fetch('/admin/operasional/pengumuman/publish-massal', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            ids: this.allRows.map(r => r.id)
        })
    })
    .then(res => res.json())
    .then(res => {
        console.log(res);

        // ✅ INI YANG KAMU BELUM LAKUKAN
        this.successCount = res.success_count ?? 0;
        this.failedCount = res.failed_count ?? 0;
        this.invalidEmails = res.invalid_emails ?? [];

        this.showPublish = false;
        this.showBerhasil = true;
    })
    .catch(err => {
        console.error(err);
        alert('Gagal publish');
    });
},

        // =========================
        // 🚀 KIRIM YANG DITANDAI
        // =========================
        kirimDitandai() {
            let ids = this.allRows.filter(r => r.checked).map(r => r.id);

            if (ids.length === 0) {
                alert('Pilih data dulu');
                return;
            }

            fetch('/admin/operasional/pengumuman/publish', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({ ids })
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    alert('Berhasil kirim ' + res.total + ' data');
                    location.reload();
                }
            })
            .catch(() => alert('Gagal kirim data'));
        },
        // =========================
// 📊 DATA UNTUK POPUP
// =========================
get siapPublish() {
    return this.allRows.filter(r => r.status_email === 'belum_terkirim');
},

get totalSiap() {
    return this.siapPublish.length;
},

get totalValid() {
    return this.siapPublish.filter(r => r.status_verifikasi === 'Berkas Valid').length;
},

get totalLulus() {
    return this.siapPublish.filter(r => r.hasil === 'Lulus').length;
},

get totalPerbaikan() {
    return this.siapPublish.filter(r => r.hasil === 'Perbaikan').length;
},
    }
}
</script>