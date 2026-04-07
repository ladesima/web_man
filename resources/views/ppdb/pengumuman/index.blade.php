@extends('layouts.ppdb-siswa')

@section('title', 'Pengumuman - PPDB MAN Jeneponto')

@section('content')

@php
    // gunakan status dari controller (JANGAN di-override lagi)
@endphp

<div class="px-6 py-6">

    {{-- STEPPER --}}
    @include('ppdb.partials.stepper', ['currentStep' => 4])

    {{-- GAMBAR LANGSUNG --}}
    <div class="flex flex-col items-center justify-center mt-4">

        {{-- ✅ DITERIMA --}}
        @if($status === 'diterima')
    <img src="{{ asset('ppdb/terima.png') }}"
         alt="Diterima"
         class="max-w-xl w-full object-contain">

    {{-- 🔥 BUTTON DAFTAR ULANG --}}
    <div class="mt-6 flex justify-center">
        <a href="{{ route('siswa.daftar-ulang', $jalur) }}"
           class="px-8 py-3 rounded-full text-white font-semibold text-sm"
           style="background-color: #22c55e;"
           onmouseover="this.style.backgroundColor='#16a34a'"
           onmouseout="this.style.backgroundColor='#22c55e'">
            Lanjut Daftar Ulang →
        </a>
    </div>

        {{-- ❌ PERBAIKAN = TIDAK LULUS --}}
        @elseif($status === 'perbaikan')
            <img src="{{ asset('ppdb/ditolak.png') }}"
                 alt="Perbaikan"
                 class="max-w-xl w-full object-contain">
                @if($pendaftaran->catatan_revisi)
    <div class="mt-6 p-4 rounded-lg bg-yellow-50 border border-yellow-200 text-sm text-yellow-800">
        <strong>Catatan Perbaikan:</strong><br>
        {{ $pendaftaran->catatan_revisi }}
    </div>
@endif
<div class="mt-6 flex justify-center">
    <a href="{{ route('siswa.perbaikan', $jalur) }}"
       class="px-8 py-3 rounded-full text-white font-semibold text-sm"
       style="background-color: #f59e0b;">
        Perbaiki Data →
    </a>
</div>

        {{-- ❌ TIDAK LOLOS --}}
        @elseif($status === 'tidaklolos')
            <img src="{{ asset('ppdb/ditolak.png') }}"
                 alt="Tidak Lolos"
                 class="max-w-xl w-full object-contain">

        {{-- ⏳ BELUM DIPUBLISH --}}
        @else
            <img src="{{ asset('ppdb/menunggu.png') }}"
                 alt="Menunggu"
                 class="max-w-xl w-full object-contain">
        @endif

    </div>

</div>

@endsection