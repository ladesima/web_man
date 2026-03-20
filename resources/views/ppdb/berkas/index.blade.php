@extends('layouts.ppdb-siswa')

@section('title', 'Upload Berkas - PPDB MAN Jeneponto')

@section('content')

<div class="px-6 py-6">

    {{-- STEPPER --}}
    @include('ppdb.partials.stepper', ['currentStep' => 2])

    <div class="bg-white rounded-2xl px-10 py-8"
         style="box-shadow: 0px 4px 4px rgba(0,0,0,0.08); min-height: 400px;">

        <h2 class="text-lg font-bold mb-6 text-[#0088A0]">Upload Berkas</h2>

        {{-- 🔴 ALERT GLOBAL --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-100 border border-red-300 text-red-700 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

            <script>
                alert("Berkas belum lengkap atau tidak sesuai!");
            </script>
        @endif

        <form action="{{ route('siswa.upload.berkas.post', $jalur) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                {{-- 1. AKTA --}}
                <div>
                    <label class="block text-sm font-medium mb-2">Akta Lahir</label>

                    <label class="upload-box flex flex-col items-center justify-center w-full cursor-pointer rounded-xl
    @error('akta') border-2 border-red-500 @enderror"
                        style="border: 2px dashed rgba(0, 136, 160, 0.5); height: 130px; background: #F0FBFD;">

                        <img src="{{ asset('ppdb/upload.png') }}" class="w-8 h-8 mb-1">
                        <span class="text-xs font-semibold text-[#0088A0]">Upload</span>
                        <span class="text-[10px] text-slate-400">File JPG atau PDF Max 5Mb</span>

                        <input type="file" name="akta" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                    </label>

                    @error('akta')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 2. KK --}}
                <div>
                    <label class="block text-sm font-medium mb-2">Kartu Keluarga</label>

                    <label class="flex flex-col items-center justify-center w-full cursor-pointer rounded-xl
                        @error('kk') border-2 border-red-500 @enderror"
                        style="border: 2px dashed rgba(0, 136, 160, 0.5); height: 130px; background: #F0FBFD;">

                        <img src="{{ asset('ppdb/upload.png') }}" class="w-8 h-8 mb-1">
                        <span class="text-xs font-semibold text-[#0088A0]">Upload</span>
                        <span class="text-[10px] text-slate-400">File JPG atau PDF Max 5Mb</span>

                        <input type="file" name="kk" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                    </label>

                    @error('kk')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 3. RAPOR --}}
                <div>
                    <label class="block text-sm font-medium mb-2">Rapor</label>

                    <label class="flex flex-col items-center justify-center w-full cursor-pointer rounded-xl
                        @error('rapor') border-2 border-red-500 @enderror"
                        style="border: 2px dashed rgba(0, 136, 160, 0.5); height: 130px; background: #F0FBFD;">

                        <img src="{{ asset('ppdb/upload.png') }}" class="w-8 h-8 mb-1">
                        <span class="text-xs font-semibold text-[#0088A0]">Upload</span>
                        <span class="text-[10px] text-slate-400">File JPG atau PDF Max 5Mb</span>

                        <input type="file" name="rapor" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                    </label>

                    @error('rapor')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 4. BUKTI PD --}}
                <div>
                    <label class="block text-sm font-medium mb-2">Bukti Verifikasi & Validasi PD</label>

                    <label class="flex flex-col items-center justify-center w-full cursor-pointer rounded-xl
                        @error('bukti_pd') border-2 border-red-500 @enderror"
                        style="border: 2px dashed rgba(0, 136, 160, 0.5); height: 130px; background: #F0FBFD;">

                        <img src="{{ asset('ppdb/upload.png') }}" class="w-8 h-8 mb-1">
                        <span class="text-xs font-semibold text-[#0088A0]">Upload</span>
                        <span class="text-[10px] text-slate-400">File JPG atau PDF Max 5Mb</span>

                        <input type="file" name="bukti_pd" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                    </label>

                    @error('bukti_pd')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 5. SERTIFIKAT --}}
                <div>
                    <label class="block text-sm font-medium mb-2">Sertifikat Prestasi</label>

                    <label class="flex flex-col items-center justify-center w-full cursor-pointer rounded-xl
                        @error('sertifikat') border-2 border-red-500 @enderror"
                        style="border: 2px dashed rgba(0, 136, 160, 0.5); height: 130px; background: #F0FBFD;">

                        <img src="{{ asset('ppdb/upload.png') }}" class="w-8 h-8 mb-1">
                        <span class="text-xs font-semibold text-[#0088A0]">Upload</span>
                        <span class="text-[10px] text-slate-400">File JPG atau PDF Max 5Mb</span>

                        <input type="file" name="sertifikat" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                    </label>

                    @error('sertifikat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 6. SKL --}}
                <div>
                    <label class="block text-sm font-medium mb-2">SK Aktif Sekolah / SKL</label>

                    <label class="flex flex-col items-center justify-center w-full cursor-pointer rounded-xl
                        @error('skl') border-2 border-red-500 @enderror"
                        style="border: 2px dashed rgba(0, 136, 160, 0.5); height: 130px; background: #F0FBFD;">

                        <img src="{{ asset('ppdb/upload.png') }}" class="w-8 h-8 mb-1">
                        <span class="text-xs font-semibold text-[#0088A0]">Upload</span>
                        <span class="text-[10px] text-slate-400">File JPG atau PDF Max 5Mb</span>

                        <input type="file" name="skl" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                    </label>

                    @error('skl')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex justify-center mt-10">
               <button type="submit"
    class="px-12 py-2.5 rounded-full text-white font-semibold text-sm bg-[#0088A0] hover:bg-[#006980] transition">
    Lanjutkan
</button>
            </div>

        </form>
    </div>

</div>
<script>
    const inputs = document.querySelectorAll('input[type="file"]');
    const button = document.querySelector('button[type="submit"]');

    function checkFiles() {
        let allFilled = true;

        inputs.forEach(input => {
            if (!input.files.length && input.name !== 'sertifikat') {
                allFilled = false;
            }
        });

        button.disabled = !allFilled;

        if (allFilled) {
            button.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            button.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    inputs.forEach(input => {
        input.addEventListener('change', function () {

            const fileName = this.files[0]?.name;

            if (fileName) {
                let label = this.closest('label');
                let text = label.querySelector('.file-name');

                if (!text) {
                    text = document.createElement('span');
                    text.classList.add('file-name', 'text-[10px]', 'mt-1', 'text-green-600', 'font-semibold');
                    label.appendChild(text);
                }

                text.innerText = fileName;
            }

            checkFiles();
        });
    });

    // initial state
    checkFiles();
</script>
@endsection