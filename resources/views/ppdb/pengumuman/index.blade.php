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

        {{-- ❌ PERBAIKAN = TIDAK LULUS --}}
        @elseif($status === 'perbaikan')
            <img src="{{ asset('ppdb/ditolak.png') }}"
                 alt="Perbaikan"
                 class="max-w-xl w-full object-contain">

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