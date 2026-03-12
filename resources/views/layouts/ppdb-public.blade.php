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

<footer id="kontak" style="background-color: #00758A; scroll-margin-top: 80px;" class="text-white">
    <div class="max-w-7xl mx-auto px-6 pt-0 pb-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center pt-6">

            <div>
                <div class="mb-4"> {{-- mb-8 → mb-4 --}}
                    <h3 class="font-bold text-base">MAN Jeneponto</h3>
                    <p class="mt-1 text-sm leading-5 text-white/90">
                        Madrasah unggul dalam prestasi,<br>
                        berkarakter, dan berlandaskan nilai keislaman.
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-4"> {{-- gap-6 → gap-4 --}}
                    <div>
                        <h4 class="font-bold text-sm mb-2">Menu Cepat</h4> {{-- mb-4 → mb-2 --}}
                        <ul class="space-y-2 text-sm text-white/90"> {{-- space-y-3 → space-y-2 --}}
                            <li><a href="#beranda" class="hover:text-white transition-colors">Beranda</a></li>
                            <li><a href="#profil" class="hover:text-white transition-colors">Profile</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Berita</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Galeri</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm mb-2">Layanan</h4>
                        <ul class="space-y-2 text-sm text-white/90">
                            <li><a href="/ppdb" class="hover:text-white transition-colors">PPDB Online</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">PTSP</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Pengaduan</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Zona Integritas</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm mb-2">Kontak</h4>
                        <div class="text-sm text-white/90 space-y-2 leading-5">
                            <p>Jl. Lanto Dg. Pasewang No. 351, Desa Balang, Kecamatan Binamu, Kabupaten Jeneponto, Provinsi Sulawesi Selatan</p>
                            <p>+123-456-780</p>
                            <p>manjeneponto@schools</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Maps lebih kecil --}}
          <div class="rounded-M overflow-hidden ml-auto" style="height: 200px; width: 70%;">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3973.5!2d119.6978!3d-5.6749!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2sMAN+Jeneponto!5e0!3m2!1sen!2sid!4v1"
                width="100%" height="100%" style="border:0;"
                allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

        </div>

        {{-- Bottom bar --}}
        <div class="border-t border-white/20 mt-6 pt-4 flex flex-col md:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2 text-sm text-white/90">
                <svg class="w-5 h-5 text-[#27C2DE]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                </svg>
                <span class="font-semibold">Risma</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="#" class="w-9 h-9 rounded-full border border-white/40 flex items-center justify-center hover:bg-white/20 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                </a>
                <a href="#" class="w-9 h-9 rounded-full border border-white/40 flex items-center justify-center hover:bg-white/20 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <a href="#" class="w-9 h-9 rounded-full border border-white/40 flex items-center justify-center hover:bg-white/20 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
            </div>
        </div>

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