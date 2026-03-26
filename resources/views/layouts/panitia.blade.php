<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Panitia PPDB MAN Jeneponto</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>

<body class="bg-[#F2F8FF] text-slate-900">

@php
    $isDashboard     = request()->routeIs('panitia.dashboard');
    $isDataPendaftar = request()->routeIs('panitia.data-pendaftar*');
    $isOperasional   = request()->routeIs('panitia.operasional*');
    $isSeleksi       = request()->routeIs('panitia.seleksi*');
    $isPengumuman    = request()->routeIs('panitia.pengumuman*');
@endphp

<div x-data="{
    sidebarOpen: true,
    showLogout: false,
    operasionalOpen: {{ $isOperasional ? 'true' : 'false' }}
}" class="h-screen overflow-hidden flex">

    {{-- ===================== SIDEBAR ===================== --}}
    <aside class="relative z-10 flex flex-col transition-all duration-300 ease-in-out overflow-hidden flex-shrink-0"
           :class="sidebarOpen ? 'w-[200px]' : 'w-[56px]'"
           style="background:#FAFEFF; box-shadow: 2px 0px 8px rgba(0,0,0,0.15);">

        {{-- Logo --}}
        <div class="border-b border-slate-100 flex flex-col items-center justify-center py-4 px-3">
            <img src="{{ asset('ppdb/man.svg') }}" alt="MAN Jeneponto"
                 class="w-9 h-9 object-contain block">
            <div x-show="sidebarOpen" x-cloak class="text-center mt-1.5">
                <p class="font-bold text-[14px]" style="color:#2B2A28;">MAN JENEPONTO</p>
                <p class="text-[9px] leading-tight" style="color:#2B2A28;">MADRASAH MAJU BERMUTU MENDUNIA</p>
            </div>
        </div>

        {{-- NAV --}}
        <nav class="flex-1 py-4 overflow-y-auto overflow-x-hidden space-y-1">

            {{-- Dashboard --}}
            @if($isDashboard)
            <a href="{{ route('panitia.dashboard') }}" class="block relative h-[44px]"
               :class="sidebarOpen ? 'ml-3 mr-0' : 'mx-2'">
                <div x-show="sidebarOpen" x-cloak class="absolute -left-3 top-0 w-[6px] h-[40px] rounded-r-xl bg-[#27C2DE]"></div>
                <div class="w-full h-full flex items-center gap-2.5"
                     :class="sidebarOpen ? 'rounded-l-[10px] px-3' : 'rounded-[10px] justify-center'"
                     style="background: linear-gradient(90deg, #15B2CE 0%, #00758A 100%);">
                    <img src="{{ asset('ppdb/admin/dashboard.png') }}" alt="" class="w-[18px] h-[18px] object-contain brightness-0 invert shrink-0">
                    <span x-show="sidebarOpen" x-cloak class="text-white font-semibold text-[13px] whitespace-nowrap">Dashboard</span>
                </div>
            </a>
            @else
            <a href="{{ route('panitia.dashboard') }}"
               class="block h-[44px] flex items-center gap-2.5 rounded-[10px] text-[#464646] hover:bg-[#EEF9FC] hover:text-[#27C2DE] transition-all font-semibold text-[13px]"
               :class="sidebarOpen ? 'mx-3 px-3' : 'mx-2 justify-center'">
                <img src="{{ asset('ppdb/admin/dashboard.png') }}" alt="" class="w-[18px] h-[18px] object-contain shrink-0">
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Dashboard</span>
            </a>
            @endif

            {{-- Data Pendaftar --}}
            @if($isDataPendaftar)
            <a href="{{ route('panitia.data-pendaftar') }}" class="block relative h-[44px]"
               :class="sidebarOpen ? 'ml-3 mr-0' : 'mx-2'">
                <div x-show="sidebarOpen" x-cloak class="absolute -left-3 top-0 w-[6px] h-[40px] rounded-r-xl bg-[#27C2DE]"></div>
                <div class="w-full h-full flex items-center gap-2.5"
                     :class="sidebarOpen ? 'rounded-l-[10px] px-3' : 'rounded-[10px] justify-center'"
                     style="background: linear-gradient(90deg, #15B2CE 0%, #00758A 100%);">
                    <img src="{{ asset('ppdb/admin/datapendaftar.png') }}" alt="" class="w-[18px] h-[18px] object-contain brightness-0 invert shrink-0">
                    <span x-show="sidebarOpen" x-cloak class="text-white font-semibold text-[13px] whitespace-nowrap">Data Pendaftar</span>
                </div>
            </a>
            @else
            <a href="{{ route('panitia.data-pendaftar') }}"
               class="block h-[44px] flex items-center gap-2.5 rounded-[10px] text-[#464646] hover:bg-[#EEF9FC] hover:text-[#27C2DE] transition-all font-semibold text-[13px]"
               :class="sidebarOpen ? 'mx-3 px-3' : 'mx-2 justify-center'">
                <img src="{{ asset('ppdb/admin/datapendaftar.png') }}" alt="" class="w-[18px] h-[18px] object-contain shrink-0">
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Data Pendaftar</span>
            </a>
            @endif

            {{-- Operasional (dropdown) --}}
            <div>
                @if($isOperasional)
                <div class="block relative h-[44px]"
                     :class="sidebarOpen ? 'ml-3 mr-0' : 'mx-2'">
                    <div x-show="sidebarOpen" x-cloak class="absolute -left-3 top-0 w-[6px] h-[44px] rounded-r-xl bg-[#27C2DE]"></div>
                    <button @click="operasionalOpen = !operasionalOpen"
                            class="w-full h-full flex items-center gap-2.5"
                            :class="sidebarOpen ? 'rounded-l-[10px] pl-3 pr-4' : 'rounded-[10px] justify-center'"
                            style="background: linear-gradient(90deg, #15B2CE 0%, #00758A 100%);">
                        <img src="{{ asset('ppdb/admin/operasional.png') }}" alt="" class="w-[18px] h-[18px] object-contain brightness-0 invert shrink-0">
                        <span x-show="sidebarOpen" x-cloak class="text-white font-semibold text-[13px] whitespace-nowrap flex-1 text-left">Operasional</span>
                        <svg x-show="sidebarOpen" x-cloak class="w-3 h-3 text-white transition-transform shrink-0"
                             :class="operasionalOpen ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>
                @else
                <button @click="operasionalOpen = !operasionalOpen"
                        class="h-[44px] flex items-center gap-2.5 rounded-[10px] text-[#464646] hover:bg-[#EEF9FC] hover:text-[#27C2DE] transition-all font-semibold text-[13px]"
                        :class="sidebarOpen ? 'mx-3 pl-3 pr-4 w-[calc(100%-24px)]' : 'mx-2 justify-center w-[calc(100%-16px)]'">
                    <img src="{{ asset('ppdb/admin/operasional.png') }}" alt="" class="w-[18px] h-[18px] object-contain shrink-0">
                    <span x-show="sidebarOpen" x-cloak class="text-[13px] whitespace-nowrap flex-1 text-left">Operasional</span>
                    <svg x-show="sidebarOpen" x-cloak class="w-3 h-3 transition-transform shrink-0"
                         :class="operasionalOpen ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                @endif

                <div x-show="operasionalOpen && sidebarOpen" x-cloak class="mt-1 ml-7">
                    @php
                        $opSubMenus = [
                            ['route' => 'panitia.operasional.verifikasi', 'label' => 'Verifikasi Berkas', 'index' => 0],
                            ['route' => 'panitia.operasional.pengumuman', 'label' => 'Pengumuman',         'index' => 1],
                            ['route' => 'panitia.operasional.faq',        'label' => 'FAQ & Bantuan',      'index' => 2],
                        ];
                        $opActiveIndex = -1;
                        foreach($opSubMenus as $sm) {
                            if(request()->routeIs($sm['route'])) $opActiveIndex = $sm['index'];
                        }
                    @endphp
                    @foreach($opSubMenus as $sm)
                    @php $isOpActive = ($sm['index'] === $opActiveIndex); @endphp
                    <div class="flex items-stretch">
                        <div class="w-px shrink-0 mr-3"
                             style="{{ $sm['index'] <= $opActiveIndex
                                ? 'background:#27C2DE;'
                                : 'background: repeating-linear-gradient(to bottom, #CBD5E1 0px, #CBD5E1 4px, transparent 4px, transparent 8px);' }}">
                        </div>
                        <a href="{{ route($sm['route']) }}"
                           class="flex-1 px-3 py-2.5 text-[12px] rounded-l-lg transition-all
                                  {{ $isOpActive ? 'text-[#27C2DE] font-semibold bg-[#EEF9FC]' : 'text-slate-500 hover:text-[#27C2DE] hover:bg-[#EEF9FC]' }}">
                            {{ $sm['label'] }}
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Seleksi Nilai --}}
            @if($isSeleksi)
            <a href="{{ route('panitia.seleksi') }}" class="block relative h-[44px]"
            :class="sidebarOpen ? 'ml-3 mr-0' : 'mx-2'">
                <div x-show="sidebarOpen" x-cloak class="absolute -left-3 top-0 w-[6px] h-[40px] rounded-r-xl bg-[#27C2DE]"></div>
                <div class="w-full h-full flex items-center gap-2.5"
                    :class="sidebarOpen ? 'rounded-l-[10px] px-3' : 'rounded-[10px] justify-center'"
                    style="background: linear-gradient(90deg, #15B2CE 0%, #00758A 100%);">
                    <img src="{{ asset('ppdb/admin/seleksi.png') }}" alt="" class="w-[18px] h-[18px] object-contain brightness-0 invert shrink-0">
                    <span x-show="sidebarOpen" x-cloak class="text-white font-semibold text-[13px] whitespace-nowrap">Seleksi Nilai</span>
                </div>
            </a>
            @else
            <a href="{{ route('panitia.seleksi') }}"
            class="block h-[44px] flex items-center gap-2.5 rounded-[10px] text-[#464646] hover:bg-[#EEF9FC] hover:text-[#27C2DE] transition-all font-semibold text-[13px]"
            :class="sidebarOpen ? 'mx-3 px-3' : 'mx-2 justify-center'">
                <img src="{{ asset('ppdb/admin/seleksi.png') }}" alt="" class="w-[18px] h-[18px] object-contain shrink-0">
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Seleksi Nilai</span>
            </a>
            @endif

            {{-- Pengumuman --}}
            @if($isPengumuman)
            <a href="{{ route('panitia.pengumuman') }}" class="block relative h-[44px]"
            :class="sidebarOpen ? 'ml-3 mr-0' : 'mx-2'">
                <div x-show="sidebarOpen" x-cloak class="absolute -left-3 top-0 w-[6px] h-[40px] rounded-r-xl bg-[#27C2DE]"></div>
                <div class="w-full h-full flex items-center gap-2.5"
                    :class="sidebarOpen ? 'rounded-l-[10px] px-3' : 'rounded-[10px] justify-center'"
                    style="background: linear-gradient(90deg, #15B2CE 0%, #00758A 100%);">
                    <img src="{{ asset('ppdb/admin/pengumuman2.png') }}" alt="" class="w-[18px] h-[18px] object-contain brightness-0 invert shrink-0">
                    <span x-show="sidebarOpen" x-cloak class="text-white font-semibold text-[13px] whitespace-nowrap">Pengumuman</span>
                </div>
            </a>
            @else
            <a href="{{ route('panitia.pengumuman') }}"
            class="block h-[44px] flex items-center gap-2.5 rounded-[10px] text-[#464646] hover:bg-[#EEF9FC] hover:text-[#27C2DE] transition-all font-semibold text-[13px]"
            :class="sidebarOpen ? 'mx-3 px-3' : 'mx-2 justify-center'">
                <img src="{{ asset('ppdb/admin/pengumuman2.png') }}" alt="" class="w-[18px] h-[18px] object-contain shrink-0">
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Pengumuman</span>
            </a>
            @endif

            {{-- Divider --}}
            <div class="pt-3 pb-2 px-3"><div class="h-px bg-slate-100"></div></div>

            {{-- Keluar --}}
            <div :class="sidebarOpen ? 'mx-3' : 'mx-2'">
                <button type="button"
                        @click="showLogout = true"
                        class="w-full h-[44px] flex items-center gap-2.5 rounded-[10px] text-[#464646] hover:bg-red-50 hover:text-red-500 transition-all font-semibold text-[12px]"
                        :class="sidebarOpen ? 'px-3' : 'justify-center'">
                    <img src="{{ asset('ppdb/admin/keluar.png') }}" alt="" class="w-[18px] h-[18px] object-contain shrink-0">
                    <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Keluar</span>
                </button>
            </div>

        </nav>
    </aside>

    {{-- ===================== MAIN ===================== --}}
    <main class="flex-1 flex flex-col overflow-hidden min-w-0">

        {{-- TOPBAR --}}
        <header class="relative z-10 h-[64px] px-6 flex items-center justify-between flex-shrink-0"
                style="background:#FAFEFF; box-shadow: 0px 4px 6px rgba(101,101,101,0.2);">

            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-all">
                    <svg x-show="sidebarOpen" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <svg x-show="!sidebarOpen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <div>
                    @if(request()->routeIs('panitia.dashboard'))
                        <h1 class="text-[17px] font-bold text-[#006E87]">Welcome back, {{ auth()->user()->name ?? 'Panitia' }}</h1>
                        <p class="text-[11px] text-[#2B2A28]">Pantau proses verifikasi berkas dan seleksi calon siswa</p>
                    @elseif(request()->routeIs('panitia.data-pendaftar*'))
                        <h1 class="text-[17px] font-bold text-[#006E87]">Data Pendaftar</h1>
                        <p class="text-[11px] text-[#2B2A28]">Daftar seluruh calon siswa yang telah mendaftar</p>
                    @elseif(request()->routeIs('panitia.operasional.verifikasi'))
                        <h1 class="text-[17px] font-bold text-[#006E87]">Verifikasi Berkas</h1>
                        <p class="text-[11px] text-[#2B2A28]">Periksa dan verifikasi kelengkapan berkas pendaftar</p>
                    @elseif(request()->routeIs('panitia.operasional.pengumuman'))
                        <h1 class="text-[17px] font-bold text-[#006E87]">Pengumuman</h1>
                        <p class="text-[11px] text-[#2B2A28]">Kelola pengumuman hasil seleksi</p>
                    @elseif(request()->routeIs('panitia.operasional.faq'))
                        <h1 class="text-[17px] font-bold text-[#006E87]">FAQ & Bantuan</h1>
                        <p class="text-[11px] text-[#2B2A28]">Kelola pertanyaan dan bantuan</p>
                    @elseif(request()->routeIs('panitia.seleksi*'))
                        <h1 class="text-[17px] font-bold text-[#006E87]">Seleksi Nilai</h1>
                        <p class="text-[11px] text-[#2B2A28]">Kelola seleksi nilai calon siswa baru</p>
                    @elseif(request()->routeIs('panitia.pengumuman*'))
                        <h1 class="text-[17px] font-bold text-[#006E87]">Pengumuman</h1>
                        <p class="text-[11px] text-[#2B2A28]">Lihat hasil seleksi peserta yang telah dipublikasikan</p>
                    @else
                        <h1 class="text-[17px] font-bold text-[#006E87]">@yield('title', 'Dashboard')</h1>
                        <p class="text-[11px] text-[#006E87] opacity-60">Panitia PPDB MAN Jeneponto</p>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">
                <img src="{{ asset('ppdb/admin/notifikasi.png') }}" alt="Notifikasi" class="w-[35px] h-[35px] object-contain cursor-pointer shrink-0">

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center gap-2 hover:bg-slate-50 rounded-xl px-2 py-1.5 transition-all">
                        <div class="w-[36px] h-[36px] rounded-full bg-gradient-to-br from-[#27C2DE] to-[#0099B8] flex items-center justify-center text-white font-bold text-sm shrink-0">
                            {{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="text-left" x-show="sidebarOpen">
                            <div class="font-semibold text-[12px] text-[#353535]">{{ auth()->user()->name ?? 'Panitia' }}</div>
                            <div class="text-[10px] text-[#ABABAB]">Panitia PPDB</div>
                        </div>
                        <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 shrink-0"
                             :class="open ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" @click.outside="open = false" x-transition x-cloak
                         class="absolute right-0 top-14 w-48 bg-white rounded-xl shadow-lg border border-slate-100 overflow-hidden z-50">
                        <a href="#" class="block px-4 py-3 text-[12px] text-slate-700 hover:bg-slate-50 transition-all">Profil Saya</a>
                        <a href="#" class="block px-4 py-3 text-[12px] text-slate-700 hover:bg-slate-50 transition-all">Ubah Password</a>
                        <div class="border-t border-slate-100"></div>
                        <button type="button"
                                @click="showLogout = true"
                                class="w-full text-left px-4 py-3 text-[12px] text-red-500 hover:bg-red-50 transition-all">
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <section class="flex-1 px-6 py-6 overflow-y-auto overflow-x-hidden bg-[#F2F8FF]">
            @yield('content')
        </section>

    </main>

    {{-- ===================== POPUP KONFIRMASI LOGOUT ===================== --}}
    <form id="logout-form-panitia" method="POST" action="{{ route('panitia.logout') }}" style="display: none;">
        @csrf
    </form>

    <template x-teleport="body">
        <div x-show="showLogout" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center"
             style="background:rgba(0,0,0,0.35);">
            <div @click.outside="showLogout = false"
                 class="bg-white rounded-2xl p-8 w-full max-w-sm shadow-xl text-center" x-transition>
                <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <h3 class="text-[15px] font-bold mb-2">Apakah anda yakin ingin keluar?</h3>
                <p class="text-[12px] text-slate-400 mb-6">Anda akan keluar dari sistem panitia</p>
                <div class="flex gap-3 justify-center">
                    <button type="button"
                            @click="showLogout = false"
                            class="px-6 py-2 rounded-xl border text-[13px]"
                            style="background:#FBFAF7; border-color:#A5A5A5;">
                        Batal
                    </button>
                    <button type="button"
                            @click="document.getElementById('logout-form-panitia').submit();"
                            class="px-6 py-2 rounded-xl text-white text-[13px]"
                            style="background:#EF4444;">
                        Keluar
                    </button>
                </div>
            </div>
        </div>
    </template>

</div>

@stack('scripts')
</body>
</html>