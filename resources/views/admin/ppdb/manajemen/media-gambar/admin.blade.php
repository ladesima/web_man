@extends('layouts.admin')
@section('title', 'Media Gambar - Admin')
@section('content')

{{-- Breadcrumb Tabs --}}
<div class="flex items-center gap-1 mb-6">
    <a href="{{ route('admin.manajemen.media-gambar') }}"
       class="px-4 py-1.5 rounded-full text-[12px] font-medium text-slate-500 hover:bg-slate-100 transition">
        Beranda
    </a>
    <span class="text-slate-300 text-sm">›</span>
    <a href="{{ route('admin.manajemen.media-gambar') }}"
       class="px-4 py-1.5 rounded-full text-[12px] font-medium text-slate-500 hover:bg-slate-100 transition">
        Media Gambar
    </a>
    <span class="text-slate-300 text-sm">›</span>
    <span class="px-4 py-1.5 rounded-full text-[12px] font-semibold text-white" style="background:#27C2DE;">
        Admin
    </span>
</div>

<div class="bg-white rounded-2xl p-8" style="box-shadow:0 2px 12px rgba(0,0,0,0.07);">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        @php
        $items = [
            ['name' => 'admin_login', 'label' => 'Registrasi/Login', 'ukuran' => '383x486'],
        ];
        @endphp

        @foreach($items as $item)
        <div>
            <label class="block text-[13px] font-semibold text-[#2B2A28] mb-1">
                {{ $item['label'] }}
                <span class="font-normal text-[11px] text-slate-400">*ukuran {{ $item['ukuran'] }}</span>
            </label>

            <label id="box-{{ $item['name'] }}"
                   class="flex flex-col items-center justify-center w-full cursor-pointer rounded-xl transition-all"
                   style="border: 2px dashed rgba(39,194,222,0.5); height: 130px; background: #F0FBFD;">

                <div id="idle-{{ $item['name'] }}" class="flex flex-col items-center">
                    {{-- Ganti SVG dengan gambar upload.png --}}
                    <img src="{{ asset('ppdb/admin/operasional/upload.png') }}" class="w-9 h-9 mb-1" alt="Upload">
                    <span class="text-[12px] font-semibold" style="color:#27C2DE;">Upload</span>
                    <span class="text-[10px] text-slate-400">File JPG, JPEG, PNG Max 5Mb</span>
                </div>

                <div id="done-{{ $item['name'] }}" class="hidden flex-col items-center">
                    {{-- Ganti SVG dengan gambar ceklis.png --}}
                    <img src="{{ asset('ppdb/admin/operasional/ceklis.png') }}" class="w-9 h-9 mb-1" alt="Check">
                    <span id="name-{{ $item['name'] }}" class="text-[11px] font-semibold text-center px-2 truncate max-w-[160px]" style="color:#27C2DE;"></span>
                    <span class="text-[10px] text-slate-400">Unggah file lain</span>
                </div>

                <input type="file" class="hidden media-input" data-name="{{ $item['name'] }}"
                       accept=".jpg,.jpeg,.png">
            </label>
        </div>
        @endforeach

    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.media-input').forEach(input => {
    input.addEventListener('change', function () {
        const name = this.dataset.name;
        const file = this.files[0];
        if (!file) return;
        document.getElementById(`idle-${name}`).classList.add('hidden');
        const done = document.getElementById(`done-${name}`);
        done.classList.remove('hidden');
        done.classList.add('flex');
        document.getElementById(`name-${name}`).textContent = file.name;
        const box = document.getElementById(`box-${name}`);
        box.style.border = '2px solid #27C2DE';
        box.style.background = '#E8FAFB';
    });
});
</script>
@endpush

@endsection