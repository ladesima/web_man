@extends('layouts.admin')

@section('title', 'Review Email')

@section('content')
<style>
.card-shadow {
    box-shadow: 0px 4px 4px 0px rgba(161, 209, 251, 0.25);
}
.pesan-card {
    border: 0.75px solid #E2E8F0;
    border-radius: 12px;
    background: white;
    transition: box-shadow 0.2s;
}
.pesan-card:hover {
    box-shadow: 0px 4px 14px 0px rgba(39, 194, 222, 0.15);
}
.btn-detail {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 14px;
    font-size: 11px;
    font-weight: 600;
    color: #27C2DE;
    border: 1px solid #27C2DE;
    border-radius: 6px;
    background: white;
    cursor: pointer;
    transition: all 0.15s;
    text-decoration: none;
}
.btn-detail:hover {
    background: #27C2DE;
    color: white;
}
</style>

<div x-data="{ tab: 'review' }">

    {{-- ===== TABS + ACTION BUTTONS ===== --}}
    <div class="flex items-center justify-between mb-5">

        {{-- Tab --}}
        <div class="flex gap-1 p-1 bg-white" style="border-radius:14px; box-shadow: 0px 2px 8px rgba(0,0,0,0.06); border: 1px solid #F0F0F0;">
            <a href="{{ route('admin.operasional.pengumuman') }}"
               class="px-5 py-1.5 text-[13px] transition-all"
               style="background:transparent; color:#94A3B8; font-weight:400; border: 1px solid transparent; border-radius:10px;">
                Home
            </a>
            <button class="px-5 py-1.5 text-[13px] transition-all"
                    style="background:#C4F4FD; color:#00758A; font-weight:700; border: 1px solid #C4F4FD; border-radius:10px;">
                Riview Email
            </button>
        </div>

        {{-- Tambah Pesan --}}
        <a href="{{ route('admin.operasional.pengumuman.tambah') }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-white text-[12px] font-semibold hover:opacity-90 transition-all"
           style="background:#27C2DE; border-radius:8px;">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pesan
        </a>
    </div>

    {{-- ===== LIST PESAN ===== --}}
    <div class="flex flex-col gap-3">

        @php
        $pesanList = [
            [
                'id'      => 1,
                'judul'   => 'Perlu Perbaikan',
                'preview' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam eu turpis molestie, dictum est a, mattis tellus. Sed dignissim, metus nec fringilla accumsan, risus sem sollicitudin lacus, ut interdum tellus elit sed risus. Maecenas eget condimentum velit, sit amet feugiat lectus. Class aptent',
            ],
            [
                'id'      => 2,
                'judul'   => 'Lulus Seleksi Berkas',
                'preview' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam eu turpis molestie, dictum est a, mattis tellus. Sed dignissim, metus nec fringilla accumsan, risus sem sollicitudin lacus, ut interdum tellus elit sed risus. Maecenas eget condimentum velit, sit amet feugiat lectus. Class aptent',
            ],
            [
                'id'      => 3,
                'judul'   => 'Selamat, Anda adalah Bagian dari MAN JENEPONTO 🤩',
                'preview' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam eu turpis molestie, dictum est a, mattis tellus. Sed dignissim, metus nec fringilla accumsan, risus sem sollicitudin lacus, ut interdum tellus elit sed risus. Maecenas eget condimentum velit, sit amet feugiat lectus. Class aptent',
            ],
        ];
        @endphp

        @foreach($pesanList as $pesan)
        <div class="pesan-card px-5 py-4">
            <h3 class="text-[13px] font-bold text-[#2B2A28] mb-1">{{ $pesan['judul'] }}</h3>
            <p class="text-[12px] text-[#94A3B8] leading-relaxed mb-3 line-clamp-2">{{ $pesan['preview'] }}</p>
            <a href="{{ route('admin.operasional.pengumuman.pesan', $pesan['id']) }}" class="btn-detail">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m0 0l3-3m-3 3l3 3"/>
                </svg>
                Detail
            </a>
        </div>
        @endforeach

    </div>

</div>
@endsection