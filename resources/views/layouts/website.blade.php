<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MAN Jeneponto')</title>

    {{-- Tailwind CSS CDN (ganti dengan vite jika sudah setup) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50:  '#f0fdf9',
                            100: '#ccfbef',
                            200: '#99f6e0',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e56',
                            900: '#134e4a',
                        },
                    },
                    fontFamily: {
                        display: ['"Plus Jakarta Sans"', 'sans-serif'],
                        body: ['"DM Sans"', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'DM Sans', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #0d9488; border-radius: 3px; }

        /* Navbar scroll effect */
        .navbar-scrolled {
            background: rgba(255,255,255,0.98) !important;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
        }

        /* Smooth scroll */
        html { scroll-behavior: smooth; }

        /* Hero overlay */
        .hero-overlay {
            background: linear-gradient(135deg, rgba(13, 148, 136, 0.85) 0%, rgba(17, 94, 86, 0.75) 100%);
        }

        /* Fade in animation */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeInUp 0.7s ease forwards; }
        .delay-1 { animation-delay: 0.1s; opacity: 0; }
        .delay-2 { animation-delay: 0.2s; opacity: 0; }
        .delay-3 { animation-delay: 0.3s; opacity: 0; }
        .delay-4 { animation-delay: 0.4s; opacity: 0; }

        /* FAQ accordion */
        .faq-answer { max-height: 0; overflow: hidden; transition: max-height 0.35s ease; }
        .faq-answer.open { max-height: 300px; }
        .faq-icon { transition: transform 0.3s ease; }
        .faq-icon.open { transform: rotate(180deg); }

        /* Card hover */
        .jalur-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(13,148,136,0.15); }
        .jalur-card { transition: all 0.3s ease; }

        /* Timeline dot */
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -1px;
            top: 50%;
            transform: translateY(-50%);
            width: 10px;
            height: 10px;
            background: #0d9488;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 0 0 2px #0d9488;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-white text-gray-800 antialiased">

    {{-- ========== NAVBAR ========== --}}
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-sm shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex items-center gap-3 flex-shrink-0">
                    <div class="w-9 h-9 bg-primary-700 rounded-lg flex items-center justify-center">
                        <span class="text-white font-display font-bold text-sm">MJ</span>
                    </div>
                    <div class="leading-tight">
                        <div class="font-display font-bold text-primary-800 text-sm">MAN JENEPONTO</div>
                        <div class="text-[10px] text-gray-500 tracking-wide">MADRASAH MAJU, BERMARTABAT, MELAYANI</div>
                    </div>
                </a>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex items-center gap-1">
                    <a href="#beranda" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-primary-700 rounded-lg hover:bg-primary-50 transition-all">Beranda</a>
                    <a href="#jalur"   class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-primary-700 rounded-lg hover:bg-primary-50 transition-all">Jalur</a>
                    <a href="#jadwal"  class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-primary-700 rounded-lg hover:bg-primary-50 transition-all">Jadwal</a>
                    <a href="#tutorial" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-primary-700 rounded-lg hover:bg-primary-50 transition-all">Tutorial</a>
                    <a href="#faq"    class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-primary-700 rounded-lg hover:bg-primary-50 transition-all">FAQ</a>
                    <a href="{{ route('hubungi-kami') }}" class="ml-3 px-4 py-2 text-sm font-semibold text-white bg-primary-700 hover:bg-primary-800 rounded-lg transition-all shadow-sm hover:shadow-md">
                        Hubungi Kami
                    </a>
                </div>

                {{-- Mobile hamburger --}}
                <button id="menu-btn" class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 bg-white px-4 py-3 space-y-1">
            <a href="#beranda"  class="block px-3 py-2 text-sm font-medium text-gray-700 hover:text-primary-700 rounded-lg hover:bg-primary-50">Beranda</a>
            <a href="#jalur"    class="block px-3 py-2 text-sm font-medium text-gray-700 hover:text-primary-700 rounded-lg hover:bg-primary-50">Jalur</a>
            <a href="#jadwal"   class="block px-3 py-2 text-sm font-medium text-gray-700 hover:text-primary-700 rounded-lg hover:bg-primary-50">Jadwal</a>
            <a href="#tutorial" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:text-primary-700 rounded-lg hover:bg-primary-50">Tutorial</a>
            <a href="#faq"      class="block px-3 py-2 text-sm font-medium text-gray-700 hover:text-primary-700 rounded-lg hover:bg-primary-50">FAQ</a>
            <a href="{{ route('hubungi-kami') }}" class="block mt-2 px-4 py-2 text-sm font-semibold text-white bg-primary-700 rounded-lg text-center">Hubungi Kami</a>
        </div>
    </nav>

    {{-- Page Content --}}
    <main>
        @yield('content')
    </main>

    {{-- ========== FOOTER ========== --}}
    <footer class="bg-gray-900 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

                {{-- Brand --}}
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 bg-primary-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-display font-bold text-sm">MJ</span>
                        </div>
                        <div class="leading-tight">
                            <div class="font-display font-bold text-white text-sm">MAN JENEPONTO</div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed">Madrasah unggul dalam prestasi, berkarakter, dan berlandaskan nilai keislaman.</p>
                    <div class="flex gap-3 mt-5">
                        <a href="#" class="w-8 h-8 bg-gray-700 hover:bg-primary-600 rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-8 h-8 bg-gray-700 hover:bg-primary-600 rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="w-8 h-8 bg-gray-700 hover:bg-primary-600 rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.67a8.27 8.27 0 004.84 1.54V6.76a4.85 4.85 0 01-1.07-.07z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Menu Cepat --}}
                <div>
                    <h4 class="font-display font-semibold text-white mb-4 text-sm">Menu Cepat</h4>
                    <ul class="space-y-2">
                        @foreach(['Beranda','Profil','Berita','Jadwal'] as $item)
                        <li><a href="#" class="text-sm text-gray-400 hover:text-primary-400 transition-colors">{{ $item }}</a></li>
                        @endforeach
                    </ul>
                </div>

                {{-- Layanan --}}
                <div>
                    <h4 class="font-display font-semibold text-white mb-4 text-sm">Layanan</h4>
                    <ul class="space-y-2">
                        @foreach(['PPDB Online','P-3P','Pengaduan','Buku Tamu Buku'] as $item)
                        <li><a href="#" class="text-sm text-gray-400 hover:text-primary-400 transition-colors">{{ $item }}</a></li>
                        @endforeach
                    </ul>
                </div>

                {{-- Kontak --}}
                <div>
                    <h4 class="font-display font-semibold text-white mb-4 text-sm">Kontak</h4>
                    <div class="space-y-3 text-sm text-gray-400">
                        <div class="flex gap-2">
                            <svg class="w-4 h-4 text-primary-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Jl. Lanto Dg. Pasewang No. 299, Desa Bontorannu, Kecamatan Turatea, Kabupaten Jeneponto, Provinsi Sulawesi Selatan</span>
                        </div>
                        <div class="flex gap-2">
                            <svg class="w-4 h-4 text-primary-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span>+62-411-700</span>
                        </div>
                        <div class="flex gap-2">
                            <svg class="w-4 h-4 text-primary-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>manjeneponto@kemenag.go.id</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 py-5 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} MAN Jeneponto. Hak Cipta Dilindungi.
        </div>
    </footer>

    {{-- Scripts --}}
    <script>
        // Navbar scroll
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('navbar-scrolled', window.scrollY > 20);
        });

        // Mobile menu
        document.getElementById('menu-btn').addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>

    @stack('scripts')
</body>
</html>