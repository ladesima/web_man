@extends('layouts.ppdb-siswa')

@section('title', 'Daftar Ulang - PPDB MAN Jeneponto')

@section('content')

<div class="px-6 py-6">

    {{-- STEPPER --}}
    @include('ppdb.partials.stepper', ['currentStep' => 5])

    {{-- BANNER JADWAL --}}
    <div class="relative w-full rounded-2xl overflow-hidden max-w-5xl mx-auto" style="min-height: 200px;">

        {{-- Gambar background --}}
        <img src="{{ asset('ppdb/prestasi2.svg') }}"
             alt="Banner Daftar Ulang"
             class="w-full object-cover"
             style="min-height: 200px;">

        {{-- Teks overlay di kiri --}}
        <div class="absolute inset-0 flex flex-col justify-center px-10"
             style="max-width: 55%;">
            <h2 class="text-xl font-bold text-white mb-4">Jadwal Daftar Ulang</h2>
            <table class="text-sm text-white" style="border-spacing: 0 6px; border-collapse: separate;">
                <tr>
                    <td class="font-medium pr-2">Tanggal</td>
                    <td class="pr-3">:</td>
                    <td>20–24 April 2026</td>
                </tr>
                <tr>
                    <td class="font-medium pr-2">Waktu</td>
                    <td class="pr-3">:</td>
                    <td>08:00 – 14:00 WITA</td>
                </tr>
                <tr>
                    <td class="font-medium pr-2">Lokasi</td>
                    <td class="pr-3">:</td>
                    <td>MAN Jeneponto</td>
                </tr>
            </table>
        </div>

    </div>

</div>

@endsection