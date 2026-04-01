@extends('layouts.panitia')
@section('title', 'Data Pendaftar')

@section('content')

<form method="GET">

{{-- FILTER --}}
<div class="mb-5">
    <div class="flex items-center gap-3 w-full">

        {{-- SEARCH --}}
        <div class="flex items-center gap-2 px-4 py-2.5 flex-[2] border border-slate-200"
        style="background:#FAFEFF;border-radius:8px;box-shadow:0 4px 4px rgba(161,209,251,.25)">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor">
                <path stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                class="bg-transparent text-[13px] w-full outline-none"
                placeholder="Cari nama/NISN">
        </div>

        {{-- JALUR --}}
        <div class="relative flex-1">
            <select name="jalur"
                class="appearance-none border border-slate-200 pl-4 pr-8 py-2.5 text-[13px] text-slate-600 w-full"
                style="background:#FAFEFF;border-radius:8px;box-shadow:0 4px 4px rgba(161,209,251,.25)">
                
                <option value="">Jalur</option>
                <option value="reguler" {{ request('jalur')=='reguler'?'selected':'' }}>Reguler</option>
                <option value="prestasi" {{ request('jalur')=='prestasi'?'selected':'' }}>Prestasi</option>
                <option value="afirmasi" {{ request('jalur')=='afirmasi'?'selected':'' }}>Afirmasi</option>
            </select>
        </div>

        {{-- GELOMBANG --}}
        <div class="relative flex-1">
            <select name="gelombang"
                class="appearance-none border border-slate-200 pl-4 pr-8 py-2.5 text-[13px] text-slate-600 w-full"
                style="background:#FAFEFF;border-radius:8px;box-shadow:0 4px 4px rgba(161,209,251,.25)">
                
                <option value="">Gelombang</option>
                <option value="1" {{ request('gelombang')=='1'?'selected':'' }}>Gelombang I</option>
                <option value="2" {{ request('gelombang')=='2'?'selected':'' }}>Gelombang II</option>
            </select>
        </div>

        {{-- STATUS --}}
        <div class="relative flex-1">
            <select name="status"
                class="appearance-none border border-slate-200 pl-4 pr-8 py-2.5 text-[13px] text-slate-600 w-full"
                style="background:#FAFEFF;border-radius:8px;box-shadow:0 4px 4px rgba(161,209,251,.25)">
                
                <option value="">Status</option>
                <option value="belum" {{ request('status')=='belum'?'selected':'' }}>Menunggu</option>
                <option value="perbaikan" {{ request('status')=='perbaikan'?'selected':'' }}>Perbaikan</option>
                <option value="valid" {{ request('status')=='valid'?'selected':'' }}>Berkas Valid</option>
                <option value="lulus" {{ request('status')=='lulus'?'selected':'' }}>Diterima</option>
                <option value="tidak_lulus" {{ request('status')=='tidak_lulus'?'selected':'' }}>Ditolak</option>
            </select>
        </div>

        {{-- WAKTU --}}
        <div class="relative flex-1">
            <select name="waktu"
                class="appearance-none border border-slate-200 pl-4 pr-8 py-2.5 text-[13px] text-slate-600 w-full"
                style="background:#FAFEFF;border-radius:8px;box-shadow:0 4px 4px rgba(161,209,251,.25)">
                
                <option value="">Waktu</option>
                <option value="hari_ini" {{ request('waktu')=='hari_ini'?'selected':'' }}>Hari Ini</option>
                <option value="minggu_ini" {{ request('waktu')=='minggu_ini'?'selected':'' }}>Minggu Ini</option>
                <option value="bulan_ini" {{ request('waktu')=='bulan_ini'?'selected':'' }}>Bulan Ini</option>
            </select>
        </div>

    </div>
</div>

</form>

{{-- TABLE --}}
<div class="bg-white rounded-2xl border border-slate-100 overflow-hidden"
style="box-shadow:0 4px 4px rgba(161,209,251,.25)">

<div class="overflow-x-auto">
<table class="w-full min-w-[900px] text-[13px]">

<thead>
<tr style="background:#C4F4FD">
    <th class="text-center py-3 px-4">No</th>
    <th class="text-left py-3 px-4">Nama Peserta</th>
    <th class="text-center py-3 px-4">NISN</th>
    <th class="text-center py-3 px-4">Jalur</th>
    <th class="text-center py-3 px-4">Gelombang</th>
    <th class="text-center py-3 px-4">Status Verifikasi</th>
    <th class="text-left py-3 px-4">Asal Sekolah</th>
    <th class="text-center py-3 px-4">Aksi</th>
</tr>
</thead>

<tbody>

@foreach($pendaftaran as $i => $row)

@php
$status = match($row->status) {
    'belum','form_selesai' => 'menunggu',
    'perbaikan' => 'perbaikan',
    'lulus' => 'lulus',
    'tidak_lulus' => 'tidak_lulus',
    default => 'menunggu',
};

$badge = [
    'menunggu'=>['bg'=>'#FFF3E0','text'=>'#E65100','border'=>'#FFCC80','label'=>'Menunggu'],
    'perbaikan'=>['bg'=>'#E3F2FD','text'=>'#1565C0','border'=>'#90CAF9','label'=>'Perbaikan'],
    'lulus'=>['bg'=>'#E8F5E9','text'=>'#2E7D32','border'=>'#A5D6A7','label'=>'Diterima'],
    'tidak_lulus'=>['bg'=>'#FFEBEE','text'=>'#C62828','border'=>'#EF9A9A','label'=>'Ditolak'],
][$status];
@endphp

<tr class="border-t border-slate-100 hover:bg-[#F9FDFF] transition">

    <td class="text-center text-slate-400 py-3.5 px-4">{{ $i+1 }}</td>

    <td class="text-slate-700 font-medium py-3.5 px-4">
        {{ $row->nama_lengkap }}
    </td>

    <td class="text-center text-slate-500 font-mono py-3.5 px-4">
        {{ $row->nisn }}
    </td>

    <td class="text-center py-3.5 px-4">
        {{ ucfirst($row->jalur) }}
    </td>

    <td class="text-center py-3.5 px-4">
        Gelombang {{ $row->gelombang }}
    </td>

    <td class="text-center py-3.5 px-4">
        <span class="px-3 py-1 rounded-lg text-[11px] font-semibold border"
        style="background:{{ $badge['bg'] }};
               color:{{ $badge['text'] }};
               border-color:{{ $badge['border'] }}">
            {{ $badge['label'] }}
        </span>
    </td>

    <td class="py-3.5 px-4">
        {{ $row->asal_sekolah }}
    </td>

    <td class="text-center py-3.5 px-4">
        <a href="#"
           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-white text-[12px] font-semibold"
           style="background:linear-gradient(90deg,#1AA2BA,#47E3FF)">
            <img src="{{ asset('ppdb/admin/operasional/preview.png') }}" class="w-3.5 h-3.5 object-contain">
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
document.querySelectorAll('select').forEach(el=>{
    el.addEventListener('change',()=>{
        el.closest('form').submit();
    });
});
</script>