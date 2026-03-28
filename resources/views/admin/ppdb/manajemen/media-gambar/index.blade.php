@extends('layouts.admin')
@section('title', 'Media Gambar')
@section('content')

<div class="grid grid-cols-1 md:grid-cols-3 gap-5">

    {{-- Sistem Informasi --}}
    <div class="bg-white rounded-2xl p-5 flex gap-4" style="box-shadow:0 2px 12px rgba(0,0,0,0.07);">
        <img src="{{ asset('ppdb/admin/operasional/sisfor.png') }}" alt="" class="w-24 h-24 object-contain shrink-0 self-start">
        <div class="flex flex-col gap-2">
            <p class="font-bold text-[14px] text-[#2B2A28]">Sistem Informasi</p>
            <p class="text-[11px] text-slate-400 leading-relaxed">Atur tampilan sistem informasi agar sesuai dengan kegiatan terbaru dan terlengkap</p>
            <a href="{{ route('admin.manajemen.media-gambar.sistem-informasi') }}"
               class="inline-block text-center px-4 py-2 rounded-lg text-white text-[12px] font-semibold transition-all hover:opacity-90 mt-1"
               style="background:#27C2DE;">
                Atur Tampilan
            </a>
        </div>
    </div>

    {{-- Siswa --}}
    <div class="bg-white rounded-2xl p-5 flex gap-4" style="box-shadow:0 2px 12px rgba(0,0,0,0.07);">
        <img src="{{ asset('ppdb/admin/operasional/siswa.png') }}" alt="" class="w-24 h-24 object-contain shrink-0 self-start">
        <div class="flex flex-col gap-2">
            <p class="font-bold text-[14px] text-[#2B2A28]">Siswa</p>
            <p class="text-[11px] text-slate-400 leading-relaxed">Atur tampilan user siswa agar sesuai dengan kegiatan anda</p>
            <a href="{{ route('admin.manajemen.media-gambar.siswa') }}"
               class="inline-block text-center px-4 py-2 rounded-lg text-white text-[12px] font-semibold transition-all hover:opacity-90 mt-1"
               style="background:#27C2DE;">
                Atur Tampilan
            </a>
        </div>
    </div>

    {{-- Admin --}}
    <div class="bg-white rounded-2xl p-5 flex gap-4" style="box-shadow:0 2px 12px rgba(0,0,0,0.07);">
        <img src="{{ asset('ppdb/admin/operasional/admin.png') }}" alt="" class="w-24 h-24 object-contain shrink-0 self-start">
        <div class="flex flex-col gap-2">
            <p class="font-bold text-[14px] text-[#2B2A28]">Admin</p>
            <p class="text-[11px] text-slate-400 leading-relaxed">Atur tampilan user Admin agar sesuai dengan kegiatan dan kebutuhan anda</p>
            <a href="{{ route('admin.manajemen.media-gambar.admin') }}"
               class="inline-block text-center px-4 py-2 rounded-lg text-white text-[12px] font-semibold transition-all hover:opacity-90 mt-1"
               style="background:#27C2DE;">
                Atur Tampilan
            </a>
        </div>
    </div>

    {{-- Panitia --}}
    <div class="bg-white rounded-2xl p-5 flex gap-4" style="box-shadow:0 2px 12px rgba(0,0,0,0.07);">
        <img src="{{ asset('ppdb/admin/operasional/panitia.png') }}" alt="" class="w-24 h-24 object-contain shrink-0 self-start">
        <div class="flex flex-col gap-2">
            <p class="font-bold text-[14px] text-[#2B2A28]">Panitia</p>
            <p class="text-[11px] text-slate-400 leading-relaxed">Atur tampilan user panitia agar sesuai dengan kegiatan dan kebutuhan anda</p>
            <a href="{{ route('admin.manajemen.media-gambar.panitia') }}"
               class="inline-block text-center px-4 py-2 rounded-lg text-white text-[12px] font-semibold transition-all hover:opacity-90 mt-1"
               style="background:#27C2DE;">
                Atur Tampilan
            </a>
        </div>
    </div>

</div>

@endsection