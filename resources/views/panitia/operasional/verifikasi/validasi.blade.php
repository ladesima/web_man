@extends('layouts.panitia')
@section('title', 'Validasi Berkas')

@section('content')

{{-- Breadcrumb --}}
<div class="relative mb-5" style="width: fit-content;">
    <img src="{{ asset('ppdb/admin/operasional/validasicrump.png') }}" style="height:40px; width:auto;" alt="">
    <div class="absolute inset-0 flex items-center" style="margin-top: -4px;">
        <a href="{{ route('panitia.operasional.verifikasi') }}"
           class="text-[12px] text-slate-500 hover:text-[#00758A] transition-colors"
           style="padding-left: 30px; padding-right: 20px;">
            Verifikasi Berkas
        </a>
        <a href="{{ route('panitia.operasional.verifikasi.detail', 1) }}"
           class="text-[12px] text-slate-500 hover:text-[#00758A] transition-colors"
           style="padding-left: 16px; padding-right: 20px;">
            Detail
        </a>
        <span class="text-[12px] text-white font-semibold"
              style="padding-left: 16px; padding-right: 20px;">
            Validasi
        </span>
    </div>
</div>

{{-- Profile Card --}}
<div class="bg-white px-6 py-5 mb-4"
     style="border-radius:12px;
            border: 1px solid #F3F3F3;
            box-shadow: 0px 4px 4px 0px rgba(161,209,251,0.25);">
    <div class="flex items-center">
        {{-- Kiri: Foto + Info --}}
        <div class="flex items-center gap-4 flex-1"
             style="border-right: 1px solid #E6E6E6; padding-right: 24px;">
            <img src="{{ asset('ppdb/admin/operasional/cadangan.png') }}" alt="foto"
                 class="w-16 h-16 object-cover border border-slate-200"
                 style="border-radius:12px;"
                 onerror="this.src='https://ui-avatars.com/api/?name=Muhammad+Naufal&background=27C2DE&color=fff&size=64'">
            <div>
                <h2 class="text-[16px] font-bold mb-0.5" style="color:#2B2A28;">Muhammad Naufal</h2>
                <p class="text-[12px] mb-2" style="color:#575551;">NISN : 1234567</p>
                <div class="flex gap-2">
                    {{-- Prestasi --}}
                    <span class="px-3 py-0.5 text-[11px] font-medium"
                          style="background: rgba(255,154,220,0.20);
                                 border: 1px solid #F80ECD;
                                 border-radius: 4px;
                                 color: #F80ECD;">Prestasi</span>
                    {{-- Gelombang I --}}
                    <span class="px-3 py-0.5 text-[11px] font-medium"
                          style="background: rgba(255,203,154,0.20);
                                 border: 1px solid #CF6F15;
                                 border-radius: 4px;
                                 color: #CF6F15;">Gelombang I</span>
                    {{-- Perlu Perbaikan --}}
                    <span class="px-3 py-0.5 text-[11px] font-medium"
                          style="background: rgba(108,153,217,0.20);
                                 border: 1px solid #1654AA;
                                 border-radius: 4px;
                                 color: #1654AA;">Perlu Perbaikan</span>
                </div>
            </div>
        </div>
        {{-- Kanan: No Pendaftaran --}}
        <div class="text-right" style="padding-left: 24px; flex-shrink: 0;">
            <p class="text-[12px] mb-1" style="color:#575551;">No Pendaftaran:</p>
            <p class="text-[22px] font-bold" style="color:#2B2A28;">12123455</p>
        </div>
    </div>
</div>

{{-- Data Calon Siswa --}}
<div class="bg-white px-6 py-5 mb-4"
     style="border-radius:16px;
            border: 1px solid #F3F3F3;
            box-shadow: 0px 4px 4px 0px rgba(161,209,251,0.25);">
    <h3 class="text-[13px] font-bold mb-1" style="color:#2B2A28;">Data Calon Siswa</h3>
    <div class="mb-4" style="border-bottom: 1px solid #E6E6E6;"></div>
    <div class="grid grid-cols-2 gap-x-8 gap-y-4">
        @php
        $fields = [
            ['label' => 'Tempat, Tanggal Lahir',        'value' => 'Jenepponto, 23 Maret 2017'],
            ['label' => 'Pekerjaan Orang Tua',           'value' => 'ASN'],
            ['label' => 'Asal Sekolah',                  'value' => 'SMP 1 Jeneponto'],
            ['label' => 'Penghasilan Orang Tua / Bulan', 'value' => 'Rp. 10.000.000'],
            ['label' => 'Alamat',                        'value' => 'Jl. Pendidikan Blok A12'],
            ['label' => 'Alamat Orang Tua',              'value' => 'Jl. Pendidikan Blok A12'],
            ['label' => 'Nama Orang Tua',                'value' => 'Halimah'],
            ['label' => 'Jumlah Bersaudara',             'value' => '2'],
        ];
        @endphp
        @foreach($fields as $f)
        <div>
            <p class="text-[11px] mb-0.5" style="color:#575551;">{{ $f['label'] }}</p>
            <p class="text-[12px]" style="color:#2B2A28; font-weight:500;">{{ $f['value'] }}</p>
        </div>
        @endforeach
    </div>
</div>

{{-- Dokumen Unggahan --}}
<div class="bg-white px-6 py-5 mb-4"
     style="border-radius:16px;
            border: 1px solid #F3F3F3;
            box-shadow: 0px 4px 4px 0px rgba(161,209,251,0.25);">
    <h3 class="text-[13px] font-bold mb-1" style="color:#2B2A28;">Dokumen Unggahan</h3>
    <div class="mb-5" style="border-bottom: 1px solid #E6E6E6;"></div>

    @php
    $dokumen = [
        ['nama' => 'Akta Lahir',           'catatan' => ''],
        ['nama' => 'Kartu Keluarga',        'catatan' => ''],
        ['nama' => 'Bukti Verifikasi',      'catatan' => 'fotonya harus HD'],
        ['nama' => 'Sertifikat Juara',      'catatan' => ''],
        ['nama' => 'Rapor',                 'catatan' => ''],
        ['nama' => 'Surat Keterangan Lulus','catatan' => ''],
    ];
    @endphp

    <div class="space-y-6">
        @foreach($dokumen as $dok)
        <div>
            <p class="text-[12px] mb-3" style="color:#2B2A28; font-weight:500;">{{ $dok['nama'] }}</p>
            <div class="flex gap-4 items-start">
                {{-- Preview Gambar --}}
                <div class="relative shrink-0 overflow-hidden border border-slate-200 bg-slate-50"
                     style="width:144px; height:112px; border-radius:12px;">
                    <img src="https://via.placeholder.com/144x112/e2f4fd/27C2DE?text=Dokumen"
                         alt="{{ $dok['nama'] }}"
                         class="w-full h-full object-cover">
                    <div class="absolute bottom-1.5 right-1.5 flex gap-1">
                        <button class="w-[23px] h-[23px] rounded-full flex items-center justify-center"
                                style="background: rgba(0,0,0,0.50);">
                            <img src="{{ asset('ppdb/admin/operasional/perkecil.png') }}" alt="perkecil" class="w-3 h-3 object-contain">
                        </button>
                        <button class="w-[23px] h-[23px] rounded-full flex items-center justify-center"
                                style="background: rgba(0,0,0,0.50);">
                            <img src="{{ asset('ppdb/admin/operasional/perbesar.png') }}" alt="perbesar" class="w-3 h-3 object-contain">
                        </button>
                    </div>
                </div>

                {{-- Aksi Valid/Tidak + Catatan --}}
                <div class="flex-1">
                    <div class="flex gap-2 mb-2">
                        {{-- Valid --}}
                        <button class="flex items-center justify-center">
                            <img src="{{ asset('ppdb/admin/operasional/ceklis.png') }}" alt="valid" class="w-6 h-6 object-contain">
                        </button>
                        {{-- Tidak Valid --}}
                        <button class="flex items-center justify-center">
                            <img src="{{ asset('ppdb/admin/operasional/silang.png') }}" alt="tidak valid" class="w-6 h-6 object-contain">
                        </button>
                    </div>
                    <p class="text-[11px] mb-1" style="color:#575551;">Catatan:</p>
                    <textarea rows="3"
                              class="w-full px-3 py-2 text-[12px] border border-slate-200 resize-none focus:outline-none focus:ring-2 focus:ring-[#27C2DE]"
                              style="border-radius:8px; color:#575551; background:#DFEAF2;"
                              placeholder="Tulis catatan...">{{ $dok['catatan'] }}</textarea>
                </div>
            </div>
        </div>
        @if(!$loop->last)
        <div style="border-top: 1px solid #E6E6E6;"></div>
        @endif
        @endforeach
    </div>
</div>

{{-- Action Buttons --}}
<div class="flex justify-center gap-3 pb-4">
    <a href="{{ route('admin.operasional.verifikasi.detail', 1) }}"
       class="px-6 py-2.5 text-[13px] font-semibold transition-all hover:bg-slate-50"
       style="border-radius:8px; border: 1px solid #E2E8F0; color:#575551;">
        Batal
    </a>
    <button class="inline-flex items-center gap-2 px-6 py-2.5 text-white text-[13px] font-semibold transition-all hover:opacity-90"
            style="background: #27C2DE; border-radius:8px;">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Simpan Verifikasi
    </button>
</div>

@endsection