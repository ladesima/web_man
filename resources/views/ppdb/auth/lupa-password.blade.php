<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - PPDB MAN Jeneponto</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <div class="absolute inset-0 z-0">
        <img src="{{ asset('ppdb/Loginbg.svg') }}" alt="bg" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-white/10 backdrop-blur-[2px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-4xl bg-white rounded-3xl shadow-2xl flex px-6 pt-6 pb-8 gap-6 pr-4">

        {{-- Form Section --}}
        <div class="flex-1 flex flex-col items-center justify-center px-8 py-8">
            <img src="{{ asset('ppdb/man.svg') }}" alt="MAN Jeneponto" class="w-12 h-12 object-contain mb-3">
            <h1 class="text-xl font-bold text-[#2B2A28]">Lupa Kata Sandi</h1>
            <p class="text-xs text-slate-400 mt-2 mb-8 text-center leading-5">
                Silahkan masukkan email yang anda gunakan untuk login,<br>
                dan Kami akan mengirimkan kode pemulihan<br>
                untuk melanjutkan proses reset Kata Sandi
            </p>

            <form method="POST" action="{{ route('ppdb.lupa-password.post') }}" class="w-full space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-medium text-[#2B2A28] mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full px-3 py-2.5 rounded bg-[#EEF2F7] border-0
                                  focus:outline-none focus:ring-2 focus:ring-[#27C2DE] focus:bg-white
                                  text-sm transition-all">
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full py-2.5 rounded font-semibold text-white text-sm transition-all shadow-md"
                        style="background-color: #91E9F9;"
                        onmouseover="this.style.backgroundColor='#27C2DE'"
                        onmouseout="this.style.backgroundColor='#91E9F9'">
                    Kirim
                </button>

                <p class="text-center text-xs text-slate-400">
                    Ingat kata sandi?
                    <a href="{{ route('ppdb.login') }}" class="text-[#27C2DE] font-semibold hover:text-[#00758A] transition-colors">
                        Masuk
                    </a>
                </p>
            </form>
        </div>

        {{-- Image Section --}}
        <div class="hidden md:flex w-[420px] flex-shrink-0 items-center justify-center p-4 pr-8">
            <img src="{{ asset('ppdb/siswalogin.svg') }}" alt="Siswa"
                 class="w-full h-auto object-contain rounded-2xl">
        </div>
    </div>

</body>
</html>