@extends('layouts.ppdb-public')

@section('title', 'PPDB MAN Jeneponto 2026/2027')

@section('content')

{{-- ================================================================
     SECTION 1 : HERO
================================================================ --}}
<section id="beranda" class="relative min-h-[520px] flex items-center overflow-hidden" style="scroll-margin-top: 80px;">
    <div class="absolute inset-0">
        <img src="{{ asset('ppdb/landingpage.svg') }}"
             alt="Hero" class="w-full h-full object-cover object-center">
        <div class="absolute inset-0"
             style="background: linear-gradient(to right, rgba(0, 0, 0, 0.2), rgba(54, 174, 120, 0.35) 80.96%, rgba(185,216,76,0.2));">
        </div>
    </div>

    <div class="relative z-10 w-full text-center px-4 py-28 md:py-36">
        <h1 class="hero-title text-2xl md:text-4xl font-extrabold text-white leading-snug drop-shadow">
            PPDBM Tahun Ajaran 2026/2027 telah dibuka
        </h1>
        <p class="hero-sub text-lg md:text-3xl font-semibold text-white drop-shadow">
            Saat ini pendaftaran sedang berlangsung untuk
        </p>
        <p class="hero-jalur mt-1 text-2xl md:text-4xl font-extrabold drop-shadow"
           style="color: #DBE124;">
            Jalur Reguler Gel I
        </p>
        <div class="hero-btn mt-8">
            <a href="/ppdb/daftar"
               class="inline-block bg-[#3ED0F3] hover:bg-[#26C6F3] text-white font-semibold px-10 py-3 rounded-full shadow-lg transition-all text-base">
                Daftar Sekarang
            </a>
        </div>
    </div>
</section>

<style>
    .hero-title,
    .hero-sub,
    .hero-jalur,
    .hero-btn {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 700ms ease, transform 700ms ease;
    }

    .hero-title.visible  { opacity: 1; transform: translateY(0); transition-delay: 200ms; }
    .hero-sub.visible    { opacity: 1; transform: translateY(0); transition-delay: 500ms; }
    .hero-jalur.visible  { opacity: 1; transform: translateY(0); transition-delay: 800ms; }
    .hero-btn.visible    { opacity: 1; transform: translateY(0); transition-delay: 1100ms; }
</style>

<script>
    const heroObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            } else {
                entry.target.classList.remove('visible');
            }
        });
    }, { threshold: 0.3 });

    document.querySelectorAll('.hero-title, .hero-sub, .hero-jalur, .hero-btn').forEach(el => {
        heroObserver.observe(el);
    });
</script>

{{-- ================================================================
     SECTION 2 : SAMBUTAN KEPALA SEKOLAH
================================================================ --}}
<section id="profil" class="bg-white py-24" style="scroll-margin-top: 80px;">
    <div class="max-w-7xl mx-auto px-6 md:px-12">
        <div class="flex flex-col md:flex-row items-center gap-14 md:gap-18">

            {{-- Foto dengan background bgbiru.svg --}}
            <div class="flex-shrink-0 w-[300px] md:w-[360px]">
                <div class="relative">

                    {{-- Background biru - datang dari KANAN --}}
                    <img src="{{ asset('ppdb/bgbiru.svg') }}" alt=""
                         class="profil-bg absolute -top-7 -right-7 w-[calc(100%+56px)] h-[calc(100%+56px)] object-contain z-0">

                    {{-- Foto - datang dari KIRI --}}
                    <div class="profil-foto relative rounded-xl overflow-hidden shadow-lg z-10">
                        <img src="{{ asset('ppdb/Ibu.svg') }}"
                             alt="Dr. Ihdiana S.Pd., M.Pd.I"
                             class="w-full h-auto object-cover block">
                    </div>

                </div>
            </div>

            {{-- Teks --}}
            <div class="profil-teks flex-1">
                <p class="text-[#00758A] text-xl md:text-2xl font-medium">Dr. Ihdiana, S.Pd., M.Pd.I</p>
                <h2 class="mt-2 text-3xl md:text-4xl font-bold text-[#2B2A28] leading-tight">
                    Sambutan Kepala Sekolah
                </h2>
                <div class="mt-8 space-y-6 text-black text-base md:text-lg leading-8 text-justify">
                    <p>"Assalamualaikum Warahmatullahi Wabarokatuh<br>
                       Selamat datang di PPDB MAN Jeneponto.</p>
                    <p>Kami membuka kesempatan bagi putra-putri terbaik untuk bergabung dan
                       berkembang bersama di madrasah yang mengedepankan prestasi, karakter, dan
                       nilai-nilai keislaman.<br>
                       Semoga MAN Jeneponto dapat menjadi tempat terbaik bagi generasi muda untuk
                       belajar, berprestasi, dan meraih masa depan yang gemilang.</p>
                    <p>Mari bergabung bersama kami."</p>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
    .profil-bg {
        opacity: 0;
        transform: translateX(180px);
        transition: opacity 900ms ease-in, transform 900ms ease-in;
    }
    .profil-bg.visible {
        opacity: 1;
        transform: translateX(0);
    }

    .profil-foto {
        opacity: 0;
        transform: translateX(-180px);
        transition: opacity 900ms ease-in 200ms, transform 900ms ease-in 200ms;
    }
    .profil-foto.visible {
        opacity: 1;
        transform: translateX(0);
    }

    .profil-teks {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 700ms ease 800ms, transform 700ms ease 800ms;
    }
    .profil-teks.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<script>
    const profilObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            } else {
                entry.target.classList.remove('visible'); // ← animasi reset saat keluar
            }
        });
    }, { threshold: 0.2 });

    document.querySelectorAll('.profil-bg, .profil-foto, .profil-teks').forEach(el => {
        profilObserver.observe(el);
    });
</script>
{{-- ================================================================
     SECTION 3 : JALUR PENDAFTARAN
================================================================ --}}
<section id="jalur" class="py-20" style="background-color: #EFFDFF; scroll-margin-top: 50px;">

    <div class="max-w-7xl mx-auto px-6 md:px-10">

        {{-- Heading --}}
        <div class="text-center mb-16 jalur-heading">
            <h2 class="text-3xl md:text-4xl font-extrabold"
                style="color: #2B2A28; text-shadow: 0px 4px 4px rgba(0,0,0,0.25);">Jalur</h2>
            <p class="mt-2 text-[15px]"
               style="color: #575551; text-shadow: 0px 4px 4px rgba(0,0,0,0.25);">
                Adapun beberapa jalur yang kami buka
            </p>
        </div>

        {{-- Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            {{-- Prestasi --}}
            <div class="jalur-card relative pt-8 flex flex-col" style="--delay: 0ms">
                <div class="absolute top-0 left-6 z-10">
                    <img src="{{ asset('ppdb/Group 18.svg') }}" alt="Prestasi" class="w-14 h-14 object-contain">
                </div>
                <div class="jalur-inner flex-1 flex flex-col rounded-2xl pt-10 pb-0 px-6 bg-white overflow-hidden"
                     style="box-shadow: 0px 4px 4px 0px rgba(0,0,0,0.25);">
                    <h3 class="text-lg font-bold text-black">Prestasi</h3>
                    <p class="mt-2 text-sm leading-6 flex-1" style="color: #575551;">
                        Jalur untuk siswa yang memiliki prestasi akademik maupun non-akademik sesuai persyaratan yang berlaku.
                    </p>
                    <div class="border-t border-slate-100 mt-8">
                        <a href="{{ route('ppdb.jalur', 'prestasi') }}"
                           class="inline-flex items-center gap-1.5 py-4 text-[#16A9D1] font-semibold text-sm group">
                            Baca Selengkapnya
                            <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Regular --}}
            <div class="jalur-card relative pt-8 flex flex-col" style="--delay: 200ms">
                <div class="absolute top-0 left-6 z-10">
                    <img src="{{ asset('ppdb/regular.svg') }}" alt="Regular" class="w-14 h-14 object-contain">
                </div>
                <div class="jalur-inner flex-1 flex flex-col rounded-2xl pt-10 pb-0 px-6 bg-white overflow-hidden"
                     style="box-shadow: 0px 4px 4px 0px rgba(0,0,0,0.25);">
                    <h3 class="text-lg font-bold text-black">Regular</h3>
                    <p class="mt-2 text-sm leading-6 flex-1" style="color: #575551;">
                        Jalur umum untuk calon peserta didik baru yang memenuhi persyaratan administrasi pendaftaran.
                    </p>
                    <div class="border-t border-slate-100 mt-8">
                        <a href="{{ route('ppdb.jalur', 'reguler') }}"
                           class="inline-flex items-center gap-1.5 py-4 text-[#16A9D1] font-semibold text-sm group">
                            Baca Selengkapnya
                            <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Afirmasi --}}
            <div class="jalur-card relative pt-8 flex flex-col" style="--delay: 400ms">
                <div class="absolute top-0 left-6 z-10">
                    <img src="{{ asset('ppdb/afirmasi.svg') }}" alt="Afirmasi" class="w-14 h-14 object-contain">
                </div>
                <div class="jalur-inner flex-1 flex flex-col rounded-2xl pt-10 pb-0 px-6 bg-white overflow-hidden"
                     style="box-shadow: 0px 4px 4px 0px rgba(0,0,0,0.25);">
                    <h3 class="text-lg font-bold text-black">Afirmasi</h3>
                    <p class="mt-2 text-sm leading-6 flex-1" style="color: #575551;">
                        Jalur untuk peserta didik sesuai ketentuan afirmasi dan dokumen pendukung yang telah ditetapkan.
                    </p>
                    <div class="border-t border-slate-100 mt-8">
                        <a href="{{ route('ppdb.jalur', 'afirmasi') }}"
                           class="inline-flex items-center gap-1.5 py-4 text-[#16A9D1] font-semibold text-sm group">
                            Baca Selengkapnya
                            <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
    .jalur-heading {
        opacity: 0;
        transform: translateY(24px);
        transition: opacity 600ms ease, transform 600ms ease;
    }
    .jalur-heading.visible {
        opacity: 1;
        transform: translateY(0);
    }
    .jalur-card {
        opacity: 0;
        transform: translateY(50px);
        transition: opacity 600ms ease var(--delay), transform 600ms ease var(--delay);
    }
    .jalur-card.visible {
        opacity: 1;
        transform: translateY(0);
    }
    .jalur-inner {
        transition: box-shadow 300ms ease, transform 300ms ease, border 300ms ease;
        cursor: pointer;
        border: 2px solid transparent;
    }
    .jalur-card:hover .jalur-inner {
        box-shadow: 0px 4px 4px 0px rgba(0,0,0,0.25), inset 0px 4px 4px 0px rgba(89,175,255,0.5);
        transform: translateY(-4px);
    }
    .jalur-card.active .jalur-inner {
        border: 2px solid #27C2DE;
        box-shadow: 0px 4px 4px 0px rgba(0,0,0,0.25), inset 0px 4px 4px 0px rgba(89,175,255,0.5);
        transform: translateY(0px);
    }
</style>

<script>
    const jalurObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            } else {
                entry.target.classList.remove('visible');
            }
        });
    }, { threshold: 0.15 });

    document.querySelectorAll('.jalur-card, .jalur-heading').forEach(el => {
        jalurObserver.observe(el);
    });

    document.querySelectorAll('.jalur-card').forEach(card => {
        card.addEventListener('click', () => {
            const isActive = card.classList.contains('active');
            document.querySelectorAll('.jalur-card').forEach(c => c.classList.remove('active'));
            if (!isActive) card.classList.add('active');
        });
    });
</script>

{{-- ================================================================
     SECTION 4 : JADWAL
================================================================ --}}
<section id="jadwal" class="bg-white py-20" style="scroll-margin-top: 50px;">
    <div class="max-w-6xl mx-auto px-6 md:px-10">

        {{-- Heading --}}
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold"
                style="color: #2B2A28; text-shadow: 0px 4px 4px rgba(0,0,0,0.25);">Jadwal</h2>
            <p class="mt-2 text-[15px]"
               style="color: #575551; text-shadow: 0px 4px 4px rgba(0,0,0,0.25);">
                Jangan sampai lupa daftar sesuai jadwal yang telah kami tentukan
            </p>
        </div>

        @php
        $jadwals = [
            'Regular' => [
                ['icon' => 'daftar.svg',              'tanggal' => '24 April - 15 Mei 2026', 'label' => 'Pendaftaran'],
                ['icon' => 'seleksiadministrasi.svg', 'tanggal' => '15 Mei - 20 Mei 2026',   'label' => 'Seleksi Administrasi'],
                ['icon' => 'pengumuman.svg',           'tanggal' => '20 Mei 2026',             'label' => 'Pengumuman'],
                ['icon' => 'daftarulang.svg',          'tanggal' => '21 Mei - 28 Mei 2026',   'label' => 'Daftar Ulang'],
            ],
            'Prestasi' => [
                ['icon' => 'daftar.svg',              'tanggal' => '24 April - 15 Mei 2026', 'label' => 'Pendaftaran'],
                ['icon' => 'seleksiadministrasi.svg', 'tanggal' => '15 Mei - 20 Mei 2026',   'label' => 'Seleksi Administrasi'],
                ['icon' => 'pengumuman.svg',           'tanggal' => '20 Mei 2026',             'label' => 'Pengumuman'],
                ['icon' => 'daftarulang.svg',          'tanggal' => '21 Mei - 28 Mei 2026',   'label' => 'Daftar Ulang'],
            ],
            'Afirmasi' => [
                ['icon' => 'daftar.svg',              'tanggal' => '24 April - 15 Mei 2026', 'label' => 'Pendaftaran'],
                ['icon' => 'seleksiadministrasi.svg', 'tanggal' => '15 Mei - 20 Mei 2026',   'label' => 'Seleksi Administrasi'],
                ['icon' => 'pengumuman.svg',           'tanggal' => '20 Mei 2026',             'label' => 'Pengumuman'],
                ['icon' => 'daftarulang.svg',          'tanggal' => '21 Mei - 28 Mei 2026',   'label' => 'Daftar Ulang'],
            ],
        ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($jadwals as $judul => $items)
            <div class="jadwal-col">
                {{-- Judul kolom --}}
                <h3 class="text-center font-bold text-lg mb-4"
                    style="
                        background: linear-gradient(180deg, #00B1D1, #00758A);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        background-clip: text;
                        filter: drop-shadow(0px 4px 4px rgba(101,101,101,0.25));
                    ">
                    {{ $judul }}
                </h3>

                {{-- Box jadwal --}}
                <div class="border-2 border-[#27C2DE] rounded-3xl p-5 bg-white">
                    <div class="space-y-0">
                        @foreach($items as $index => $item)
                        <div class="flex gap-3">

                            {{-- Kiri: icon + garis --}}
                            <div class="flex flex-col items-center flex-shrink-0">
                                {{-- Icon tanpa bg --}}
                                <div class="w-8 h-8 flex items-center justify-center flex-shrink-0">
                                    <img src="{{ asset('ppdb/' . $item['icon']) }}"
                                        alt="{{ $item['label'] }}"
                                        class="w-7 h-7 object-contain">
                                </div>
                                {{-- Garis penghubung --}}
                                @if(!$loop->last)
                                <div class="w-[2px] flex-1 my-1"
                                    style="background: repeating-linear-gradient(to bottom, #27C2DE 0px, #27C2DE 5px, transparent 5px, transparent 10px); min-height: 24px;">
                                </div>
                                @endif
                            </div>

                            {{-- Kanan: info --}}
                            <div class="pb-4">
                                <p class="text-[11px] font-semibold leading-tight"
                                   style="color: #00B1D1;">
                                    {{ $item['tanggal'] }}
                                </p>
                                <p class="text-sm font-bold mt-0.5" style="color: #2B2A28;">
                                    {{ $item['label'] }}
                                </p>
                            </div>

                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

<style>
    .jadwal-col {
        opacity: 0;
        transform: translateY(40px);
        transition: opacity 600ms ease, transform 600ms ease;
    }
    .jadwal-col.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<script>
    const jadwalObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            } else {
                entry.target.classList.remove('visible');
            }
        });
    }, { threshold: 0.15 });

    document.querySelectorAll('.jadwal-col').forEach((el, i) => {
        el.style.transitionDelay = (i * 150) + 'ms';
        jadwalObserver.observe(el);
    });
</script>

{{-- ================================================================
     SECTION 5 : TUTORIAL
================================================================ --}}
<section id="tutorial" class="bg-[#EAF5F8] py-20" style="scroll-margin-top: 50px;">
    <div class="max-w-4xl mx-auto px-6 md:px-10 text-center">
        <div class="tutorial-heading">
            <h2 class="text-3xl md:text-4xl font-extrabold"
                style="color: #2B2A28; text-shadow: 0px 4px 4px rgba(0,0,0,0.25);">Tutorial Pendaftaran</h2>
            <p class="mt-2 text-[15px]"
               style="color: #575551; text-shadow: 0px 4px 4px rgba(0,0,0,0.25);">
                Silakan lihat video panduan pendaftaran untuk memudahkan proses registrasi
            </p>
        </div>

        {{-- Video Box --}}
        <div class="mt-10 tutorial-video">
            <div class="bg-white rounded-3xl p-4 shadow-sm">
                <div class="relative rounded-2xl overflow-hidden bg-slate-700 group cursor-pointer"
                     style="aspect-ratio: 16/9;">
                    <img src="{{ asset('ppdb/landingpage.svg') }}"
                         alt="Thumbnail tutorial"
                         class="absolute inset-0 w-full h-full object-cover opacity-60">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-xl
                                    group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-[#27C2DE] ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ================================================================
     SECTION 6 : FAQ
================================================================ --}}
<section id="faq" class="bg-white py-20" style="scroll-margin-top: 50px;">
    <div class="max-w-4xl mx-auto px-6 md:px-10">

        {{-- Heading --}}
        <div class="text-center mb-12 faq-heading">
            <h2 class="text-3xl md:text-4xl font-extrabold"
                style="color: #2B2A28; text-shadow: 0px 4px 4px rgba(0,0,0,0.25);">Frequently Asked Question</h2>
            <p class="mt-2 text-[15px]"
               style="color: #575551; text-shadow: 0px 4px 4px rgba(0,0,0,0.25);">
                Pertanyaan yang sering diajukan seputar PPDB
            </p>
        </div>

        {{-- List FAQ --}}
        <div class="space-y-3">
            @php
            $faqs = [
                [
                    'q' => 'Siapa saja yang dapat mendaftar di MAN Jeneponto?',
                    'a' => 'Seluruh lulusan SMP/MTs sederajat yang memiliki NISN valid dan memenuhi persyaratan yang telah ditetapkan. Pendaftar tidak dibatasi wilayah tempat tinggal.',
                ],
                [
                    'q' => 'Apa saja jalur pendaftaran yang tersedia?',
                    'a' => 'Tersedia 3 jalur pendaftaran: Jalur Prestasi untuk siswa berprestasi, Jalur Reguler untuk seleksi umum, dan Jalur Afirmasi untuk siswa kurang mampu atau disabilitas.',
                ],
                [
                    'q' => 'Bagaimana cara melakukan pendaftaran?',
                    'a' => 'Pendaftaran dilakukan secara online. Klik tombol "Daftar Sekarang", buat akun, isi formulir pendaftaran, dan upload berkas yang diperlukan.',
                ],
                [
                    'q' => 'Dokumen apa saja yang perlu disiapkan saat pendaftaran?',
                    'a' => 'Ijazah/SKL, Rapor 5 semester terakhir, Kartu Keluarga, Akta Kelahiran, dan Pas Foto terbaru. Untuk jalur tertentu mungkin ada dokumen tambahan.',
                ],
                [
                    'q' => 'Bagaimana cara mengetahui hasil seleksi?',
                    'a' => 'Hasil seleksi dapat dilihat di halaman pengumuman pada website ini atau melalui dashboard akun pendaftar yang sudah dibuat.',
                ],
            ];
            @endphp

            @foreach($faqs as $index => $faq)
            <details class="faq-item group bg-[#F7F7F7] rounded-xl cursor-pointer
                            border border-transparent hover:border-[#C5EAF5] hover:bg-[#F0FAFD]
                            transition-all duration-200"
                     style="--delay: {{ $index * 100 }}ms">
                <summary class="list-none flex items-center justify-between gap-4 px-6 py-4
                                font-semibold text-slate-800 text-sm md:text-[15px]">
                    <span>{{ $faq['q'] }}</span>
                    <svg class="chevron w-5 h-5 text-slate-400 group-open:text-[#27C2DE] flex-shrink-0 transition-all duration-300"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <div class="px-6 pb-5 pt-1 border-t border-slate-100">
                    <p class="text-sm leading-7 text-slate-500">{{ $faq['a'] }}</p>
                </div>
            </details>
            @endforeach
        </div>

    </div>
</section>

{{-- ================================================================
     POPUP HUBUNGI KAMI
     Taruh ini di bagian PALING BAWAH landing.blade.php,
     tepat sebelum @endsection
================================================================ --}}

@push('styles')
<style>
    #popup-kontak {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0,0,0,0.45);
        backdrop-filter: blur(2px);
        align-items: center;
        justify-content: center;
    }
    #popup-kontak.active {
        display: flex;
    }
    #popup-kontak .popup-box {
        background: white;
        border-radius: 16px;
        padding: 40px 48px;
        width: 100%;
        max-width: 520px;
        margin: 0 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        animation: popupIn 0.3s ease;
    }
    @keyframes popupIn {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
</style>
@endpush

{{-- Popup Element --}}
<div id="popup-kontak" onclick="if(event.target===this) tutupPopupKontak()">
    <div class="popup-box">

        <h2 style="text-align:center; font-size:20px; font-weight:700; color:#2B2A28; margin-bottom:28px;">
            Tambah Pertanyaan
        </h2>

        <form onsubmit="submitKontak(event)">

            {{-- Nama --}}
            <div style="margin-bottom:18px;">
                <label style="display:block; font-size:13px; font-weight:600; color:#2B2A28; margin-bottom:6px;">Nama</label>
                <input type="text" name="nama" autocomplete="off"
                       style="width:100%; border:none; border-bottom:1.5px solid #CBD5E1; padding:8px 0; font-size:13px; color:#2B2A28; outline:none; background:transparent;"
                       onfocus="this.style.borderBottomColor='#27C2DE'"
                       onblur="this.style.borderBottomColor='#CBD5E1'">
            </div>

            {{-- Email --}}
            <div style="margin-bottom:18px;">
                <label style="display:block; font-size:13px; font-weight:600; color:#2B2A28; margin-bottom:4px;">
                    Email
                    <span style="font-size:11px; font-weight:400; color:#27C2DE; margin-left:4px;">
                        *email yang anda inputkan harus bisa menerima pesan
                    </span>
                </label>
                <input type="email" name="email" autocomplete="off"
                       style="width:100%; border:none; border-bottom:1.5px solid #CBD5E1; padding:8px 0; font-size:13px; color:#2B2A28; outline:none; background:transparent;"
                       onfocus="this.style.borderBottomColor='#27C2DE'"
                       onblur="this.style.borderBottomColor='#CBD5E1'">
            </div>

            {{-- Kategori --}}
            <div style="margin-bottom:18px;">
                <label style="display:block; font-size:13px; font-weight:600; color:#2B2A28; margin-bottom:6px;">Kategori</label>
                <div style="position:relative;">
                    <select name="kategori"
                            style="width:100%; border:none; border-bottom:1.5px solid #CBD5E1; padding:8px 0; font-size:13px; color:#2B2A28; outline:none; background:transparent; appearance:none; cursor:pointer;"
                            onfocus="this.style.borderBottomColor='#27C2DE'"
                            onblur="this.style.borderBottomColor='#CBD5E1'">
                        <option value="" disabled selected></option>
                        <option value="pendaftaran">Pendaftaran</option>
                        <option value="berkas">Berkas</option>
                        <option value="jalur_seleksi">Jalur Seleksi</option>
                        <option value="jadwal">Jadwal</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                    <svg style="position:absolute; right:4px; top:50%; transform:translateY(-50%); width:16px; height:16px; color:#94A3B8; pointer-events:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>

            {{-- Pertanyaan --}}
            <div style="margin-bottom:28px;">
                <label style="display:block; font-size:13px; font-weight:600; color:#2B2A28; margin-bottom:6px;">Pertanyaan</label>
                <textarea name="pertanyaan" rows="3"
                          style="width:100%; border:none; border-bottom:1.5px solid #CBD5E1; padding:8px 0; font-size:13px; color:#2B2A28; outline:none; background:transparent; resize:none; font-family:inherit;"
                          onfocus="this.style.borderBottomColor='#27C2DE'"
                          onblur="this.style.borderBottomColor='#CBD5E1'"></textarea>
            </div>

            {{-- Tombol --}}
            <div style="display:flex; justify-content:center;">
                <button type="submit"
                        style="width:160px; padding:12px; background:#27C2DE; border-radius:4px; border:none; cursor:pointer; color:white; font-size:15px; font-weight:600; font-family:inherit;"
                        onmouseenter="this.style.opacity='0.85'"
                        onmouseleave="this.style.opacity='1'">
                    Kirim
                </button>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>
    function submitKontak(e) {
        e.preventDefault();
        tutupPopupKontak();
        // Tambahkan logic POST ke server di sini
    }
</script>
@endpush

<style>
    /* Tutorial */
    .tutorial-heading {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 600ms ease, transform 600ms ease;
    }
    .tutorial-heading.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .tutorial-video {
        opacity: 0;
        transform: translateY(40px);
        transition: opacity 600ms ease 200ms, transform 600ms ease 200ms;
    }
    .tutorial-video.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* FAQ */
    .faq-heading {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 600ms ease, transform 600ms ease;
    }
    .faq-heading.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .faq-item {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 500ms ease var(--delay), transform 500ms ease var(--delay);
    }
    .faq-item.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<script>
    const generalObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            } else {
                entry.target.classList.remove('visible');
            }
        });
    }, { threshold: 0.15 });

    document.querySelectorAll('.tutorial-heading, .tutorial-video, .faq-heading, .faq-item').forEach(el => {
        generalObserver.observe(el);
    });
</script>

@endsection