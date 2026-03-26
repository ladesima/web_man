@extends('layouts.panitia')
@section('title', 'Data Pendaftar')

@section('content')

{{-- Filter & Search Bar --}}
<div class="mb-5">
    <div class="flex items-center gap-3 w-full">

        {{-- Search --}}
       <div class="flex items-center gap-2 px-4 py-2.5 flex-[2] border border-slate-200"
     style="background: #FAFEFF; border-radius: 8px;">
            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
            <input type="text"
                   placeholder="Cari nama/NISN"
                   class="bg-transparent text-[13px] text-slate-600 placeholder-slate-400 outline-none w-full">
        </div>

        {{-- Filter Jalur --}}
        <div class="relative flex-1">
            <select class="appearance-none border border-slate-200 pl-4 pr-8 py-2.5 text-[13px] text-slate-600 outline-none cursor-pointer w-full"
                    style="background: #FAFEFF; border-radius: 8px;">
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
        <div class="relative flex-1">
            <select class="appearance-none border border-slate-200 pl-4 pr-8 py-2.5 text-[13px] text-slate-600 outline-none cursor-pointer w-full"
                    style="background: #FAFEFF; border-radius: 8px;">
                <option value="">Gelombang</option>
                <option>Gelombang I</option>
                <option>Gelombang II</option>
            </select>
            <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        {{-- Filter Status --}}
        <div class="relative flex-1">
            <select class="appearance-none border border-slate-200 pl-4 pr-8 py-2.5 text-[13px] text-slate-600 outline-none cursor-pointer w-full"
                    style="background: #FAFEFF; border-radius: 8px;">
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
        <div class="relative flex-1">
            <select class="appearance-none border border-slate-200 pl-4 pr-8 py-2.5 text-[13px] text-slate-600 outline-none cursor-pointer w-full"
                    style="background: #FAFEFF; border-radius: 8px;">
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
                <tr style="background: #C4F4FD;">
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
                @php
                    $dummy = [
                        ['nama' => 'Ahmad Sahroni',       'nisn' => '0012345678', 'jalur' => 'Prestasi',  'gelombang' => 'I',  'status' => 'valid',     'sekolah' => 'SMP Negeri 1 Jeneponto'],
                        ['nama' => 'Siti Nurhaliza',       'nisn' => '0023456789', 'jalur' => 'Reguler',   'gelombang' => 'I',  'status' => 'menunggu',  'sekolah' => 'SMP Negeri 2 Jeneponto'],
                        ['nama' => 'Muhammad Naufal',      'nisn' => '0034567890', 'jalur' => 'Afirmasi',  'gelombang' => 'II', 'status' => 'perbaikan', 'sekolah' => 'MTs Negeri 1 Makassar'],
                        ['nama' => 'Zahara Liberty',       'nisn' => '0045678901', 'jalur' => 'Prestasi',  'gelombang' => 'I',  'status' => 'diterima',  'sekolah' => 'SMP Islam Terpadu Makassar'],
                        ['nama' => 'Zony Erikson',         'nisn' => '0056789012', 'jalur' => 'Reguler',   'gelombang' => 'II', 'status' => 'ditolak',   'sekolah' => 'SMP Negeri 3 Jeneponto'],
                        ['nama' => 'Nurul Aisyah',         'nisn' => '0067890123', 'jalur' => 'Afirmasi',  'gelombang' => 'I',  'status' => 'menunggu',  'sekolah' => 'MTs Swasta Al-Ikhlas'],
                        ['nama' => 'Bagas Firmansyah',     'nisn' => '0078901234', 'jalur' => 'Reguler',   'gelombang' => 'II', 'status' => 'valid',     'sekolah' => 'SMP Negeri 4 Jeneponto'],
                        ['nama' => 'Putri Ramadhani',      'nisn' => '0089012345', 'jalur' => 'Prestasi',  'gelombang' => 'I',  'status' => 'perbaikan', 'sekolah' => 'SMP Muhammadiyah 1 Makassar'],
                    ];
                    $badgeMap = [
                        'menunggu'  => ['bg' => '#FFF3E0', 'text' => '#E65100', 'border' => '#FFCC80', 'label' => 'Menunggu'],
                        'perbaikan' => ['bg' => '#E3F2FD', 'text' => '#1565C0', 'border' => '#90CAF9', 'label' => 'Perbaikan'],
                        'valid'     => ['bg' => '#E8F5E9', 'text' => '#2E7D32', 'border' => '#A5D6A7', 'label' => 'Berkas Valid'],
                        'diterima'  => ['bg' => '#E8F5E9', 'text' => '#2E7D32', 'border' => '#A5D6A7', 'label' => 'Diterima'],
                        'ditolak'   => ['bg' => '#FFEBEE', 'text' => '#C62828', 'border' => '#EF9A9A', 'label' => 'Ditolak'],
                    ];
                @endphp

                @foreach($dummy as $i => $row)
                @php $badge = $badgeMap[$row['status']]; @endphp
                <tr class="border-t border-slate-100 hover:bg-[#F9FDFF] transition-colors">
                    <td class="text-center text-slate-400 py-3.5 px-4">{{ $i + 1 }}</td>
                    <td class="text-left text-slate-700 font-medium py-3.5 px-4 whitespace-nowrap">{{ $row['nama'] }}</td>
                    <td class="text-center text-slate-500 py-3.5 px-4 font-mono tracking-wide">{{ $row['nisn'] }}</td>
                    <td class="text-center text-slate-600 py-3.5 px-4">{{ $row['jalur'] }}</td>
                    <td class="text-center text-slate-600 py-3.5 px-4">Gelombang {{ $row['gelombang'] }}</td>
                    <td class="text-center py-3.5 px-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-[8px] text-[11px] font-semibold border"
                            style="background:{{ $badge['bg'] }}; color:{{ $badge['text'] }}; border-color:{{ $badge['border'] }};">
                            {{ $badge['label'] }}
                        </span>
                    </td>
                    <td class="text-left text-slate-600 py-3.5 px-4 whitespace-nowrap">{{ $row['sekolah'] }}</td>
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
                @endforeach

            </tbody>
        </table>
    </div>
</div>

@endsection