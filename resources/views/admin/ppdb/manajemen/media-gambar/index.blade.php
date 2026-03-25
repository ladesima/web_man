@extends('layouts.admin')
@section('title', 'Media Gambar')
@section('content')

<div class="grid grid-cols-1 md:grid-cols-3 gap-5">

    {{-- Sistem Informasi --}}
    <div class="bg-white rounded-2xl p-5 flex flex-col gap-3" style="box-shadow:0 2px 12px rgba(0,0,0,0.07);">
        <div class="flex items-center gap-4">
            <img src="{{ asset('ppdb/admin/media-si.png') }}" alt="" class="w-16 h-16 object-contain shrink-0">
            <div>
                <p class="font-bold text-[14px] text-[#2B2A28]">Sistem Informasi</p>
                <p class="text-[11px] text-slate-400 leading-relaxed mt-0.5">Atur tampilan sistem informasi agar sesuai dengan kegiatan terbaru dan terlengkap</p>
            </div>
        </div>
        <a href="{{ route('admin.manajemen.media-gambar.sistem-informasi') }}"
           class="block text-center py-2 rounded-lg text-white text-[12px] font-semibold transition-all hover:opacity-90"
           style="background:#27C2DE;">
            Atur Tampilan
        </a>
    </div>

    {{-- Siswa --}}
    <div class="bg-white rounded-2xl p-5 flex flex-col gap-3" style="box-shadow:0 2px 12px rgba(0,0,0,0.07);">
        <div class="flex items-center gap-4">
            <img src="{{ asset('ppdb/admin/media-siswa.png') }}" alt="" class="w-16 h-16 object-contain shrink-0">
            <div>
                <p class="font-bold text-[14px] text-[#2B2A28]">Siswa</p>
                <p class="text-[11px] text-slate-400 leading-relaxed mt-0.5">Atur tampilan user siswa agar sesuai dengan kegiatan anda</p>
            </div>
        </div>
        <a href="{{ route('admin.manajemen.media-gambar.siswa') }}"
           class="block text-center py-2 rounded-lg text-white text-[12px] font-semibold transition-all hover:opacity-90"
           style="background:#27C2DE;">
            Atur Tampilan
        </a>
    </div>

    {{-- Admin --}}
    <div class="bg-white rounded-2xl p-5 flex flex-col gap-3" style="box-shadow:0 2px 12px rgba(0,0,0,0.07);">
        <div class="flex items-center gap-4">
            <img src="{{ asset('ppdb/admin/media-admin.png') }}" alt="" class="w-16 h-16 object-contain shrink-0">
            <div>
                <p class="font-bold text-[14px] text-[#2B2A28]">Admin</p>
                <p class="text-[11px] text-slate-400 leading-relaxed mt-0.5">Atur tampilan user Admin agar sesuai dengan kegiatan dan kebutuhan anda</p>
            </div>
        </div>
        <a href="{{ route('admin.manajemen.media-gambar.admin') }}"
           class="block text-center py-2 rounded-lg text-white text-[12px] font-semibold transition-all hover:opacity-90"
           style="background:#27C2DE;">
            Atur Tampilan
        </a>
    </div>

    {{-- Panitia --}}
    <div class="bg-white rounded-2xl p-5 flex flex-col gap-3" style="box-shadow:0 2px 12px rgba(0,0,0,0.07);">
        <div class="flex items-center gap-4">
            <img src="{{ asset('ppdb/admin/media-panitia.png') }}" alt="" class="w-16 h-16 object-contain shrink-0">
            <div>
                <p class="font-bold text-[14px] text-[#2B2A28]">Panitia</p>
                <p class="text-[11px] text-slate-400 leading-relaxed mt-0.5">Atur tampilan user panitia agar sesuai dengan kegiatan dan kebutuhan anda</p>
            </div>
        </div>
        <a href="{{ route('admin.manajemen.media-gambar.panitia') }}"
           class="block text-center py-2 rounded-lg text-white text-[12px] font-semibold transition-all hover:opacity-90"
           style="background:#27C2DE;">
            Atur Tampilan
        </a>
    </div>

</div>

@endsection