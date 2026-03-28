@extends('layouts.admin')

@section('title', 'FAQ & Bantuan')

@section('content')
<style>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
.card-shadow {
    box-shadow: 0px 4px 4px 0px rgba(161, 209, 251, 0.25);
}
</style>
<div x-data='{
    tab: "faq",
    showJawabPopup: false,
    selectedPertanyaan: null,

    pertanyaanRows: @json($pertanyaans),
    faqRows: @json($faqs),

    jawabanText: "",

    searchPertanyaan: "",
    filterstatusPertanyaan: "",

    search: "",
    filterKategori: "",
    filterStatus: "",

    get filteredFaqRows() {
        return this.faqRows.filter(row => {
            const matchSearch = row.pertanyaan.toLowerCase().includes(this.search.toLowerCase());

            const matchKategori =
                this.filterKategori === "" || this.filterKategori === "Kategori"
                    ? true
                    : row.kategori === this.filterKategori;

            const matchStatus =
                this.filterStatus === "" || this.filterStatus === "Status"
                    ? true
                    : (this.filterStatus === "Aktif"
                        ? row.status === "aktif"
                        : row.status === "tidak_aktif");

            return matchSearch && matchKategori && matchStatus;
        });
    },

    get filteredPertanyaanRows() {
        return this.pertanyaanRows.filter(row => {
            const matchSearch = row.pertanyaan.toLowerCase().includes(this.searchPertanyaan.toLowerCase());

            const matchStatus =
                this.filterstatusPertanyaan === "" ||
                this.filterstatusPertanyaan === "Status"
                    ? true
                    : (this.filterstatusPertanyaan === "Sudah Dijawab"
                        ? row.status === "sudah_dijawab"
                        : row.status === "belum_dijawab");

            return matchSearch && matchStatus;
        });
    },

    toggleStatus(row) {
        fetch("/admin/operasional/faq/" + row.id + "/toggle", {
            method: "PATCH",
            headers: {
                "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content,
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                row.status = data.status;
                this.faqRows = [...this.faqRows];
            }
        });
    },

    deleteFaq(row) {
        if (!confirm("Yakin ingin menghapus FAQ ini?")) return;

        fetch("/admin/operasional/faq/" + row.id, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content,
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                this.faqRows = this.faqRows.filter(item => item.id !== row.id);
            }
        });
    },

    openJawab(row) {
        this.selectedPertanyaan = row;
        this.jawabanText = row.jawaban || "";
        this.showJawabPopup = true;
    },

    kirimJawaban() {
        if (!this.selectedPertanyaan || !this.jawabanText) {
            alert("Jawaban tidak boleh kosong");
            return;
        }

        fetch("/admin/operasional/pertanyaan/" + this.selectedPertanyaan.id + "/jawab", {
            method: "PATCH",
            headers: {
                "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content,
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({
                jawaban: this.jawabanText
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                this.selectedPertanyaan.status = "sudah_dijawab";
                this.pertanyaanRows = [...this.pertanyaanRows];

                this.jawabanText = "";
                this.showJawabPopup = false;
            }
        });
    }
}'>

    {{-- ===== TAHUN AJARAN & STATUS ===== --}}

    {{-- ===== TABS ===== --}}
    <div class="flex items-center justify-between mb-5">
        <div class="flex gap-1 p-1 bg-white" style="border-radius:14px; box-shadow: 0px 2px 8px rgba(0,0,0,0.06); border: 1px solid #F0F0F0;">
            <button @click="tab = 'faq'"
                    :style="tab === 'faq'
                        ? 'background:#C4F4FD; color:#00758A; font-weight:700; border: 1px solid #C4F4FD; border-radius:10px;'
                        : 'background:transparent; color:#94A3B8; font-weight:400; border: 1px solid transparent; border-radius:10px;'"
                    class="px-6 py-1.5 text-[13px] transition-all">
                FAQ
            </button>
            <button @click="tab = 'pertanyaan'"
                    :style="tab === 'pertanyaan'
                        ? 'background:#C4F4FD; color:#00758A; font-weight:700; border: 1px solid #C4F4FD; border-radius:10px;'
                        : 'background:transparent; color:#94A3B8; font-weight:400; border: 1px solid #E2E8F0; border-radius:10px;'"
                    class="px-6 py-1.5 text-[13px] transition-all">
                Pertanyaan
            </button>
        </div>
    </div>

    {{-- ===== TAB FAQ ===== --}}
    <div x-show="tab === 'faq'" x-cloak>

        {{-- Filter + Tambah --}}
        <div class="flex gap-3 mb-4 items-center">
            <div class="relative flex-[2]">
                <input type="text" placeholder="Cari" x-model="search"
                       class="w-full pl-9 pr-4 py-2.5 text-[12px] border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#27C2DE] card-shadow transition-all"
                       style="border-radius:8px;">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <div class="relative flex-1">
                <select class="appearance-none w-full pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none card-shadow" x-model="filterKategori"
                        style="border-radius:8px;">
                    <option>Kategori</option>
                    <option>Pendaftaran</option>
                    <option>Berkas</option>
                    <option>Jalur Seleksi</option>
                    <option>Jadwal</option>
                </select>
                <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>

            <div class="relative flex-1">
                <select class="appearance-none w-full pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none card-shadow" x-model="filterStatus"
                        style="border-radius:8px;">
                    <option>Status</option>
                    <option>Aktif</option>
                    <option>Tidak Aktif</option>
                </select>
                <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>

            <div class="flex-1"></div>

            {{-- Tombol Tambah FAQ → pindah ke halaman baru --}}
            <a href="{{ route('admin.operasional.faq.tambah') }}"
               class="flex items-center gap-2 px-4 py-2.5 text-white text-[12px] transition-all hover:opacity-90"
               style="background:#27C2DE; border-radius:4px; font-weight:400;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah FAQ
            </a>
        </div>

        {{-- Tabel FAQ --}}
        <div class="bg-white rounded-2xl overflow-hidden card-shadow">
            <div class="w-full overflow-x-auto no-scrollbar">
                <table class="w-full min-w-[1200px] border-separate border-spacing-0">
                    <thead>
                        <tr style="background:#C4F4FD;">
                            <th class="text-center py-3 px-4 text-[13px] font-semibold text-[#2B2A28] sticky left-0 z-20 bg-[#C4F4FD]"
                                style="min-width:60px;">No</th>
                            <th class="text-left py-3 px-4 text-[13px] font-semibold text-[#2B2A28] sticky left-[60px] z-20 bg-[#C4F4FD]"
                                style="min-width:340px; box-shadow: 4px 0 4px 0 rgba(161,209,251,0.25);">Pertanyaan</th>
                            <th class="text-center py-3 px-4 text-[13px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:120px;">Status</th>
                            <th class="text-center py-3 px-4 text-[13px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:150px;">Kategori</th>
                            <th class="text-center py-3 px-4 text-[13px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:90px;">Urutan</th>
                            <th class="text-center py-3 px-4 text-[13px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:200px;">Terakhir Diupdate</th>
                            <th class="text-center py-3 px-4 text-[13px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(row, i) in filteredFaqRows" :key="row.id">
                            <tr class="hover:bg-slate-50 transition-all">
                                <td class="text-center py-3 px-4 text-[13px] text-[#2B2A28] sticky left-0 z-10 bg-white"
                                    x-text="i+1"></td>
                                <td class="py-3 px-4 text-[13px] text-[#2B2A28] sticky left-[60px] z-10 bg-white"
                                    style="box-shadow: 4px 0 4px 0 rgba(161,209,251,0.25);"
                                    x-text="row.pertanyaan"></td>

                                <td class="text-center py-3 px-4 whitespace-nowrap">
                                    <span x-show="row.status === 'aktif'"
                                          class="px-3 py-1 text-[12px] font-normal" style="background:rgba(22,163,74,0.08); color:#16A34A; border:1px solid #16A34A; border-radius:8px;">Aktif</span>
                                    <span x-show="row.status === 'tidak_aktif'"
                                          class="px-3 py-1 text-[12px] font-normal" style="background:rgba(148,163,184,0.08); color:#94A3B8; border:1px solid #CBD5E1; border-radius:8px;">Tidak Aktif</span>
                                </td>

                                <td class="text-center py-3 px-4 whitespace-nowrap">
                                    <template x-if="row.kategori === 'Pendaftaran'">
                                        <span class="px-3 py-1 text-[12px] font-normal" style="background:rgba(249,115,22,0.08); color:#F97316; border:1px solid #F97316; border-radius:8px;">Pendaftaran</span>
                                    </template>
                                    <template x-if="row.kategori === 'Berkas'">
                                        <span class="px-3 py-1 text-[12px] font-normal" style="background:rgba(236,72,153,0.08); color:#EC4899; border:1px solid #EC4899; border-radius:8px;">Berkas</span>
                                    </template>
                                    <template x-if="row.kategori === 'Jalur Seleksi'">
                                        <span class="px-3 py-1 text-[12px] font-normal" style="background:rgba(22,163,74,0.08); color:#16A34A; border:1px solid #16A34A; border-radius:8px;">Jalur Seleksi</span>
                                    </template>
                                    <template x-if="row.kategori === 'Jadwal'">
                                        <span class="px-3 py-1 text-[12px] font-normal" style="background:rgba(59,130,246,0.08); color:#3B82F6; border:1px solid #3B82F6; border-radius:8px;">Jadwal</span>
                                    </template>
                                </td>

                                <td class="text-center py-3 px-4 text-[13px] text-[#2B2A28] whitespace-nowrap" x-text="row.urutan"></td>
                                <td class="text-center py-3 px-4 text-[13px] text-[#575551] whitespace-nowrap" x-text="row.terakhir"></td>

                                <td class="text-center py-3 px-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">

                                      

                                      <button
        @click="window.location.href = '/admin/operasional/faq/' + row.id + '/edit'"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[12px] transition-all hover:opacity-80"
        style="border: 1.5px solid #16A34A; color:#16A34A; background:rgba(22,163,74,0.08); border-radius:6px; font-weight:400;">
    
    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="#16A34A" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
    </svg>

    Edit
</button>
 {{-- Tombol Hapus --}}
<button 
    @click="deleteFaq(row)"
    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[12px] transition-all hover:opacity-80"
    style="border: 1.5px solid #EF4444; color:#EF4444; background:rgba(239,68,68,0.08); border-radius:6px; font-weight:400;">
    
    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="#EF4444" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7v10m6-10v10M10 11v6m4-6v6M5 7l1-3h12l1 3"/>
    </svg>

    Hapus
</button>

                                        {{-- Tombol Aktifkan (jika tidak aktif) --}}
                                        <button x-show="row.status === 'tidak_aktif'"
        @click="toggleStatus(row)"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[12px] transition-all hover:opacity-80"
        style="border: 1.5px solid #F43F5E; color:#F43F5E; background:rgba(244,63,94,0.08); border-radius:6px; font-weight:400;">
    
    Aktifkan
</button>

                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== TAB PERTANYAAN ===== --}}
    <div x-show="tab === 'pertanyaan'" x-cloak>

        <div class="flex gap-3 mb-4 items-center">
            <div class="relative flex-[2]">
                <input type="text" placeholder="Cari" x-model="searchPertanyaan"
                       class="w-full pl-9 pr-4 py-2.5 text-[12px] border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#27C2DE] card-shadow transition-all"
                       style="border-radius:8px;">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <div class="relative flex-1">
                <select class="appearance-none w-full pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none card-shadow" x-model="filterstatusPertanyaan"
                        style="border-radius:8px;">
                    <option>Status</option>
                    <option>Sudah Dijawab</option>
                    <option>Belum Dijawab</option>
                </select>
                <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl overflow-hidden card-shadow">
            <div class="w-full overflow-x-auto no-scrollbar">
                <table class="w-full min-w-[800px] border-separate border-spacing-0">
                    <thead>
                        <tr style="background:#C4F4FD;">
                            <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] sticky left-0 z-20 bg-[#C4F4FD]"
                                style="min-width:50px;">No</th>
                            <th class="text-left py-3 px-4 text-[12px] font-semibold text-[#2B2A28] sticky left-[50px] z-20 bg-[#C4F4FD]"
                                style="min-width:240px; box-shadow: 4px 0 4px 0 rgba(161,209,251,0.25);">Pertanyaan</th>
                            <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:140px;">Pengirim</th>
                            <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:130px;">Status</th>
                            <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:130px;">Kategori</th>
                            <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(row, i) in filteredPertanyaanRows" :key="row.id">
                            <tr class="hover:bg-slate-50 transition-all">
                                <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28] sticky left-0 z-10 bg-white"
                                    x-text="i+1"></td>
                                <td class="py-3 px-4 text-[12px] text-[#2B2A28] sticky left-[50px] z-10 bg-white"
                                    style="box-shadow: 4px 0 4px 0 rgba(161,209,251,0.25);"
                                    x-text="row.pertanyaan"></td>

                                <td class="text-center py-3 px-4 whitespace-nowrap">
                                    <p class="text-[12px] font-medium text-[#2B2A28]" x-text="row.pengirim"></p>
                                    <p class="text-[10px] text-slate-400" x-text="row.email"></p>
                                </td>

                                <td class="text-center py-3 px-4 whitespace-nowrap">
                                    <span x-show="row.status === 'sudah_dijawab'"
                                          class="px-3 py-1 text-[11px] font-normal" style="background:rgba(22,163,74,0.08); color:#16A34A; border:1px solid #16A34A; border-radius:8px;">Sudah dijawab</span>
                                    <span x-show="row.status === 'belum_dijawab'"
                                          class="px-3 py-1 text-[11px] font-normal" style="background:rgba(148,163,184,0.08); color:#94A3B8; border:1px solid #CBD5E1; border-radius:8px;">Belum dijawab</span>
                                </td>

                                <td class="text-center py-3 px-4 whitespace-nowrap">
                                    <template x-if="row.kategori === 'Pendaftaran'">
                                        <span class="px-3 py-1 text-[11px] font-normal" style="background:rgba(249,115,22,0.08); color:#F97316; border:1px solid #F97316; border-radius:8px;">Pendaftaran</span>
                                    </template>
                                    <template x-if="row.kategori === 'Berkas'">
                                        <span class="px-3 py-1 text-[11px] font-normal" style="background:rgba(236,72,153,0.08); color:#EC4899; border:1px solid #EC4899; border-radius:8px;">Berkas</span>
                                    </template>
                                    <template x-if="row.kategori === 'Jalur Seleksi'">
                                        <span class="px-3 py-1 text-[11px] font-normal" style="background:rgba(22,163,74,0.08); color:#16A34A; border:1px solid #16A34A; border-radius:8px;">Jalur Seleksi</span>
                                    </template>
                                    <template x-if="row.kategori === 'Jadwal'">
                                        <span class="px-3 py-1 text-[11px] font-normal" style="background:rgba(59,130,246,0.08); color:#3B82F6; border:1px solid #3B82F6; border-radius:8px;">Jadwal</span>
                                    </template>
                                </td>

                                <td class="text-center py-3 px-4 whitespace-nowrap">
                                    <button @click="openJawab(row)"
                                            class="px-4 py-1.5 text-white text-[11px] transition-all hover:opacity-90"
                                            style="background:#27C2DE; border-radius:4px; font-weight:400;">
                                        Balas
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== POPUP JAWAB PERTANYAAN ===== --}}
    <template x-teleport="body">
        <div x-show="showJawabPopup" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center"
             style="background:rgba(0,0,0,0.45); backdrop-filter:blur(2px);">
            <div @click.outside="showJawabPopup = false"
                 x-transition
                 style="width:480px; position:relative; background:white; overflow:hidden; border-radius:12px; padding-bottom:32px;">

                <div style="padding: 24px 24px 0 24px;">
                    <p style="color:#2B2A28; font-size:16px; font-family:Poppins,sans-serif; font-weight:700;">Jawab Pertanyaan</p>
                </div>

                <div style="margin: 20px 32px 0 32px; display:flex; flex-direction:column; gap:12px;">

                    <div style="display:flex; flex-direction:column; gap:8px;">
                        <p style="color:#2B2A28; font-size:13px; font-family:Poppins,sans-serif; font-weight:600;">Kategori</p>
                        <div>
                            <template x-if="selectedPertanyaan && selectedPertanyaan.kategori === 'Pendaftaran'">
                                <span style="display:inline-flex; align-items:center; padding:4px 10px; border-radius:8px; background:rgba(249,115,22,0.08); border:1px solid #F97316; color:#F97316; font-size:12px; font-family:Poppins,sans-serif;">Pendaftaran</span>
                            </template>
                            <template x-if="selectedPertanyaan && selectedPertanyaan.kategori === 'Berkas'">
                                <span style="display:inline-flex; align-items:center; padding:4px 10px; border-radius:8px; background:rgba(236,72,153,0.08); border:1px solid #EC4899; color:#EC4899; font-size:12px; font-family:Poppins,sans-serif;">Berkas</span>
                            </template>
                            <template x-if="selectedPertanyaan && selectedPertanyaan.kategori === 'Jalur Seleksi'">
                                <span style="display:inline-flex; align-items:center; padding:4px 10px; border-radius:8px; background:rgba(22,163,74,0.08); border:1px solid #16A34A; color:#16A34A; font-size:12px; font-family:Poppins,sans-serif;">Jalur Seleksi</span>
                            </template>
                            <template x-if="selectedPertanyaan && selectedPertanyaan.kategori === 'Jadwal'">
                                <span style="display:inline-flex; align-items:center; padding:4px 10px; border-radius:8px; background:rgba(59,130,246,0.08); border:1px solid #3B82F6; color:#3B82F6; font-size:12px; font-family:Poppins,sans-serif;">Jadwal</span>
                            </template>
                        </div>
                    </div>

                    <div style="display:flex; flex-direction:column; gap:8px;">
                        <p style="color:#2B2A28; font-size:13px; font-family:Poppins,sans-serif; font-weight:600;">Pertanyaan</p>
                        <textarea :value="selectedPertanyaan ? selectedPertanyaan.pertanyaan : ''" readonly
                            style="width:100%; height:90px; background:#F5F7FF; border-radius:10px; border:1px solid #DFEAF2; resize:none; padding:10px 12px; font-size:13px; font-family:Poppins,sans-serif; color:#64748b; outline:none;"></textarea>
                    </div>

                    <div style="display:flex; flex-direction:column; gap:8px;">
                        <p style="color:#2B2A28; font-size:13px; font-family:Poppins,sans-serif; font-weight:600;">Jawaban</p>
                        <textarea x-model="jawabanText" placeholder="Tulis jawaban..."
                            style="width:100%; height:110px; background:#F5F7FF; border-radius:10px; border:1px solid #DFEAF2; resize:none; padding:10px 12px; font-size:13px; font-family:Poppins,sans-serif; color:#2B2A28; outline:none;"></textarea>
                    </div>

                </div>

                <div style="display:flex; justify-content:center; margin-top:24px;">
                    <button @click="kirimJawaban()"
                            style="width:120px; padding:8px; background:#27C2DE; border-radius:4px; border:none; cursor:pointer; color:#FAFEFF; font-size:14px; font-family:Poppins,sans-serif; font-weight:600;">
                        Kirim
                    </button>
                </div>

            </div>
        </div>
    </template>

</div>
@endsection