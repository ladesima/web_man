@extends('layouts.admin')

@section('title', 'FAQ & Bantuan')

@section('content')
<div x-data="{
    tab: 'faq',
    showTambahFaq: false,
    showJawabPopup: false,
    selectedPertanyaan: null,

    faqRows: [
        { id: 1, pertanyaan: 'Siapa saja yang dapat mendaftar di MAN Jeneponto?', status: 'aktif',     kategori: 'Pendaftaran',   urutan: 1, terakhir: '23/3/26 - 12:22' },
        { id: 2, pertanyaan: 'Apa saja jalur pendaftaran yang tersedia?',          status: 'tidak_aktif', kategori: 'Berkas',        urutan: 2, terakhir: '23/3/26 - 12:22' },
        { id: 3, pertanyaan: 'Bagaimana cara melakukan pendaftaran?',              status: 'tidak_aktif', kategori: 'Jalur Seleksi', urutan: 3, terakhir: '23/3/26 - 12:22' },
        { id: 4, pertanyaan: 'Bagaimana cara melakukan pendaftaran?',              status: 'tidak_aktif', kategori: 'Jadwal',        urutan: 4, terakhir: '23/3/26 - 12:22' },
    ],

    pertanyaanRows: [
        { id: 1, pertanyaan: 'Apakah bisa mendaftar lebih dari 1 jalur?', pengirim: 'maudy', email: 'maudy@gmail.com', status: 'sudah_dijawab', kategori: 'Pendaftaran' },
        { id: 2, pertanyaan: 'Kenapa saya tidak bisa login?',             pengirim: 'maudy', email: 'maudy@gmail.com', status: 'belum_dijawab', kategori: 'Berkas' },
        { id: 3, pertanyaan: 'Tutotrial Upload berkas',                   pengirim: 'maudy', email: 'maudy@gmail.com', status: 'belum_dijawab', kategori: 'Jalur Seleksi' },
        { id: 4, pertanyaan: 'NISN saya tidak ditemukan',                 pengirim: 'maudy', email: 'maudy@gmail.com', status: 'belum_dijawab', kategori: 'Jadwal' },
    ],

    openJawab(row) {
        this.selectedPertanyaan = row;
        this.showJawabPopup = true;
    }
}">

    {{-- ===== TABS ===== --}}
    <div class="flex items-center justify-between mb-5">
        <div class="flex gap-1 p-1 rounded-2xl bg-white" style="box-shadow: 0px 2px 8px rgba(0,0,0,0.06);">
            <button @click="tab = 'faq'"
                    :class="tab === 'faq' ? 'font-bold' : 'text-slate-400 font-normal'"
                    :style="tab === 'faq' ? 'background:#C9F5FF; color:#0E7490;' : 'background:transparent; color:#94A3B8;'"
                    class="px-6 py-2 rounded-xl text-[13px] transition-all">
                FAQ
            </button>
            <button @click="tab = 'pertanyaan'"
                    :class="tab === 'pertanyaan' ? 'font-bold' : 'text-slate-400 font-normal'"
                    :style="tab === 'pertanyaan' ? 'background:#C9F5FF; color:#0E7490;' : 'background:transparent; color:#94A3B8;'"
                    class="px-6 py-2 rounded-xl text-[13px] transition-all">
                Pertanyaan
            </button>
        </div>
    </div>

    {{-- ============================= --}}
    {{-- ===== TAB FAQ ===== --}}
    {{-- ============================= --}}
    <div x-show="tab === 'faq'" x-cloak>

        {{-- Filter + Tambah --}}
        <div class="flex gap-3 mb-4 items-center">
            <div class="relative flex-1 max-w-xs">
                <input type="text" placeholder="Cari"
                       class="w-full rounded-xl pl-9 pr-4 py-2.5 text-[12px] border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#27C2DE] transition-all">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <div class="relative">
                <select class="appearance-none rounded-xl pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
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

            <div class="relative">
                <select class="appearance-none rounded-xl pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
                    <option>Status</option>
                    <option>Aktif</option>
                    <option>Tidak Aktif</option>
                </select>
                <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>

            <div class="flex-1"></div>

            <button @click="showTambahFaq = true"
                    class="flex items-center gap-2 px-4 py-2.5 text-white text-[12px] font-semibold transition-all hover:opacity-90"
                    style="background:#27C2DE; border-radius:4px;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah FAQ
            </button>
        </div>

        {{-- Tabel FAQ --}}
        <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow: 0px 2px 8px rgba(0,0,0,0.06);">
            <table class="w-full">
                <thead>
                    <tr style="background:#C4F4FD;">
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">No</th>
                        <th class="text-left py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Pertanyaan</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Status</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Kategori</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Urutan</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Terakhir Diup...</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="(row, i) in faqRows" :key="row.id">
                        <tr class="hover:bg-slate-50 transition-all">
                            <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28]" x-text="i+1"></td>
                            <td class="py-3 px-4 text-[12px] text-[#2B2A28]" x-text="row.pertanyaan"></td>

                            {{-- Status Badge --}}
                            <td class="text-center py-3 px-4">
                                <span x-show="row.status === 'aktif'"
                                      class="px-3 py-1 text-[11px] font-normal" style="background:rgba(22,163,74,0.08); color:#16A34A; border:1px solid #16A34A; border-radius:8px;">Aktif</span>
                                <span x-show="row.status === 'tidak_aktif'"
                                      class="px-3 py-1 text-[11px] font-normal" style="background:rgba(148,163,184,0.08); color:#94A3B8; border:1px solid #CBD5E1; border-radius:8px;">Tidak Aktif</span>
                            </td>

                            {{-- Kategori Badge --}}
                            <td class="text-center py-3 px-4">
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

                            <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28]" x-text="row.urutan"></td>
                            <td class="text-center py-3 px-4 text-[12px] text-slate-400" x-text="row.terakhir"></td>

                            {{-- Aksi --}}
                            <td class="text-center py-3 px-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="w-7 h-7 rounded-lg flex items-center justify-center hover:bg-blue-50 transition-all" title="Edit">
                                        <img src="{{ asset('ppdb/admin/edit.png') }}" alt="edit" class="w-4 h-4 object-contain">
                                    </button>
                                    <button class="w-7 h-7 rounded-lg flex items-center justify-center hover:bg-red-50 transition-all" title="Hapus">
                                        <img src="{{ asset('ppdb/admin/hapus.png') }}" alt="hapus" class="w-4 h-4 object-contain">
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================================= --}}
    {{-- ===== TAB PERTANYAAN ===== --}}
    {{-- ================================= --}}
    <div x-show="tab === 'pertanyaan'" x-cloak>

        <div class="flex gap-3 mb-4 items-center">
            <div class="relative flex-1 max-w-xs">
                <input type="text" placeholder="Cari"
                       class="w-full rounded-xl pl-9 pr-4 py-2.5 text-[12px] border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#27C2DE] transition-all">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <div class="relative">
                <select class="appearance-none rounded-xl pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
                    <option>Status</option>
                    <option>Sudah Dijawab</option>
                    <option>Belum Dijawab</option>
                </select>
                <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow: 0px 2px 8px rgba(0,0,0,0.06);">
            <table class="w-full">
                <thead>
                    <tr style="background:#C4F4FD;">
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">No</th>
                        <th class="text-left py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Pertanyaan</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Pengirim</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Status</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Kategori</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="(row, i) in pertanyaanRows" :key="row.id">
                        <tr class="hover:bg-slate-50 transition-all">
                            <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28]" x-text="i+1"></td>
                            <td class="py-3 px-4 text-[12px] text-[#2B2A28]" x-text="row.pertanyaan"></td>

                            <td class="text-center py-3 px-4">
                                <p class="text-[12px] font-medium text-[#2B2A28]" x-text="row.pengirim"></p>
                                <p class="text-[10px] text-slate-400" x-text="row.email"></p>
                            </td>

                            <td class="text-center py-3 px-4">
                                <span x-show="row.status === 'sudah_dijawab'"
                                      class="px-3 py-1 text-[11px] font-normal" style="background:rgba(22,163,74,0.08); color:#16A34A; border:1px solid #16A34A; border-radius:8px;">Sudah dijawab</span>
                                <span x-show="row.status === 'belum_dijawab'"
                                      class="px-3 py-1 text-[11px] font-normal" style="background:rgba(148,163,184,0.08); color:#94A3B8; border:1px solid #CBD5E1; border-radius:8px;">Belum dijawab</span>
                            </td>

                            <td class="text-center py-3 px-4">
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

                            <td class="text-center py-3 px-4">
                                <button @click="openJawab(row)"
                                        class="px-4 py-1.5 text-white text-[11px] font-semibold transition-all hover:opacity-90"
                                        style="background:#27C2DE; border-radius:4px;">
                                    Balas
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== POPUP TAMBAH FAQ ===== --}}
    <template x-teleport="body">
        <div x-show="showTambahFaq" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center"
             style="background:rgba(0,0,0,0.35);">
            <div @click.outside="showTambahFaq = false"
                 class="bg-white rounded-2xl p-8 w-full max-w-2xl shadow-2xl" x-transition>

                <div class="mb-6">
                    <h2 class="text-[16px] font-bold text-[#2B2A28]">Tambah FAQ</h2>
                    <p class="text-[12px] text-slate-400 mt-0.5">Lorem ipsum dolor sit amet, consectetur adipiscing.</p>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[12px] font-semibold text-[#2B2A28] mb-1.5">Pertanyaan</label>
                            <textarea rows="3" class="w-full rounded-xl px-4 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] focus:outline-none focus:ring-2 focus:ring-[#27C2DE] resize-none transition-all" placeholder="Tulis pertanyaan..."></textarea>
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold text-[#2B2A28] mb-1.5">Jawaban</label>
                            <textarea rows="5" class="w-full rounded-xl px-4 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] focus:outline-none focus:ring-2 focus:ring-[#27C2DE] resize-none transition-all" placeholder="Tulis jawaban..."></textarea>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[12px] font-semibold text-[#2B2A28] mb-1.5">Status</label>
                            <div class="relative">
                                <select class="w-full appearance-none rounded-xl px-4 pr-9 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
                                    <option value="">Pilih Status</option>
                                    <option>Aktif</option>
                                    <option>Tidak Aktif</option>
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold text-[#2B2A28] mb-1.5">Kategori</label>
                            <div class="relative">
                                <select class="w-full appearance-none rounded-xl px-4 pr-9 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
                                    <option value="">Pilih Kategori</option>
                                    <option>Pendaftaran</option>
                                    <option>Berkas</option>
                                    <option>Jalur Seleksi</option>
                                    <option>Jadwal</option>
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold text-[#2B2A28] mb-1.5">Urutan</label>
                            <div class="relative">
                                <select class="w-full appearance-none rounded-xl px-4 pr-9 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
                                    <option value="">Pilih Urutan</option>
                                    @for ($u = 1; $u <= 20; $u++)
                                        <option value="{{ $u }}">{{ $u }}</option>
                                    @endfor
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center mt-7">
                    <button @click="showTambahFaq = false"
                            class="px-10 py-2.5 text-white text-[13px] font-semibold transition-all hover:opacity-90"
                            style="background:#27C2DE; border-radius:4px;">
                        Tambah
                    </button>
                </div>
            </div>
        </div>
    </template>

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
                        <textarea placeholder="Tulis jawaban..."
                            style="width:100%; height:110px; background:#F5F7FF; border-radius:10px; border:1px solid #DFEAF2; resize:none; padding:10px 12px; font-size:13px; font-family:Poppins,sans-serif; color:#2B2A28; outline:none;"></textarea>
                    </div>

                </div>

                <div style="display:flex; justify-content:center; margin-top:24px;">
                    <button @click="showJawabPopup = false"
                            style="width:120px; padding:8px; background:#27C2DE; border-radius:4px; border:none; cursor:pointer; color:#FAFEFF; font-size:14px; font-family:Poppins,sans-serif; font-weight:600;">
                        Kirim
                    </button>
                </div>

            </div>
        </div>
    </template>

</div>
@endsection