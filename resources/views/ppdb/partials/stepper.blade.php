@php
$currentStep = $currentStep ?? 1;
$steps = [
    ['label' => 'Isi Formulir',    'icon' => 'isiformulir.jpg'],
    ['label' => 'Upload Berkas',   'icon' => 'uploadberkas.jpg'],
    ['label' => 'Verifikasi Data', 'icon' => 'verivdata.jpg'],
    ['label' => 'Pengumuman',      'icon' => 'pengumuman.jpg'],
    ['label' => 'Daftar Ulang',    'icon' => 'daftarulang.jpg'],
    ['label' => 'Final',           'icon' => 'final.jpg'],
];
@endphp

{{-- STEPPER dengan max-width agar tidak melebar --}}
<div class="max-w-5xl mx-auto flex items-start justify-between relative px-4 py-6 mb-2">

    {{-- Garis penghubung --}}
    <div class="absolute z-0" style="top: 55px; left: 70px; right: 70px;">
        <div style="width:100%; border-top: 2px dashed #27C2DE;"></div>
    </div>

    @foreach($steps as $i => $step)
    @php
        $stepNum  = $i + 1;
        $isDone   = $stepNum < $currentStep;
        $isActive = $stepNum === $currentStep;
    @endphp

    <div class="relative z-10 flex flex-col items-center gap-1.5" style="width: 80px;">

        {{-- Icon --}}
        <div class="w-14 h-14 flex items-center justify-center rounded-full bg-[#F4F8FF]">
            @if($isDone)
                <img src="{{ asset('ppdb/ceklis.jpg') }}"
                     alt="selesai"
                     class="w-12 h-12 object-contain rounded-full">
            @else
                <img src="{{ asset('ppdb/' . $step['icon']) }}"
                     alt="{{ $step['label'] }}"
                     class="w-12 h-12 object-contain rounded-full"
                     style="{{ !$isActive ? 'opacity: 0.3;' : '' }}">
            @endif
        </div>

        {{-- Label --}}
        <p class="text-center text-[11px] font-semibold leading-tight"
           style="color: {{ $isActive || $isDone ? '#27C2DE' : '#94a3b8' }};">
            {{ $step['label'] }}
        </p>

        {{-- Badge --}}
        <span class="text-[9px] px-2 py-0.5 rounded-full font-medium"
              style="background: {{ $isDone ? '#DCFCE7' : ($isActive ? '#E0F7FC' : '#EEF2F7') }};
                     color: {{ $isDone ? '#16a34a' : ($isActive ? '#0891b2' : '#94a3b8') }};">
            {{ $isDone ? 'Selesai' : ($isActive ? 'Proses' : 'Belum') }}
        </span>

    </div>
    @endforeach

</div>