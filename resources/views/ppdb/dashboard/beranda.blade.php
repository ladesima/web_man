{{-- resources/views/ppdb/dashboard/beranda.blade.php --}}
@extends('layouts.ppdb-siswa')

@section('title', 'Dashboard - PPDB MAN Jeneponto')

@section('content')


    {{-- KONTEN --}}
    <div class="max-w-7xl mx-auto px-6 py-8 space-y-10">

        {{-- BANNER --}}
        <div class="relative">
            <img src="{{ asset('ppdb/benner.svg') }}" alt="Banner" 
                class="w-full h-auto block">
            <div class="absolute inset-0 flex flex-col justify-center px-10">
                <h2 class="text-white text-2xl font-bold">
                    Assalamu'alaikum,  {{ Auth::guard('ppdb')->user()->nama }}!
                </h2>
                <p class="text-white/90 text-sm mt-2 leading-7">
                    Kamu belum memilih jalur pendaftaran.<br>
                    Silahkan pilih jalur yang terbuka dan sesuai denganmu
                </p>
            </div>
        </div>

    
       
        {{-- PILIH JALUR --}}
       <div style="margin-top: -20px;">
            <h3 class="text-base font-bold text-[#2B2A28] mb-6">Pilih Jalur Pendaftaran</h3>
             @php
    $activeJalurs = $ppdb->jalurs->where('is_active', 1)->pluck('jalur')->toArray();
@endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                {{-- Prestasi --}}
                @php $isPrestasi = in_array('prestasi', $activeJalurs); @endphp
                <div class="jalur-card relative pt-8 flex flex-col {{ !$isPrestasi ? 'opacity-50' : '' }}" style="--delay: 0ms">
                    <div class="absolute top-0 left-6 z-10">
                        <img src="{{ asset('ppdb/Group 18.svg') }}" alt="Prestasi" class="w-14 h-14 object-contain">
                    </div>
                    <div class="jalur-inner flex-1 flex flex-col rounded-2xl pt-10 pb-0 px-6 bg-white overflow-hidden
    {{ !$isPrestasi ? 'pointer-events-none cursor-not-allowed' : '' }}"
                         style="box-shadow: 0px 4px 4px 0px rgba(0,0,0,0.25);">
                        <h3 class="text-base font-bold text-black">Jalur Prestasi</h3>
                        <p class="mt-2 text-sm leading-6 flex-1" style="color: #575551;">
                            Untuk siswa dengan prestasi akademik atau non akademik
                        </p>
                       <div class="border-t border-slate-100 mt-6">
    @if($isPrestasi)
        <a href="{{ route('ppdb.pilih_jalur', 'prestasi') }}"
           class="inline-flex items-center gap-1.5 py-4 font-semibold text-sm group"
           style="color: #16A9D1;">
            Baca Selengkapnya <span class="group-hover:translate-x-1 transition-transform">→</span>
        </a>
    @else
        <span class="inline-flex items-center py-4 text-gray-400 font-semibold text-sm">
            Belum Dibuka
        </span>
    @endif
</div>
                    </div>
                </div>

                {{-- Regular --}}
                @php $isReguler = in_array('reguler', $activeJalurs); @endphp

<div class="jalur-card relative pt-8 flex flex-col {{ !$isReguler ? 'opacity-50' : '' }}" style="--delay: 200ms">
                    <div class="absolute top-0 left-6 z-10">
                        <img src="{{ asset('ppdb/regular.svg') }}" alt="Regular" class="w-14 h-14 object-contain">
                    </div>
                    <div class="jalur-inner flex-1 flex flex-col rounded-2xl pt-10 pb-0 px-6 bg-white overflow-hidden
                    {{ !$isReguler ? 'pointer-events-none cursor-not-allowed' : '' }}"
                         style="box-shadow: 0px 4px 4px 0px rgba(0,0,0,0.25);">
                        <h3 class="text-base font-bold text-black">Jalur Regular</h3>
                        <p class="mt-2 text-sm leading-6 flex-1" style="color: #575551;">
                            Jalur pendaftaran umum bagi calon siswa yang ingin melanjutkan pendidikan di MAN Jeneponto.
                        </p>
                        <div class="border-t border-slate-100 mt-6">
    @if($isReguler)
        <a href="{{ route('ppdb.pilih_jalur', 'reguler') }}"
           class="inline-flex items-center gap-1.5 py-4 font-semibold text-sm group"
           style="color: #16A9D1;">
            Baca Selengkapnya <span class="group-hover:translate-x-1 transition-transform">→</span>
        </a>
    @else
        <span class="inline-flex items-center py-4 text-gray-400 font-semibold text-sm">
            Belum Dibuka
        </span>
    @endif
</div>
                    </div>
                </div>

                {{-- Afirmasi --}}
                @php $isAfirmasi = in_array('afirmasi', $activeJalurs); @endphp

<div class="jalur-card relative pt-8 flex flex-col {{ !$isAfirmasi ? 'opacity-50' : '' }}" style="--delay: 400ms">
                    <div class="absolute top-0 left-6 z-10">
                        <img src="{{ asset('ppdb/afirmasi.svg') }}" alt="Afirmasi" class="w-14 h-14 object-contain">
                    </div>
                    <div class="jalur-inner flex-1 flex flex-col rounded-2xl pt-10 pb-0 px-6 bg-white overflow-hidden
                    {{ !$isAfirmasi ? 'pointer-events-none cursor-not-allowed' : '' }}"
                         style="box-shadow: 0px 4px 4px 0px rgba(0,0,0,0.25);">
                        <h3 class="text-base font-bold text-black">Jalur Afirmasi</h3>
                        <p class="mt-2 text-sm leading-6 flex-1" style="color: #575551;">
                            Untuk calon siswa dari keluarga kurang mampu atau yang memiliki kondisi khusus sesuai ketentuan
                        </p>
                        <div class="border-t border-slate-100 mt-6">
    @if($isAfirmasi)
        <a href="{{ route('ppdb.pilih_jalur', 'afirmasi') }}"
           class="inline-flex items-center gap-1.5 py-4 font-semibold text-sm group"
           style="color: #16A9D1;">
            Baca Selengkapnya <span class="group-hover:translate-x-1 transition-transform">→</span>
        </a>
    @else
        <span class="inline-flex items-center py-4 text-gray-400 font-semibold text-sm">
            Belum Dibuka
        </span>
    @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- JADWAL PENDAFTARAN --}}
        <div class="pb-10">
            <h3 class="text-base font-bold text-[#2B2A28] mb-6">Jadwal Pendaftaran</h3>

            

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

@forelse($jalurs ?? [] as $jalur)

<div class="jadwal-col">

    {{-- JUDUL --}}
    <h3 class="text-center font-bold text-lg mb-4"
        style="background: linear-gradient(180deg, #00B1D1, #00758A);
               -webkit-background-clip: text;
               -webkit-text-fill-color: transparent;
               background-clip: text;
               filter: drop-shadow(0px 4px 4px rgba(101,101,101,0.25));">
        {{ ucfirst($jalur->jalur) }}
    </h3>

    {{-- BOX --}}
    <div class="border-2 border-[#27C2DE] rounded-3xl p-5 bg-white">

        @forelse($jalur->tahapans->sortBy('tanggal_mulai') as $tahap)

        <div class="flex gap-3">

            {{-- ICON --}}
            <div class="flex flex-col items-center flex-shrink-0">
                <div class="w-8 h-8 flex items-center justify-center">
                    <img src="{{ asset('ppdb/daftar.svg') }}" class="w-7 h-7 object-contain">
                </div>

                @if(!$loop->last)
                <div class="w-[2px] flex-1 my-1"
                     style="background: repeating-linear-gradient(to bottom, #27C2DE 0px, #27C2DE 5px, transparent 5px, transparent 10px); min-height: 24px;">
                </div>
                @endif
            </div>

            {{-- TEXT --}}
            <div class="pb-4">
                <p class="text-[11px] font-semibold leading-tight text-[#00B1D1]">
                    {{ \Carbon\Carbon::parse($tahap->tanggal_mulai)->format('d M Y') }}
                    -
                    {{ \Carbon\Carbon::parse($tahap->tanggal_selesai)->format('d M Y') }}
                </p>

                <p class="text-sm font-bold text-[#2B2A28]">
                    {{ $tahap->nama_tahapan }}
                </p>
            </div>

        </div>

        @empty
        <p class="text-center text-gray-400 text-sm">
            Jadwal belum tersedia
        </p>
        @endforelse

    </div>

</div>

@empty
<p class="text-center text-gray-400 col-span-3">
    Belum ada data jadwal
</p>
@endforelse

</div>
        </div>

    </div>

@endsection

@push('scripts')
<style>
    .jalur-card {
        opacity: 0;
        transform: translateY(50px);
        transition: opacity 600ms ease var(--delay), transform 600ms ease var(--delay);
    }
    .jalur-card.visible { opacity: 1; transform: translateY(0); }
    .jalur-inner {
        transition: box-shadow 300ms ease, transform 300ms ease, border 300ms ease;
        cursor: pointer;
        border: 2px solid transparent;
    }
    .jalur-card:hover .jalur-inner {
        box-shadow: 0px 4px 4px 0px rgba(0,0,0,0.25), inset 0px 4px 4px 0px rgba(89,175,255,0.5);
        transform: translateY(-4px);
    }
    .jalur-card.active .jalur-inner { border: 2px solid #27C2DE; }
    .jadwal-col {
        opacity: 0;
        transform: translateY(40px);
        transition: opacity 600ms ease, transform 600ms ease;
    }
    .jadwal-col.visible { opacity: 1; transform: translateY(0); }
</style>
<script>
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            entry.target.classList.toggle('visible', entry.isIntersecting);
        });
    }, { threshold: 0.15 });

    document.querySelectorAll('.jalur-card, .jadwal-col').forEach((el, i) => observer.observe(el));
    document.querySelectorAll('.jadwal-col').forEach((el, i) => el.style.transitionDelay = (i * 150) + 'ms');
    document.querySelectorAll('.jalur-card').forEach(card => {
        card.addEventListener('click', () => {
            const isActive = card.classList.contains('active');
            document.querySelectorAll('.jalur-card').forEach(c => c.classList.remove('active'));
            if (!isActive) card.classList.add('active');
        });
    });
</script>
@endpush