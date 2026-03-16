<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Admin MAN Jeneponto</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>

<body class="bg-[#F2F8FF] text-slate-900">

@php
    $isDashboard   = request()->routeIs('admin.dashboard');
    $isMaster      = request()->routeIs('admin.master*');
    $isOperasional = request()->routeIs('admin.operasional*');
    $isManajemen   = request()->routeIs('admin.manajemen*');

    $navItems = [
        ['label' => 'Dashboard',       'href' => route('admin.dashboard'), 'icon' => 'dashboard.png',       'active' => $isDashboard],
        ['label' => 'Master PPDB',     'href' => '#',                      'icon' => 'masterppdb.png',       'active' => $isMaster],
        ['label' => 'Operasional',     'href' => '#',                      'icon' => 'operasional.png',      'active' => $isOperasional],
        ['label' => 'Manajemen Sistem','href' => '#',                      'icon' => 'manajemensistem.png',  'active' => $isManajemen],
    ];
@endphp

<div x-data="{ sidebarOpen: true }" class="h-screen overflow-hidden flex">

    {{-- ===================== SIDEBAR ===================== --}}
    <aside class="bg-white border-r border-slate-100 flex flex-col transition-all duration-300 ease-in-out overflow-hidden"
           :class="sidebarOpen ? 'w-[210px]' : 'w-[60px]'"
           style="box-shadow: 2px 0 8px rgba(0,0,0,0.06);">

        {{-- Logo --}}
        <div class="border-b border-slate-100 flex flex-col items-center justify-center py-5 px-3">
            <img src="{{ asset('ppdb/man.svg') }}" alt="MAN Jeneponto"
                 class="w-12 h-12 object-contain block">
            <div x-show="sidebarOpen" x-cloak class="text-center mt-2">
                <p class="font-bold text-[11px] text-[#2B7A0B] leading-tight">MAN JENEPONTO</p>
                <p class="text-[8px] text-slate-400 leading-tight">MADRASAH MAJU BERMUTU MENDUNIA</p>
            </div>
        </div>

        {{-- NAV --}}
        <nav class="flex-1 py-4 overflow-y-auto overflow-x-hidden space-y-1">

            @foreach($navItems as $item)
                @if($item['active'])
                    {{-- ACTIVE --}}
                    <a href="{{ $item['href'] }}" class="block relative h-[44px]"
                       :class="sidebarOpen ? 'ml-3 mr-0' : 'mx-2'">
                        {{-- Strip kiri (hanya saat expanded) --}}
                        <div x-show="sidebarOpen" x-cloak
                             class="absolute -left-3 top-0 w-[7px] h-[44px] rounded-r-xl bg-[#27C2DE]"></div>
                        {{-- Pill --}}
                        <div class="w-full h-full flex items-center gap-3 px-4"
                             :class="sidebarOpen ? 'rounded-l-[10px]' : 'rounded-[10px] justify-center !px-0'"
                             style="background: linear-gradient(90deg, #15B2CE 0%, #00758A 100%);">
                            <img src="{{ asset('ppdb/admin/' . $item['icon']) }}" alt=""
                                 class="w-[22px] h-[22px] object-contain brightness-0 invert shrink-0">
                            <span x-show="sidebarOpen" x-cloak
                                  class="text-white font-semibold text-[13px] whitespace-nowrap">
                                {{ $item['label'] }}
                            </span>
                        </div>
                    </a>
                @else
                    {{-- INACTIVE --}}
                    <a href="{{ $item['href'] }}"
                       class="block relative h-[44px] flex items-center gap-3 rounded-[10px]
                              text-[#464646] hover:bg-[#EEF9FC] hover:text-[#27C2DE] transition-all
                              font-semibold text-[13px]"
                       :class="sidebarOpen ? 'mx-3 px-4' : 'mx-2 justify-center px-0'">
                        <img src="{{ asset('ppdb/admin/' . $item['icon']) }}" alt=""
                             class="w-[22px] h-[22px] object-contain shrink-0">
                        <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">{{ $item['label'] }}</span>
                    </a>
                @endif
            @endforeach

            {{-- Divider --}}
            <div class="pt-4 pb-2 px-3"><div class="h-px bg-slate-100"></div></div>

            {{-- Keluar --}}
            <div :class="sidebarOpen ? 'mx-3' : 'mx-2'">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full h-[44px] flex items-center gap-3 rounded-[10px]
                                   text-[#464646] hover:bg-red-50 hover:text-red-500 transition-all
                                   font-semibold text-[13px]"
                            :class="sidebarOpen ? 'px-4' : 'justify-center px-0'">
                        <img src="{{ asset('ppdb/admin/keluar.png') }}" alt=""
                             class="w-[22px] h-[22px] object-contain shrink-0">
                        <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Keluar</span>
                    </button>
                </form>
            </div>

        </nav>
    </aside>

    {{-- ===================== MAIN ===================== --}}
    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- TOPBAR --}}
        <header class="h-[70px] px-6 flex items-center justify-between bg-white border-b border-slate-100"
                style="box-shadow: 0px 2px 4px rgba(175,175,175,0.2);">

            <div class="flex items-center gap-4">
                {{-- Toggle Sidebar --}}
                <button @click="sidebarOpen = !sidebarOpen"
                        class="w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center
                               text-slate-500 hover:bg-slate-50 transition-all">
                    <svg x-show="sidebarOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <svg x-show="!sidebarOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                {{-- Page Title --}}
                <div>
                    <h1 class="text-[18px] font-bold text-[#363636]">
                        Welcome back, {{ auth()->user()->name ?? 'Admin' }}
                    </h1>
                    <p class="text-[11px] text-slate-400">Tetap semangat dan jangan lupa ibadah</p>
                </div>
            </div>

            {{-- Right: Bell + User --}}
            <div class="flex items-center gap-3">

                {{-- Bell --}}
                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-[#EAF3FF]"
                     style="filter: drop-shadow(0px 2px 4px rgba(0,0,0,0.1));">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 7h18s-3 0-3-7Z"
                              stroke="#353535" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M10 19a2 2 0 0 0 4 0"
                              stroke="#353535" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>

                {{-- User Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center gap-2 hover:bg-slate-50 rounded-xl px-2 py-1.5 transition-all">
                        <div class="w-[40px] h-[40px] rounded-full bg-gradient-to-br from-[#27C2DE] to-[#0099B8]
                                    flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="text-left" x-show="sidebarOpen">
                            <div class="font-semibold text-[13px] text-[#353535]">
                                {{ auth()->user()->name ?? 'Admin' }}
                            </div>
                            <div class="text-[11px] text-[#ABABAB]">
                                {{ auth()->user()->role ?? 'Admin' }}
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                             :class="open ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="open" @click.outside="open = false" x-transition x-cloak
                         class="absolute right-0 top-14 w-48 bg-white rounded-xl shadow-lg border border-slate-100 overflow-hidden z-50">
                        <a href="#" class="block px-4 py-3 text-[13px] text-slate-700 hover:bg-slate-50 transition-all">
                            Profil Saya
                        </a>
                        <a href="#" class="block px-4 py-3 text-[13px] text-slate-700 hover:bg-slate-50 transition-all">
                            Ubah Password
                        </a>
                        <div class="border-t border-slate-100"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left px-4 py-3 text-[13px] text-red-500 hover:bg-red-50 transition-all">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        {{-- KONTEN --}}
        <section class="flex-1 px-6 py-7 overflow-y-auto overflow-x-hidden bg-[#F2F8FF]">
            @yield('content')
        </section>

    </main>
</div>

@stack('scripts')
</body>
</html>