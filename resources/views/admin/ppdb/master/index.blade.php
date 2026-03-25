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
         style="box-shadow: 0 4px 4px rgba(161,209,251,0.25);">

        <table class="w-full border-collapse">
            <thead>
                <tr style="background:#C4F4FD;">
                    <th class="text-center py-3 px-6 text-[13px] font-semibold">Tahun Ajar</th>
                    <th class="text-center py-3 px-6 text-[13px] font-semibold">Status</th>
                    <th class="text-center py-3 px-6 text-[13px] font-semibold">Gelombang</th>
                    <th class="text-center py-3 px-6 text-[13px] font-semibold">Aksi</th>
                 </tr>
            </thead>

            <tbody>
                @forelse($data as $row)
                <tr class="even:bg-[#FEFEFE] odd:bg-[#F3F9FF] transition">
                    <td class="text-center py-4 text-[13px] font-medium">
                        {{ $row->tahun_ajaran }}
                     </td>
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
                    <td class="text-center py-4 text-[13px]">
                        {{ $row->gelombang ?? '-' }}
                     </td>
                    <td class="text-center py-4 flex justify-center gap-2">
                        {{-- TOMBOL DETAIL DENGAN LOGO PREVIEW.PNG --}}
                        <a href="{{ route('admin.master.detail', $row->id) }}"
                           class="flex items-center gap-1 px-4 py-1.5 text-white text-[12px] rounded-lg"
                           style="background: linear-gradient(90deg, #1AA2BA, #47E3FF);">
                           <img src="{{ asset('ppdb/admin/ditail.png') }}" alt="detail" class="w-4 h-4 object-contain">
                            Detail
                        </a>

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

                <div class="flex gap-3" x-data="{ status: '0' }">

                    <label class="cursor-pointer">
                        <input type="radio" name="is_active" value="1" class="hidden" x-model="status">
                        <span
                            class="px-4 py-1 text-xs border rounded font-semibold transition-all duration-200"
                            :class="status === '1'
                                ? 'bg-green-100 text-green-600 border-green-600 scale-105 shadow-sm'
                                : 'bg-gray-100 text-gray-400 border-gray-200 hover:bg-green-50 hover:text-green-500 hover:border-green-400'">
                            Aktif
                        </span>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="is_active" value="0" class="hidden" x-model="status">
                        <span
                            class="px-4 py-1 text-xs border rounded font-semibold transition-all duration-200"
                            :class="status === '0'
                                ? 'bg-red-100 text-red-500 border-red-500 scale-105 shadow-sm'
                                : 'bg-gray-100 text-gray-400 border-gray-200 hover:bg-red-50 hover:text-red-500 hover:border-red-400'">
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