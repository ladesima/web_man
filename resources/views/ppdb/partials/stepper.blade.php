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

<div class="max-w-5xl mx-auto flex items-start justify-between relative px-4 py-6 mb-2">

    @foreach($steps as $i => $step)
    @php
        $stepNum  = $i + 1;
        $isDone   = $stepNum < $currentStep;
        $isActive = $stepNum === $currentStep;
        $isLast   = $i === count($steps) - 1;

        // Garis setelah step ini: selesai kalau step berikutnya sudah done atau active
        $lineIsDone = ($stepNum + 1) <= $currentStep;
    @endphp

    <div class="relative z-10 flex flex-col items-center gap-1.5" style="flex: 1; min-width: 0;">

        {{-- Icon + Garis kanan --}}
        <div class="relative w-full flex items-center justify-center" style="height: 56px;">

            {{-- Icon --}}
            <div class="relative z-10 w-14 h-14 flex items-center justify-center rounded-full bg-[#F4F8FF]">
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

            {{-- Garis ke kanan (kecuali step terakhir) --}}
            @if(!$isLast)
            <div class="absolute z-0"
                 style="left: calc(50% + 28px);
                        right: calc(-50% + 28px);
                        top: 50%;
                        transform: translateY(-50%);
                        height: 2px;
                        background: {{ $lineIsDone ? '#27C2DE' : 'transparent' }};
                        border-top: {{ $lineIsDone ? 'none' : '2px dashed #27C2DE' }};">
            </div>
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