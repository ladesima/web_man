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
                Masukkan NISN kamu untuk memulai<br>pendaftaran PPDB MAN Jeneponto
            </p>

            <form method="POST" action="#" class="w-full max-w-xs space-y-3">
                @csrf

                <div>
                    <label class="block text-xs font-medium text-[#2B2A28] mb-1">NISN</label>
                    <input type="text" name="nisn" id="nisnInput"
                           placeholder="Masukkan NISN kamu"
                           maxlength="10"
                           class="w-full px-3 py-2 rounded bg-[#EEF2F7] border-0
                                  focus:outline-none focus:ring-2 focus:ring-[#27C2DE] focus:bg-white
                                  text-sm transition-all">
                    <p id="nisnError" style="display:none; font-size:12px; color:red; margin-top:4px;"></p>
                </div>

                <button type="button" onclick="cekNisn()"
                        class="w-full py-2 rounded font-semibold text-white text-sm transition-all shadow-md"
                        style="background-color: #91E9F9;"
                        onmouseover="this.style.backgroundColor='#27C2DE'"
                        onmouseout="this.style.backgroundColor='#91E9F9'"
                        onmousedown="this.style.backgroundColor='#27C2DE'">
                    Masukkan NISN
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

    {{-- POPUP MODAL --}}
   <div id="popupNisn" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; z-index:9999; background-color:rgba(0,0,0,0.4); align-items:center; justify-content:center;">
    <div style="width:100%; max-width:420px; margin:0 16px; position:relative;">

        <img src="{{ asset('ppdb/popupnisn.svg') }}" alt="NISN" style="width:100%; display:block;">

       <div style="position:absolute; bottom:5%; left:0; right:0; padding:0 28px; text-align:center;">
    <h2 style="font-size:15px; font-weight:700; color:#27C2DE; margin:0;">Data Berhasil disimpan</h2>
    <p id="popupNisnText" style="font-size:13px; font-weight:600; color:#2B2A28; margin:4px 0 0 0;"></p>
    <p style="font-size:11px; color:#94a3b8; margin-top:6px; line-height:1.4;">
        Jika Data Sudah Benar Silahkan Melanjutkan Registrasi
    </p>
    <button onclick="lanjutRegistrasi()"
            style="margin-top:10px; width:70%; padding:9px; border-radius:999px; font-weight:600; color:white; font-size:13px; background-color:#27C2DE; border:none; cursor:pointer;"
            onmouseover="this.style.backgroundColor='#00758A'"
            onmouseout="this.style.backgroundColor='#27C2DE'">
        Lanjutkan Registrasi
    </button>
</div>
    </div>
</div>
   <script>
    let nisnValue = '';

    function cekNisn() {
        const nisn = document.getElementById('nisnInput').value.trim();
        const error = document.getElementById('nisnError');

        error.style.display = 'none';

        if (nisn === '') {
            error.textContent = 'NISN tidak boleh kosong.';
            error.style.display = 'block';
            return;
        }
        if (nisn.length < 10) {
            error.textContent = 'NISN harus 10 digit.';
            error.style.display = 'block';
            return;
        }

        nisnValue = nisn;
        document.getElementById('popupNisnText').textContent = nisn + ' - Nama Siswa';
        
        const popup = document.getElementById('popupNisn');
        popup.style.display = 'flex';
        popup.style.position = 'fixed';
        popup.style.top = '0';
        popup.style.left = '0';
        popup.style.width = '100%';
        popup.style.height = '100%';
        popup.style.zIndex = '9999';
        popup.style.backgroundColor = 'rgba(0,0,0,0.5)';
        popup.style.alignItems = 'center';
        popup.style.justifyContent = 'center';
    }

    function lanjutRegistrasi() {
        window.location.href = "{{ route('ppdb.daftar.step2') }}?nisn=" + nisnValue;
    }

    document.getElementById('popupNisn').addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });

    document.getElementById('nisnInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') cekNisn();
    });
</script>

</body>

</html>