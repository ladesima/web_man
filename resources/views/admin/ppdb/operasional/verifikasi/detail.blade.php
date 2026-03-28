@extends('layouts.admin')
@section('title', 'Detail Pendaftar')

@section('content')

{{-- Breadcrumb --}}
<div class="relative mb-5" style="width: fit-content;">
    <img src="{{ asset('ppdb/admin/operasional/detailcrump.png') }}" style="height:40px;">
    <div class="absolute inset-0 grid items-center"
         style="grid-template-columns: 143px 45px; margin-top: -4px;">

        <a href="{{ route('admin.operasional.verifikasi') }}"
           class="text-[12px] text-slate-500 hover:text-[#00758A] text-center">
            Verifikasi Berkas
        </a>

        <span class="text-[12px] text-white font-semibold text-center">
            Detail
        </span>
    </div>
</div>

{{-- ACTION --}}
@php
    $status = $pendaftaran->status;

    $config = match ($status) {
        'perbaikan' => [
            'text' => 'Perlu Perbaikan',
            'bg' => 'rgba(108,153,217,0.20)',
            'color' => '#1654AA',
            'border' => '#1654AA'
        ],
        'lulus' => [
            'text' => 'Berkas Valid',
            'bg' => 'rgba(34,197,94,0.15)',
            'color' => '#15803D',
            'border' => '#15803D'
        ],
        'tidak_lulus' => [
            'text' => 'Berkas Ditolak',
            'bg' => 'rgba(239,68,68,0.15)',
            'color' => '#DC2626',
            'border' => '#DC2626'
        ],
        default => [
            'text' => 'Menunggu Verifikasi',
            'bg' => 'rgba(14,165,233,0.15)',
            'color' => '#0284C7',
            'border' => '#0284C7'
        ],
    };
@endphp

<div class="flex justify-end gap-3 mb-5">

    {{-- STATUS BUTTON (DINAMIS) --}}
    <button class="px-5 py-2 text-[13px] font-semibold"
            style="border-radius:8px;
                   color:{{ $config['color'] }};
                   background: {{ $config['bg'] }};
                   border:1px solid {{ $config['border'] }};">
        {{ $config['text'] }}
    </button>

    {{-- VERIFIKASI ULANG --}}
    <a href="{{ route('admin.verifikasi.validasi', $pendaftaran->id) }}"
       class="inline-flex items-center gap-2 px-5 py-2 text-white text-[13px] font-semibold"
       style="background:#27C2DE; border-radius:4px;">
       <img src="{{ asset('ppdb/admin/operasional/verifikasiulang.png') }}" alt="" class="w-4 h-4 object-contain">
        Verifikasi Ulang
    </a>

</div>
<div class="bg-white px-6 py-5 mb-4"
     style="border-radius:12px;
            border: 1px solid #F3F3F3;
            box-shadow: 0px 4px 4px 0px rgba(161,209,251,0.25);">

    <div class="flex items-center">

        {{-- KIRI --}}
        <div class="flex items-center gap-4 flex-1"
             style="border-right: 1px solid #E6E6E6; padding-right: 24px;">

            <img src="{{ $pendaftaran->foto 
        ? asset('storage/' . $pendaftaran->foto) 
        : asset('ppdb/admin/operasional/cadangan.png') }}"
     class="w-16 h-16 object-cover border border-slate-200"
     style="border-radius:12px;">

            <div>
                <h2 class="text-[16px] font-bold mb-0.5"
                    style="color:#2B2A28;">
                    {{ optional($pendaftaran->user)->nama ?? '-' }}
                </h2>

                <p class="text-[12px] mb-2"
                   style="color:#575551;">
                    NISN : {{ $pendaftaran->nisn }}
                </p>

                <div class="flex gap-2">

                    {{-- JALUR --}}
                    <span class="px-3 py-0.5 text-[11px] font-medium"
                          style="background: rgba(255,154,220,0.20);
                                 border: 1px solid #F80ECD;
                                 border-radius: 4px;
                                 color: #F80ECD;">
                        {{ ucfirst($pendaftaran->jalur) }}
                    </span>

                    {{-- GELOMBANG --}}
                    <span class="px-3 py-0.5 text-[11px] font-medium"
                          style="background: rgba(255,203,154,0.20);
                                 border: 1px solid #CF6F15;
                                 border-radius: 4px;
                                 color: #CF6F15;">
                        Gelombang I
                    </span>

                </div>
            </div>
        </div>

        {{-- KANAN --}}
        <div class="text-right"
             style="padding-left: 24px; flex-shrink: 0;">

            <p class="text-[12px] mb-1"
               style="color:#575551;">
                No Pendaftaran:
            </p>

            <p class="text-[22px] font-bold"
               style="color:#2B2A28;">
                {{ $pendaftaran->id }}
            </p>

        </div>

    </div>
</div>

{{-- GRID --}}
<div class="grid grid-cols-5 gap-4 mb-4">

   {{-- DATA CALON SISWA --}}
<div class="col-span-3 bg-white px-6 py-5"
     style="border-radius:16px;
            border: 1px solid #F3F3F3;
            box-shadow: 0px 4px 4px 0px rgba(161,209,251,0.25);">

    {{-- TITLE --}}
    <h3 class="text-[13px] font-bold mb-1"
        style="color:#2B2A28;">
        Data Calon Siswa
    </h3>

    {{-- DIVIDER --}}
    <div class="mb-4"
         style="border-bottom: 1px solid #E6E6E6;"></div>

    {{-- CONTENT --}}
    <div class="grid grid-cols-2 gap-x-8 gap-y-4">

        <div>
            <p class="text-[11px] mb-0.5" style="color:#575551;">
                Tempat, Tanggal Lahir
            </p>
            <p class="text-[12px]" style="color:#2B2A28; font-weight:500;">
                {{ $pendaftaran->ttl }}
            </p>
        </div>

        <div>
            <p class="text-[11px] mb-0.5" style="color:#575551;">
                Pekerjaan Orang Tua
            </p>
            <p class="text-[12px]" style="color:#2B2A28; font-weight:500;">
                {{ $pendaftaran->pekerjaan_ortu }}
            </p>
        </div>

        <div>
            <p class="text-[11px] mb-0.5" style="color:#575551;">
                Asal Sekolah
            </p>
            <p class="text-[12px]" style="color:#2B2A28; font-weight:500;">
                {{ $pendaftaran->asal_sekolah }}
            </p>
        </div>

        <div>
            <p class="text-[11px] mb-0.5" style="color:#575551;">
                Penghasilan Orang Tua / Bulan
            </p>
            <p class="text-[12px]" style="color:#2B2A28; font-weight:500;">
                {{ $pendaftaran->penghasilan_ortu }}
            </p>
        </div>

        <div>
            <p class="text-[11px] mb-0.5" style="color:#575551;">
                Alamat
            </p>
            <p class="text-[12px]" style="color:#2B2A28; font-weight:500;">
                {{ $pendaftaran->alamat }}
            </p>
        </div>

        <div>
            <p class="text-[11px] mb-0.5" style="color:#575551;">
                Alamat Orang Tua
            </p>
            <p class="text-[12px]" style="color:#2B2A28; font-weight:500;">
                {{ $pendaftaran->alamat_ortu }}
            </p>
        </div>

        <div>
            <p class="text-[11px] mb-0.5" style="color:#575551;">
                Nama Orang Tua
            </p>
            <p class="text-[12px]" style="color:#2B2A28; font-weight:500;">
                {{ $pendaftaran->nama_ortu }}
            </p>
        </div>

        <div>
            <p class="text-[11px] mb-0.5" style="color:#575551;">
                Jumlah Bersaudara
            </p>
            <p class="text-[12px]" style="color:#2B2A28; font-weight:500;">
                {{ $pendaftaran->jumlah_saudara }}
            </p>
        </div>

    </div>
</div>

{{-- DOKUMEN --}}
<div class="col-span-2 bg-white px-6 py-5"
     style="border-radius:16px;
            border: 1px solid #F3F3F3;
            box-shadow: 0px 4px 4px 0px rgba(161,209,251,0.25);">

    <h3 class="text-[13px] font-bold mb-1" style="color:#2B2A28;">
        Dokumen Unggahan
    </h3>

    <div class="mb-4" style="border-bottom: 1px solid #E6E6E6;"></div>

    @php
    $dokumen = [
        'Akta Lahir' => $pendaftaran->akta_lahir,
        'Kartu Keluarga' => $pendaftaran->kartu_keluarga,
        'Bukti Verifikasi' => $pendaftaran->verifikasi_pd,
        'SK/Sertifikat Juara' => $pendaftaran->sertifikat_prestasi,
        'Rapor' => $pendaftaran->rapor,
        'SKL' => $pendaftaran->sk_sekolah,
    ];
    @endphp

    <table class="w-full text-[12px]">
        <thead>
            <tr style="background:#F4F4F4;">
                <th class="text-left py-2 px-3 font-semibold" style="color:#2B2A28;">
                    Dokumen
                </th>
                <th class="text-center py-2 px-3 font-semibold" style="color:#2B2A28;">
                    Status
                </th>
                <th class="text-center py-2 px-3 font-semibold" style="color:#2B2A28;">
                    Aksi
                </th>
            </tr>
        </thead>

        <tbody class="divide-y divide-slate-100">

@foreach($dokumen as $label => $file)
<tr>

    {{-- NAMA DOKUMEN --}}
    <td class="py-2.5 px-3" style="color:#2B2A28;">
        {{ $label }}
    </td>

    {{-- STATUS --}}
    <td class="py-2.5 px-3 text-center">
        @if($file)
            <span class="inline-block w-full text-center text-[11px] font-medium py-1"
                  style="background:#DCFCE7;
                         color:#16A34A;
                         border: 1px solid #16A34A;
                         border-radius: 4px;">
                Tersedia
            </span>
        @else
            <span class="inline-block w-full text-center text-[11px] font-medium py-1"
                  style="background:#FEE2E2;
                         color:#DC2626;
                         border: 1px solid #DC2626;
                         border-radius: 4px;">
                Tidak Ada
            </span>
        @endif
    </td>

    {{-- AKSI --}}
    <td class="py-2.5 px-3 text-center">
        @if($file)
            @php
                $url = route('preview.dokumen', ['file' => $file]);
            @endphp

            <button onclick="openPreview('{{ $url }}', '{{ $file }}')"
                    class="inline-flex items-center justify-center gap-1.5 w-full py-1 text-white text-[11px] font-semibold"
                    style="background: linear-gradient(135deg, #5EEAD4 0%, #06B6D4 40%, #0284C7 100%);
                           border-radius: 4px;">

                {{-- ICON --}}
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-3.5 h-3.5"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>

                Preview
            </button>
        @endif
    </td>

</tr>
@endforeach

        </tbody>
    </table>
</div>
</div>

{{-- CATATAN --}}
<div class="bg-white px-6 py-6 mt-2"
     style="border-radius:16px;
            border: 1px solid #F3F3F3;
            box-shadow: 0px 4px 4px 0px rgba(161,209,251,0.25);">

    {{-- TITLE --}}
    <h3 class="text-[14px] font-semibold mb-2"
        style="color:#2B2A28;">
        Catatan Verifikasi
    </h3>

    {{-- DIVIDER --}}
    <div class="mb-4"
         style="border-bottom: 1px solid #E6E6E6;"></div>

    @php
        $verifikasi = json_decode($pendaftaran->verifikasi_dokumen ?? '{}', true);
    @endphp

    {{-- LIST --}}
    <ul class="text-[13px] space-y-2 pl-5"
        style="color:#575551; list-style-type: disc;">

        {{-- DATA DINAMIS --}}
        @if(!empty($verifikasi))
            @foreach($verifikasi as $key => $item)

                @if(($item['status'] ?? '') === 'no' && !empty($item['catatan']))
                    <li class="leading-relaxed">
                        {{ strtoupper(str_replace('_', ' ', $key)) }}
                        :
                        {{ $item['catatan'] }}
                    </li>
                @endif

            @endforeach
        @endif

        {{-- FALLBACK --}}
        @if(
            empty($verifikasi) ||
            collect($verifikasi)->where('status','no')->count() === 0
        )
            <li class="leading-relaxed">
                {{ $pendaftaran->catatan_revisi ?? 'Tidak ada catatan' }}
            </li>
        @endif

    </ul>
</div>

@endsection
<div id="previewModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-lg w-[80%] h-[85%] relative p-2">

        <button onclick="closePreview()"
                class="absolute top-2 right-3 text-xl font-bold text-gray-600 z-10">
            ×
        </button>

        {{-- IMAGE --}}
        <img id="previewImage" class="hidden w-full h-full object-contain rounded">

        {{-- PDF / FILE --}}
        <iframe id="previewFrame"
                class="hidden w-full h-full rounded"
                src=""></iframe>

    </div>
</div>

<script>
function openPreview(url, fileName) {

    let img = document.getElementById('previewImage');
    let frame = document.getElementById('previewFrame');

    // reset
    img.classList.add('hidden');
    frame.classList.add('hidden');

    img.src = '';
    frame.src = '';

    // ambil extension dari nama file
    let ext = fileName.split('.').pop().toLowerCase();

    // DETECT TYPE
    if (['jpg','jpeg','png','webp'].includes(ext)) {
        img.src = url;
        img.classList.remove('hidden');

    } else if (ext === 'pdf') {
        frame.src = url;
        frame.classList.remove('hidden');

    } else {
        frame.src = url;
        frame.classList.remove('hidden');
    }

    document.getElementById('previewModal').classList.remove('hidden');
    document.getElementById('previewModal').classList.add('flex');
}

function closePreview() {
    document.getElementById('previewImage').src = '';
    document.getElementById('previewFrame').src = '';
    document.getElementById('previewModal').classList.add('hidden');
}
</script>