@extends('layouts.ppdb-siswa')

@section('title', 'Pengumuman - PPDB MAN Jeneponto')

@section('content')

@php
    // nanti diganti dari database: diterima, ditolak
    $status = 'diterima';
@endphp

<div class="px-6 py-6">

    {{-- STEPPER --}}
    @include('ppdb.partials.stepper', ['currentStep' => 4])

    {{-- GAMBAR LANGSUNG --}}
    <div class="flex flex-col items-center justify-center mt-4">
        @if($status === 'diterima')
            <img src="{{ asset('ppdb/terima.png') }}"
                 alt="Diterima"
                 class="max-w-xl w-full object-contain">
        @else
            <img src="{{ asset('ppdb/ditolak.png') }}"
                 alt="Ditolak"
                 class="max-w-xl w-full object-contain">
        @endif
    </div>

</div>

@endsection