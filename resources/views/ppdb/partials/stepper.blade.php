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

<div class="w-full px-4 py-6 mb-2">
    <div class="flex items-start justify-between relative w-full">

        @foreach($steps as $i => $step)
        @php
            $stepNum    = $i + 1;
            $isDone     = $stepNum < $currentStep;
            $isActive   = $stepNum === $currentStep;
            $isLast     = $i === count($steps) - 1;
            $lineIsDone = ($stepNum + 1) <= $currentStep;
        @endphp

        <div class="relative flex flex-col items-center flex-1 min-w-0">

            {{-- Icon + Garis --}}
            <div class="relative w-full flex items-center justify-center" style="height: 56px;">

                {{-- Garis kiri --}}
                @if($i > 0)
                <div class="absolute z-0"
                     style="right: 50%; left: 0; top: 50%; transform: translateY(-50%); height: 2px;
                            background: {{ $isDone ? '#27C2DE' : 'transparent' }};
                            border-top: {{ $isDone ? 'none' : '2px dashed #27C2DE' }};">
                </div>
                @endif

                {{-- Icon --}}
                <div class="relative z-10 w-12 h-12 flex items-center justify-center rounded-full"
                     style="background: #F4F8FF; flex-shrink: 0;">
                    @if($isDone)
                        <img src="{{ asset('ppdb/ceklis.jpg') }}"
                             alt="selesai"
                             class="w-10 h-10 object-contain rounded-full">
                    @else
                        <img src="{{ asset('ppdb/' . $step['icon']) }}"
                             alt="{{ $step['label'] }}"
                             class="w-10 h-10 object-contain rounded-full"
                             style="{{ !$isActive ? 'opacity: 0.3;' : '' }}">
                    @endif
                </div>

                {{-- Garis kanan --}}
                @if(!$isLast)
                <div class="absolute z-0"
                     style="left: 50%; right: 0; top: 50%; transform: translateY(-50%); height: 2px;
                            background: {{ $lineIsDone ? '#27C2DE' : 'transparent' }};
                            border-top: {{ $lineIsDone ? 'none' : '2px dashed #27C2DE' }};">
                </div>
                @endif

            </div>

            {{-- Label --}}
            <p class="text-center font-semibold leading-tight mt-1"
               style="font-size: clamp(9px, 1vw, 11px);
                      color: {{ $isActive || $isDone ? '#27C2DE' : '#94a3b8' }};">
                {{ $step['label'] }}
            </p>

            {{-- Badge --}}
            <span class="px-2 py-0.5 rounded-full font-medium mt-0.5"
                  style="font-size: clamp(8px, 0.8vw, 10px);
                         background: {{ $isDone ? '#DCFCE7' : ($isActive ? '#E0F7FC' : '#EEF2F7') }};
                         color: {{ $isDone ? '#16a34a' : ($isActive ? '#0891b2' : '#94a3b8') }};">
                {{ $isDone ? 'Selesai' : ($isActive ? 'Proses' : 'Belum') }}
            </span>

        </div>
        @endforeach

    </div>
</div>