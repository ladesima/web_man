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
        Verifikasi Ulang
    </a>

</div>

{{-- PROFILE --}}
<div class="bg-white px-6 py-5 mb-4 rounded-xl shadow-sm">
    <div class="flex items-center justify-between">

        <div class="flex items-center gap-4">
            <img src="{{ asset('ppdb/admin/operasional/cadangan.png') }}"
                 class="w-16 h-16 rounded-lg border">

            <div>
                <h2 class="text-[16px] font-bold">
                    {{ optional($pendaftaran->user)->nama ?? '-' }}
                </h2>

                <p class="text-[12px] mb-2">
                    NISN : {{ $pendaftaran->nisn }}
                </p>

                <div class="flex gap-2">
                    <span class="px-2 py-0.5 text-[10px] rounded"
                          style="background: rgba(255,154,220,0.20); border:1px solid #F80ECD; color:#F80ECD;">
                        {{ ucfirst($pendaftaran->jalur) }}
                    </span>

                    <span class="px-2 py-0.5 text-[10px] rounded"
                          style="background: rgba(255,203,154,0.20); border:1px solid #CF6F15; color:#CF6F15;">
                        Gelombang I
                    </span>
                </div>
            </div>
        </div>

        <div class="text-right">
            <p class="text-[12px] text-gray-500">No Pendaftaran:</p>
            <p class="text-[20px] font-bold">
                {{ $pendaftaran->id }}
            </p>
        </div>

    </div>
</div>

{{-- GRID --}}
<div class="grid grid-cols-5 gap-4 mb-4">

    {{-- DATA --}}
    <div class="col-span-3 bg-white px-6 py-5 rounded-xl shadow-sm">
        <h3 class="text-[13px] font-bold mb-3">Data Calon Siswa</h3>

        <div class="grid grid-cols-2 gap-y-3 gap-x-6 text-[12px]">

            <div>
                <p class="text-gray-500">Tempat, Tanggal Lahir</p>
                <p class="font-medium">{{ $pendaftaran->ttl }}</p>
            </div>

            <div>
                <p class="text-gray-500">Pekerjaan Orang Tua</p>
                <p class="font-medium">{{ $pendaftaran->pekerjaan_ortu }}</p>
            </div>

            <div>
                <p class="text-gray-500">Asal Sekolah</p>
                <p class="font-medium">{{ $pendaftaran->asal_sekolah }}</p>
            </div>

            <div>
                <p class="text-gray-500">Penghasilan Orang Tua</p>
                <p class="font-medium">{{ $pendaftaran->penghasilan_ortu }}</p>
            </div>

            <div>
                <p class="text-gray-500">Alamat</p>
                <p class="font-medium">{{ $pendaftaran->alamat }}</p>
            </div>

            <div>
                <p class="text-gray-500">Alamat Orang Tua</p>
                <p class="font-medium">{{ $pendaftaran->alamat_ortu }}</p>
            </div>

            <div>
                <p class="text-gray-500">Nama Orang Tua</p>
                <p class="font-medium">{{ $pendaftaran->nama_ortu }}</p>
            </div>

            <div>
                <p class="text-gray-500">Jumlah Saudara</p>
                <p class="font-medium">{{ $pendaftaran->jumlah_saudara }}</p>
            </div>

        </div>
    </div>

    {{-- DOKUMEN --}}
    <div class="col-span-2 bg-white px-6 py-5 rounded-xl shadow-sm">
        <h3 class="text-[13px] font-bold mb-3">Dokumen Unggahan</h3>

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
                <tr class="text-left text-gray-500 border-b">
                    <th>Dokumen</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
@foreach($dokumen as $label => $file)
<tr class="border-b">
    <td class="py-2">{{ $label }}</td>

    <td class="text-center">
        @if($file)
            <span class="px-2 py-0.5 text-[10px] rounded bg-green-100 text-green-600">
                Tersedia
            </span>
        @else
            <span class="px-2 py-0.5 text-[10px] rounded bg-red-100 text-red-600">
                Tidak Ada
            </span>
        @endif
    </td>

    <td class="text-center">
        @if($file)
            @php
                $url = route('preview.dokumen', ['file' => $file]);
            @endphp

            <button onclick="openPreview('{{ $url }}', '{{ $file }}')"
                    class="px-3 py-1 text-[10px] text-white rounded"
                    style="background:#27C2DE;">
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
<div class="bg-white px-6 py-5 rounded-xl shadow-sm">
    <h3 class="text-[13px] font-bold mb-2">Catatan Verifikasi</h3>

    <ul class="text-[12px] list-disc pl-4">
        <li>{{ $pendaftaran->catatan_revisi ?? 'Tidak ada catatan' }}</li>
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