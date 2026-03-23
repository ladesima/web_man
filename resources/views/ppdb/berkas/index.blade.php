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

        <form action="{{ route('siswa.upload.berkas.post', $jalur) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                @php
                $berkas = [
                    ['name' => 'akta',      'label' => 'Akta Lahir'],
                    ['name' => 'kk',        'label' => 'Kartu Keluarga'],
                    ['name' => 'rapor',     'label' => 'Rapor'],
                    ['name' => 'bukti_pd',  'label' => 'Bukti Verifikasi & Validasi PD'],
                    ['name' => 'sertifikat','label' => 'Sertifikat Prestasi'],
                    ['name' => 'skl',       'label' => 'SK Aktif Sekolah / SKL'],
                ];
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
                <button type="submit" id="btn-submit"
                        class="px-12 py-2.5 rounded-full text-white font-semibold text-sm bg-[#0088A0] hover:bg-[#006980] transition opacity-50 cursor-not-allowed"
                        disabled>
                    Lanjutkan
                </button>
            </div>

        </form>
    </div>

</div>

<script>
    const inputs  = document.querySelectorAll('input.file-input');
    const button  = document.getElementById('btn-submit');
    // sertifikat tidak wajib
    const required = ['akta', 'kk', 'rapor', 'bukti_pd', 'skl'];

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
                // sembunyikan state idle, tampilkan state done
                document.getElementById(`idle-${name}`).classList.add('hidden');
                const done = document.getElementById(`done-${name}`);
                done.classList.remove('hidden');
                done.classList.add('flex');

                // tampilkan nama file
                document.getElementById(`name-${name}`).textContent = fileName;

                // ubah border box jadi solid biru
                const box = document.getElementById(`box-${name}`);
                box.style.border = '2px solid #0088A0';
                box.style.background = '#E8FAFB';
            }

            checkFiles();
        });
    });

    checkFiles();
</script>

@endsection