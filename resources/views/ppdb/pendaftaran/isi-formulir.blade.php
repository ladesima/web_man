@extends('layouts.ppdb-siswa')

@section('title', 'Isi Formulir - PPDB MAN Jeneponto')

@section('content')

<div class="max-w-4xl mx-auto px-6 py-8">

    @include('ppdb.partials.stepper', ['currentStep' => 1])

    <div class="bg-white rounded-2xl px-8 py-8" style="box-shadow: 0px 4px 4px rgba(0,0,0,0.08);">

        <h2 class="text-lg font-bold mb-6 text-[#27C2DE]">Data Formulir</h2>

        {{-- FOTO PROFIL --}}
        <div class="flex justify-center mb-6">
            <div class="relative w-24 h-24">
                {{-- Foto preview --}}
                <img id="foto-preview"
                     src="{{ asset('ppdb/profil.png') }}"
                     alt="Foto Profil"
                     class="w-24 h-24 rounded-full object-cover border-4 border-white"
                     style="box-shadow: 0 2px 12px rgba(39,194,222,0.3);">

                {{-- Tombol kamera --}}
                <button type="button"
                        onclick="document.getElementById('foto-input').click()"
                        class="absolute bottom-0 right-0 w-7 h-7 rounded-full flex items-center justify-center border-2 border-white hover:opacity-90 transition"
                        style="background:#27C2DE;">
                    <img src="{{ asset('ppdb/kamera.png') }}" alt="Upload" class="w-4 h-4 object-contain brightness-0 invert">
                </button>

                {{-- Input file (hidden) --}}
                <input type="file" id="foto-input" name="foto" accept="image/*"
                       class="hidden"
                       onchange="previewFoto(this)">
            </div>
        </div>

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

        <form id="form-pendaftaran" action="{{ route('siswa.pendaftaran.post', $jalur) }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Input foto disembunyikan dalam form --}}
            <input type="file" id="foto-input-form" name="foto" accept="image/*" class="hidden">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">

                <div class="space-y-5">

                    {{-- Nama (AUTO) --}}
                    <div>
                        <label class="block text-sm mb-1.5">Nama Lengkap</label>
                        <input type="text"
                               value="{{ auth('ppdb')->user()->nama }}"
                               readonly
                               class="w-full px-4 py-2.5 rounded text-sm bg-gray-200 cursor-not-allowed">
                    </div>

                    {{-- TTL --}}
                    <div>
                        <label class="block text-sm mb-1.5">Tempat, Tanggal Lahir</label>
                        <input type="text" name="ttl" value="{{ old('ttl') }}"
                               class="w-full px-4 py-2.5 rounded-[10px] text-sm outline-none bg-[#F5F7FF] border border-[#DFEAF2]
                               @error('ttl') border border-red-500 @enderror">
                        @error('ttl')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NISN (AUTO) --}}
                    <div>
                        <label class="block text-sm mb-1.5">NISN</label>
                        <input type="text"
                               value="{{ auth('ppdb')->user()->nisn }}"
                               readonly
                               class="w-full px-4 py-2.5 rounded text-sm bg-gray-200 cursor-not-allowed">
                    </div>

                    {{-- Asal Sekolah --}}
                    <div>
                        <label class="block text-sm mb-1.5">Asal Sekolah</label>
                        <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah') }}"
                               class="w-full px-4 py-2.5 rounded-[10px] text-sm outline-none bg-[#F5F7FF] border border-[#DFEAF2]
                               @error('asal_sekolah') border border-red-500 @enderror">
                        @error('asal_sekolah')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label class="block text-sm mb-1.5">Alamat</label>
                        <input type="text" name="alamat" value="{{ old('alamat') }}"
                               class="w-full px-4 py-2.5 rounded-[10px] text-sm outline-none bg-[#F5F7FF] border border-[#DFEAF2]
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
                               class="w-full px-4 py-2.5 rounded-[10px] text-sm outline-none bg-[#F5F7FF] border border-[#DFEAF2]
                               @error('nama_ortu') border border-red-500 @enderror">
                        @error('nama_ortu')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pekerjaan --}}
                    <div>
                        <label class="block text-sm mb-1.5">Pekerjaan Orang Tua</label>
                        <input type="text" name="pekerjaan_ortu" value="{{ old('pekerjaan_ortu') }}"
                               class="w-full px-4 py-2.5 rounded-[10px] text-sm outline-none bg-[#F5F7FF] border border-[#DFEAF2]
                               @error('pekerjaan_ortu') border border-red-500 @enderror">
                        @error('pekerjaan_ortu')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Penghasilan --}}
                    <div>
                        <label class="block text-sm mb-1.5">Penghasilan Orang Tua</label>
                        <input type="text" name="penghasilan_ortu" value="{{ old('penghasilan_ortu') }}"
                               class="w-full px-4 py-2.5 rounded-[10px] text-sm outline-none bg-[#F5F7FF] border border-[#DFEAF2]
                               @error('penghasilan_ortu') border border-red-500 @enderror">
                        @error('penghasilan_ortu')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat Ortu --}}
                    <div>
                        <label class="block text-sm mb-1.5">Alamat Orang Tua</label>
                        <input type="text" name="alamat_ortu" value="{{ old('alamat_ortu') }}"
                               class="w-full px-4 py-2.5 rounded-[10px] text-sm outline-none bg-[#F5F7FF] border border-[#DFEAF2]
                               @error('alamat_ortu') border border-red-500 @enderror">
                        @error('alamat_ortu')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jumlah Saudara --}}
                    <div>
                        <label class="block text-sm mb-1.5">Jumlah Bersaudara</label>
                        <input type="number" name="jumlah_saudara" value="{{ old('jumlah_saudara') }}"
                               class="w-full px-4 py-2.5 rounded-[10px] text-sm outline-none bg-[#F5F7FF] border border-[#DFEAF2]
                               @error('jumlah_saudara') border border-red-500 @enderror">
                        @error('jumlah_saudara')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

            </div>

            <div class="flex justify-center mt-10">
                {{-- Tombol Lanjutkan — membuka modal, BUKAN langsung submit --}}
                <button type="button"
                        onclick="bukaModa()"
                        class="px-12 py-2.5 rounded-full text-white font-semibold text-sm bg-[#27C2DE] hover:bg-[#00B1D1] transition">
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
        <div class="absolute bottom-0 left-0 right-0 pb-6 px-8 text-center" style="top:60%;">
            <h3 class="text-[13px] font-bold text-[#2B2A28] mb-1">
                Yakin ingin melanjutkan Proses?
            </h3>
            <p class="text-[11px] text-slate-400 mb-3 leading-relaxed">
                Semua data yang anda masukkan tidak dapat diubah,<br>
                pastikan semua datanya benar.
            </p>
            <div class="flex gap-4 justify-center">
                <button type="button"
                        onclick="tutupModal()"
                        class="px-5 py-1.5 rounded-xl border border-slate-300 text-[12px] font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                    Batal
                </button>
                <button type="button"
                        onclick="document.getElementById('form-pendaftaran').submit()"
                        class="px-5 py-1.5 rounded-xl text-white text-[12px] font-semibold transition-all"
                        style="background:#27C2DE;">
                    Lanjutkan
                </button>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
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
    function previewFoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('foto-preview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);

            // Transfer file ke input dalam form
            const formInput = document.getElementById('foto-input-form');
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(input.files[0]);
            formInput.files = dataTransfer.files;
        }
    }


</script>
@endpush

@endsection