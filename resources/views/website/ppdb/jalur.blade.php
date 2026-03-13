@extends('layouts.ppdb-public')

@section('title', 'Jalur ' . ucfirst($slug) . ' - PPDB MAN Jeneponto')

@section('content')

@php
$data = [
    'prestasi' => [
        'judul'      => 'Jalur Prestasi',
        'banner'     => 'ppdb/prestasi2.svg',
        'deskripsi'  => 'Jalur Prestasi adalah jalur pendaftaran bagi calon peserta didik yang memiliki pencapaian akademik maupun non-akademik. Jalur ini memberikan kesempatan bagi siswa berprestasi untuk melanjutkan pendidikan di MAN Jeneponto dengan menunjukkan bukti prestasi yang dimiliki.',
        'syarat_khusus' => [
            'Memiliki sertifikat atau piagam prestasi akademik maupun non-akademik.',
            'Prestasi yang dilampirkan minimal tingkat kabupaten/kota.',
            'Bukti prestasi yang diunggah harus jelas, valid, dan dapat diverifikasi.',
            'Prestasi sesuai dengan periode waktu yang ditentukan oleh panitia.',
        ],
    ],
    'reguler' => [
        'judul'      => 'Jalur Reguler',
        'banner'     => 'ppdb/regular2.svg',
        'deskripsi'  => 'Jalur Reguler adalah jalur umum pendaftaran bagi calon peserta didik baru yang memenuhi persyaratan administrasi pendaftaran yang telah ditetapkan oleh MAN Jeneponto.',
        'syarat_khusus' => [
            'Memiliki nilai rapor yang memenuhi standar minimum yang ditetapkan.',
            'Mengikuti seleksi administrasi yang ditentukan panitia.',
            'Tidak sedang terdaftar sebagai peserta didik di sekolah lain.',
            'Memenuhi semua persyaratan administrasi yang telah ditetapkan.',
        ],
    ],
    'afirmasi' => [
        'judul'      => 'Jalur Afirmasi',
        'banner'     => 'ppdb/afirmasi2.svg',
        'deskripsi'  => 'Jalur Afirmasi adalah jalur pendaftaran bagi calon peserta didik yang berasal dari keluarga kurang mampu secara ekonomi atau penyandang disabilitas, sesuai ketentuan yang telah ditetapkan.',
        'syarat_khusus' => [
            'Memiliki Kartu Indonesia Pintar (KIP) atau Kartu Keluarga Sejahtera (KKS).',
            'Melampirkan surat keterangan tidak mampu dari kelurahan/desa.',
            'Bagi penyandang disabilitas melampirkan surat keterangan dari dokter.',
            'Dokumen pendukung harus asli dan dapat diverifikasi oleh panitia.',
        ],
    ],
];

$jalur = $data[$slug];

$syarat_umum = [
    'Calon peserta didik berasal dari SMP/MTs atau sederajat.',
    'Memiliki NISN yang aktif dan valid.',
    'Mengisi formulir pendaftaran secara online melalui website PPDB.',
    'Mengunggah dokumen persyaratan sesuai ketentuan.',
];

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

{{-- Breadcrumb --}}
<div class="bg-white px-6 py-4 ">
    <div class="max-w-7xl mx-auto">
        <div class="relative items-center">

            {{-- SVG background --}}
            <img src="{{ asset('ppdb/Breadscrup.svg') }}" class="h-10" alt="">

            {{-- Teks ditimpa di atas SVG --}}
            <div class="absolute inset-0 flex items-center">
                <a href="{{ route('beranda') }}"
                   class="text-sm text-slate-500 hover:text-[#00758A] transition-colors px-5">
                    Beranda
                </a>
                <a href="{{ route('ppdb.landing') }}"
                   class="text-sm text-slate-500 hover:text-[#00758A] transition-colors px-5">
                    PPDB
                </a>
                <span class="text-sm text-white font-semibold px-5">
                    {{ ucfirst($slug) }}
                </span>
            </div>
        </div>
    </div>
</div>

{{-- Hero Card --}}
<section class="bg-white pt-4 px-6 md:px-12">
    <div class="max-w-7xl mx-auto">
        <div class="hero-card relative rounded-3xl overflow-hidden" style="min-height: 320px;">

            {{-- SVG penuh --}}
            <img src="{{ asset($jalur['banner']) }}"
                 alt="{{ $jalur['judul'] }}"
                 class="w-full h-full object-fill absolute inset-0">

            {{-- Teks ditimpa di atas SVG --}}
            <div class="hero-text relative z-10 flex flex-col justify-center px-10 py-12 h-full" style="min-height: 320px;">
                <h1 class="text-3xl md:text-4xl font-extrabold text-white">
                    {{ $jalur['judul'] }}
                </h1>
                <p class="mt-4 text-sm md:text-base leading-7 text-white/90 max-w-lg">
                    {{ $jalur['deskripsi'] }}
                </p>
            <a href="{{ route('ppdb.daftar') }}"
                class="mt-6 inline-block bg-white text-[#00758A] font-semibold px-8 py-2.5 rounded-full transition-all text-sm w-fit hover:bg-slate-100">
                    Daftar Sekarang
            </a>
            </div>

        </div>
    </div>
</section>
{{-- Persyaratan --}}
<section class="bg-[#FFFFFF] py-16">
    <div class="max-w-7xl mx-auto px-6 md:px-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Syarat Umum --}}
            <div class="syarat-card bg-white rounded-2xl p-8 border border-[#C5EAF5]">
                <h2 class="text-xl font-bold text-[#2B2A28] mb-6">Syarat Umum</h2>
                <ul class="space-y-4">
                    @foreach($syarat_umum as $s)
                    <li class="flex items-start gap-3 text-sm text-slate-600 leading-6">
                        <span class="mt-0.5 flex-shrink-0 w-5 h-5 rounded-full bg-[#00B1D1] flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        {{ $s }}
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Syarat Khusus --}}
            <div class="syarat-card bg-white rounded-2xl p-8 border border-[#C5EAF5]">
                <h2 class="text-xl font-bold text-[#2B2A28] mb-6">Syarat Khusus</h2>
                <ul class="space-y-4">
                    @foreach($jalur['syarat_khusus'] as $s)
                    <li class="flex items-start gap-3 text-sm text-slate-600 leading-6">
                        <span class="mt-0.5 flex-shrink-0 w-5 h-5 rounded-full bg-[#00B1D1] flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        {{ $s }}
                    </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</section>

{{-- Jadwal --}}
<section class="bg-[#EFFDFF] py-16">
    <div class="max-w-6xl mx-auto px-6 md:px-10">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold"
                style="color: #2B2A28; text-shadow: 0px 4px 4px rgba(0,0,0,0.25);">Jadwal</h2>
            <p class="mt-2 text-[15px]"
               style="color: #575551; text-shadow: 0px 4px 4px rgba(0,0,0,0.25);">
                Jangan sampai lupa daftar sesuai jadwal yang telah kami tentukan
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($jadwals as $judul => $items)
            <div class="jadwal-col">
                <h3 class="text-center font-bold text-lg mb-4"
                    style="background: linear-gradient(180deg, #00B1D1, #00758A); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; filter: drop-shadow(0px 4px 4px rgba(101,101,101,0.25));">
                    {{ $judul }}
                </h3>
                <div class="border-2 border-[#27C2DE] rounded-3xl p-5 bg-white">
                    @foreach($items as $item)
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center flex-shrink-0">
                            <div class="w-8 h-8 flex items-center justify-center flex-shrink-0">
                                <img src="{{ asset('ppdb/' . $item['icon']) }}" alt="{{ $item['label'] }}" class="w-7 h-7 object-contain">
                            </div>
                            @if(!$loop->last)
                            <div class="w-[2px] flex-1 my-1"
                                style="background: repeating-linear-gradient(to bottom, #27C2DE 0px, #27C2DE 5px, transparent 5px, transparent 10px); min-height: 24px;"></div>
                            @endif
                        </div>
                        <div class="pb-4">
                            <p class="text-[11px] font-semibold leading-tight" style="color: #00B1D1;">{{ $item['tanggal'] }}</p>
                            <p class="text-sm font-bold mt-0.5" style="color: #2B2A28;">{{ $item['label'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    .hero-text {
        opacity: 0;
        transform: translateX(-30px);
        transition: opacity 700ms ease 200ms, transform 700ms ease 200ms;
    }
    .hero-text.visible { opacity: 1; transform: translateX(0); }

    .hero-img {
        opacity: 0;
        transform: translateX(30px);
        transition: opacity 700ms ease 400ms, transform 700ms ease 400ms;
    }
    .hero-img.visible { opacity: 1; transform: translateX(0); }

    .syarat-card {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 600ms ease, transform 600ms ease;
    }
    .syarat-card.visible { opacity: 1; transform: translateY(0); }

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

    document.querySelectorAll('.hero-text, .hero-img, .syarat-card, .jadwal-col').forEach((el, i) => {
        if (el.classList.contains('jadwal-col')) {
            el.style.transitionDelay = (i * 150) + 'ms';
        }
        observer.observe(el);
    });
</script>

@endsection