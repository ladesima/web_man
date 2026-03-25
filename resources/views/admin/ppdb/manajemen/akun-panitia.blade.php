@extends('layouts.admin')

@section('title', 'Akun Panitia')

@section('content')
<style>
.custom-check { appearance:none; -webkit-appearance:none; width:20px; height:20px; border:none; border-radius:4px; background:white; cursor:pointer; position:relative; box-shadow:0 0 0 0.75px #2B2A28; }
.custom-check:checked { background:white; border:none; box-shadow:0 0 0 0.75px #2B2A28; }
.custom-check:checked::after { content:""; position:absolute; left:5px; top:2px; width:7px; height:11px; border:2px solid #27C2DE; border-top:none; border-left:none; transform:rotate(45deg); }
</style>
<div x-data="{
    showTambah: false,
    showEdit: false,
    showHapus: false,
    selectedRow: null,
    rows: [
        { id: 1, nama: 'Ayu',   email: '@ayu@gimel.kom',   status: 'aktif',     username: 'ayuuuuuu', password: '34238uw', terakhir: '1 jam yang lalu' },
        { id: 2, nama: 'Bagus', email: '@bagus@gmail.kom',  status: 'tidak_aktif', username: 'bagus',    password: '34238uw', terakhir: 'Kemarin 23:56' },
        { id: 3, nama: 'Pika',  email: '@gmail.kom',        status: 'tidak_aktif', username: 'pipika',   password: '34238uw', terakhir: '3 hari yang lalu' },
        { id: 4, nama: 'Ary',   email: '@ary@gimel.kom',    status: 'tidak_aktif', username: 'arryryr',  password: '34238uw', terakhir: '3 Maret 2026' },
    ],
    openEdit(row) { this.selectedRow = {...row}; this.showEdit = true; },
    openHapus(row) { this.selectedRow = row; this.showHapus = true; }
}">

    {{-- Search + Tambah --}}
    <div class="flex items-center justify-between mb-4">
        <div class="relative w-72">
            <input type="text" placeholder="Cari nama panitia" 
                   class="w-full rounded-xl pl-10 pr-4 py-2.5 text-[12px] border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <button @click="showTambah = true"
                class="flex items-center gap-2 px-4 py-2.5 text-white text-[12px] font-semibold hover:opacity-90 transition-all"
                style="background:#27C2DE; border-radius:4px;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Akun
        </button>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow: 0px 2px 8px rgba(0,0,0,0.06);">
        <table class="w-full">
            <thead>
                <tr style="background:#C4F4FD;">
                    <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">No</th>
                    <th class="text-left py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Nama</th>
                    <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Status</th>
                    <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Username</th>
                    <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Password</th>
                    <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]">Terakhir Aktif</th>
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
                        <td class="text-center py-3 px-4">
                            <span x-show="row.status === 'aktif'"
                                  class="px-3 py-1 text-[11px] font-normal"
                                  style="background:rgba(22,163,74,0.08); color:#16A34A; border:1px solid #16A34A; border-radius:8px;">Aktif</span>
                            <span x-show="row.status === 'tidak_aktif'"
                                  class="px-3 py-1 text-[11px] font-normal"
                                  style="background:rgba(148,163,184,0.08); color:#94A3B8; border:1px solid #CBD5E1; border-radius:8px;">Tidak Aktif</span>
                        </td>
                        <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28]" x-text="row.username"></td>
                        <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28]" x-text="row.password"></td>
                        <td class="text-center py-3 px-4 text-[12px] text-slate-500" x-text="row.terakhir"></td>
                        <td class="text-center py-3 px-4">
                            <div class="flex items-center justify-center gap-1.5">
                                {{-- Detail --}}
                                <button class="w-8 h-8 rounded-lg flex items-center justify-center hover:opacity-80 transition-all">
                                    <img src="{{ asset('ppdb/admin/detail.png') }}" alt="" class="w-8 h-8 object-contain">
                                </button>
                                {{-- Edit --}}
                                <button @click="openEdit(row)"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center hover:opacity-80 transition-all">
                                    <img src="{{ asset('ppdb/admin/edit.png') }}" alt="" class="w-8 h-8 object-contain">
                                </button>
                                {{-- Hapus --}}
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

    {{-- POPUP TAMBAH --}}
    <template x-teleport="body">
        <div x-show="showTambah" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center"
             style="background:rgba(0,0,0,0.45); backdrop-filter:blur(2px);">
            <div @click.outside="showTambah = false"
                 class="bg-white rounded-2xl p-7 shadow-2xl w-full max-w-md" x-transition>
                <h2 class="text-[16px] font-bold text-[#2B2A28] mb-5">Tambah Akun Panitia</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#2B2A28] mb-1.5">Nama</label>
                        <input type="text" placeholder="Nama panitia"
                               class="w-full rounded-xl px-4 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#2B2A28] mb-1.5">Email</label>
                        <input type="email" placeholder="Email panitia"
                               class="w-full rounded-xl px-4 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#2B2A28] mb-1.5">Username</label>
                        <input type="text" placeholder="Username"
                               class="w-full rounded-xl px-4 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#2B2A28] mb-1.5">Password</label>
                        <input type="password" placeholder="Password"
                               class="w-full rounded-xl px-4 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#2B2A28] mb-1.5">Status</label>
                        <div class="relative">
                            <select class="w-full appearance-none rounded-xl px-4 pr-9 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
                                <option value="">Pilih Status</option>
                                <option>Aktif</option>
                                <option>Tidak Aktif</option>
                            </select>
                            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center gap-3 mt-6">
                    <button @click="showTambah = false"
                            class="px-6 py-2.5 rounded-xl border border-slate-200 text-[12px] font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                        Batal
                    </button>
                    <button @click="showTambah = false"
                            class="px-8 py-2.5 text-white text-[12px] font-semibold hover:opacity-90 transition-all"
                            style="background:#27C2DE; border-radius:4px;">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </template>

    {{-- POPUP EDIT --}}
    <template x-teleport="body">
        <div x-show="showEdit" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center"
             style="background:rgba(0,0,0,0.45); backdrop-filter:blur(2px);">
            <div @click.outside="showEdit = false"
                 class="bg-white rounded-2xl p-7 shadow-2xl w-full max-w-md" x-transition>
                <h2 class="text-[16px] font-bold text-[#2B2A28] mb-5">Edit Akun Panitia</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#2B2A28] mb-1.5">Nama</label>
                        <input type="text" :value="selectedRow?.nama"
                               class="w-full rounded-xl px-4 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#2B2A28] mb-1.5">Username</label>
                        <input type="text" :value="selectedRow?.username"
                               class="w-full rounded-xl px-4 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#2B2A28] mb-1.5">Password Baru</label>
                        <input type="password" placeholder="Kosongkan jika tidak diubah"
                               class="w-full rounded-xl px-4 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-[#2B2A28] mb-1.5">Status</label>
                        <div class="relative">
                            <select class="w-full appearance-none rounded-xl px-4 pr-9 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
                                <option>Aktif</option>
                                <option>Tidak Aktif</option>
                            </select>
                            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center gap-3 mt-6">
                    <button @click="showEdit = false"
                            class="px-6 py-2.5 rounded-xl border border-slate-200 text-[12px] font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                        Batal
                    </button>
                    <button @click="showEdit = false"
                            class="px-8 py-2.5 text-white text-[12px] font-semibold hover:opacity-90 transition-all"
                            style="background:#27C2DE; border-radius:4px;">
                        Simpan
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
                                @click="showHapus = false"
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