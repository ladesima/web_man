@extends('layouts.admin')

@section('title', 'Akun Panitia')

@section('content')
<style>
.custom-check { appearance:none; -webkit-appearance:none; width:20px; height:20px; border:none; border-radius:4px; background:white; cursor:pointer; position:relative; box-shadow:0 0 0 0.75px #2B2A28; }
.custom-check:checked { background:white; border:none; box-shadow:0 0 0 0.75px #2B2A28; }
.custom-check:checked::after { content:""; position:absolute; left:5px; top:2px; width:7px; height:11px; border:2px solid #27C2DE; border-top:none; border-left:none; transform:rotate(45deg); }
</style>

<div x-data="initPanitia()">

    {{-- Search + Tambah --}}
    <div class="flex items-center justify-between mb-4">
        <div class="relative w-72">
            <input type="text" placeholder="Cari nama panitia" x-model="search"
                   class="w-full rounded-xl pl-10 pr-4 py-2.5 text-[12px] border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        <button @click="showTambah = true"
                class="flex items-center gap-2 px-4 py-2.5 text-white text-[12px] font-semibold hover:opacity-90 transition-all"
                style="background:#27C2DE; border-radius:4px;">
            Tambah Akun
        </button>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow: 0px 2px 8px rgba(0,0,0,0.06);">
        <table class="w-full">
            <thead>
                <tr style="background:#C4F4FD;">
                    <th class="text-center py-3 px-4 text-[12px] font-semibold">No</th>
                    <th class="text-left py-3 px-4 text-[12px] font-semibold">Nama</th>
                    <th class="text-center py-3 px-4 text-[12px] font-semibold">Status</th>
                    <th class="text-center py-3 px-4 text-[12px] font-semibold">Username</th>
                    <th class="text-center py-3 px-4 text-[12px] font-semibold">Password</th>
                    <th class="text-center py-3 px-4 text-[12px] font-semibold">Terakhir Aktif</th>
                    <th class="text-center py-3 px-4 text-[12px] font-semibold">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <template x-for="(row, i) in filteredRows" :key="row.id">
                    <tr>
                        <td class="text-center py-3 px-4" x-text="i+1"></td>

                        <td class="py-3 px-4">
                            <p x-text="row.nama"></p>
                            <p class="text-slate-400 text-xs" x-text="row.email"></p>
                        </td>

                        <td class="text-center">
                            <span x-show="row.status === 'aktif'">Aktif</span>
                            <span x-show="row.status === 'tidak_aktif'">Tidak Aktif</span>
                        </td>

                        <td class="text-center" x-text="row.username"></td>

                        {{-- 🔥 FIX PASSWORD --}}
                        <td class="text-center text-slate-400"  x-text="row.password !== '-' ? row.password : '-'"></td>

                       <td class="text-center" x-text="row.last_login"></td>

                     <td class="text-center">
    <div class="flex items-center justify-center gap-1.5">

        {{-- DETAIL --}}
        <button @click="openDetail(row)"
            class="w-8 h-8 rounded-lg flex items-center justify-center hover:opacity-80 transition-all">
            <img src="{{ asset('ppdb/admin/detail.png') }}"
                 class="w-8 h-8 object-contain">
        </button>

        {{-- EDIT --}}
        <button @click="openEdit(row)"
            class="w-8 h-8 rounded-lg flex items-center justify-center hover:opacity-80 transition-all">
            <img src="{{ asset('ppdb/admin/edit.png') }}"
                 class="w-8 h-8 object-contain">
        </button>

        {{-- HAPUS --}}
        <button @click="openHapus(row)"
            class="w-8 h-8 rounded-lg flex items-center justify-center hover:opacity-80 transition-all">
            <img src="{{ asset('ppdb/admin/hapus.png') }}"
                 class="w-8 h-8 object-contain">
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

            <h2 class="text-[16px] font-bold text-[#2B2A28] mb-5">
                Tambah Akun Panitia
            </h2>

            {{-- 🔥 BACKEND TETAP --}}
            <form method="POST" action="{{ route('admin.manajemen.akun.store') }}">
                @csrf

                <div class="space-y-4">

                    {{-- Nama --}}
                    <div>
                        <label class="block text-[12px] font-semibold mb-1.5">Nama</label>
                        <input name="nama" type="text" placeholder="Nama panitia"
                               class="w-full rounded-xl px-4 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] focus:ring-2 focus:ring-[#27C2DE]">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-[12px] font-semibold mb-1.5">Email</label>
                        <input name="email" type="email" placeholder="Email panitia"
                               class="w-full rounded-xl px-4 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] focus:ring-2 focus:ring-[#27C2DE]">
                    </div>

                    {{-- Username --}}
                    <div>
                        <label class="block text-[12px] font-semibold mb-1.5">Username</label>
                        <input name="username" type="text" placeholder="Username"
                               class="w-full rounded-xl px-4 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] focus:ring-2 focus:ring-[#27C2DE]">
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-[12px] font-semibold mb-1.5">Password</label>
                        <input name="password" type="password" placeholder="Password"
                               class="w-full rounded-xl px-4 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] focus:ring-2 focus:ring-[#27C2DE]">
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-[12px] font-semibold mb-1.5">Status</label>
                        <div class="relative">
                            <select name="status"
                                    class="w-full appearance-none rounded-xl px-4 pr-9 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] focus:ring-2 focus:ring-[#27C2DE]">
                                <option value="">Pilih Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="tidak_aktif">Tidak Aktif</option>
                            </select>

                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400">
                                <path stroke="currentColor" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>

                </div>

                {{-- Tombol --}}
                <div class="flex justify-center gap-3 mt-6">
                    <button type="button"
                            @click="showTambah = false"
                            class="px-6 py-2.5 rounded-xl border text-[12px]">
                        Batal
                    </button>

                    {{-- 🔥 SUBMIT BENER --}}
                    <button type="submit"
                            class="px-8 py-2.5 text-white text-[12px] font-semibold"
                            style="background:#27C2DE;">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>
</template>

    {{-- POPUP HAPUS --}}
    <template x-teleport="body">
    <div x-show="showHapus" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center"
         style="background:rgba(0,0,0,0.35);">

        <div @click.outside="showHapus = false"
             class="relative w-full max-w-sm" x-transition>

            <img src="{{ asset('ppdb/admin/delate.png') }}" class="w-full">

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
                            class="px-6 py-2 rounded-xl border">
                        Batal
                    </button>

                    {{-- 🔥 DELETE BACKEND --}}
                    <button type="button"
                            @click="deleteAkun()"
                            class="px-6 py-2 rounded-xl text-white"
                            style="background:#EF4444;">
                        Ya, Hapus
                    </button>

                </div>
            </div>
        </div>
    </div>
</template>

    {{-- POPUP EDIT --}}
<template x-teleport="body">
    <div x-show="showEdit && selectedRow" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center"
         style="background:rgba(0,0,0,0.45); backdrop-filter:blur(2px);">

        <div @click.outside="showEdit = false"
             class="bg-white rounded-2xl p-7 shadow-2xl w-full max-w-md" x-transition>

            <h2 class="text-[16px] font-bold text-[#2B2A28] mb-5">
                Edit Akun Panitia
            </h2>

            {{-- 🔥 BACKEND PATCH --}}
           <form x-show="selectedRow"
      :action="'/admin/manajemen/akun/' + (selectedRow ? selectedRow.id : '')"
      method="POST">
                @csrf
                @method('PATCH')

                <div class="space-y-4">

                    {{-- Nama --}}
                    <div>
                        <label class="block text-[12px] font-semibold mb-1.5">Nama</label>
                        <input name="nama"
                               type="text"
                               x-model="selectedRow.nama"
                               class="w-full rounded-xl px-4 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] focus:ring-2 focus:ring-[#27C2DE]">
                    </div>

                    {{-- Username --}}
                    <div>
                        <label class="block text-[12px] font-semibold mb-1.5">Username</label>
                        <input name="username"
                               type="text"
                               x-model="selectedRow.username"
                               class="w-full rounded-xl px-4 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] focus:ring-2 focus:ring-[#27C2DE]">
                    </div>

                    {{-- Password Baru --}}
                    <div>
                        <label class="block text-[12px] font-semibold mb-1.5">Password Baru</label>
                        <input name="password"
                               type="password"
                               placeholder="Kosongkan jika tidak diubah"
                               class="w-full rounded-xl px-4 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] focus:ring-2 focus:ring-[#27C2DE]">
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-[12px] font-semibold mb-1.5">Status</label>
                        <div class="relative">
                            <select name="status"
                                    x-model="selectedRow.status"
                                    class="w-full appearance-none rounded-xl px-4 pr-9 py-3 text-[12px] border border-slate-200 bg-[#F8FAFC] focus:ring-2 focus:ring-[#27C2DE]">
                                <option value="aktif">Aktif</option>
                                <option value="tidak_aktif">Tidak Aktif</option>
                            </select>

                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400">
                                <path stroke="currentColor" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>

                </div>

                {{-- Tombol --}}
                <div class="flex justify-center gap-3 mt-6">

                    <button type="button"
                            @click="showEdit = false"
                            class="px-6 py-2.5 rounded-xl border text-[12px]">
                        Batal
                    </button>

                    {{-- 🔥 SUBMIT --}}
                    <button type="submit"
                            class="px-8 py-2.5 text-white text-[12px] font-semibold"
                            style="background:#27C2DE;">
                        Simpan
                    </button>

                </div>

            </form>
        </div>
    </div>
</template>

{{-- POPUP DETAIL --}}
<template x-teleport="body">
    <div x-show="showDetail && selectedRow" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center"
         style="background:rgba(0,0,0,0.45); backdrop-filter:blur(2px);">

        <div @click.outside="showDetail = false"
             class="bg-white rounded-2xl p-7 shadow-2xl w-full max-w-md">

            <h2 class="text-[16px] font-bold mb-5">
                Detail Akun Panitia
            </h2>

            <div class="space-y-3 text-[12px]">

                <div>
                    <b>Nama:</b>
                    <span x-text="selectedRow.nama"></span>
                </div>

                <div>
                    <b>Email:</b>
                    <span x-text="selectedRow.email"></span>
                </div>

                <div>
                    <b>Username:</b>
                    <span x-text="selectedRow.username"></span>
                </div>

                <div>
                    <b>Password:</b>
                    <span x-text="selectedRow.password"></span>
                </div>

                <div>
                    <b>Status:</b>
                    <span x-text="selectedRow.status"></span>
                </div>

                <div>
                    <b>Terakhir Aktif:</b>
                    <span x-text="selectedRow.last_login"></span>
                </div>

            </div>

            <div class="mt-6 text-center">
                <button @click="showDetail = false"
                        class="px-6 py-2 bg-[#27C2DE] text-white rounded-xl text-[12px]">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</template>

</div>
@endsection
<script>
function initPanitia() {
    return {
        showDetail: false,
        showTambah: false,
        showEdit: false,
        showHapus: false,
        selectedRow: {
    nama: '',
    username: '',
    status: ''
},
        rows: @json($panitias ?? []),
        search: '',

        get filteredRows() {
            return this.rows.filter(row =>
                (row.nama || '').toLowerCase().includes(this.search.toLowerCase())
            );
        },
        openDetail(row) {
    this.selectedRow = row;
    this.showDetail = true;
},

        openEdit(row) {
            this.selectedRow = { ...row };
            this.showEdit = true;
        },

        openHapus(row) {
            this.selectedRow = row;
            this.showHapus = true;
        },

        deleteAkun() {
            fetch('/admin/manajemen/akun/' + this.selectedRow.id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(() => {
                this.rows = this.rows.filter(i => i.id !== this.selectedRow.id);
                this.showHapus = false;
            })
            .catch(() => alert('Gagal hapus data'));
        }
    }
}
</script>