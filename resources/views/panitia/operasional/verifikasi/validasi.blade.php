@extends('layouts.panitia')
@section('title', 'Validasi Berkas')

@section('content')

{{-- ================= BREADCRUMB ================= --}}
<div class="relative mb-5" style="width: fit-content;">
    <img src="{{ asset('ppdb/admin/operasional/validasicrump.png') }}" style="height:40px;">
    <div class="absolute inset-0 flex items-center" style="margin-top:-4px;">
        <a href="{{ route('panitia.operasional.verifikasi') }}"
           class="text-[12px] text-slate-500 hover:text-[#00758A]"
           style="padding-left:30px; padding-right:20px;">
            Verifikasi Berkas
        </a>
        <a href="{{ route('panitia.operasional.verifikasi.detail', $pendaftaran->id) }}"
           class="text-[12px] text-slate-500 hover:text-[#00758A]"
           style="padding-left:16px; padding-right:20px;">
            Detail
        </a>
        <span class="text-[12px] text-white font-semibold"
              style="padding-left:16px; padding-right:20px;">
            Validasi
        </span>
    </div>
</div>

{{-- ================= PROFILE ================= --}}
<div class="bg-white px-6 py-5 mb-4"
     style="border-radius:12px; border:1px solid #F3F3F3; box-shadow:0 4px 4px rgba(161,209,251,0.25);">

    <div class="flex items-center">

        <div class="flex items-center gap-4 flex-1"
             style="border-right:1px solid #E6E6E6; padding-right:24px;">

            <img src="{{ asset('ppdb/admin/operasional/cadangan.png') }}"
                 class="w-16 h-16 object-cover border"
                 style="border-radius:12px;"
                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($pendaftaran->user->nama ?? 'User') }}&background=27C2DE&color=fff'">

            <div>
                <h2 class="text-[16px] font-bold">{{ $pendaftaran->user->nama ?? '-' }}</h2>
                <p class="text-[12px] mb-2">NISN : {{ $pendaftaran->nisn ?? '-' }}</p>

                <div class="flex gap-2">

                    <span class="px-3 py-0.5 text-[11px]"
                          style="background:rgba(255,154,220,0.20); border:1px solid #F80ECD; border-radius:4px;">
                        {{ ucfirst($pendaftaran->jalur ?? '-') }}
                    </span>

                    <span class="px-3 py-0.5 text-[11px]"
                          style="background:rgba(108,153,217,0.20); border:1px solid #1654AA; border-radius:4px;">
                        {{ match($pendaftaran->status) {
                            'perbaikan' => 'Perlu Perbaikan',
                            'lulus' => 'Berkas Valid',
                            'tidak_lulus' => 'Berkas Ditolak',
                            default => 'Menunggu'
                        } }}
                    </span>

                </div>
            </div>
        </div>

        <div class="text-right pl-6">
            <p class="text-[12px]">No Pendaftaran:</p>
            <p class="text-[22px] font-bold">{{ $pendaftaran->nisn ?? '-' }}</p>
        </div>

    </div>
</div>

{{-- ================= DATA SISWA ================= --}}
<div class="bg-white px-6 py-5 mb-4"
     style="border-radius:16px; border:1px solid #F3F3F3;">

    <h3 class="text-[13px] font-bold mb-1">Data Calon Siswa</h3>
    <div class="mb-4" style="border-bottom:1px solid #E6E6E6;"></div>

    <div class="grid grid-cols-2 gap-x-8 gap-y-4 text-[12px]">

        <div><p>Tempat, Tanggal Lahir</p><p class="font-medium">{{ $pendaftaran->ttl ?? '-' }}</p></div>
        <div><p>Pekerjaan Orang Tua</p><p class="font-medium">{{ $pendaftaran->pekerjaan_ortu ?? '-' }}</p></div>
        <div><p>Asal Sekolah</p><p class="font-medium">{{ $pendaftaran->asal_sekolah ?? '-' }}</p></div>
        <div><p>Penghasilan Orang Tua</p><p class="font-medium">{{ $pendaftaran->penghasilan_ortu ?? '-' }}</p></div>
        <div><p>Alamat</p><p class="font-medium">{{ $pendaftaran->alamat ?? '-' }}</p></div>
        <div><p>Alamat Orang Tua</p><p class="font-medium">{{ $pendaftaran->alamat_ortu ?? '-' }}</p></div>
        <div><p>Nama Orang Tua</p><p class="font-medium">{{ $pendaftaran->nama_ortu ?? '-' }}</p></div>
        <div><p>Jumlah Saudara</p><p class="font-medium">{{ $pendaftaran->jumlah_saudara ?? '-' }}</p></div>

    </div>
</div>
@php
$dokumen = [
    [
        'nama' => 'akta_lahir',
        'label' => 'Akta Lahir',
        'file' => $pendaftaran->akta_lahir,
    ],
    [
        'nama' => 'kartu_keluarga',
        'label' => 'Kartu Keluarga',
        'file' => $pendaftaran->kartu_keluarga,
    ],
    [
        'nama' => 'verifikasi_pd',
        'label' => 'Bukti Verifikasi',
        'file' => $pendaftaran->verifikasi_pd,
    ],
];

// =============================
// 🔥 LOGIKA BERDASARKAN JALUR
// =============================

if ($pendaftaran->jalur === 'prestasi') {
    $dokumen[] = [
        'nama' => 'sertifikat_prestasi',
        'label' => 'Sertifikat Prestasi',
        'file' => $pendaftaran->sertifikat_prestasi,
    ];
}

if ($pendaftaran->jalur === 'afirmasi') {
    $dokumen[] = [
        'nama' => 'kip',
        'label' => 'Kartu Indonesia Pintar (KIP)',
        'file' => $pendaftaran->kip,
    ];
}

// ❌ reguler tidak ada sertifikat

$dokumen[] = [
    'nama' => 'rapor',
    'label' => 'Rapor',
    'file' => $pendaftaran->rapor,
];

$dokumen[] = [
    'nama' => 'sk_sekolah',
    'label' => 'SKL',
    'file' => $pendaftaran->sk_sekolah,
];
@endphp
{{-- ================= DOKUMEN ================= --}}
<form method="POST" action="{{ route('panitia.operasional.verifikasi.simpan', $pendaftaran->id) }}">
@csrf

@foreach($dokumen as $dok)
<div class="mb-6">

    <p class="text-[12px] font-medium mb-3">{{ $dok['label'] }}</p>

    <div class="flex items-start gap-6">

        {{-- PREVIEW (TIDAK DIUBAH) --}}
        <div style="width:150px; height:110px;"
             class="border border-[#E6E6E6] bg-[#F9FAFB] rounded-md overflow-hidden">

            @if($dok['file'])
                @php
                    $ext = strtolower(pathinfo($dok['file'], PATHINFO_EXTENSION));
                    $url = route('preview.dokumen', ['file'=>$dok['file']]);
                @endphp

                @if(in_array($ext, ['jpg','jpeg','png','webp']))
                    <img src="{{ $url }}"
                         class="w-full h-full object-cover cursor-pointer"
                         onclick="openPreview('{{ $url }}','{{ $dok['file'] }}')">

                @elseif($ext === 'pdf')
                    <div class="flex items-center justify-center h-full bg-gray-100 cursor-pointer"
                         onclick="openPreview('{{ $url }}','{{ $dok['file'] }}')">
                        <span class="text-xs text-red-500 font-semibold">PDF</span>
                    </div>
                @endif
            @endif
        </div>

        {{-- STATUS + AKSI --}}
        <div class="flex-1">

            {{-- 🔥 HIDDEN STATUS --}}
            <input type="hidden"
                name="verifikasi[{{ $dok['nama'] }}][status]"
                id="status_{{ $dok['nama'] }}"
                value="{{ $verifikasi[$dok['nama']]['status'] ?? '' }}">

            {{-- 🔥 BUTTON (TIDAK MERUSAK UI) --}}
            <div class="flex items-center gap-2 mb-2">

                {{-- ICON OK --}}
                <button type="button"
                    onclick="setStatus('{{ $dok['nama'] }}','ok')"
                    class="p-1 opacity-70 hover:opacity-100">
                    <img src="{{ asset('ppdb/admin/operasional/ceklis.png') }}" class="w-5">
                </button>

                {{-- ICON NO --}}
                <button type="button"
                    onclick="setStatus('{{ $dok['nama'] }}','no')"
                    class="p-1 opacity-70 hover:opacity-100">
                    <img src="{{ asset('ppdb/admin/operasional/silang.png') }}" class="w-5">
                </button>

                {{-- STATUS BADGE (UI LAMA TETAP) --}}
               

            </div>

            {{-- CATATAN --}}
            <textarea
                name="verifikasi[{{ $dok['nama'] }}][catatan]"
                id="catatan_{{ $dok['nama'] }}"
                class="w-full text-[12px] border border-[#E6E6E6] rounded-md px-3 py-2 bg-[#F9FAFB]"
                placeholder="Catatan">{{ $verifikasi[$dok['nama']]['catatan'] ?? '' }}</textarea>

        </div>

    </div>

</div>
@endforeach

{{-- BUTTON --}}
<div class="flex justify-center gap-3 pb-4">

    <a href="{{ route('panitia.operasional.verifikasi.detail', $pendaftaran->id) }}"
       class="px-6 py-2.5 text-[13px] font-semibold border rounded">
        Kembali
    </a>

    <button type="submit"
        class="px-6 py-2.5 text-white text-[13px] font-semibold"
        style="background:#27C2DE;border-radius:8px;">
        Simpan Verifikasi
    </button>

</div>

</form>

{{-- ================= MODAL ================= --}}
<div id="previewModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-lg w-[80%] h-[85%] relative p-2">

        <button onclick="closePreview()"
                class="absolute top-2 right-3 text-xl font-bold text-gray-600 z-10">
            ×
        </button>

        <img id="previewImage" class="hidden w-full h-full object-contain">
        <iframe id="previewFrame" class="hidden w-full h-full"></iframe>

    </div>
</div>

<script>
function openPreview(url, fileName) {

    let img = document.getElementById('previewImage');
    let frame = document.getElementById('previewFrame');

    img.classList.add('hidden');
    frame.classList.add('hidden');

    let ext = fileName.split('.').pop().toLowerCase();

    if (['jpg','jpeg','png','webp'].includes(ext)) {
        img.src = url;
        img.classList.remove('hidden');
    } else {
        frame.src = url;
        frame.classList.remove('hidden');
    }

    document.getElementById('previewModal').classList.remove('hidden');
    document.getElementById('previewModal').classList.add('flex');
}

function closePreview() {
    document.getElementById('previewModal').classList.add('hidden');
    document.getElementById('previewImage').src = '';
    document.getElementById('previewFrame').src = '';
}
</script>
<script>
function setStatus(field, status) {
    let input = document.getElementById('status_' + field);
    let catatan = document.getElementById('catatan_' + field);

    input.value = status;

    if (status === 'no') {
        catatan.required = true;
        catatan.style.border = '1px solid #EF4444';
        catatan.style.background = '#FEF2F2';
    } else {
        catatan.required = false;
        catatan.style.border = '1px solid #22C55E';
        catatan.style.background = '#F0FDF4';
    }
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    @foreach($dokumen as $dok)
        setStatus(
            "{{ $dok['nama'] }}",
            "{{ $verifikasi[$dok['nama']]['status'] ?? '' }}"
        );
    @endforeach
});
function setStatus(field, status) {
    let input = document.getElementById('status_' + field);
    let catatan = document.getElementById('catatan_' + field);

    input.value = status;

    if (status === 'no') {
        catatan.required = true;
        catatan.style.border = '1px solid #EF4444';
        catatan.style.background = '#FEF2F2';
    } else if (status === 'ok') {
        catatan.required = false;
        catatan.value = ''; // 🔥 AUTO HAPUS CATATAN
        catatan.style.border = '1px solid #22C55E';
        catatan.style.background = '#F0FDF4';
    }
}
</script>
@endsection