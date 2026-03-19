<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - PPDB MAN Jeneponto')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        html { scroll-behavior: smooth; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen" style="background-color: #F4F8FF;">

    {{-- NAVBAR --}}
    <nav class="bg-[#27C2DE] shadow-[0px_4px_4px_rgba(101,101,101,0.25)] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="h-14 flex items-center justify-between">

                {{-- Logo --}}
                <div class="flex items-center gap-2">
                    <img src="{{ asset('ppdb/man.svg') }}" alt="Logo MAN Jeneponto"
                         class="h-9 w-9 object-contain">
                    <div class="text-white">
                        <h1 class="text-sm font-bold leading-none">MAN JENEPONTO</h1>
                        <p class="text-[9px] tracking-wide mt-0.5 uppercase text-white/90">
                            Madrasah Maju Bermutu Mendunia
                        </p>
                    </div>
                </div>

                {{-- Kanan: Notif + Profile --}}
                <div class="flex items-center gap-3">

                    {{-- Notifikasi --}}
                    <button class="text-white relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </button>

                    {{-- Profile Dropdown --}}
                    <div class="relative" id="profileDropdown">
                        <div class="flex items-center gap-2 cursor-pointer" onclick="toggleDropdown()">
                            <div class="w-7 h-7 rounded-full overflow-hidden border-2 border-white/60 bg-white/20 flex items-center justify-center">
                                <img src="{{ asset('ppdb/man.svg') }}" alt="profile" class="w-5 h-5 object-contain">
                            </div>
                            <span class="text-white text-xs font-semibold">
                                Halo, {{ Auth::guard('ppdb')->user()->nama }} 👋
                            </span>
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        {{-- Dropdown --}}
                        <div id="dropdownMenu"
                             style="display:none; position:absolute; right:0; top:44px; background:white; border-radius:12px; box-shadow:0 10px 40px rgba(0,0,0,0.15); min-width:180px; z-index:100;">
                            <div style="padding:8px 0;">
                                <a href="#"
                                   style="display:flex; align-items:center; gap:10px; padding:10px 16px; font-size:13px; color:#2B2A28; text-decoration:none;"
                                   onmouseover="this.style.backgroundColor='#F0FAFD'"
                                   onmouseout="this.style.backgroundColor='transparent'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profil Saya
                                </a>
                                <a href="{{ route('siswa.dashboard') }}"
                                   style="display:flex; align-items:center; gap:10px; padding:10px 16px; font-size:13px; color:#2B2A28; text-decoration:none;"
                                   onmouseover="this.style.backgroundColor='#F0FAFD'"
                                   onmouseout="this.style.backgroundColor='transparent'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    Dashboard
                                </a>
                                <div style="border-top:1px solid #f0f0f0; margin:4px 0;"></div>
                                <a href="{{ route('ppdb.login') }}"
                                   style="display:flex; align-items:center; gap:10px; padding:10px 16px; font-size:13px; color:#ef4444; text-decoration:none;"
                                   onmouseover="this.style.backgroundColor='#FFF5F5'"
                                   onmouseout="this.style.backgroundColor='transparent'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Keluar
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </nav>

    <main>@yield('content')</main>

    <script>
        function toggleDropdown() {
            const menu = document.getElementById('dropdownMenu');
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        }
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('profileDropdown');
            if (!dropdown.contains(e.target)) {
                document.getElementById('dropdownMenu').style.display = 'none';
            }
        });
    </script>

    @stack('scripts')

</body>
</html>