@extends('layouts.admin')

@section('title', 'Master PPDB')

@section('content')
<div x-data="{
    showTambah: false,
    statusForm: 'aktif',
}">

    {{-- Tombol Tambah --}}
    <div class="flex justify-end mb-4">
        <button @click="showTambah = true"
                class="flex items-center gap-2 px-4 py-2 rounded-l text-white text-[13px] font-semibold"
                style="background:#27C2DE;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Tahun Ajar
        </button>
    </div>

    {{-- Tabel Tahun Ajar --}}
    <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow: 0px 2px 8px rgba(0,0,0,0.06);">
        <table class="w-full">
            <thead>
                <tr style="background:#C4F4FD;">
                    <th class="text-center py-3 px-8 text-[13px] font-semibold text-[#2B2A28]">Tahun Ajar</th>
                    <th class="text-center py-3 px-8 text-[13px] font-semibold text-[#2B2A28]">Status</th>
                    <th class="text-center py-3 px-8 text-[13px] font-semibold text-[#2B2A28]">Gelombang</th>
                    <th class="text-center py-3 px-8 text-[13px] font-semibold text-[#2B2A28]">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @php
                $rows = [
                    ['tahun' => '2026/2027', 'status' => 'aktif',       'gelombang' => 'I'],
                    ['tahun' => '2025/2026', 'status' => 'tidak_aktif', 'gelombang' => 'I'],
                    ['tahun' => '2024/2025', 'status' => 'tidak_aktif', 'gelombang' => 'I'],
                    ['tahun' => '2023/2024', 'status' => 'tidak_aktif', 'gelombang' => 'I'],
                ];
                @endphp

                @foreach($rows as $row)
                <tr class="hover:bg-slate-50 transition-all">
                    <td class="text-center py-4 px-8 text-[13px] font-medium text-[#2B2A28]">
                        {{ $row['tahun'] }}
                    </td>
                    <td class="text-center py-4 px-8">
                        @if($row['status'] === 'aktif')
                            <span class="px-4 py-1 text-[11px] font-semibold"
                                  style="background:#DCFCE7; color:#16A34A; border: 1px solid #16A34A; border-radius:4px;">Aktif</span>
                        @else
                            <span class="px-4 py-1 text-[11px] font-semibold"
                                  style="background:#FEE2E2; color:#EF4444; border: 1px solid #EF4444; border-radius:4px;">
                                Tidak Aktif
                            </span>
                        @endif
                    </td>
                    <td class="text-center py-4 px-8 text-[13px] text-[#2B2A28]">
                        {{ $row['gelombang'] }}
                    </td>
                    <td class="text-center py-4 px-8">
                        <a href="{{ route('admin.master.detail', str_replace('/', '-', $row['tahun'])) }}"
                           class="px-5 py-1.5 rounded-lg text-[12px] font-semibold text-white transition-all"
                           style="background: linear-gradient(90deg, #47E3FF, #1AA2BA);">
                            Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ===== MODAL TAMBAH TAHUN AJAR ===== --}}
    <div x-show="showTambah" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center"
         style="background:rgba(0,0,0,0.35);">
        <div @click.outside="showTambah = false"
             class="bg-white rounded-2xl p-8 w-full max-w-sm shadow-2xl" x-transition>

            <h2 class="text-[16px] font-bold text-[#2B2A28] text-center mb-6">Tambah Tahun Ajar</h2>

            <div class="space-y-4">
                {{-- Tahun Ajar --}}
                <div>
                    <label class="block text-[12px] font-medium text-[#2B2A28] mb-1.5">Tahun Ajar</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg px-3 py-2.5 text-[13px] text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#27C2DE] border-0"
                                style="background:#F5F7FF;">
                            <option value=""></option>
                            <option>2027/2028</option>
                            <option>2026/2027</option>
                            <option>2025/2026</option>
                        </select>
                        <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-[12px] font-medium text-[#2B2A28] mb-1.5">Status</label>
                    <div class="flex gap-2">
                        <button type="button"
                                @click="statusForm = 'aktif'"
                                :style="statusForm === 'aktif'
                                    ? 'background:#DCFCE7; color:#16A34A; border:1px solid #16A34A;'
                                    : 'background:#F5F7FF; color:#aaa; border:1px solid #e2e8f0;'"
                                style="border-radius:4px;"
                                class="px-5 py-1.5 text-[12px] font-semibold transition-all">
                            Aktif
                        </button>
                        <button type="button"
                                @click="statusForm = 'tidak_aktif'"
                                :style="statusForm === 'tidak_aktif'
                                    ? 'background:#FEE2E2; color:#EF4444; border:1px solid #EF4444;'
                                    : 'background:#F5F7FF; color:#aaa; border:1px solid #e2e8f0;'"
                                style="border-radius:4px;"
                                class="px-5 py-1.5 text-[12px] font-semibold transition-all">
                            Tidak Aktif
                        </button>
                    </div>
                </div>

                {{-- Gelombang --}}
                <div>
                    <label class="block text-[12px] font-medium text-[#2B2A28] mb-1.5">Gelombang</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg px-3 py-2.5 text-[13px] text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#27C2DE] border-0"
                                style="background:#F5F7FF;">
                            <option value=""></option>
                            <option>I</option>
                            <option>II</option>
                            <option>III</option>
                        </select>
                        <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                {{-- Simpan --}}
                <div class="flex justify-center pt-3">
                    <button class="px-10 py-2.5 rounded-xl text-white text-[13px] font-semibold"
                            style="background:#27C2DE;">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection