@extends('layouts.admin')
@section('title', 'Detail Pendaftar')

@section('content')

{{-- Breadcrumb --}}
<div class="relative mb-5" style="width: fit-content;">
    <img src="{{ asset('ppdb/admin/operasional/detailcrump.png') }}" style="height:40px; width:auto; display:block;" alt="">
    <div class="absolute inset-0 grid items-center"
         style="grid-template-columns: 143px 45px; margin-top: -4px;">
        <a href="{{ route('admin.operasional.verifikasi') }}"
           class="text-[12px] text-slate-500 hover:text-[#00758A] transition-colors text-center">
            Verifikasi Berkas
        </a>
        <span class="text-[12px] text-white font-semibold text-center">
            Detail
        </span>
    </div>
</div>

{{-- Action Buttons --}}
<div class="flex justify-end gap-3 mb-5">
    <button class="px-5 py-2 text-[13px] font-semibold transition-all hover:opacity-90"
            style="border-radius:8px;
                   color:#1654AA;
                   background: rgba(108,153,217,0.20);
                   border: 1px solid #1654AA;">
        Perlu Perbaikan
    </button>
    <a href="{{ route('admin.operasional.verifikasi.validasi', 1) }}"
       class="inline-flex items-center gap-2 px-5 py-2 text-white text-[13px] font-semibold transition-all hover:opacity-90"
       style="background: #27C2DE; border-radius:4px;">
        <img src="{{ asset('ppdb/admin/operasional/verifikasiulang.png') }}" alt="" class="w-4 h-4 object-contain">
        Verifikasi Ulang
    </a>
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
                 style="border-radius:12px;">
            <div>
                <h2 class="text-[16px] font-bold mb-0.5" style="color:#2B2A28;">Muhammad Naufal</h2>
                <p class="text-[12px] mb-2" style="color:#575551;">NISN : 1234567</p>
                <div class="flex gap-2">
                    <span class="px-3 py-0.5 text-[11px] font-medium"
                          style="background: rgba(255,154,220,0.20);
                                 border: 1px solid #F80ECD;
                                 border-radius: 4px;
                                 color: #F80ECD;">Prestasi</span>
                    <span class="px-3 py-0.5 text-[11px] font-medium"
                          style="background: rgba(255,203,154,0.20);
                                 border: 1px solid #CF6F15;
                                 border-radius: 4px;
                                 color: #CF6F15;">Gelombang I</span>
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

{{-- Content Grid --}}
<div class="grid grid-cols-5 gap-4 mb-4">

    {{-- Data Calon Siswa --}}
    <div class="col-span-3 bg-white px-6 py-5" style="border-radius:16px; border: 1px solid #F3F3F3; box-shadow: 0px 4px 4px 0px rgba(161,209,251,0.25);">
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
                {{-- #2B2A28, font-weight 500 (medium) --}}
                <p class="text-[12px]" style="color:#2B2A28; font-weight:500;">{{ $f['value'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Dokumen Unggahan --}}
    <div class="col-span-2 bg-white px-6 py-5" style="border-radius:16px; border: 1px solid #F3F3F3; box-shadow: 0px 4px 4px 0px rgba(161,209,251,0.25);">
        <h3 class="text-[13px] font-bold mb-1" style="color:#2B2A28;">Dokumen Unggahan</h3>
        <div class="mb-4" style="border-bottom: 1px solid #E6E6E6;"></div>
        <table class="w-full text-[12px]">
            <thead>
                <tr style="background:#F4F4F4;">
                    <th class="text-left py-2 px-3 font-semibold" style="color:#2B2A28;">Dokumen</th>
                    <th class="text-center py-2 px-3 font-semibold" style="color:#2B2A28;">Status</th>
                    <th class="text-center py-2 px-3 font-semibold" style="color:#2B2A28;">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach(['Akta Lahir','Kartu Keluarga','Bukti Verifikasi','SK/Sertifikat Juara','Rapor','SKL'] as $dok)
                <tr>
                    <td class="py-2.5 px-3" style="color:#2B2A28;">{{ $dok }}</td>

                    {{-- Tersedia: lebar penuh di cell, teks center, border-radius 8px agar mirip gambar 1 --}}
                    <td class="py-2.5 px-3 text-center">
                        <span class="inline-block w-full text-center text-[11px] font-medium py-1"
                              style="background:#DCFCE7;
                                     color:#16A34A;
                                     border: 1px solid #16A34A;
                                     border-radius: 4px;">
                            Tersedia
                        </span>
                    </td>

                    {{-- Preview: gradient kiri cyan terang → kanan biru tua, border-radius 8px --}}
                    <td class="py-2.5 px-3 text-center">
                        <button class="inline-flex items-center justify-center gap-1.5 w-full py-1 text-white text-[11px] font-semibold"
                                style="background: linear-gradient(135deg, #5EEAD4 0%, #06B6D4 40%, #0284C7 100%);
                                       border-radius: 4px;">
                            {{-- Eye icon SVG inline --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            Preview
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Catatan Verifikasi --}}
<div class="bg-white px-6 py-5" style="border-radius:16px; border: 1px solid #F3F3F3; box-shadow: 0px 4px 4px 0px rgba(161,209,251,0.25);">
    <h3 class="text-[13px] font-bold mb-1" style="color:#2B2A28;">Catatan Verifikasi</h3>
    <div class="mb-4" style="border-bottom: 1px solid #E6E6E6;"></div>
    <ul class="list-disc list-inside text-[12px] space-y-1" style="color:#575551;">
        <li>Fotonya Harus HD</li>
    </ul>
</div>

@endsection