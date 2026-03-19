<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - PPDB</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="bg-white shadow-xl rounded-2xl p-8 max-w-md w-full text-center">

        <h1 class="text-xl font-bold text-gray-800 mb-2">
            📧 Verifikasi Email
        </h1>

        <p class="text-gray-600 text-sm mb-6">
            Kami telah mengirim link verifikasi ke email kamu.  
            Silakan cek inbox atau folder spam.
        </p>

        {{-- Success resend --}}
        @if (session('message'))
            <div class="mb-4 text-green-600 text-sm">
                {{ session('message') }}
            </div>
        @endif

        {{-- Tombol resend --}}
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg text-sm font-semibold transition">
                Kirim Ulang Email
            </button>
        </form>

        {{-- Divider --}}
        <div class="my-4 text-gray-400 text-xs">atau</div>

        {{-- Login --}}
        <a href="{{ route('ppdb.login') }}"
           class="block text-sm text-blue-500 hover:underline">
            Kembali ke Login
        </a>

    </div>

</body>
</html>