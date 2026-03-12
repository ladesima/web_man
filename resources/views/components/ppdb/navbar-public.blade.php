<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PPDB MAN Jeneponto')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        html { scroll-behavior: smooth; }
        details summary::-webkit-details-marker { display: none; }
        details[open] summary .chevron { transform: rotate(180deg); }
        .chevron { transition: transform 0.25s ease; }
    </style>
    @stack('styles')
</head>
<body class="bg-white text-slate-800 antialiased">

    {{-- ===== NAVBAR ===== --}}
    <nav class="bg-[#27C2DE] shadow-[0px_4px_4px_rgba(101,101,101,0.25)] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="h-20 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('ppdb/man.svg') }}" alt="Logo MAN Jeneponto"
                         class="h-12 w-12 object-contain"
                         onerror="this.style.display='none'">
                    <div class="text-white">
                        <h1 class="text-lg font-bold leading-none">MAN JENEPONTO</h1>
                        <p class="text-[10px] tracking-wide mt-1 uppercase text-white/90">
                            Madrasah Maju Bermutu Mendunia
                        </p>
                    </div>
                </div>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex items-center gap-7 text-sm text-white font-medium">
                    <a href="#beranda"  class="hover:opacity-75 transition">Beranda</a>
                    <a href="#jalur"    class="hover:opacity-75 transition">Jalur</a>
                    <a href="#jadwal"   class="hover:opacity-75 transition">Jadwal</a>
                    <a href="#tutorial" class="hover:opacity-75 transition">Tutorial</a>
                    <a href="#faq"      class="hover:opacity-75 transition">FAQ</a>
                    <a href="#kontak"
                       class="bg-white text-[#27C2DE] px-5 py-2 rounded-full font-semibold hover:bg-slate-50 transition shadow-sm">
                        Hubungi Kami
                    </a>
                </div>

                {{-- Mobile Toggle --}}
                <button id="mob-btn" class="md:hidden text-white p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mob-menu" class="hidden md:hidden bg-[#22b0cb] border-t border-white/20 px-4 py-3 space-y-1">
            <a href="#beranda"  class="block py-2 text-sm text-white font-medium">Beranda</a>
            <a href="#jalur"    class="block py-2 text-sm text-white font-medium">Jalur</a>
            <a href="#jadwal"   class="block py-2 text-sm text-white font-medium">Jadwal</a>
            <a href="#tutorial" class="block py-2 text-sm text-white font-medium">Tutorial</a>
            <a href="#faq"      class="block py-2 text-sm text-white font-medium">FAQ</a>
            <a href="#kontak"   class="block mt-2 text-center bg-white text-[#27C2DE] py-2 rounded-full text-sm font-semibold">Hubungi Kami</a>
        </div>
    </nav>

    <main>@yield('content')</main>

    {{-- ===== FOOTER ===== --}}
    <footer id="kontak" class="bg-[#1A2E35] text-gray-300">
        <div class="max-w-7xl mx-auto px-4 py-14">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

                {{-- Brand --}}
                <div class="md:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-[#27C2DE] rounded-lg flex items-center justify-center text-white font-bold text-xs">MJ</div>
                        <span class="font-bold text-white text-sm">MAN Jeneponto</span>
                    </div>
                    <p class="text-xs text-gray-400 leading-relaxed">
                        Madrasah unggul dalam prestasi, berkarakter, dan berlandaskan nilai keislaman.
                    </p>
                    <div class="flex gap-2 mt-5">
                        @foreach(['instagram','facebook','tiktok'] as $sosmed)
                        <a href="#" class="w-8 h-8 bg-gray-700 hover:bg-[#27C2DE] rounded-lg flex items-center justify-center transition-colors">
                            @if($sosmed === 'instagram')
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            @elseif($sosmed === 'facebook')
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            @else
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.67a8.27 8.27 0 004.84 1.54V6.76a4.85 4.85 0 01-1.07-.07z"/></svg>
                            @endif
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Menu Cepat --}}
                <div>
                    <h4 class="font-semibold text-white text-sm mb-4">Menu Cepat</h4>
                    <ul class="space-y-2">
                        @foreach(['Beranda','Profil','Berita','Jadwal'] as $m)
                        <li><a href="#" class="text-xs text-gray-400 hover:text-[#27C2DE] transition">{{ $m }}</a></li>
                        @endforeach
                    </ul>
                </div>

                {{-- Layanan --}}
                <div>
                    <h4 class="font-semibold text-white text-sm mb-4">Layanan</h4>
                    <ul class="space-y-2">
                        @foreach(['PPDB Online','P-3P','Pengaduan','Buku Tamu Buku'] as $m)
                        <li><a href="#" class="text-xs text-gray-400 hover:text-[#27C2DE] transition">{{ $m }}</a></li>
                        @endforeach
                    </ul>
                </div>

                {{-- Kontak --}}
                <div>
                    <h4 class="font-semibold text-white text-sm mb-4">Kontak</h4>
                    <div class="space-y-3 text-xs text-gray-400">
                        <div class="flex gap-2">
                            <svg class="w-3.5 h-3.5 text-[#27C2DE] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            <span>Jl. Lanto Dg. Pasewang No. 299, Desa Bontorannu, Kec. Turatea, Kab. Jeneponto, Sulawesi Selatan</span>
                        </div>
                        <div class="flex gap-2">
                            <svg class="w-3.5 h-3.5 text-[#27C2DE] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span>+62-411-700</span>
                        </div>
                        <div class="flex gap-2">
                            <svg class="w-3.5 h-3.5 text-[#27C2DE] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>manjeneponto@kemenag.go.id</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-700 py-4 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} MAN Jeneponto. Hak Cipta Dilindungi.
        </div>
    </footer>

    <script>
        document.getElementById('mob-btn').addEventListener('click', () => {
            document.getElementById('mob-menu').classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>