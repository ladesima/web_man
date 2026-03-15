@extends('layouts.ppdb-siswa')

@section('title', 'Upload Berkas - PPDB MAN Jeneponto')

@section('content')

<div class="px-6 py-6">

    {{-- STEPPER --}}
    @include('ppdb.partials.stepper', ['currentStep' => 2])

    {{-- CARD PUTIH LEBAR --}}
    <div class="bg-white rounded-2xl px-10 py-8"
         style="box-shadow: 0px 4px 4px rgba(0,0,0,0.08); min-height: 400px;">

        <h2 class="text-lg font-bold mb-6" style="color: rgba(0, 136, 160, 1);">Upload Berkas</h2>

        <form action="{{ route('siswa.berkas.post', $jalur) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                {{-- 1. Akta Lahir --}}
                <div>
                    <label class="block text-sm font-medium text-[#2B2A28] mb-2">Akta Lahir</label>
                    <label class="flex flex-col items-center justify-center w-full cursor-pointer rounded-xl"
                           style="border: 2px dashed rgba(0, 136, 160, 0.5); height: 130px; background: #F0FBFD;">
                        <img src="{{ asset('ppdb/upload.png') }}" alt="upload" class="w-8 h-8 mb-1 object-contain">
                        <span class="text-xs font-semibold" style="color: rgba(0, 136, 160, 1);">Upload</span>
                        <span class="text-[10px] mt-1" style="color:#94a3b8;">File JPG atau PDF Max 5Mb</span>
                        <input type="file" name="akta" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                    </label>
                </div>

                {{-- 2. Kartu Keluarga --}}
                <div>
                    <label class="block text-sm font-medium text-[#2B2A28] mb-2">Kartu Keluarga</label>
                    <label class="flex flex-col items-center justify-center w-full cursor-pointer rounded-xl"
                           style="border: 2px dashed rgba(0, 136, 160, 0.5); height: 130px; background: #F0FBFD;">
                        <img src="{{ asset('ppdb/upload.png') }}" alt="upload" class="w-8 h-8 mb-1 object-contain">
                        <span class="text-xs font-semibold" style="color: rgba(0, 136, 160, 1);">Upload</span>
                        <span class="text-[10px] mt-1" style="color:#94a3b8;">File JPG atau PDF Max 5Mb</span>
                        <input type="file" name="kk" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                    </label>
                </div>

                {{-- 3. Rapor --}}
                <div>
                    <label class="block text-sm font-medium text-[#2B2A28] mb-2">Rapor</label>
                    <label class="flex flex-col items-center justify-center w-full cursor-pointer rounded-xl"
                           style="border: 2px dashed rgba(0, 136, 160, 0.5); height: 130px; background: #F0FBFD;">
                        <img src="{{ asset('ppdb/upload.png') }}" alt="upload" class="w-8 h-8 mb-1 object-contain">
                        <span class="text-xs font-semibold" style="color: rgba(0, 136, 160, 1);">Upload</span>
                        <span class="text-[10px] mt-1" style="color:#94a3b8;">File JPG atau PDF Max 5Mb</span>
                        <input type="file" name="rapor" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                    </label>
                </div>

                {{-- 4. Bukti Verifikasi & Validasi PD --}}
                <div>
                    <label class="block text-sm font-medium text-[#2B2A28] mb-2">Bukti Verifikasi & Validasi PD</label>
                    <label class="flex flex-col items-center justify-center w-full cursor-pointer rounded-xl"
                           style="border: 2px dashed rgba(0, 136, 160, 0.5); height: 130px; background: #F0FBFD;">
                        <img src="{{ asset('ppdb/upload.png') }}" alt="upload" class="w-8 h-8 mb-1 object-contain">
                        <span class="text-xs font-semibold" style="color: rgba(0, 136, 160, 1);">Upload</span>
                        <span class="text-[10px] mt-1" style="color:#94a3b8;">File JPG atau PDF Max 5Mb</span>
                        <input type="file" name="bukti_pd" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                    </label>
                </div>

                {{-- 5. SK Berprestasi dan Sertifikat Juara --}}
                <div>
                    <label class="block text-sm font-medium text-[#2B2A28] mb-2">SK Berprestasi dan Sertifikat Juara (kab/Prov/Nasio)</label>
                    <label class="flex flex-col items-center justify-center w-full cursor-pointer rounded-xl"
                           style="border: 2px dashed rgba(0, 136, 160, 0.5); height: 130px; background: #F0FBFD;">
                        <img src="{{ asset('ppdb/upload.png') }}" alt="upload" class="w-8 h-8 mb-1 object-contain">
                        <span class="text-xs font-semibold" style="color: rgba(0, 136, 160, 1);">Upload</span>
                        <span class="text-[10px] mt-1" style="color:#94a3b8;">File JPG atau PDF Max 5Mb</span>
                        <input type="file" name="sertifikat" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                    </label>
                </div>

                {{-- 6. SK Aktif Sekolah / SKL --}}
                <div>
                    <label class="block text-sm font-medium text-[#2B2A28] mb-2">SK Aktif Sekolah / SKL</label>
                    <label class="flex flex-col items-center justify-center w-full cursor-pointer rounded-xl"
                           style="border: 2px dashed rgba(0, 136, 160, 0.5); height: 130px; background: #F0FBFD;">
                        <img src="{{ asset('ppdb/upload.png') }}" alt="upload" class="w-8 h-8 mb-1 object-contain">
                        <span class="text-xs font-semibold" style="color: rgba(0, 136, 160, 1);">Upload</span>
                        <span class="text-[10px] mt-1" style="color:#94a3b8;">File JPG atau PDF Max 5Mb</span>
                        <input type="file" name="skl" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                    </label>
                </div>

            </div>

            <div class="flex justify-center mt-10">
                <button type="submit"
                        class="px-12 py-2.5 rounded-full text-white font-semibold text-sm"
                        style="background-color: rgba(0, 136, 160, 1);"
                        onmouseover="this.style.backgroundColor='#006980'"
                        onmouseout="this.style.backgroundColor='rgba(0, 136, 160, 1)'">
                    Lanjutkan
                </button>
            </div>

        </form>
    </div>

</div>

@endsection