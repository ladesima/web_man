@extends('layouts.admin')
@section('title', 'Validasi Berkas')

@section('content')

{{-- ================= BREADCRUMB ================= --}}
<div class="relative mb-5" style="width: fit-content;">
    <img src="{{ asset('ppdb/admin/operasional/validasicrump.png') }}" style="height:40px;">
    <div class="absolute inset-0 flex items-center" style="margin-top:-4px;">
        <a href="/admin/operasional/verifikasi"
           class="text-[12px] text-slate-500 hover:text-[#00758A]"
           style="padding-left:30px; padding-right:20px;">
            Verifikasi Berkas
        </a>
        <a href="/admin/operasional/verifikasi/{{ $pendaftaran->id }}"
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
                        {{ ucfirst(str_replace('_',' ',$pendaftaran->status ?? '-')) }}
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

        <div>
            <p>Tempat, Tanggal Lahir</p>
            <p class="font-medium">{{ $pendaftaran->ttl ?? '-' }}</p>
        </div>

        <div>
            <p>Pekerjaan Orang Tua</p>
            <p class="font-medium">{{ $pendaftaran->pekerjaan_ortu ?? '-' }}</p>
        </div>

        <div>
            <p>Asal Sekolah</p>
            <p class="font-medium">{{ $pendaftaran->asal_sekolah ?? '-' }}</p>
        </div>

        <div>
            <p>Penghasilan Orang Tua</p>
            <p class="font-medium">{{ $pendaftaran->penghasilan_ortu ?? '-' }}</p>
        </div>

        <div>
            <p>Alamat</p>
            <p class="font-medium">{{ $pendaftaran->alamat ?? '-' }}</p>
        </div>

        <div>
            <p>Alamat Orang Tua</p>
            <p class="font-medium">{{ $pendaftaran->alamat_ortu ?? '-' }}</p>
        </div>

        <div>
            <p>Nama Orang Tua</p>
            <p class="font-medium">{{ $pendaftaran->nama_ortu ?? '-' }}</p>
        </div>

        <div>
            <p>Jumlah Saudara</p>
            <p class="font-medium">{{ $pendaftaran->jumlah_saudara ?? '-' }}</p>
        </div>

    </div>
</div>

{{-- ================= FORM START ================= --}}
<form method="POST" action="{{ route('admin.verifikasi.simpan', $pendaftaran->id) }}">
@csrf

@php
$verifikasi = json_decode($pendaftaran->verifikasi_dokumen ?? '{}', true);
@endphp
{{-- ================= DOKUMEN ================= --}}
<div class="bg-white px-6 py-5 mb-4"
     style="border-radius:16px; border:1px solid #F3F3F3;">

@php
$dokumen = [
 ['nama'=>'akta_lahir','label'=>'Akta Kelahiran','file'=>$pendaftaran->akta_lahir],
 ['nama'=>'kartu_keluarga','label'=>'Kartu Keluarga','file'=>$pendaftaran->kartu_keluarga],
 ['nama'=>'rapor','label'=>'Rapor','file'=>$pendaftaran->rapor],
 ['nama'=>'verifikasi_pd','label'=>'Verifikasi PD','file'=>$pendaftaran->verifikasi_pd],
 ['nama'=>'sertifikat_prestasi','label'=>'Sertifikat Prestasi','file'=>$pendaftaran->sertifikat_prestasi],
 ['nama'=>'sk_sekolah','label'=>'SK Sekolah','file'=>$pendaftaran->sk_sekolah],
];
@endphp

@foreach($dokumen as $dok)
<div class="mb-6">

    <p class="text-[12px] font-medium mb-3">{{ $dok['label'] }}</p>

    <div class="flex items-start gap-6">

        {{-- PREVIEW --}}
        <div style="width:150px; height:110px;"
             class="border border-[#E6E6E6] bg-[#F9FAFB] rounded-md overflow-hidden">

            @if($dok['file'])

                @php
    $ext = strtolower(pathinfo($dok['file'], PATHINFO_EXTENSION));

    $url = route('preview.dokumen', [
        'file' => $dok['file']
    ]);
@endphp

                @if(in_array($ext, ['jpg','jpeg','png','webp']))
                    <img src="{{ $url }}"
                         class="w-full h-full object-cover cursor-pointer"
                         onclick="openPreview('{{ $url }}')">

                @elseif($ext === 'pdf')
                    <div class="flex items-center justify-center h-full bg-gray-100 cursor-pointer"
                         onclick="openPreview('{{ $url }}', '{{ $dok['file'] }}')">
                        <span class="text-xs text-red-500 font-semibold">PDF</span>
                    </div>
                @endif

            @endif

        </div>

        {{-- ACTION --}}
        <div class="flex-1">

            <input type="hidden"
       name="verifikasi[{{ $dok['nama'] }}][status]"
       id="status_{{ $dok['nama'] }}"
       value="{{ $verifikasi[$dok['nama']]['status'] ?? '' }}">

            <div class="flex items-center gap-2 mb-2">

              <button type="button"
        id="btn_ok_{{ $dok['nama'] }}"
        onclick="setStatus('{{ $dok['nama'] }}','ok')"
        class="p-1 rounded transition-all duration-200 ease-in-out">
    <img src="{{ asset('ppdb/admin/operasional/ceklis.png') }}" class="w-5">
</button>

<button type="button"
        id="btn_no_{{ $dok['nama'] }}"
        onclick="setStatus('{{ $dok['nama'] }}','no')"
        class="p-1 rounded transition-all duration-200 ease-in-out">
    <img src="{{ asset('ppdb/admin/operasional/silang.png') }}" class="w-5">
</button>

            </div>

            <textarea
    name="verifikasi[{{ $dok['nama'] }}][catatan]"
    id="catatan_{{ $dok['nama'] }}"
    class="w-full text-[12px] border border-[#E6E6E6] rounded-md px-3 py-2 bg-[#F9FAFB]"
    placeholder="Catatan">{{ $verifikasi[$dok['nama']]['catatan'] ?? '' }}</textarea>

        </div>

    </div>

</div>
@endforeach

</div>
{{-- ================= ACTION ================= --}}
<div class="flex justify-center gap-3 pb-4">

    {{-- BATAL --}}
    <a href="/admin/operasional/verifikasi/{{ $pendaftaran->id }}"
       class="px-6 py-2.5 text-[13px] font-semibold transition-all hover:bg-slate-50"
       style="border-radius:8px;
              border: 1px solid #E2E8F0;
              color:#575551;">
        Batal
    </a>

    {{-- SIMPAN --}}
    <button type="submit"
            class="inline-flex items-center gap-2 px-6 py-2.5 text-white text-[13px] font-semibold transition-all hover:opacity-90"
            style="background: #27C2DE;
                   border-radius:8px;">

        {{-- ICON --}}
         <img src="{{ asset('ppdb/admin/operasional/verifikasiulang.png') }}" alt="" class="w-4 h-4 object-contain">

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
function setStatus(field, status) {

    let s = document.getElementById('status_' + field);
    let c = document.getElementById('catatan_' + field);

    let btnOk = document.getElementById('btn_ok_' + field);
    let btnNo = document.getElementById('btn_no_' + field);

    s.value = status;

    // RESET
    btnOk.style.transform = 'scale(1)';
    btnNo.style.transform = 'scale(1)';
    btnOk.style.opacity = '0.6';
    btnNo.style.opacity = '0.6';

    if (status === 'ok') {

        // ✅ BESAR + AKTIF
        btnOk.style.transform = 'scale(1.3)';
        btnOk.style.opacity = '1';

        c.required = false;
        c.style.border = '1px solid #22C55E';
        c.style.background = '#F0FDF4';

    } else if (status === 'no') {

        // ❌ BESAR + AKTIF
        btnNo.style.transform = 'scale(1.3)';
        btnNo.style.opacity = '1';

        c.required = true;
        c.style.border = '1px solid #EF4444';
        c.style.background = '#FEF2F2';
    }
}

function openPreview(url, fileName) {

    let img = document.getElementById('previewImage');
    let frame = document.getElementById('previewFrame');

    // reset
    img.classList.add('hidden');
    frame.classList.add('hidden');

    img.src = '';
    frame.src = '';

    // ambil extension dari nama file (bukan URL)
    let ext = fileName.split('.').pop().toLowerCase();

    // DETECT TYPE
    if (['jpg','jpeg','png','webp'].includes(ext)) {

        img.src = url;
        img.classList.remove('hidden');

    } else if (ext === 'pdf') {

        frame.src = url; // 🔥 ini fix utama
        frame.classList.remove('hidden');

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
document.addEventListener('DOMContentLoaded', function () {

    const fields = @json(array_column($dokumen, 'nama'));

    fields.forEach(field => {

        let status = document.getElementById('status_' + field).value;
        let catatan = document.getElementById('catatan_' + field);

        if (status === 'no') {
            catatan.required = true;
            catatan.style.border = '1px solid red';
        }

    });

});
document.addEventListener('DOMContentLoaded', function () {

    const fields = @json(array_column($dokumen, 'nama'));

    fields.forEach(field => {

        let status = document.getElementById('status_' + field).value;

        if (status) {
            setStatus(field, status);
        }

    });

});
</script>

@endsection