<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP - PPDB MAN Jeneponto</title>
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
            <p class="text-xs text-slate-400 mt-2 mb-1 text-center leading-5">
                Kode sudah dikirimkan ke email
                <span class="font-bold text-[#2B2A28]">{{ session('ppdb_reset_email') ?? 'email@gmail.com' }}</span>
            </p>
            <p class="text-xs text-slate-400 mb-8 text-center">
                kode pemulihan akan dikirimkan kembali setelah 2 menit
            </p>

            {{-- Error --}}
            @if ($errors->any())
                <div class="w-full mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-xs text-red-600 text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('ppdb.verify-otp.post') }}" class="w-full space-y-6">
    @csrf

    <div class="flex justify-center gap-3">
        @for($i = 0; $i < 4; $i++)
        <input type="text" name="otp[]" maxlength="1"
               class="w-14 h-14 text-center text-xl font-bold rounded-xl bg-[#EEF2F7]
                      focus:ring-2 focus:ring-[#27C2DE] otp-input"
               inputmode="numeric" autocomplete="off">
        @endfor
    </div>

    <button type="submit"
            id="submitBtn"
            class="w-full py-2.5 rounded font-semibold text-white text-sm shadow-md"
            style="background-color: #91E9F9;">
        Kirim
    </button>
</form>
        </div>

        {{-- Image Section --}}
        <div class="hidden md:flex w-[420px] flex-shrink-0 items-center justify-center p-4 pr-8">
            <img src="{{ asset('ppdb/siswalogin.svg') }}" alt="Siswa"
                 class="w-full h-auto object-contain rounded-2xl">
        </div>
    </div>
<script>
    const otpInputs = document.querySelectorAll('.otp-input');
    const form = document.querySelector('form');

    // 🔥 auto focus pertama
    otpInputs[0].focus();

    otpInputs.forEach((input, index) => {

        input.addEventListener('input', () => {
            input.value = input.value.replace(/[^0-9]/g, '');

            if (input.value && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }

            // 🔥 auto submit kalau sudah 4 digit
            if (index === otpInputs.length - 1 && input.value) {
                setTimeout(() => form.submit(), 300);
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && index > 0) {
                otpInputs[index - 1].focus();
            }
        });
    });

    /*
    |--------------------------------------------------
    | COUNTDOWN RESEND
    |--------------------------------------------------
    */
    let seconds = 120;
    const countdownEl = document.getElementById('countdown');
    const resendBtn = document.getElementById('resendBtn');

    const timer = setInterval(() => {
        seconds--;
        countdownEl.textContent = seconds;

        if (seconds <= 0) {
            clearInterval(timer);
            resendBtn.disabled = false;
            resendBtn.innerText = 'Kirim ulang';
        }
    }, 1000);

    /*
    |--------------------------------------------------
    | RESEND OTP (FIXED)
    |--------------------------------------------------
    */
    function resendOtp() {
        window.location.href = "{{ route('ppdb.lupa-password') }}";
    }
</script>
</body>
</html>