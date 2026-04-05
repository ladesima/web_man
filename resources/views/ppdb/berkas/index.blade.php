@extends('layouts.ppdb-siswa')

@section('title', 'Upload Berkas - PPDB MAN Jeneponto')

@section('content')

<div class="px-6 py-6">

    {{-- STEPPER --}}
    @include('ppdb.partials.stepper', ['currentStep' => 2])

    <div class="bg-white rounded-2xl px-10 py-8"
         style="box-shadow: 0px 4px 4px rgba(0,0,0,0.08); min-height: 400px;">

        <h2 class="text-lg font-bold mb-6 text-[#0088A0]">Upload Berkas</h2>

        {{-- ALERT GLOBAL --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-100 border border-red-300 text-red-700 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <script>alert("Berkas belum lengkap atau tidak sesuai!");</script>
        @endif

        <form id="form-berkas" action="{{ route('siswa.upload.berkas.post', $jalur) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                @php
$berkas = [
    ['name' => 'akta',      'label' => 'Akta Lahir'],
    ['name' => 'kk',        'label' => 'Kartu Keluarga'],
    ['name' => 'rapor',     'label' => 'Rapor'],
    ['name' => 'bukti_pd',  'label' => 'Bukti Verifikasi & Validasi PD'],
];

// 🔥 LOGIKA JALUR
if ($jalur === 'prestasi') {
    $berkas[] = ['name' => 'sertifikat','label' => 'Sertifikat Prestasi'];
}

if ($jalur === 'afirmasi') {
    $berkas[] = ['name' => 'kip','label' => 'Scan KIP'];
}

$berkas[] = ['name' => 'skl', 'label' => 'SK Aktif Sekolah / SKL'];
@endphp

                @foreach($berkas as $b)
                <div>
                    <label class="block text-sm font-medium mb-2">{{ $b['label'] }}</label>

                    <label id="box-{{ $b['name'] }}"
                           class="upload-box flex flex-col items-center justify-center w-full cursor-pointer rounded-xl transition-all
                                  @error($b['name']) border-2 border-red-500 @enderror"
                           style="border: 2px dashed rgba(0,136,160,0.5); height: 130px; background: #F0FBFD;">

                        {{-- State: belum upload --}}
                        <div id="idle-{{ $b['name'] }}" class="flex flex-col items-center">
                            <img src="{{ asset('ppdb/upload.png') }}" class="w-8 h-8 mb-1">
                            <span class="text-xs font-semibold text-[#0088A0]">Upload</span>
                            <span class="text-[10px] text-slate-400">File JPG atau PDF Max 5Mb</span>
                        </div>

                        {{-- State: sudah upload (hidden by default) --}}
                        <div id="done-{{ $b['name'] }}" class="hidden flex flex-col items-center">
                            <img src="{{ asset('ppdb/ceklisberkas.png') }}" class="w-8 h-8 mb-1"
                                 style="mix-blend-mode: multiply;">
                            <span id="name-{{ $b['name'] }}" class="text-xs font-semibold text-[#0088A0] text-center px-2 truncate max-w-[160px]"></span>
                            <span class="text-[10px]" style="color:#1654AA;">Unggah file lain</span>
                        </div>

                        <input type="file" name="{{ $b['name'] }}" class="hidden file-input"
                               data-name="{{ $b['name'] }}"
                               accept=".pdf,.jpg,.jpeg,.png">
                    </label>

                    @error($b['name'])
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @endforeach

            </div>

            <div class="flex justify-center mt-10">
                {{-- Tombol membuka modal, bukan langsung submit --}}
                <button type="button" id="btn-submit"
                        onclick="bukaModa()"
                        class="px-12 py-2.5 rounded-full text-white font-semibold text-sm bg-[#0088A0] hover:bg-[#006980] transition opacity-50 cursor-not-allowed"
                        disabled>
                    Lanjutkan
                </button>
            </div>

        </form>
    </div>

</div>

{{-- ============================================================ --}}
{{-- MODAL KONFIRMASI                                            --}}
{{-- ============================================================ --}}
<div id="modal-konfirmasi"
     class="hidden fixed inset-0 z-50 flex items-center justify-center"
     style="background:rgba(0,0,0,0.35);">

    <div id="modal-box" class="relative w-full max-w-sm">

        {{-- Gambar sebagai background popup --}}
        <img src="{{ asset('ppdb/lanjutproses.png') }}" alt="" class="w-full">

        {{-- Konten overlay di atas gambar --}}
        <div class="absolute bottom-0 left-0 right-0 pb-5 px-8 text-center" style="top:60%;">
            <h3 class="text-[13px] font-bold text-[#2B2A28] mb-1">
                Yakin ingin melanjutkan Proses?
            </h3>
            <p class="text-[11px] text-slate-400 mb-3 leading-relaxed">
                Semua data yang anda masukkan tidak dapat diubah,<br>
                pastikan semua datanya benar.
            </p>
            <div class="flex gap-3 justify-center">
                <button type="button"
                        onclick="tutupModal()"
                        class="px-5 py-1.5 rounded-xl border border-slate-300 text-[12px] font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                    Batal
                </button>
                <button type="button"
                        onclick="document.getElementById('form-berkas').submit()"
                        class="px-5 py-1.5 rounded-xl text-white text-[12px] font-semibold transition-all"
                        style="background:#0088A0;">
                    Lanjutkan
                </button>
            </div>
        </div>

    </div>
</div>

<script>
    const inputs  = document.querySelectorAll('input.file-input');
    const button  = document.getElementById('btn-submit');
    // sertifikat tidak wajib

const jalur = "{{ $jalur }}";

let required = ['akta', 'kk', 'rapor', 'bukti_pd', 'skl'];

if (jalur === 'prestasi') {
    required.push('sertifikat');
}

if (jalur === 'afirmasi') {
    required.push('kip');
}


    function checkFiles() {
        const allFilled = required.every(name => {
            const inp = document.querySelector(`input[name="${name}"]`);
            return inp && inp.files.length > 0;
        });

        button.disabled = !allFilled;
        button.classList.toggle('opacity-50', !allFilled);
        button.classList.toggle('cursor-not-allowed', !allFilled);
    }

    inputs.forEach(input => {
        input.addEventListener('change', function () {
            const name     = this.dataset.name;
            const fileName = this.files[0]?.name;

            if (fileName) {
                document.getElementById(`idle-${name}`).classList.add('hidden');
                const done = document.getElementById(`done-${name}`);
                done.classList.remove('hidden');
                done.classList.add('flex');
                document.getElementById(`name-${name}`).textContent = fileName;

                const box = document.getElementById(`box-${name}`);
                box.style.border = '2px solid #0088A0';
                box.style.background = '#E8FAFB';
            }

            checkFiles();
        });
    });

    checkFiles();

    /* ── Modal ── */
    function bukaModa() {
        document.getElementById('modal-konfirmasi').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function tutupModal() {
        document.getElementById('modal-konfirmasi').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Tutup modal jika klik di luar kotak
    document.getElementById('modal-konfirmasi').addEventListener('click', function(e) {
        if (e.target === this) tutupModal();
    });
</script>

@endsection