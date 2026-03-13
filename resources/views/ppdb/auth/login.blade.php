<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PPDB MAN Jeneponto</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <div class="absolute inset-0 z-0">
        <img src="{{ asset('ppdb/Loginbg.svg') }}" alt="bg" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-white/10 backdrop-blur-[2px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-4xl bg-white rounded-3xl shadow-2xl flex px-6 pt-6 pb-8 gap-6 pr-4">

        <div class="flex-1 flex flex-col items-center justify-center px-8 py-4">
            <img src="{{ asset('ppdb/man.svg') }}" alt="MAN Jeneponto" class="w-12 h-12 object-contain mb-3">
            <h1 class="text-xl font-bold text-[#2B2A28]">Selamat Datang</h1>
            <p class="text-xs text-slate-400 mt-1 mb-6">Masuk untuk melanjutkan pendaftaran PPDB</p>

            <form method="POST" action="#" class="w-full max-w-xs space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-medium text-[#2B2A28] mb-1">NISN</label>
                    <input type="text" name="nisn"
                           class="w-full px-3 py-2.5 rounded bg-[#EEF2F7] border-0
                                  focus:outline-none focus:ring-2 focus:ring-[#27C2DE] focus:bg-white
                                  text-sm transition-all">
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#2B2A28] mb-1">Email</label>
                    <input type="email" name="email"
                           class="w-full px-3 py-2.5 rounded bg-[#EEF2F7] border-0
                                  focus:outline-none focus:ring-2 focus:ring-[#27C2DE] focus:bg-white
                                  text-sm transition-all">
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#2B2A28] mb-1">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="passwordInput"
                               class="w-full px-3 py-2.5 pr-10 rounded bg-[#EEF2F7] border-0
                                      focus:outline-none focus:ring-2 focus:ring-[#27C2DE] focus:bg-white
                                      text-sm transition-all">
                        <button type="button" onclick="togglePassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#27C2DE]">
                            <svg id="eyeIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="text-right mt-1">
                        <a href="{{ route('ppdb.lupa-password') }}" class="text-xs text-[#27C2DE] hover:text-[#00758A] transition-colors">
                            Lupa Kata sandi
                        </a>
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-2.5 rounded font-semibold text-white text-sm transition-all shadow-md"
                        style="background-color: #91E9F9;"
                        onmouseover="this.style.backgroundColor='#27C2DE'"
                        onmouseout="this.style.backgroundColor='#91E9F9'"
                        onmousedown="this.style.backgroundColor='#27C2DE'">
                    Masuk
                </button>

                <p class="text-center text-xs text-slate-400">
                    Belum punya akun?
                    <a href="{{ route('ppdb.daftar') }}" class="text-[#27C2DE] font-semibold hover:text-[#00758A] transition-colors">
                        Daftar Sekarang
                    </a>
                </p>
            </form>
        </div>

        <div class="hidden md:flex w-[420px] flex-shrink-0 items-center justify-center p-4 pr-8">
            <img src="{{ asset('ppdb/siswalogin.svg') }}" alt="Siswa Login"
                 class="w-full h-auto object-contain rounded-2xl">
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                `;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                `;
            }
        }
    </script>
</body>
</html>