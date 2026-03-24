@extends('layouts.admin')
@section('title', 'Data Pendaftar')

@section('content')

{{-- Filter & Search Bar --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 px-5 py-4 mb-5">
    <div class="flex flex-wrap items-center gap-3">

        {{-- Search --}}
        <div class="flex items-center gap-2 bg-[#F2F8FF] rounded-xl px-4 py-2.5 flex-1 min-w-[180px] max-w-[360px] border border-slate-200">
            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
            <input type="text"
                   placeholder="Cari nama/NISN"
                   class="bg-transparent text-[13px] text-slate-600 placeholder-slate-400 outline-none w-full">
        </div>

        {{-- Filter Jalur --}}
        <div class="relative">
            <select class="appearance-none bg-[#F2F8FF] border border-slate-200 rounded-xl pl-4 pr-8 py-2.5 text-[13px] text-slate-600 outline-none cursor-pointer">
                <option value="">Jalur</option>
                <option>Reguler</option>
                <option>Prestasi</option>
                <option>Afirmasi</option>
            </select>
            <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        {{-- Filter Gelombang --}}
        <div class="relative">
            <select class="appearance-none bg-[#F2F8FF] border border-slate-200 rounded-xl pl-4 pr-8 py-2.5 text-[13px] text-slate-600 outline-none cursor-pointer">
                <option value="">Gelombang</option>
                <option>Gelombang I</option>
                <option>Gelombang II</option>
            </select>
            <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        {{-- Filter Status --}}
        <div class="relative">
            <select class="appearance-none bg-[#F2F8FF] border border-slate-200 rounded-xl pl-4 pr-8 py-2.5 text-[13px] text-slate-600 outline-none cursor-pointer">
                <option value="">Status</option>
                <option>Menunggu</option>
                <option>Perbaikan</option>
                <option>Berkas Valid</option>
                <option>Diterima</option>
                <option>Ditolak</option>
            </select>
            <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        {{-- Filter Waktu --}}
        <div class="relative">
            <select class="appearance-none bg-[#F2F8FF] border border-slate-200 rounded-xl pl-4 pr-8 py-2.5 text-[13px] text-slate-600 outline-none cursor-pointer">
                <option value="">Waktu</option>
                <option>Hari Ini</option>
                <option>Minggu Ini</option>
                <option>Bulan Ini</option>
            </select>
            <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

    </div>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[900px] text-[13px]">

            {{-- Header --}}
            <thead>
                <tr style="background: linear-gradient(90deg, #D6F3F8 0%, #E8FAFC 100%);">
                    <th class="text-center font-semibold text-[#2B2A28] py-3.5 px-4 whitespace-nowrap w-[50px]">No</th>
                    <th class="text-left   font-semibold text-[#2B2A28] py-3.5 px-4 whitespace-nowrap">Nama Peserta</th>
                    <th class="text-center font-semibold text-[#2B2A28] py-3.5 px-4 whitespace-nowrap">NISN</th>
                    <th class="text-center font-semibold text-[#2B2A28] py-3.5 px-4 whitespace-nowrap">Jalur</th>
                    <th class="text-center font-semibold text-[#2B2A28] py-3.5 px-4 whitespace-nowrap">Gelombang</th>
                    <th class="text-center font-semibold text-[#2B2A28] py-3.5 px-4 whitespace-nowrap">Status Verifikasi</th>
                    <th class="text-left   font-semibold text-[#2B2A28] py-3.5 px-4 whitespace-nowrap">Asal Sekolah</th>
                    <th class="text-center font-semibold text-[#2B2A28] py-3.5 px-4 whitespace-nowrap">Aksi</th>
                </tr>
            </thead>

            {{-- Body --}}
            <tbody>
                @forelse($pendaftaran as $index => $item)
                @php
                    $status = $item->status ?? 'menunggu';
                    $badge = match($status) {
                        'menunggu'  => ['bg' => '#FFF3E0', 'text' => '#E65100', 'border' => '#FFCC80', 'label' => 'Menunggu'],
                        'perbaikan' => ['bg' => '#E3F2FD', 'text' => '#1565C0', 'border' => '#90CAF9', 'label' => 'Perbaikan'],
                        'valid'     => ['bg' => '#E8F5E9', 'text' => '#2E7D32', 'border' => '#A5D6A7', 'label' => 'Berkas Valid'],
                        'diterima'  => ['bg' => '#E8F5E9', 'text' => '#2E7D32', 'border' => '#A5D6A7', 'label' => 'Diterima'],
                        'ditolak'   => ['bg' => '#FFEBEE', 'text' => '#C62828', 'border' => '#EF9A9A', 'label' => 'Ditolak'],
                        default     => ['bg' => '#F5F5F5', 'text' => '#616161', 'border' => '#E0E0E0', 'label' => ucfirst($status)],
                    };
                @endphp
                <tr class="border-t border-slate-100 hover:bg-[#F9FDFF] transition-colors">
                    <td class="text-center text-slate-400 py-3.5 px-4">
                        {{ $pendaftaran->firstItem() + $index }}
                    </td>
                    <td class="text-left text-slate-700 font-medium py-3.5 px-4 whitespace-nowrap">
                        {{ $item->ppdbUser->nama_lengkap ?? '-' }}
                    </td>
                    <td class="text-center text-slate-500 py-3.5 px-4 font-mono tracking-wide">
                        {{ $item->ppdbUser->nisn ?? '-' }}
                    </td>
                    <td class="text-center text-slate-600 py-3.5 px-4 capitalize">
                        {{ ucfirst($item->jalur ?? '-') }}
                    </td>
                    <td class="text-center text-slate-600 py-3.5 px-4">
                        {{ $item->gelombang ? 'Gelombang ' . $item->gelombang : '-' }}
                    </td>
                    <td class="text-center py-3.5 px-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold border"
                              style="background:{{ $badge['bg'] }}; color:{{ $badge['text'] }}; border-color:{{ $badge['border'] }};">
                            {{ $badge['label'] }}
                        </span>
                    </td>
                    <td class="text-left text-slate-600 py-3.5 px-4 whitespace-nowrap">
                        {{ $item->asal_sekolah ?? '-' }}
                    </td>
                    <td class="text-center py-3.5 px-4">
                        <a href="#"
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-white text-[12px] font-semibold transition-all hover:opacity-90 active:scale-95"
                           style="background: linear-gradient(90deg, #15B2CE 0%, #00758A 100%);">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-16">
                        <div class="flex flex-col items-center gap-3">
                            <svg class="w-14 h-14 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-[13px] text-slate-400">Belum ada data pendaftar</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

 

</div>

@endsection