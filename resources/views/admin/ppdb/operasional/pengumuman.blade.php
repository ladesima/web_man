@extends('layouts.admin')

@section('title', 'Pengumuman')

@section('content')
<style>
.custom-check { appearance:none; -webkit-appearance:none; width:20px; height:20px; border:none; border-radius:4px; background:white; cursor:pointer; position:relative; box-shadow:0 0 0 0.75px #2B2A28; }
.custom-check:checked { background:white; border:none; box-shadow:0 0 0 0.75px #2B2A28; }
.custom-check:checked::after { content:""; position:absolute; left:5px; top:2px; width:7px; height:11px; border:2px solid #27C2DE; border-top:none; border-left:none; transform:rotate(45deg); }
</style>
<div x-data="{
    tab: 'home',
    showPublish: false,
    showBerhasil: false,
    checkedCount: 0,
    checkAll: false,
    rows: [
        { id: 1, nama: 'Ahmad Sahroni',   no: '121731871', jalur: 'Prestasi', status_verif: 'menunggu',     hasil: 'Lulus',       status_pub: 'publish', checked: false },
        { id: 2, nama: 'Muhammad Naufal', no: '121731871', jalur: 'Prestasi', status_verif: 'siap_seleksi', hasil: 'Tidak Lulus', status_pub: 'belum',   checked: false },
        { id: 3, nama: 'Zahara Liberty',  no: '121731871', jalur: 'Prestasi', status_verif: 'berkas_valid', hasil: 'Lulus',       status_pub: 'belum',   checked: false },
        { id: 4, nama: 'Zony Erikson',    no: '121731871', jalur: 'Prestasi', status_verif: 'menunggu',     hasil: 'Tidak Lulus', status_pub: 'publish', checked: false },
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

    {{-- ===== TABS + PUBLISH MASSAL ===== --}}
    <div class="flex items-center justify-between mb-5">
        {{-- Tab container --}}
        <div class="flex gap-1 p-1 rounded-2xl bg-white" style="box-shadow: 0px 2px 8px rgba(0,0,0,0.06);">
            <button @click="tab = 'home'"
                    :class="tab === 'home' ? 'font-semibold text-white' : 'text-slate-400 font-normal'"
                    :style="tab === 'home' ? 'background:#27C2DE;' : 'background:transparent;'"
                    class="px-6 py-2 rounded-xl text-[13px] transition-all">
                Home
            </button>
            <button @click="tab = 'review'"
                    :class="tab === 'review' ? 'font-semibold text-white' : 'text-slate-400 font-normal'"
                    :style="tab === 'review' ? 'background:#27C2DE;' : 'background:transparent;'"
                    class="px-6 py-2 rounded-xl text-[13px] transition-all">
                Review Email
            </button>
        </div>

        <button @click="showPublish = true"
                class="flex items-center gap-2 px-4 py-2 text-white text-[12px] font-semibold hover:opacity-90 transition-all"
                style="background:#27C2DE; border-radius:4px;">
            <img src="{{ asset('ppdb/admin/operasional/publish.png') }}" alt="" class="w-4 h-4 object-contain brightness-0 invert">
            Publish Massal
        </button>
    </div>

    {{-- ===== TAB HOME ===== --}}
    <div x-show="tab === 'home'">

        {{-- Stat Cards --}}
        <div class="grid grid-cols-4 gap-4 mb-5">
            @php
            $stats = [
                ['label' => 'Siap Diumumkan', 'icon' => 'siapseleksi.png',    'from' => '#FAFEFF', 'to' => '#FF91FB', 'color' => '#9333EA'],
                ['label' => 'Total Lulus',    'icon' => 'berkasvalid.png',    'from' => '#FAFEFF', 'to' => '#88FFC4', 'color' => '#15803D'],
                ['label' => 'Tidak Lulus',    'icon' => 'berkasditolak.png',  'from' => '#FAFEFF', 'to' => '#FF9696', 'color' => '#DC2626'],
                ['label' => 'Cadangan',       'icon' => 'cadangan.png', 'from' => '#FFFEFA', 'to' => '#FFBF9D', 'color' => '#EA580C'],
            ];
            @endphp
            @foreach($stats as $s)
            <div class="relative rounded-2xl px-4 py-3 overflow-hidden cursor-pointer transition-all duration-200"
                 style="background: linear-gradient(to bottom left, {{ $s['from'] }} 0%, {{ $s['to'] }} 100%);
                        border: 0.66px solid #F3F3F3;
                        box-shadow: 0px 2.62px 2.62px 0px rgba(161,209,251,0.25);
                        filter: saturate(0.45) brightness(1.08);"
                 onmouseenter="this.style.filter='saturate(1) brightness(1)'; this.style.boxShadow='0px 6px 14px rgba(0,0,0,0.10)'"
                 onmouseleave="this.style.filter='saturate(0.45) brightness(1.08)'; this.style.boxShadow='0px 2.62px 2.62px 0px rgba(161,209,251,0.25)'">
                <div class="flex items-start justify-between mb-1">
                    <p class="text-[11px] font-semibold" style="color:{{ $s['color'] }}">{{ $s['label'] }}</p>
                    <img src="{{ asset('ppdb/admin/operasional/' . $s['icon']) }}" alt="" class="w-8 h-8 object-contain">
                </div>
                <p class="text-[26px] font-bold text-[#2B2A28]">1298</p>
            </div>
            @endforeach
        </div>

        {{-- Filter --}}
        <div class="flex gap-2 mb-4 items-center">
            <div class="relative flex-1 max-w-xs">
                <input type="text" placeholder="Cari"
                       class="w-full rounded-xl pl-9 pr-4 py-2.5 text-[12px] border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            @foreach(['Jalur', 'Gelombang', 'Hasil Seleksi', 'Status Publish', 'Email'] as $f)
            <div class="relative">
                <select class="appearance-none rounded-xl pl-3 pr-7 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none">
                    <option>{{ $f }}</option>
                </select>
                <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            @endforeach
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow: 0px 2px 8px rgba(0,0,0,0.06);">
            <table class="w-full">
                <thead>
                    <tr style="background:#C4F4FD;">
                        <th class="py-3 px-4 w-10">
                            <input type="checkbox" @change="toggleAll()" x-model="checkAll" class="custom-check">
                        </th>
                        <th class="text-center py-3 px-3 text-[12px] font-semibold text-[#2B2A28]">No</th>
                        <th class="text-left py-3 px-3 text-[12px] font-semibold text-[#2B2A28]">Nama Peserta</th>
                        <th class="text-center py-3 px-3 text-[12px] font-semibold text-[#2B2A28]">No Pendaftaran</th>
                        <th class="text-center py-3 px-3 text-[12px] font-semibold text-[#2B2A28]">Jalur</th>
                        <th class="text-center py-3 px-3 text-[12px] font-semibold text-[#2B2A28]">Status Verifikasi</th>
                        <th class="text-center py-3 px-3 text-[12px] font-semibold text-[#2B2A28]">Hasil Seleksi</th>
                        <th class="text-center py-3 px-3 text-[12px] font-semibold text-[#2B2A28]">Status Publi...</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="(row, i) in rows" :key="row.id">
                        <tr class="hover:bg-slate-50 transition-all">
                            <td class="py-3 px-4">
                                <input type="checkbox" x-model="row.checked" @change="updateCount()" class="custom-check">
                            </td>
                            <td class="text-center py-3 px-3 text-[12px] text-[#2B2A28]" x-text="i+1"></td>
                            <td class="py-3 px-3 text-[12px] font-medium text-[#2B2A28]" x-text="row.nama"></td>
                            <td class="text-center py-3 px-3 text-[12px] text-[#2B2A28]" x-text="row.no"></td>
                            <td class="text-center py-3 px-3 text-[12px] text-[#2B2A28]" x-text="row.jalur"></td>
                            <td class="text-center py-3 px-3">
                                <span x-show="row.status_verif === 'menunggu'"
                                      style="background:#FEF3C7; color:#D97706; border:1px solid #D97706; border-radius:4px;"
                                      class="px-3 py-1 text-[11px] font-semibold">Menunggu</span>
                                <span x-show="row.status_verif === 'siap_seleksi'"
                                      style="background:#EDE9FE; color:#7C3AED; border:1px solid #7C3AED; border-radius:4px;"
                                      class="px-3 py-1 text-[11px] font-semibold">Siap Seleksi</span>
                                <span x-show="row.status_verif === 'berkas_valid'"
                                      style="background:#DCFCE7; color:#16A34A; border:1px solid #16A34A; border-radius:4px;"
                                      class="px-3 py-1 text-[11px] font-semibold">Berkas Valid</span>
                            </td>
                            <td class="text-center py-3 px-3 text-[12px] text-[#2B2A28]" x-text="row.hasil"></td>
                            <td class="text-center py-3 px-3">
                                <span x-show="row.status_pub === 'publish'"
                                      style="background:#27C2DE; color:white; border-radius:4px;"
                                      class="px-3 py-1 text-[11px] font-semibold">Publish</span>
                                <span x-show="row.status_pub === 'belum'"
                                      style="background:#FEE2E2; color:#EF4444; border:1px solid #EF4444; border-radius:4px;"
                                      class="px-3 py-1 text-[11px] font-semibold">Belum</span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== TAB REVIEW EMAIL ===== --}}
    <div x-show="tab === 'review'" x-cloak>
        <div class="bg-white rounded-2xl p-8 text-center" style="box-shadow: 0px 2px 8px rgba(0,0,0,0.06);">
            <p class="text-[14px] text-slate-400">Halaman Review Email</p>
        </div>
    </div>

    {{-- ===== POPUP PUBLISH — x-teleport agar tidak terperangkap overflow ===== --}}
    <template x-teleport="body">
        <div x-show="showPublish" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center"
             style="background:rgba(0,0,0,0.45); backdrop-filter:blur(2px);">
            <div @click.outside="showPublish = false"
                 class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-2xl" x-transition>
                <h2 class="text-[15px] font-bold text-[#2B2A28] mb-1">Publish Pengumuman</h2>
                <p class="text-[13px] font-semibold text-[#2B2A28] mb-3">Publikasikan Pengumuman Untuk 2 Peserta?</p>
                <div class="space-y-2 mb-5">
                    @foreach(['Sudah Divalidasi : 216 Peserta', 'Lulus : - Peserta', 'Tidak Lulus : 2 Peserta'] as $item)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-[12px] text-[#2B2A28]">{{ $item }}</span>
                    </div>
                    @endforeach
                    @foreach(['Semua Peserta Akan Menerima Pengumuman Via Email', 'Pastikan Data Sudah Benar Sebelum Dipublikasikan'] as $item)
                    <div class="flex items-center gap-2">
                        <span class="text-orange-500">⚠</span>
                        <span class="text-[12px] text-[#2B2A28]">{{ $item }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="flex gap-3 justify-end">
                    <button @click="showPublish = false"
                            class="px-5 py-2 rounded-xl border border-slate-300 text-[12px] font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                        Batal
                    </button>
                    <button @click="showPublish = false; showBerhasil = true"
                            class="px-5 py-2 rounded-xl text-white text-[12px] font-semibold hover:opacity-90 transition-all"
                            style="background:#27C2DE;">
                        Publish
                    </button>
                </div>
            </div>
        </div>
    </template>

    {{-- ===== POPUP BERHASIL — x-teleport ===== --}}
    <template x-teleport="body">
        <div x-show="showBerhasil" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center"
             style="background:rgba(0,0,0,0.45); backdrop-filter:blur(2px);">
            <div @click.outside="showBerhasil = false"
                 class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-2xl" x-transition>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h2 class="text-[15px] font-bold text-[#2B2A28]">Pengumuman Berhasil!</h2>
                </div>
                <p class="text-[12px] text-[#2B2A28] mb-4">
                    Pengumuman Berhasil Dipublikasikan Kepada Seluruh Peserta PPDB!
                </p>
                <div class="bg-slate-50 rounded-xl p-3 mb-3">
                    <p class="text-[12px] font-semibold text-[#2B2A28] mb-2">1 Pengumuman Terkirim Via Email</p>
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-[11px] text-slate-600">Berhasil Kirim : 1 Email</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span class="text-[11px] text-slate-600">Gagal Terkirim : 1 Email</span>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 rounded-xl px-3 py-2 mb-5 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span class="text-[11px] text-slate-600">Zahra@Gimel.Com</span>
                    </div>
                    <span class="text-[11px] font-semibold text-red-500">Email Tidak Valid</span>
                </div>
                <div class="flex justify-center">
                    <button @click="showBerhasil = false"
                            class="px-8 py-2 rounded-xl text-white text-[12px] font-semibold hover:opacity-90 transition-all"
                            style="background:#27C2DE;">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </template>

</div>
@endsection