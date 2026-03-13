<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - PPDB MAN Jeneponto</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center px-4 relative overflow-hidden">

    <div class="absolute inset-0 z-0">
        <img src="{{ asset('ppdb/Loginbg.svg') }}" alt="bg" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-white/10 backdrop-blur-[2px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-4xl bg-white rounded-3xl shadow-2xl flex px-6 py-5 gap-6">

        {{-- KIRI: Form --}}
        <div class="flex-1 flex flex-col items-center justify-center px-6">
            <img src="{{ asset('ppdb/man.svg') }}" alt="MAN Jeneponto" class="w-10 h-10 object-contain mb-2">
            <h1 class="text-lg font-bold text-[#2B2A28]">Selamat Datang</h1>
            <p class="text-xs text-slate-400 mt-0.5 mb-3 text-center leading-4">
                Lengkapi data akun kamu untuk menyelesaikan pendaftaran
            </p>

            {{-- Badge NISN --}}
            <div class="w-full max-w-xs mb-3 flex items-center gap-2 bg-green-50 border border-green-200 rounded px-3 py-1.5">
                <svg class="w-3.5 h-3.5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-xs text-green-700">NISN <strong>{{ request('nisn') }}</strong> ditemukan</span>
            </div>

            <form method="GET" action="#" class="w-full max-w-xs space-y-3">
                @csrf
                <input type="hidden" name="nisn" value="{{ request('nisn') }}">

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-medium text-[#2B2A28] mb-1">Email</label>
                    <input type="email" name="email"
                           placeholder="Masukkan email kamu"
                           class="w-full px-3 py-2 rounded bg-[#EEF2F7] border-0
                                  focus:outline-none focus:ring-2 focus:ring-[#27C2DE] focus:bg-white
                                  text-sm transition-all">
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-xs font-medium text-[#2B2A28] mb-1">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="passwordInput"
                               placeholder="Buat password"
                               class="w-full px-3 py-2 pr-10 rounded bg-[#EEF2F7] border-0
                                      focus:outline-none focus:ring-2 focus:ring-[#27C2DE] focus:bg-white
                                      text-sm transition-all">
                        <button type="button" onclick="togglePass('passwordInput')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#27C2DE]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-xs font-medium text-[#2B2A28] mb-1">Konfirmasi Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="passwordConfirm"
                               placeholder="Ulangi password"
                               class="w-full px-3 py-2 pr-10 rounded bg-[#EEF2F7] border-0
                                      focus:outline-none focus:ring-2 focus:ring-[#27C2DE] focus:bg-white
                                      text-sm transition-all">
                        <button type="button" onclick="togglePass('passwordConfirm')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#27C2DE]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Tombol --}}
                <button type="submit"
                        class="w-full py-2 rounded font-semibold text-white text-sm transition-all shadow-md"
                        style="background-color: #91E9F9;"
                        onmouseover="this.style.backgroundColor='#27C2DE'"
                        onmouseout="this.style.backgroundColor='#91E9F9'"
                        onmousedown="this.style.backgroundColor='#27C2DE'">
                    Daftar Sekarang
                </button>

                <p class="text-center text-xs text-slate-400">
                    Sudah punya akun?
                    <a href="{{ route('ppdb.login') }}" class="text-[#27C2DE] font-semibold hover:text-[#00758A] transition-colors">
                        Login sekarang
                    </a>
                </p>
            </form>
        </div>

        {{-- KANAN: Ilustrasi --}}
        <div class="hidden md:flex w-[400px] flex-shrink-0 items-center justify-center p-3 pr-6">
            <img src="{{ asset('ppdb/siswalogin.svg') }}" alt="Siswa Login"
                 class="w-full h-auto object-contain rounded-2xl">
        </div>

    </div>

    <script>
        function togglePass(inputId) {
            const input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>

</body>
</html>