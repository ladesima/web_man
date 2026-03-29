@extends('layouts.admin')

@section('title', 'Riwayat Aktivitas')

@section('content')
<div x-data='{
    showDetail: false,
    showHapus: false,
    selectedRow: null,
    rows: @json($rows),

    openDetail(row) {
        this.selectedRow = row;
        this.showDetail = true;
    },

    openHapus(row) {
        this.selectedRow = row;
        this.showHapus = true;
    },

    deleteData() {
        fetch("/admin/manajemen/riwayat/" + this.selectedRow.id, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content,
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(() => {
            this.rows = this.rows.filter(i => i.id !== this.selectedRow.id);
            this.showHapus = false;
        })
        .catch(() => alert("Gagal hapus data"));
    }
}'>

    {{-- Search --}}
    <div class="flex items-center mb-4">
        <div class="relative w-72">
            <input type="text" placeholder="Cari aktivitas" autocomplete="off"
                   class="w-full rounded-xl pl-10 pr-4 py-2.5 text-[12px] border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow: 0px 2px 8px rgba(0,0,0,0.06);">
        <table class="w-full">
            <thead>
                <tr style="background:#C4F4FD;">
                    <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">No</th>
                    <th class="text-left py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Panitia</th>
                    <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Aktivitas</th>
                    <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Waktu</th>
                    <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <template x-for="(row, i) in rows" :key="row.id">
                    <tr class="hover:bg-slate-50 transition-all">
                        <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28]" x-text="i+1"></td>
                        <td class="py-3 px-4">
                            <p class="text-[12px] font-medium text-[#2B2A28]" x-text="row.nama"></p>
                            <p class="text-[10px] text-slate-400" x-text="row.email"></p>
                        </td>
                        <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28]" x-text="row.aktivitas"></td>
                        <td class="text-center py-3 px-4 text-[12px] text-slate-500" x-text="row.waktu"></td>
                        <td class="text-center py-3 px-4">
                            <div class="flex items-center justify-center gap-1.5">
                                <button @click="openDetail(row)"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center hover:opacity-80 transition-all">
                                    <img src="{{ asset('ppdb/admin/detail.png') }}" alt="" class="w-8 h-8 object-contain">
                                </button>
                                <button @click="openHapus(row)"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center hover:opacity-80 transition-all">
                                    <img src="{{ asset('ppdb/admin/hapus.png') }}" alt="" class="w-8 h-8 object-contain">
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- POPUP DETAIL --}}
    <template x-teleport="body">
        <div x-show="showDetail" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center"
             style="background:rgba(0,0,0,0.45); backdrop-filter:blur(2px);">
            <div @click.outside="showDetail = false"
                 class="bg-white rounded-2xl p-7 shadow-2xl w-full max-w-sm" x-transition>
                <h2 class="text-[16px] font-bold text-[#2B2A28] mb-5">Detail Aktivitas</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-[12px] text-slate-400">Panitia</span>
                        <span class="text-[12px] font-semibold text-[#2B2A28]" x-text="selectedRow?.nama"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[12px] text-slate-400">Aktivitas</span>
                        <span class="text-[12px] font-semibold text-[#2B2A28]" x-text="selectedRow?.aktivitas"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[12px] text-slate-400">Waktu</span>
                        <span class="text-[12px] font-semibold text-[#2B2A28]" x-text="selectedRow?.waktu"></span>
                    </div>
                </div>
                <div class="flex justify-center mt-6">
                    <button @click="showDetail = false"
                            class="px-8 py-2.5 text-white text-[12px] font-semibold hover:opacity-90 transition-all"
                            style="background:#27C2DE; border-radius:4px;">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </template>

    {{-- POPUP HAPUS (diperbarui) --}}
    <template x-teleport="body">
        <div x-show="showHapus" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center"
             style="background:rgba(0,0,0,0.35);">
            <div @click.outside="showHapus = false"
                 class="relative w-full max-w-sm" x-transition>
                <img src="{{ asset('ppdb/admin/delate.png') }}" class="w-full" alt="">
                <div class="absolute bottom-0 left-0 right-0 pb-8 px-8 text-center">
                    <h3 class="text-[15px] font-bold mb-2">
                        Apa anda yakin ingin menghapus data ini?
                    </h3>
                    <p class="text-[12px] text-slate-400 mb-5">
                        Tindakan ini tidak dapat dibatalkan
                    </p>
                    <div class="flex gap-3 justify-center">
                        <button type="button"
                                @click="showHapus = false"
                                class="px-6 py-2 rounded-xl border"
                                style="background:#FBFAF7; border-color:#A5A5A5;">
                            Batal
                        </button>
                        <button type="button"
                                @click="deleteData()"
                                class="px-6 py-2 rounded-xl text-white"
                                style="background:#EF4444;">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

</div>
@endsection