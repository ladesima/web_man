@extends('layouts.admin')

@section('title', 'Verifikasi Berkas')

@section('content')
<style>
.custom-check { appearance:none; -webkit-appearance:none; width:20px; height:20px; border:none; border-radius:4px; background:white; cursor:pointer; position:relative; box-shadow:0 0 0 0.75px #2B2A28; }
.custom-check:checked { background:white; border:none; box-shadow:0 0 0 0.75px #2B2A28; }
.custom-check:checked::after { content:""; position:absolute; left:5px; top:2px; width:7px; height:11px; border:2px solid #27C2DE; border-top:none; border-left:none; transform:rotate(45deg); }
</style>

<div x-data='{
    showSeleksi: false,
    checkedCount: 0,
    checkAll: false,

    rows: @json($pendaftar ?? []),

    // 🔥 FILTER STATE
    search: "",
    filterJalur: "",
    filterStatus: "",
    filterCard: "",

    // 🔥 FILTER LOGIC (CORE)
    get filteredRows() {
        return this.rows.filter(row => {

            let matchSearch =
                row.nama.toLowerCase().includes(this.search.toLowerCase()) ||
                row.no.toString().includes(this.search);

            let matchJalur =
                this.filterJalur === "" ||
                row.jalur.toLowerCase() === this.filterJalur.toLowerCase();

            let matchStatus =
                this.filterStatus === "" ||
                row.status === this.filterStatus;

            let matchCard =
    this.filterCard === "" ||
    row.status === this.filterCard;

            return matchSearch && matchJalur && matchStatus && matchCard;
        });
    },

    toggleAll() {
        this.checkAll = !this.checkAll;
        this.filteredRows.forEach(r => r.checked = this.checkAll);
        this.updateCount();
    },

    updateCount() {
        this.checkedCount = this.rows.filter(r => r.checked).length;
    },

    // 🔥 FILTER BY CARD
    filterByCard(type) {

    // kalau klik card yang sama → reset
    if (this.filterCard === type) {
        this.filterCard = "";
    } else {
        // klik card baru → aktifkan filter
        this.filterCard = type;
    }
},
        resetFilter() {
    this.search = "";
    this.filterJalur = "";
    this.filterStatus = "";
    this.filterCard = "";
}
}'>

    {{-- ===== STAT CARDS (4 kolom) ===== --}}
    <div class="grid grid-cols-4 gap-3 mb-5">
        @php
$stats = [
    ['key'=>'menunggu','label'=>'Menunggu Verifikasi','icon'=>'totalpendaftar.png','from'=>'#FAFEFF','to'=>'#59DEFF','color'=>'#0099B8'],
    ['key'=>'perlu_perbaikan','label'=>'Perlu Perbaikan','icon'=>'perluperbaikan.png','from'=>'#FAFEFF','to'=>'#7AB2FF','color'=>'#2563EB'],
    ['key'=>'berkas_valid','label'=>'Berkas Valid','icon'=>'berkasvalid.png','from'=>'#FAFEFF','to'=>'#88FFC4','color'=>'#15803D'],
    ['key'=>'berkas_ditolak','label'=>'Berkas Ditolak','icon'=>'berkasditolak.png','from'=>'#FAFEFF','to'=>'#FF9696','color'=>'#DC2626'],
];
@endphp

@foreach($stats as $s)
<div 
    @click="filterByCard('{{ $s['key'] }}')"
    class="relative rounded-2xl px-4 py-4 overflow-hidden cursor-pointer transition-all duration-200"
    
    :class="filterCard === '{{ $s['key'] }}' ? 'ring-2 ring-black/20 scale-[1.03]' : ''"

    style="background: linear-gradient(to bottom left, {{ $s['from'] }} 0%, {{ $s['to'] }} 100%);
           border: 0.66px solid #F3F3F3;
           box-shadow: 0px 2.62px 2.62px 0px rgba(161,209,251,0.25);
           filter: saturate(0.45) brightness(1.08);"

    onmouseenter="this.style.filter='saturate(1) brightness(1)'; this.style.boxShadow='0px 6px 14px rgba(0,0,0,0.10)'"
    onmouseleave="this.style.filter='saturate(0.45) brightness(1.08)'; this.style.boxShadow='0px 2.62px 2.62px 0px rgba(161,209,251,0.25)'"
>

    <div class="flex items-start justify-between mb-2">
        <p class="text-[11px] font-semibold" style="color:{{ $s['color'] }}">
            {{ $s['label'] }}
        </p>
        <img src="{{ asset('ppdb/admin/operasional/' . $s['icon']) }}" class="w-7 h-7">
    </div>

    <p class="text-[26px] font-bold text-[#2B2A28]">
      {{ $counts[$s['key']] ?? 0 }}
    </p>
</div>
@endforeach
    </div>

    {{-- ===== SEARCH & FILTER ===== --}}
    <div class="flex gap-3 mb-4 items-center w-full">
        {{-- Search --}}
        <div class="relative flex-[2]">
            <input type="text" placeholder="Cari" x-model="search"
                   class="w-full pl-9 pr-4 py-2.5 text-[12px] border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#27C2DE]"
                   style="border-radius: 8px;">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        {{-- Filter Jalur --}}
        <div class="relative flex-1">
            <select class="appearance-none w-full pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none"
                    style="border-radius: 8px;" x-model="filterJalur">
                <option>Jalur</option>
                <option>Reguler</option>
                <option>Prestasi</option>
                <option>Afirmasi</option>
            </select>
            <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
        {{-- Filter Gelombang --}}
        <div class="relative flex-1">
            <select class="appearance-none w-full pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none"
                    style="border-radius: 8px;">
                <option>Gelombang</option>
                <option>Gelombang I</option>
                <option>Gelombang II</option>
            </select>
            <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
        {{-- Filter Status --}}
        <div class="relative flex-1">
            <select class="appearance-none w-full pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none"
                    style="border-radius: 8px;" x-model="filterStatus">
                <option value="">Status</option>
    <option value="menunggu">Menunggu</option>
    <option value="perlu_perbaikan">Perlu Perbaikan</option>
    <option value="berkas_valid">Berkas Valid</option>
    <option value="berkas_ditolak">Berkas Ditolak</option>
            </select>
            <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
        <button 
    @click="resetFilter()"
    class="px-4 py-2 text-[12px] font-semibold rounded-lg border border-slate-300 hover:bg-slate-100 transition-all">
    Reset
</button>
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
                    <th class="text-left   py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Nama Peserta</th>
                    <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">No Pendaftaran</th>
                    <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Jalur</th>
                    <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Status</th>
                    <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Catatan</th>
                    <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <template x-for="(row, i) in filteredRows" :key="row.id">
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
                            <span x-show="row.status === 'perlu_perbaikan'"
                                  style="background:#E3F2FD; color:#1565C0; border:1px solid #1565C0; border-radius:4px;"
                                  class="px-3 py-1 text-[11px] font-semibold">Perlu Perbaikan</span>
                            <span x-show="row.status === 'siap_seleksi'"
                                  style="background:#EDE9FE; color:#7C3AED; border:1px solid #7C3AED; border-radius:4px;"
                                  class="px-3 py-1 text-[11px] font-semibold">Siap Seleksi</span>
                            <span x-show="row.status === 'berkas_valid'"
                                  style="background:#DCFCE7; color:#16A34A; border:1px solid #16A34A; border-radius:4px;"
                                  class="px-3 py-1 text-[11px] font-semibold">Berkas Valid</span>
                            <span x-show="row.status === 'berkas_ditolak'"
                                  style="background:#FFEBEE; color:#C62828; border:1px solid #C62828; border-radius:4px;"
                                  class="px-3 py-1 text-[11px] font-semibold">Berkas Ditolak</span>
                        </td>
                        <td class="text-center py-3 px-4 text-[12px] text-slate-500" x-text="row.catatan"></td>
                        <td class="text-center py-3 px-4">
                           <a :href="`/admin/operasional/verifikasi/${row.id}`"
   class="inline-flex items-center px-4 py-1.5 rounded-lg text-white text-[12px] font-semibold transition-all hover:opacity-90 active:scale-95"
   style="background: linear-gradient(90deg, #15B2CE 0%, #00758A 100%);">
    <img src="{{ asset('ppdb/admin/ditail.png') }}" alt="detail" class="w-4 h-4 object-contain">
    Detail
</a>
                        </td>
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