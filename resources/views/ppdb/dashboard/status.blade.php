@extends('layouts.ppdb-siswa')

@section('title', 'Verifikasi Data - PPDB MAN Jeneponto')

@section('content')

@php
    $status = $status ?? 'menunggu';
@endphp

<div class="px-6 py-6">

    {{-- STEPPER --}}
    @include('ppdb.partials.stepper', ['currentStep' => 3])

    {{-- CARD PUTIH LEBAR (outer) --}}
    <div class="bg-white rounded-2xl px-10 py-10"
         style="box-shadow: 0px 4px 4px rgba(0,0,0,0.08); min-height: 400px;">

        @if($status === 'menunggu')

        <div class="rounded-2xl px-8 py-8 mx-auto max-w-xl"
             style="border: 1.5px solid #27C2DE; background-color: #F0FBFD;">
            <div class="flex flex-col items-center text-center">
                <img src="{{ asset('ppdb/menunggu.png') }}" alt="Menunggu"
                     class="w-16 h-16 object-contain mb-3">
                <h2 class="text-base font-bold mb-2" style="color: #27C2DE;">
                    Menunggu Verifikasi
                </h2>
                <p class="text-sm text-slate-500 leading-6 max-w-sm">
                    Berkas Anda sedang diperiksa oleh panitia PPDB. Proses verifikasi
                    membutuhkan waktu maksimal 1–3 hari kerja.
                </p>
            </div>
        </div>

        @elseif($status === 'diterima')

        <div class="rounded-2xl px-8 py-8 mx-auto max-w-xl"
             style="border: 1.5px solid #27C2DE; background-color: #F0FBFD;">
            <div class="flex flex-col items-center text-center">
                <img src="{{ asset('ppdb/diterima.png') }}" alt="Diterima"
                     class="w-16 h-16 object-contain mb-3">
                <h2 class="text-base font-bold mb-2" style="color: #27C2DE;">
                    Berkas Diterima
                </h2>
                <p class="text-sm text-slate-500 leading-6 max-w-sm">
                    Semua dokumen telah diverifikasi dan memenuhi persyaratan. Selamat!
                    Kamu selanjutnya dapat melanjutkan proses daftar ulang.
                </p>
                <div class="mt-5">
                    <a href="{{ route('siswa.pengumuman', $jalur) }}"
                       class="px-8 py-2 rounded-full text-white font-semibold text-sm"
                       style="background-color: #27C2DE;"
                       onmouseover="this.style.backgroundColor='#00B1D1'"
                       onmouseout="this.style.backgroundColor='#27C2DE'">
                        Lihat Pengumuman →
                    </a>
                </div>
            </div>
        </div>

        @elseif($status === 'perbaikan')

        <div class="rounded-2xl px-8 py-8 mx-auto max-w-xl"
             style="border: 1.5px solid #f59e0b; background-color: #FFFBF0;">
            <div class="flex flex-col items-center text-center">
                <img src="{{ asset('ppdb/perbaikan.png') }}" alt="Perbaikan"
                     class="w-16 h-16 object-contain mb-3">
                <h2 class="text-base font-bold mb-2" style="color: #f59e0b;">
    Tidak Lulus - Perlu Perbaikan
</h2>

<p class="text-sm text-slate-500 leading-6 max-w-sm">
    Maaf, Anda belum lulus pada tahap ini karena terdapat berkas yang perlu diperbaiki.
    Silakan lakukan perbaikan sesuai arahan panitia untuk melanjutkan proses.
</p>
                <div class="mt-5">
                    <a href="{{ route('siswa.berkas', $jalur) }}"
                       class="px-8 py-2 rounded-full text-white font-semibold text-sm"
                       style="background-color: #f59e0b;"
                       onmouseover="this.style.backgroundColor='#d97706'"
                       onmouseout="this.style.backgroundColor='#f59e0b'">
                        Upload Ulang Berkas →
                    </a>
                </div>
            </div>
        </div>

        @elseif($status === 'tidaklolos')

        <div class="rounded-2xl px-8 py-8 mx-auto max-w-xl"
             style="border: 1.5px solid #ef4444; background-color: #FFF5F5;">
            <div class="flex flex-col items-center text-center">
                <img src="{{ asset('ppdb/tidaklolos.png') }}" alt="Tidak Lolos"
                     class="w-16 h-16 object-contain mb-3">
                <h2 class="text-base font-bold mb-2" style="color: #ef4444;">
                    Verifikasi Tidak Lolos
                </h2>
                <p class="text-sm text-slate-500 leading-6 max-w-sm">
                    Mohon maaf, berkas Anda tidak memenuhi persyaratan yang telah
                    ditetapkan. Silakan hubungi panitia PPDB untuk informasi lebih lanjut.
                </p>
                <div class="mt-5">
                    <a href="{{ route('siswa.dashboard') }}"
                       class="px-8 py-2 rounded-full text-white font-semibold text-sm"
                       style="background-color: #ef4444;"
                       onmouseover="this.style.backgroundColor='#dc2626'"
                       onmouseout="this.style.backgroundColor='#ef4444'">
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>

        @endif

    </div>

</div>

@endsection