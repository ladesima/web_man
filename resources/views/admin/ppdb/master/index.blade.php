@extends('layouts.admin')

@section('title', 'Master PPDB')

@section('content')
<div x-data="{ showTambah: false }">

    {{-- ================= BUTTON TAMBAH ================= --}}
    <div class="flex justify-end mb-4">
        <button @click="showTambah = true"
                class="flex items-center gap-2 px-4 py-2 rounded-lg text-white text-[13px] font-semibold"
                style="background:#27C2DE;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Tahun Ajar
        </button>
    </div>

    {{-- ================= ALERT ================= --}}
    @if(session('success'))
        <div class="mb-4 px-4 py-2 rounded bg-green-100 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-2xl overflow-hidden"
         style="box-shadow: 0px 2px 8px rgba(0,0,0,0.06);">

        <table class="w-full">
            <thead>
                <tr style="background:#C4F4FD;">
                    <th class="text-center py-3 px-6 text-[13px] font-semibold">Tahun Ajar</th>
                    <th class="text-center py-3 px-6 text-[13px] font-semibold">Status</th>
                    <th class="text-center py-3 px-6 text-[13px] font-semibold">Gelombang</th>
                    <th class="text-center py-3 px-6 text-[13px] font-semibold">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($data as $row)
                <tr class="hover:bg-slate-50 transition">

                    {{-- TAHUN --}}
                    <td class="text-center py-4 text-[13px] font-medium">
                        {{ $row->tahun_ajaran }}
                    </td>

                    {{-- STATUS --}}
                    <td class="text-center py-4">
                        @if($row->is_active)
                            <span class="px-4 py-1 text-[11px] font-semibold rounded"
                                  style="background:#DCFCE7; color:#16A34A; border:1px solid #16A34A;">
                                Aktif
                            </span>
                        @else
                            <span class="px-4 py-1 text-[11px] font-semibold rounded"
                                  style="background:#FEE2E2; color:#EF4444; border:1px solid #EF4444;">
                                Tidak Aktif
                            </span>
                        @endif
                    </td>

                    {{-- GELOMBANG --}}
                    <td class="text-center py-4 text-[13px]">
                        {{ $row->gelombang ?? '-' }}
                    </td>

                    {{-- AKSI --}}
                    <td class="text-center py-4 flex justify-center gap-2">

                        {{-- DETAIL --}}
                        <a href="{{ route('admin.master.detail', $row->id) }}"
                           class="px-4 py-1.5 text-white text-[12px] rounded-lg"
                           style="background: linear-gradient(90deg, #47E3FF, #1AA2BA);">
                            Detail
                        </a>

                        {{-- AKTIFKAN --}}
                        @if(!$row->is_active)
                        <form action="{{ route('admin.master.activate', $row->id) }}" method="POST">
                            @csrf
                            <button class="px-3 py-1 text-[11px] rounded bg-green-500 text-white hover:bg-green-600">
                                Aktifkan
                            </button>
                        </form>
                        @endif

                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-6 text-gray-500 text-sm">
                        Belum ada data tahun ajar
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    {{-- ================= MODAL TAMBAH ================= --}}
    <form method="POST" action="{{ route('admin.master.store') }}">
    @csrf

    <div x-show="showTambah" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center"
         style="background:rgba(0,0,0,0.35);">

        <div @click.outside="showTambah = false"
             class="bg-white rounded-2xl p-8 w-full max-w-md shadow-xl">

            <h2 class="text-[16px] font-bold text-center mb-6">
                Tambah Tahun Ajar
            </h2>

            {{-- ERROR --}}
            @if($errors->any())
                <div class="mb-3 text-red-500 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- TAHUN AJAR --}}
            <div class="mb-4">
                <label class="text-[12px] font-medium">Tahun Ajar</label>
                <select name="tahun_ajaran"
                        required
                        class="w-full mt-1 px-3 py-2 rounded-lg text-sm"
                        style="background:#F5F7FF;">
                    <option value="">Pilih</option>
                    <option>2026/2027</option>
                    <option>2027/2028</option>
                    <option>2028/2029</option>
                </select>
            </div>

            {{-- STATUS --}}
            <div class="mb-4">
                <label class="text-[12px] font-medium">Status</label>

                <div class="flex gap-2 mt-2">

                    <label class="cursor-pointer">
                        <input type="radio" name="is_active" value="1" class="hidden">
                        <span class="px-4 py-1 text-xs border rounded"
                              style="background:#DCFCE7; color:#16A34A;">
                            Aktif
                        </span>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="is_active" value="0" class="hidden" checked>
                        <span class="px-4 py-1 text-xs border rounded"
                              style="background:#FEE2E2; color:#EF4444;">
                            Tidak Aktif
                        </span>
                    </label>

                </div>
            </div>

            {{-- GELOMBANG --}}
            <div class="mb-6">
                <label class="text-[12px] font-medium">Gelombang</label>
                <select name="gelombang"
                        required
                        class="w-full mt-1 px-3 py-2 rounded-lg text-sm"
                        style="background:#F5F7FF;">
                    <option value="">Pilih</option>
                    <option value="I">I</option>
                    <option value="II">II</option>
                    <option value="III">III</option>
                </select>
            </div>

            {{-- BUTTON --}}
            <div class="flex justify-center gap-3">

                <button type="button"
                        @click="showTambah = false"
                        class="px-6 py-2 rounded-lg text-sm border">
                    Batal
                </button>

                <button type="submit"
                        class="px-8 py-2 rounded-xl text-white text-sm font-semibold"
                        style="background:#27C2DE;">
                    Simpan
                </button>

            </div>

        </div>
    </div>

    </form>

</div>
@endsection