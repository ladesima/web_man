@extends('layouts.ppdb-siswa')

@section('title', 'Isi Formulir - PPDB MAN Jeneponto')

@section('content')

<div class="max-w-4xl mx-auto px-6 py-8">

    @include('ppdb.partials.stepper', ['currentStep' => 1])

    <div class="bg-white rounded-2xl px-8 py-8" style="box-shadow: 0px 4px 4px rgba(0,0,0,0.08);">

        <h2 class="text-lg font-bold mb-6 text-[#27C2DE]">Data Formulir</h2>

        {{-- ALERT GLOBAL --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-100 border border-red-300 text-red-700 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('siswa.pendaftaran.post', $jalur) }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">

                <div class="space-y-5">

                    {{-- Nama (AUTO) --}}
                    <div>
                        <label class="block text-sm mb-1.5">Nama Lengkap</label>
                       <input type="text"
    value="{{ old('nama_lengkap', $user->nama ?? session('nama')) }}"
    readonly
                            class="w-full px-4 py-2.5 rounded text-sm bg-gray-200 cursor-not-allowed">
                    </div>

                    {{-- TTL --}}
                    <div>
                        <label class="block text-sm mb-1.5">Tempat, Tanggal Lahir</label>
                        <input type="text" name="ttl" value="{{ old('ttl') }}"
                            class="w-full px-4 py-2.5 rounded text-sm outline-none bg-[#EEF2F7]
                            @error('ttl') border border-red-500 @enderror">

                        @error('ttl')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NISN (AUTO) --}}
                    <div>
                        <label class="block text-sm mb-1.5">NISN</label>
                      <input type="text"
    value="{{ \App\Models\PpdbUser::find(session('ppdb_user_id'))->nisn }}"
    readonly
                            class="w-full px-4 py-2.5 rounded text-sm bg-gray-200 cursor-not-allowed">
                    </div>

                    {{-- Asal Sekolah --}}
                    <div>
                        <label class="block text-sm mb-1.5">Asal Sekolah</label>
                        <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah') }}"
                            class="w-full px-4 py-2.5 rounded text-sm outline-none bg-[#EEF2F7]
                            @error('asal_sekolah') border border-red-500 @enderror">

                        @error('asal_sekolah')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label class="block text-sm mb-1.5">Alamat</label>
                        <input type="text" name="alamat" value="{{ old('alamat') }}"
                            class="w-full px-4 py-2.5 rounded text-sm outline-none bg-[#EEF2F7]
                            @error('alamat') border border-red-500 @enderror">

                        @error('alamat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="space-y-5">

                    {{-- Nama Ortu --}}
                    <div>
                        <label class="block text-sm mb-1.5">Nama Orang Tua/Wali</label>
                        <input type="text" name="nama_ortu" value="{{ old('nama_ortu') }}"
                            class="w-full px-4 py-2.5 rounded text-sm outline-none bg-[#EEF2F7]
                            @error('nama_ortu') border border-red-500 @enderror">

                        @error('nama_ortu')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pekerjaan --}}
                    <div>
                        <label class="block text-sm mb-1.5">Pekerjaan Orang Tua</label>
                        <input type="text" name="pekerjaan_ortu" value="{{ old('pekerjaan_ortu') }}"
                            class="w-full px-4 py-2.5 rounded text-sm outline-none bg-[#EEF2F7]
                            @error('pekerjaan_ortu') border border-red-500 @enderror">

                        @error('pekerjaan_ortu')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Penghasilan --}}
                    <div>
                        <label class="block text-sm mb-1.5">Penghasilan Orang Tua</label>
                        <input type="text" name="penghasilan_ortu" value="{{ old('penghasilan_ortu') }}"
                            class="w-full px-4 py-2.5 rounded text-sm outline-none bg-[#EEF2F7]
                            @error('penghasilan_ortu') border border-red-500 @enderror">

                        @error('penghasilan_ortu')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat Ortu --}}
                    <div>
                        <label class="block text-sm mb-1.5">Alamat Orang Tua</label>
                        <input type="text" name="alamat_ortu" value="{{ old('alamat_ortu') }}"
                            class="w-full px-4 py-2.5 rounded text-sm outline-none bg-[#EEF2F7]
                            @error('alamat_ortu') border border-red-500 @enderror">

                        @error('alamat_ortu')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jumlah Saudara --}}
                    <div>
                        <label class="block text-sm mb-1.5">Jumlah Bersaudara</label>
                        <input type="number" name="jumlah_saudara" value="{{ old('jumlah_saudara') }}"
                            class="w-full px-4 py-2.5 rounded text-sm outline-none bg-[#EEF2F7]
                            @error('jumlah_saudara') border border-red-500 @enderror">

                        @error('jumlah_saudara')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

            </div>

            <div class="flex justify-center mt-10">
                <button type="submit"
                        class="px-12 py-2.5 rounded-full text-white font-semibold text-sm bg-[#27C2DE] hover:bg-[#00B1D1] transition">
                    Lanjutkan
                </button>
            </div>

        </form>
    </div>

</div>

@endsection