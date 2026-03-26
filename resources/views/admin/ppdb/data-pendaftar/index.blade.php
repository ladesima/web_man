@extends('layouts.admin')
@section('title', 'Data Pendaftar')

@section('content')
<form method="GET">

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
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama/NISN"
                   class="bg-transparent text-[13px] text-slate-600 placeholder-slate-400 outline-none w-full">
        </div>

        {{-- Filter Jalur --}}
        <div class="relative flex-1">
            <select name="jalur"
                class="appearance-none border border-slate-200 pl-4 pr-8 py-2.5 text-[13px] text-slate-600 outline-none cursor-pointer w-full"
                style="background: #FAFEFF; border-radius: 8px;">
                <option value="">Jalur</option>
                <option value="reguler" {{ request('jalur')=='reguler'?'selected':'' }}>Reguler</option>
                <option value="prestasi" {{ request('jalur')=='prestasi'?'selected':'' }}>Prestasi</option>
                <option value="afirmasi" {{ request('jalur')=='afirmasi'?'selected':'' }}>Afirmasi</option>
            </select>
            <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        {{-- Filter Gelombang --}}
        <div class="relative flex-1">
            <select name="gelombang"
                class="appearance-none border border-slate-200 pl-4 pr-8 py-2.5 text-[13px] text-slate-600 outline-none cursor-pointer w-full"
                style="background: #FAFEFF; border-radius: 8px;">
                <option value="">Gelombang</option>
                <option value="1" {{ request('gelombang')=='1'?'selected':'' }}>Gelombang I</option>
                <option value="2" {{ request('gelombang')=='2'?'selected':'' }}>Gelombang II</option>
            </select>
            <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        {{-- Filter Status --}}
        <div class="relative flex-1">
            <select name="status"
                class="appearance-none border border-slate-200 pl-4 pr-8 py-2.5 text-[13px] text-slate-600 outline-none cursor-pointer w-full"
                style="background: #FAFEFF; border-radius: 8px;">
                <option value="">Status</option>
                <option value="belum" {{ request('status')=='belum'?'selected':'' }}>Menunggu</option>
                <option value="perbaikan" {{ request('status')=='perbaikan'?'selected':'' }}>Perbaikan</option>
                <option value="valid" {{ request('status')=='valid'?'selected':'' }}>Berkas Valid</option>
                <option value="lulus" {{ request('status')=='lulus'?'selected':'' }}>Diterima</option>
                <option value="tidak_lulus" {{ request('status')=='tidak_lulus'?'selected':'' }}>Ditolak</option>
            </select>
            <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        {{-- Filter Waktu --}}
        <div class="relative flex-1">
            <select name="waktu"
                class="appearance-none border border-slate-200 pl-4 pr-8 py-2.5 text-[13px] text-slate-600 outline-none cursor-pointer w-full"
                style="background: #FAFEFF; border-radius: 8px;">
                <option value="">Waktu</option>
                <option value="hari_ini" {{ request('waktu')=='hari_ini'?'selected':'' }}>Hari Ini</option>
                <option value="minggu_ini" {{ request('waktu')=='minggu_ini'?'selected':'' }}>Minggu Ini</option>
                <option value="bulan_ini" {{ request('waktu')=='bulan_ini'?'selected':'' }}>Bulan Ini</option>
            </select>
            <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

    </div>
</div>

</form>
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
    $badgeMap = [
        'menunggu'  => ['bg' => '#FFF3E0', 'text' => '#E65100', 'border' => '#FFCC80', 'label' => 'Menunggu'],
        'perbaikan' => ['bg' => '#E3F2FD', 'text' => '#1565C0', 'border' => '#90CAF9', 'label' => 'Perbaikan'],
        'valid'     => ['bg' => '#E8F5E9', 'text' => '#2E7D32', 'border' => '#A5D6A7', 'label' => 'Berkas Valid'],
        'lulus'     => ['bg' => '#E8F5E9', 'text' => '#2E7D32', 'border' => '#A5D6A7', 'label' => 'Diterima'],
        'tidak_lulus'=> ['bg' => '#FFEBEE', 'text' => '#C62828', 'border' => '#EF9A9A', 'label' => 'Ditolak'],
    ];
@endphp

@foreach($pendaftaran as $i => $row)

@php
    $status = match($row->status) {
        'belum', 'form_selesai' => 'menunggu',
        'perbaikan' => 'perbaikan',
        'lulus' => 'lulus',
        'tidak_lulus' => 'tidak_lulus',
        default => 'menunggu',
    };

    $badge = $badgeMap[$status];
@endphp

<tr class="border-t border-slate-100 hover:bg-[#F9FDFF] transition-colors">
    <td class="text-center text-slate-400 py-3.5 px-4">{{ $i + 1 }}</td>

    <td class="text-left text-slate-700 font-medium py-3.5 px-4 whitespace-nowrap">
        {{ $row->nama_lengkap ?? '-' }}
    </td>

    <td class="text-center text-slate-500 py-3.5 px-4 font-mono tracking-wide">
        {{ $row->nisn ?? '-' }}
    </td>

    <td class="text-center text-slate-600 py-3.5 px-4">
        {{ ucfirst($row->jalur ?? '-') }}
    </td>

    <td class="text-center text-slate-600 py-3.5 px-4">
        Gelombang {{ $row->gelombang ?? '-' }}
    </td>

    <td class="text-center py-3.5 px-4">
        <span class="inline-flex items-center px-3 py-1 rounded-[8px] text-[11px] font-semibold border"
            style="background:{{ $badge['bg'] }}; color:{{ $badge['text'] }}; border-color:{{ $badge['border'] }};">
            {{ $badge['label'] }}
        </span>
    </td>

    <td class="text-left text-slate-600 py-3.5 px-4 whitespace-nowrap">
        {{ $row->asal_sekolah ?? '-' }}
    </td>

    <td class="text-center py-3.5 px-4">
        <a href="{{ route('data-pendaftar.show', $row->id) }}"
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
<script>
document.querySelectorAll('select').forEach(el => {
    el.addEventListener('change', function () {
        this.closest('form').submit();
    });
});
</script>