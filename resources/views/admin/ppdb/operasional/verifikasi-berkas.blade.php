@extends('layouts.admin')

@section('title', 'Verifikasi Berkas')

@section('content')
<style>
.custom-check { appearance:none; -webkit-appearance:none; width:20px; height:20px; border:none; border-radius:4px; background:white; cursor:pointer; position:relative; box-shadow:0 0 0 0.75px #2B2A28; }
.custom-check:checked { background:white; border:none; box-shadow:0 0 0 0.75px #2B2A28; }
.custom-check:checked::after { content:""; position:absolute; left:5px; top:2px; width:7px; height:11px; border:2px solid #27C2DE; border-top:none; border-left:none; transform:rotate(45deg); }
</style>
<div x-data="{
    showSeleksi: false,
    checkedCount: 0,
    checkAll: false,
    rows: [
        { id: 1, nama: 'Ahmad Sahroni',    no: '121731871', jalur: 'Prestasi', status: 'menunggu',     catatan: 'Perbaiki cara fo...', checked: false },
        { id: 2, nama: 'Muhammad Naufal',  no: '121731871', jalur: 'Prestasi', status: 'siap_seleksi', catatan: '',                   checked: false },
        { id: 3, nama: 'Zahara Liberty',   no: '121731871', jalur: 'Prestasi', status: 'berkas_valid', catatan: '',                   checked: false },
        { id: 4, nama: 'Zony Erikson',     no: '121731871', jalur: 'Prestasi', status: 'menunggu',     catatan: 'File Ij...',          checked: false },
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

    {{-- ===== STAT CARDS ===== --}}
    <div class="grid grid-cols-6 gap-3 mb-5">
        @php
        $stats = [
            ['label' => 'Total Pendaftar', 'icon' => 'totalpendaftar.png', 'from' => '#FAFEFF', 'to' => '#59DEFF', 'color' => '#0099B8'],
            ['label' => 'Menunggu',        'icon' => 'menunggu.png',       'from' => '#FFFEFA', 'to' => '#FFBF9D', 'color' => '#EA580C'],
            ['label' => 'Perlu Perbaikan', 'icon' => 'perluperbaikan.png', 'from' => '#FAFEFF', 'to' => '#7AB2FF', 'color' => '#2563EB'],
            ['label' => 'Berkas Valid',    'icon' => 'berkasvalid.png',    'from' => '#FAFEFF', 'to' => '#88FFC4', 'color' => '#15803D'],
            ['label' => 'Berkas Ditolak',  'icon' => 'berkasditolak.png',  'from' => '#FAFEFF', 'to' => '#FF9696', 'color' => '#DC2626'],
            ['label' => 'Siap Seleksi',    'icon' => 'siapseleksi.png',    'from' => '#FAFEFF', 'to' => '#FF91FB', 'color' => '#9333EA'],
        ];
        @endphp
        @foreach($stats as $s)
        <div class="relative rounded-2xl px-3 py-3 overflow-hidden cursor-pointer transition-all duration-200"
             style="background: linear-gradient(to bottom left, {{ $s['from'] }} 0%, {{ $s['to'] }} 100%);
                    border: 0.66px solid #F3F3F3;
                    box-shadow: 0px 2.62px 2.62px 0px rgba(161,209,251,0.25);
                    filter: saturate(0.45) brightness(1.08);"
             onmouseenter="this.style.filter='saturate(1) brightness(1)'; this.style.boxShadow='0px 6px 14px rgba(0,0,0,0.10)'"
             onmouseleave="this.style.filter='saturate(0.45) brightness(1.08)'; this.style.boxShadow='0px 2.62px 2.62px 0px rgba(161,209,251,0.25)'">
            <div class="flex items-start justify-between mb-1">
                <p class="text-[10px] font-semibold" style="color:{{ $s['color'] }}">{{ $s['label'] }}</p>
                <img src="{{ asset('ppdb/admin/operasional/' . $s['icon']) }}" alt="" class="w-7 h-7 object-contain">
            </div>
            <p class="text-[22px] font-bold text-[#2B2A28]">1298</p>
        </div>
        @endforeach
    </div>

    {{-- ===== SEARCH & FILTER ===== --}}
    <div class="flex gap-3 mb-4 items-center">
        <div class="relative flex-1 max-w-xs">
            <input type="text" placeholder="Cari"
                   class="w-full rounded-xl pl-9 pr-4 py-2.5 text-[12px] border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        @foreach(['Jalur', 'Gelombang', 'Status'] as $filter)
        <div class="relative">
            <select class="appearance-none rounded-xl pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none">
                <option>{{ $filter }}</option>
            </select>
            <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
        @endforeach
    </div>

    {{-- ===== TABEL ===== --}}
    <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow: 0px 2px 8px rgba(0,0,0,0.06);">
        <table class="w-full">
            <thead>
                <tr style="background:#C4F4FD;">
                    <th class="py-3 px-4 w-10">
                        <input type="checkbox" @change="toggleAll()" x-model="checkAll" class="custom-check">
                    </th>
                    <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">No</th>
                    <th class="text-left py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Nama Peserta</th>
                    <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">No Pendaftaran</th>
                    <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Jalur</th>
                    <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Status</th>
                    <th class="text-left py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <template x-for="(row, i) in rows" :key="row.id">
                    <tr class="hover:bg-slate-50 transition-all">
                        <td class="py-3 px-4">
                            <input type="checkbox" x-model="row.checked" @change="updateCount()" class="custom-check">
                        </td>
                        <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28]" x-text="i+1"></td>
                        <td class="py-3 px-4 text-[12px] font-medium text-[#2B2A28]" x-text="row.nama"></td>
                        <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28]" x-text="row.no"></td>
                        <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28]" x-text="row.jalur"></td>
                        <td class="text-center py-3 px-4">
                            <span x-show="row.status === 'menunggu'"
                                  style="background:#FEF3C7; color:#D97706; border:1px solid #D97706; border-radius:4px;"
                                  class="px-3 py-1 text-[11px] font-semibold">Menunggu</span>
                            <span x-show="row.status === 'siap_seleksi'"
                                  style="background:#EDE9FE; color:#7C3AED; border:1px solid #7C3AED; border-radius:4px;"
                                  class="px-3 py-1 text-[11px] font-semibold">Siap Seleksi</span>
                            <span x-show="row.status === 'berkas_valid'"
                                  style="background:#DCFCE7; color:#16A34A; border:1px solid #16A34A; border-radius:4px;"
                                  class="px-3 py-1 text-[11px] font-semibold">Berkas Valid</span>
                        </td>
                        <td class="py-3 px-4 text-[12px] text-slate-400" x-text="row.catatan"></td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- ===== FLOATING BUTTON ===== --}}
    <div x-show="checkedCount > 0" x-cloak class="fixed bottom-6 right-8 z-30">
        <button @click="showSeleksi = true"
                class="px-6 py-2.5 rounded-xl text-white text-[13px] font-semibold shadow-lg"
                style="background:#27C2DE;">
            <span x-text="checkedCount + ' data, Siap Seleksi?'"></span>
        </button>
    </div>

    {{-- ===== POPUP SELEKSI ===== --}}
    <template x-teleport="body">
        <div x-show="showSeleksi" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center"
             style="background:rgba(0,0,0,0.35);">
            <div @click.outside="showSeleksi = false"
                 class="relative w-full max-w-sm" x-transition>
                <img src="{{ asset('ppdb/admin/operasional/popup.png') }}" alt="" class="w-full">
                <div class="absolute bottom-0 left-0 right-0 pb-8 px-8 text-center">
                    <h3 class="text-[15px] font-bold text-[#2B2A28] mb-2">
                        Apa anda yakin ingin menyeleksi?
                    </h3>
                    <p class="text-[12px] text-slate-400 mb-5">
                        data siswa akan di masukkan ke draft pengumuman
                    </p>
                    <div class="flex gap-3 justify-center">
                        <button @click="showSeleksi = false"
                                class="px-6 py-2 rounded-xl border border-slate-300 text-[13px] font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                            Batal
                        </button>
                        <button class="px-6 py-2 rounded-xl text-white text-[13px] font-semibold transition-all"
                                style="background:#27C2DE;">
                            Yakin
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

</div>
@endsection