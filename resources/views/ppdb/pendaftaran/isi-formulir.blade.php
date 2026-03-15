@extends('layouts.ppdb-siswa')

@section('title', 'Isi Formulir - PPDB MAN Jeneponto')

@section('content')

<div class="max-w-4xl mx-auto px-6 py-8">

    @include('ppdb.partials.stepper', ['currentStep' => 1])

    <div class="bg-white rounded-2xl px-8 py-8" style="box-shadow: 0px 4px 4px rgba(0,0,0,0.08);">

        <h2 class="text-lg font-bold mb-6" style="color: #27C2DE;">Data Formulir</h2>

        <form action="{{ route('siswa.pendaftaran.post', $jalur) }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm text-[#2B2A28] mb-1.5">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap"
                               class="w-full px-4 py-2.5 rounded text-sm outline-none"
                               style="background: #EEF2F7; border: none;">
                    </div>
                    <div>
                        <label class="block text-sm text-[#2B2A28] mb-1.5">Tempat, Tanggal Lahir</label>
                        <input type="text" name="ttl"
                               class="w-full px-4 py-2.5 rounded text-sm outline-none"
                               style="background: #EEF2F7; border: none;">
                    </div>
                    <div>
                        <label class="block text-sm text-[#2B2A28] mb-1.5">NISN</label>
                        <input type="text" name="nisn"
                               class="w-full px-4 py-2.5 rounded text-sm outline-none"
                               style="background: #EEF2F7; border: none;">
                    </div>
                    <div>
                        <label class="block text-sm text-[#2B2A28] mb-1.5">Asal Sekolah</label>
                        <input type="text" name="asal_sekolah"
                               class="w-full px-4 py-2.5 rounded text-sm outline-none"
                               style="background: #EEF2F7; border: none;">
                    </div>
                    <div>
                        <label class="block text-sm text-[#2B2A28] mb-1.5">Alamat</label>
                        <input type="text" name="alamat"
                               class="w-full px-4 py-2.5 rounded text-sm outline-none"
                               style="background: #EEF2F7; border: none;">
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm text-[#2B2A28] mb-1.5">Nama Orang Tua/Wali</label>
                        <input type="text" name="nama_ortu"
                               class="w-full px-4 py-2.5 rounded text-sm outline-none"
                               style="background: #EEF2F7; border: none;">
                    </div>
                    <div>
                        <label class="block text-sm text-[#2B2A28] mb-1.5">Pekerjaan Orang Tua</label>
                        <input type="text" name="pekerjaan_ortu"
                               class="w-full px-4 py-2.5 rounded text-sm outline-none"
                               style="background: #EEF2F7; border: none;">
                    </div>
                    <div>
                        <label class="block text-sm text-[#2B2A28] mb-1.5">Penghasilan Orang Tua</label>
                        <input type="text" name="penghasilan_ortu"
                               class="w-full px-4 py-2.5 rounded text-sm outline-none"
                               style="background: #EEF2F7; border: none;">
                    </div>
                    <div>
                        <label class="block text-sm text-[#2B2A28] mb-1.5">Alamat Orang Tua</label>
                        <input type="text" name="alamat_ortu"
                               class="w-full px-4 py-2.5 rounded text-sm outline-none"
                               style="background: #EEF2F7; border: none;">
                    </div>
                    <div>
                        <label class="block text-sm text-[#2B2A28] mb-1.5">Jumlah Bersaudara</label>
                        <input type="number" name="jumlah_saudara"
                               class="w-full px-4 py-2.5 rounded text-sm outline-none"
                               style="background: #EEF2F7; border: none;">
                    </div>
                </div>

            </div>

            <div class="flex justify-center mt-10">
                <button type="submit"
                        class="px-12 py-2.5 rounded-full text-white font-semibold text-sm"
                        style="background-color: #27C2DE;"
                        onmouseover="this.style.backgroundColor='#00B1D1'"
                        onmouseout="this.style.backgroundColor='#27C2DE'">
                    Lanjutkan
                </button>
            </div>

        </form>
    </div>

</div>

@endsection