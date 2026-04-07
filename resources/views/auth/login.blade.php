<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - MAN Jeneponto</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    {{-- Background --}}
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('ppdb/Loginbg.svg') }}" alt="bg" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-white/10 backdrop-blur-[2px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-4xl bg-white rounded-3xl shadow-2xl flex px-6 pt-6 pb-8 gap-6 pr-4">
        @php
use Illuminate\Support\Facades\Storage;
@endphp
        {{-- Form Section --}}
        <div class="flex-1 flex flex-col items-center justify-center px-8 py-4">
            <img src="{{ asset('ppdb/man.svg') }}" alt="MAN Jeneponto" class="w-12 h-12 object-contain mb-3">
            <h1 class="text-xl font-bold text-[#2B2A28]">Selamat Datang</h1>
            <p class="text-xs text-slate-400 mt-1 mb-6">Masuk untuk mengakses panel admin</p>

            {{-- Error Message --}}
            @if ($errors->any())
                <div class="w-full mb-4 p-3 rounded bg-red-50 border border-red-200 text-xs text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="w-full space-y-4">
                @csrf
                {{-- Role --}}
<div>
    <label class="block text-xs font-medium text-[#2B2A28] mb-1">Login Sebagai</label>
   <select name="role" id="roleSelect"
    class="w-full px-3 py-2.5 rounded bg-[#EEF2F7] border-0
           focus:outline-none focus:ring-2 focus:ring-[#27C2DE] focus:bg-white
           text-sm transition-all">
    <option value="admin">Admin</option>
    <option value="panitia">Panitia</option>
</select>
</div>
                {{-- Email --}}
                <div id="emailField">
    <label class="block text-xs font-medium text-[#2B2A28] mb-1">Email</label>
    <input type="email" name="email"
           placeholder="admin@example.com"
           class="w-full px-3 py-2.5 rounded bg-[#EEF2F7] border-0
                  focus:outline-none focus:ring-2 focus:ring-[#27C2DE] focus:bg-white
                  text-sm transition-all">
</div>
                {{-- Username (untuk panitia) --}}
<div id="usernameField">
    <label class="block text-xs font-medium text-[#2B2A28] mb-1">Username (Panitia)</label>
    <input type="text" name="username"
           class="w-full px-3 py-2.5 rounded bg-[#EEF2F7] border-0
                  focus:outline-none focus:ring-2 focus:ring-[#27C2DE] focus:bg-white
                  text-sm transition-all">
</div>
                {{-- Password --}}
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
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-2.5 rounded font-semibold text-white text-sm transition-all shadow-md"
                        style="background-color: #91E9F9;"
                        onmouseover="this.style.backgroundColor='#27C2DE'"
                        onmouseout="this.style.backgroundColor='#91E9F9'"
                        onmousedown="this.style.backgroundColor='#27C2DE'">
                    Masuk
                </button>
            </form>
        </div>

        {{-- Image Section --}}
        <div class="hidden md:flex w-[420px] flex-shrink-0 items-center justify-center p-4 pr-8">
            
        <img src="{{ isset($media['admin_login']) 
    ? Storage::url($media['admin_login']) 
    : asset('ppdb/manjepot.png') }}"
     alt="MAN Jeneponto"
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
    <script>
    const roleSelect = document.getElementById('roleSelect');
    const emailField = document.getElementById('emailField');
    const usernameField = document.getElementById('usernameField');

    function toggleFields() {
        if (roleSelect.value === 'admin') {
            emailField.style.display = 'block';
            usernameField.style.display = 'none';
        } else {
            emailField.style.display = 'none';
            usernameField.style.display = 'block';
        }
    }

    // run saat halaman load
    toggleFields();

    // run saat ganti role
    roleSelect.addEventListener('change', toggleFields);
</script>
</body>
</html>