@extends('layouts.panitia')

@section('title', 'Pengumuman')

@section('content')
<style>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
.card-shadow { box-shadow: 0px 4px 4px 0px rgba(161, 209, 251, 0.25); }
</style>

<div x-data="{
    showDetail: false,
    selectedPeserta: null,

    peserta: [
        { id:1, nama:'Ahmad Sahroni',   nisn:'121731871', noPendaftaran:'12123455', asal_sekolah:'SMP 1 Makassar',   jalur:'Prestasi', gelombang:'I', nilaiRapor:70, nilaiPrestasi:76, nilaiTotal:72, hasil:'Lulus',       foto:null },
        { id:2, nama:'Muhammad Naufal', nisn:'1234567',   noPendaftaran:'12123456', asal_sekolah:'SMP 2 Jeneponto',  jalur:'Prestasi', gelombang:'I', nilaiRapor:87, nilaiPrestasi:87, nilaiTotal:72, hasil:'Lulus',       foto:null },
        { id:3, nama:'Zahara Liberty',  nisn:'121731871', noPendaftaran:'12123457', asal_sekolah:'SMP 3 Makassar',   jalur:'Prestasi', gelombang:'I', nilaiRapor:99, nilaiPrestasi:87, nilaiTotal:92, hasil:'Lulus',       foto:null },
        { id:4, nama:'Zony Erikson',    nisn:'121731871', noPendaftaran:'12123458', asal_sekolah:'SMP 4 Gowa',       jalur:'Prestasi', gelombang:'I', nilaiRapor:65, nilaiPrestasi:66, nilaiTotal:60, hasil:'Tidak Lulus', foto:null },
    ],

    get totalPeserta()    { return this.peserta.length; },
    get totalLulus()      { return this.peserta.filter(p => p.hasil === 'Lulus').length; },
    get totalTidakLulus() { return this.peserta.filter(p => p.hasil === 'Tidak Lulus').length; },
    get totalSanggah()    { return this.peserta.filter(p => p.hasil === 'Masa Sanggah').length; },

    getHasilStyle(hasil) {
        if (hasil === 'Lulus')        return 'background:#DCFCE7; color:#16A34A; border:1px solid #16A34A; border-radius:4px;';
        if (hasil === 'Tidak Lulus')  return 'background:#FFEBEE; color:#C62828; border:1px solid #C62828; border-radius:4px;';
        if (hasil === 'Masa Sanggah') return 'background:#FEF3C7; color:#D97706; border:1px solid #D97706; border-radius:4px;';
        return 'background:#F1F5F9; color:#64748B; border:1px solid #CBD5E1; border-radius:4px;';
    },

    openDetail(p) {
        this.selectedPeserta = p;
        this.showDetail = true;
    }
}">

    {{-- ===== STAT CARDS ===== --}}
    <div class="grid grid-cols-4 gap-3 mb-5">
        <div class="relative rounded-2xl px-4 py-4 overflow-hidden transition-all duration-200"
             style="background: linear-gradient(to bottom left, #FAFEFF 0%, #59DEFF 100%); border: 0.66px solid #F3F3F3; box-shadow: 0px 2.62px 2.62px 0px rgba(161,209,251,0.25); filter: saturate(0.45) brightness(1.08);"
             onmouseenter="this.style.filter='saturate(1) brightness(1)'; this.style.boxShadow='0px 6px 14px rgba(0,0,0,0.10)'"
             onmouseleave="this.style.filter='saturate(0.45) brightness(1.08)'; this.style.boxShadow='0px 2.62px 2.62px 0px rgba(161,209,251,0.25)'">
            <div class="flex items-start justify-between mb-2">
                <p class="text-[11px] font-semibold" style="color:#0099B8;">Total Peserta diumumkan</p>
                <img src="{{ asset('ppdb/admin/operasional/totalpendaftar.png') }}" class="w-7 h-7 object-contain">
            </div>
            <p class="text-[26px] font-bold text-[#2B2A28]" x-text="totalPeserta"></p>
        </div>

        <div class="relative rounded-2xl px-4 py-4 overflow-hidden transition-all duration-200"
             style="background: linear-gradient(to bottom left, #FAFEFF 0%, #88FFC4 100%); border: 0.66px solid #F3F3F3; box-shadow: 0px 2.62px 2.62px 0px rgba(161,209,251,0.25); filter: saturate(0.45) brightness(1.08);"
             onmouseenter="this.style.filter='saturate(1) brightness(1)'; this.style.boxShadow='0px 6px 14px rgba(0,0,0,0.10)'"
             onmouseleave="this.style.filter='saturate(0.45) brightness(1.08)'; this.style.boxShadow='0px 2.62px 2.62px 0px rgba(161,209,251,0.25)'">
            <div class="flex items-start justify-between mb-2">
                <p class="text-[11px] font-semibold" style="color:#15803D;">Lulus</p>
                <img src="{{ asset('ppdb/admin/operasional/berkasvalid.png') }}" class="w-7 h-7 object-contain">
            </div>
            <p class="text-[26px] font-bold text-[#2B2A28]" x-text="totalLulus"></p>
        </div>

        <div class="relative rounded-2xl px-4 py-4 overflow-hidden transition-all duration-200"
             style="background: linear-gradient(to bottom left, #FAFEFF 0%, #FF9696 100%); border: 0.66px solid #F3F3F3; box-shadow: 0px 2.62px 2.62px 0px rgba(161,209,251,0.25); filter: saturate(0.45) brightness(1.08);"
             onmouseenter="this.style.filter='saturate(1) brightness(1)'; this.style.boxShadow='0px 6px 14px rgba(0,0,0,0.10)'"
             onmouseleave="this.style.filter='saturate(0.45) brightness(1.08)'; this.style.boxShadow='0px 2.62px 2.62px 0px rgba(161,209,251,0.25)'">
            <div class="flex items-start justify-between mb-2">
                <p class="text-[11px] font-semibold" style="color:#DC2626;">Tidak Lulus</p>
                <img src="{{ asset('ppdb/admin/operasional/berkasditolak.png') }}" class="w-7 h-7 object-contain">
            </div>
            <p class="text-[26px] font-bold text-[#2B2A28]" x-text="totalTidakLulus"></p>
        </div>

        <div class="relative rounded-2xl px-4 py-4 overflow-hidden transition-all duration-200"
             style="background: linear-gradient(to bottom left, #FAFEFF 0%, #FFD59E 100%); border: 0.66px solid #F3F3F3; box-shadow: 0px 2.62px 2.62px 0px rgba(161,209,251,0.25); filter: saturate(0.45) brightness(1.08);"
             onmouseenter="this.style.filter='saturate(1) brightness(1)'; this.style.boxShadow='0px 6px 14px rgba(0,0,0,0.10)'"
             onmouseleave="this.style.filter='saturate(0.45) brightness(1.08)'; this.style.boxShadow='0px 2.62px 2.62px 0px rgba(161,209,251,0.25)'">
            <div class="flex items-start justify-between mb-2">
                <p class="text-[11px] font-semibold" style="color:#D97706;">Menunggu Sanggah</p>
                <img src="{{ asset('ppdb/admin/operasional/menunggu.png') }}" class="w-7 h-7 object-contain">
            </div>
            <p class="text-[26px] font-bold text-[#2B2A28]" x-text="totalSanggah"></p>
        </div>
    </div>

    {{-- ===== SEARCH & FILTER ===== --}}
    <div class="flex gap-3 mb-4 items-center w-full">
        <div class="relative flex-[2]">
            <input type="text" placeholder="Cari nama/NISN"
                   class="w-full pl-9 pr-4 py-2.5 text-[12px] border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#27C2DE] card-shadow"
                   style="border-radius:8px;">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <div class="relative flex-1">
            <select class="appearance-none w-full pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none card-shadow" style="border-radius:8px;">
                <option>Jalur</option><option>Prestasi</option><option>Reguler</option><option>Hafidz</option>
            </select>
            <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </div>
        <div class="relative flex-1">
            <select class="appearance-none w-full pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none card-shadow" style="border-radius:8px;">
                <option>Gelombang</option><option>Gelombang I</option><option>Gelombang II</option><option>Gelombang III</option>
            </select>
            <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </div>
        <div class="relative flex-1">
            <select class="appearance-none w-full pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none card-shadow" style="border-radius:8px;">
                <option>Status</option><option>Lulus</option><option>Tidak Lulus</option><option>Masa Sanggah</option>
            </select>
            <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </div>
    </div>

    {{-- ===== TABEL ===== --}}
    <div class="bg-white rounded-2xl overflow-hidden card-shadow">
        <div class="w-full overflow-x-auto no-scrollbar">
            <table class="w-full min-w-[900px] border-separate border-spacing-0">
                <thead>
                    <tr style="background:#C4F4FD;">
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]" style="min-width:40px;">No</th>
                        <th class="text-left   py-3 px-4 text-[12px] font-semibold text-[#2B2A28]" style="min-width:160px;">Nama Peserta</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:120px;">NISN</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:90px;">Jalur</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:110px;">Gelombang</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:100px;">Nilai Total</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:120px;">Hasil Seleksi</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="(p, i) in peserta" :key="p.id">
                        <tr class="hover:bg-slate-50 transition-all">
                            <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28]" x-text="i+1"></td>
                            <td class="py-3 px-4 text-[12px] font-medium text-[#2B2A28]" x-text="p.nama"></td>
                            <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28] font-mono" x-text="p.nisn"></td>
                            <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28]" x-text="p.jalur"></td>
                            <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28]" x-text="'Gelombang ' + p.gelombang"></td>
                            <td class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]" x-text="p.nilaiTotal"></td>
                            <td class="text-center py-3 px-4">
                                <span class="px-3 py-1 text-[11px] font-semibold" :style="getHasilStyle(p.hasil)" x-text="p.hasil"></span>
                            </td>
                            <td class="text-center py-3 px-4">
                                <button @click="openDetail(p)"
                                        class="inline-flex items-center px-4 py-1.5 text-white text-[12px] font-semibold transition-all hover:opacity-90 active:scale-95"
                                        style="background: linear-gradient(90deg, #15B2CE 0%, #00758A 100%); border-radius:6px;">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== PAGINATION ===== --}}
    <div class="flex items-center gap-3 px-1 py-3">
        <span class="text-[12px] text-slate-400 whitespace-nowrap">1 - 4 of 4</span>
        <div class="flex-1"></div>
        <div class="flex items-center gap-2">
            <span class="text-[12px] text-slate-400 whitespace-nowrap">Rows per page:</span>
            <div class="relative">
                <select class="appearance-none pl-3 pr-7 py-1.5 text-[12px] text-white font-semibold focus:outline-none"
                        style="background:#27C2DE; border-radius:6px; min-width:52px;">
                    <option>15</option><option>25</option><option>50</option>
                </select>
                <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </div>
        <div class="flex gap-2">
            <button class="flex items-center justify-center hover:opacity-80 transition-all active:scale-95"
                    style="width:29px; height:32px; background:#C8E6FD; border-radius:8px; box-shadow:0px 4px 12px 0px rgba(0,0,0,0.20);">
                <img src="{{ asset('ppdb/admin/operasional/arrowleft.png') }}" alt="prev" class="w-3.5 h-3.5 object-contain">
            </button>
            <button class="flex items-center justify-center hover:opacity-80 transition-all active:scale-95"
                    style="width:29px; height:32px; background:#005C6B; border-radius:8px; box-shadow:0px 4px 12px 0px rgba(0,0,0,0.20);">
                <img src="{{ asset('ppdb/admin/operasional/arrowright.png') }}" alt="next" class="w-3.5 h-3.5 object-contain brightness-0 invert">
            </button>
        </div>
    </div>

    {{-- ===== POPUP DETAIL ===== --}}
    <template x-teleport="body">
        <div x-show="showDetail" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center"
             style="background:rgba(0,0,0,0.35);">
            <div @click.outside="showDetail = false"
                 class="bg-white rounded-2xl w-full max-w-sm shadow-2xl overflow-hidden"
                 x-transition>

                {{-- Header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                    <h3 class="text-[14px] font-bold text-[#2B2A28]">Detail Pengumuman</h3>
                    <button @click="showDetail = false"
                            class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="px-5 py-4 space-y-4">

                    {{-- No Pendaftaran --}}
                    <p class="text-[11px] text-slate-400">
                        No Pendaftaran: <span class="font-semibold text-[#2B2A28]" x-text="selectedPeserta ? selectedPeserta.noPendaftaran : ''"></span>
                    </p>

                    {{-- Info Peserta --}}
                    <div class="flex items-center gap-3">
                        <template x-if="selectedPeserta && selectedPeserta.foto">
                            <img :src="selectedPeserta.foto" alt="Foto" class="w-16 h-16 rounded-xl object-cover shrink-0">
                        </template>
                        <template x-if="!selectedPeserta || !selectedPeserta.foto">
                            <div class="w-16 h-16 rounded-xl flex items-center justify-center text-white font-bold text-xl shrink-0"
                                 style="background: linear-gradient(135deg, #27C2DE 0%, #00758A 100%);">
                                <span x-text="selectedPeserta ? selectedPeserta.nama.charAt(0) : ''"></span>
                            </div>
                        </template>
                        <div>
                            <p class="font-bold text-[14px] text-[#2B2A28]" x-text="selectedPeserta ? selectedPeserta.nama : ''"></p>
                            <p class="text-[11px] text-slate-400 mt-0.5">NISN : <span x-text="selectedPeserta ? selectedPeserta.nisn : ''"></span></p>
                            <p class="text-[11px] text-slate-400">Asal Sekolah : <span x-text="selectedPeserta ? selectedPeserta.asal_sekolah : ''"></span></p>
                            <div class="flex gap-1.5 mt-1.5">
                                <span class="px-2 py-0.5 text-[10px] font-semibold"
                                      style="background:#EEF9FC; border:1px solid #27C2DE; border-radius:4px; color:#27C2DE;"
                                      x-text="selectedPeserta ? selectedPeserta.jalur : ''"></span>
                                <span class="px-2 py-0.5 text-[10px] font-semibold"
                                      style="background:#FFFBEB; border:1px solid #D97706; border-radius:4px; color:#D97706;"
                                      x-text="selectedPeserta ? 'Gelombang ' + selectedPeserta.gelombang : ''"></span>
                            </div>
                        </div>
                    </div>

                    <div class="h-px bg-slate-100"></div>

                    {{-- Status Kelulusan --}}
                    <div class="text-center space-y-2">
                        <p class="text-[14px] font-bold text-[#2B2A28]">
                            <span x-text="selectedPeserta && selectedPeserta.hasil === 'Lulus' ? 'Selamat🥳' : (selectedPeserta && selectedPeserta.hasil === 'Tidak Lulus' ? 'Mohon Maaf😔' : 'Informasi Hasil')"></span>
                        </p>
                        <p class="text-[12px] text-[#575551]">Anda dinyatakan:</p>
                        <div>
                            <span class="inline-block px-5 py-1.5 text-[13px] font-bold"
                                  :style="selectedPeserta ? getHasilStyle(selectedPeserta.hasil) : ''"
                                  x-text="selectedPeserta ? selectedPeserta.hasil : ''"></span>
                        </div>
                        <template x-if="selectedPeserta && selectedPeserta.hasil === 'Lulus'">
                            <p class="text-[12px] text-[#575551] leading-relaxed">
                                Silahkan melakukan daftar ulang pada tanggal 20-22 Mei 2026. Di lokasi yang telah ditentukan.
                            </p>
                        </template>
                        <template x-if="selectedPeserta && selectedPeserta.hasil === 'Tidak Lulus'">
                            <p class="text-[12px] text-[#575551] leading-relaxed">
                                Terima kasih telah mendaftar. Semoga di kesempatan berikutnya lebih berhasil.
                            </p>
                        </template>
                        <template x-if="selectedPeserta && selectedPeserta.hasil === 'Masa Sanggah'">
                            <p class="text-[12px] text-[#575551] leading-relaxed">
                                Anda berada di masa sanggah. Silahkan hubungi panitia jika ada keberatan.
                            </p>
                        </template>
                    </div>

                    <div class="h-px bg-slate-100"></div>

                    {{-- Hasil Seleksi --}}
                    <div>
                        <p class="text-[13px] font-bold text-[#2B2A28] mb-3 text-center">Hasil Seleksi</p>
                        <div class="space-y-1.5">
                            <p class="text-[12px] text-[#575551]">Nilai Rapor &nbsp;: <span class="font-semibold text-[#2B2A28]" x-text="selectedPeserta ? selectedPeserta.nilaiRapor : ''"></span></p>
                            <p class="text-[12px] text-[#575551]">Nilai Prestasi : <span class="font-semibold text-[#2B2A28]" x-text="selectedPeserta ? selectedPeserta.nilaiPrestasi : ''"></span></p>
                            <p class="text-[12px] text-[#575551]">Total Nilai &nbsp;: <span class="font-bold" style="color:#00758A;" x-text="selectedPeserta ? selectedPeserta.nilaiTotal : ''"></span></p>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-5 pb-5 flex gap-3">
                    <button @click="showDetail = false"
                            class="flex-1 py-2.5 text-[13px] font-semibold text-[#2B2A28] hover:bg-slate-50 transition-all"
                            style="border-radius:8px; border:1px solid #A5A5A5; background:#FBFAF7;">
                        Kembali
                    </button>
                    <button class="flex-1 py-2.5 text-white text-[13px] font-semibold hover:opacity-90 transition-all"
                            style="background:#27C2DE; border-radius:8px;"
                            onclick="window.print()">
                        Cetak
                    </button>
                </div>

            </div>
        </div>
    </template>

</div>
@endsection