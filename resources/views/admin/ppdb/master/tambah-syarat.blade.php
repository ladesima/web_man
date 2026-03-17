@extends('layouts.admin')

@section('title', 'Tambah Syarat')

@section('content')

<div class="bg-white rounded-2xl p-8 max-w-2xl"
     style="box-shadow: 0px 2px 8px rgba(0,0,0,0.06);">

    <div class="mb-6">
        <h2 class="text-[18px] font-bold text-[#27C2DE]">Tambah Syarat</h2>
        <p class="text-[12px] text-slate-400 mt-1">Harap, Syarat yang kamu masukkan sesuai dan detail</p>
    </div>

    <form class="space-y-5">

        {{-- Row 1: Nama Persyaratan + Ukuran --}}
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-[12px] font-medium text-[#2B2A28] mb-1.5">Nama Persyaratan</label>
                <input type="text"
                       class="w-full bg-[#F0F0F0] rounded-lg px-3 py-2.5 text-[13px] border-0 focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
            </div>
            <div>
                <label class="block text-[12px] font-medium text-[#2B2A28] mb-1.5">Ukuran</label>
                <input type="text"
                       class="w-full bg-[#F0F0F0] rounded-lg px-3 py-2.5 text-[13px] border-0 focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
            </div>
        </div>

        {{-- Row 2: Tipe Input + Kebutuhan --}}
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-[12px] font-medium text-[#2B2A28] mb-1.5">Tipe Input</label>
                <div class="relative">
                    <select class="w-full appearance-none bg-[#F0F0F0] rounded-lg px-3 py-2.5 text-[13px] border-0 focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
                        <option value=""></option>
                        <option>Teks</option>
                        <option>File</option>
                        <option>Angka</option>
                    </select>
                    <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div>
                <label class="block text-[12px] font-medium text-[#2B2A28] mb-1.5">Kebutuhan</label>
                <div class="relative">
                    <select class="w-full appearance-none bg-[#F0F0F0] rounded-lg px-3 py-2.5 text-[13px] border-0 focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
                        <option value=""></option>
                        <option>Wajib</option>
                        <option>Opsional</option>
                    </select>
                    <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Row 3: Format File + Catatan --}}
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-[12px] font-medium text-[#2B2A28] mb-1.5">Format File</label>
                <div class="relative">
                    <select class="w-full appearance-none bg-[#F0F0F0] rounded-lg px-3 py-2.5 text-[13px] border-0 focus:outline-none focus:ring-2 focus:ring-[#27C2DE]">
                        <option value=""></option>
                        <option>PDF</option>
                        <option>JPG, PNG</option>
                        <option>PDF, JPG, PNG</option>
                    </select>
                    <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div>
                <label class="block text-[12px] font-medium text-[#2B2A28] mb-1.5">Catatan</label>
                <textarea rows="3"
                          class="w-full bg-[#F0F0F0] rounded-lg px-3 py-2.5 text-[13px] border-0 focus:outline-none focus:ring-2 focus:ring-[#27C2DE] resize-none">
                </textarea>
            </div>
        </div>

        {{-- Simpan --}}
        <div class="flex justify-center pt-3">
            <button type="submit"
                    class="px-12 py-2.5 rounded-xl text-white text-[13px] font-semibold"
                    style="background: linear-gradient(90deg, #15B2CE, #00758A);">
                Simpan
            </button>
        </div>

    </form>
</div>

@endsection