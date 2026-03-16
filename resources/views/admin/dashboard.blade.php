@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

{{-- ===================== STAT CARDS ===================== --}}
<div class="grid grid-cols-4 gap-4 mb-6">

    {{-- 1. Total Pendaftar --}}
    <div class="stat-card relative rounded-2xl px-4 py-3 transition-all duration-300 cursor-pointer overflow-hidden"
         style="background: linear-gradient(to bottom left, rgba(250,254,255,1) 0%, rgba(201,244,255,1) 100%);
                border: 1px solid rgba(243,243,243,1);
                box-shadow: 0px 4px 4px rgba(161,209,251,0.25);"
         onmouseover="this.style.background='linear-gradient(to bottom left, rgba(250,254,255,1) 0%, rgba(62,216,255,1) 100%)'"
         onmouseout="this.style.background='linear-gradient(to bottom left, rgba(250,254,255,1) 0%, rgba(201,244,255,1) 100%)'">
        <div class="flex items-start justify-between mb-1">
            <p class="text-[12px] font-medium text-slate-400">Total Pendaftar</p>
            <img src="{{ asset('ppdb/admin/totalpendaftar.png') }}" alt="" class="w-8 h-8 object-contain">
        </div>
        <p class="text-[28px] font-bold text-[#2B2A28] leading-none mb-2">1298</p>
        <span class="text-[10px] font-semibold text-[#2B2A28] px-3 py-[4px] rounded-full"
              style="background: linear-gradient(90deg, rgba(252,255,203,1) 0%, rgba(229,241,7,1) 100%);">
            +32 dari kemarin
        </span>
    </div>

    {{-- 2. Pendaftar Hari Ini --}}
    <div class="stat-card relative rounded-2xl px-4 py-3 transition-all duration-300 cursor-pointer overflow-hidden"
         style="background: linear-gradient(to bottom left, rgba(255,254,250,1) 0%, rgba(255,212,192,1) 100%);
                border: 1px solid rgba(243,243,243,1);
                box-shadow: 0px 4px 4px rgba(161,209,251,0.25);"
         onmouseover="this.style.background='linear-gradient(to bottom left, rgba(255,254,250,1) 0%, rgba(255,150,100,1) 100%)'"
         onmouseout="this.style.background='linear-gradient(to bottom left, rgba(255,254,250,1) 0%, rgba(255,212,192,1) 100%)'">
        <div class="flex items-start justify-between mb-1">
            <p class="text-[12px] font-medium text-slate-400">Pendaftar Hari ini</p>
            <img src="{{ asset('ppdb/admin/pendaftarhariini.png') }}" alt="" class="w-8 h-8 object-contain">
        </div>
        <p class="text-[28px] font-bold text-[#2B2A28] leading-none mb-2">234</p>
        <span class="text-[10px] font-semibold text-[#2B2A28] px-3 py-[4px] rounded-full"
              style="background: linear-gradient(90deg, rgba(252,255,203,1) 0%, rgba(229,241,7,1) 100%);">
            -12 dari kemarin
        </span>
    </div>

    {{-- 3. Jalur Aktif --}}
    <div class="stat-card relative rounded-2xl px-4 py-3 transition-all duration-300 cursor-pointer overflow-hidden"
         style="background: linear-gradient(to bottom left, rgba(250,254,255,1) 0%, rgba(192,215,247,1) 100%);
                border: 1px solid rgba(243,243,243,1);
                box-shadow: 0px 4px 4px rgba(161,209,251,0.25);"
         onmouseover="this.style.background='linear-gradient(to bottom left, rgba(250,254,255,1) 0%, rgba(100,160,230,1) 100%)'"
         onmouseout="this.style.background='linear-gradient(to bottom left, rgba(250,254,255,1) 0%, rgba(192,215,247,1) 100%)'">
        <div class="flex items-start justify-between mb-1">
            <p class="text-[12px] font-medium text-slate-400">Jalur Aktif</p>
            <img src="{{ asset('ppdb/admin/jaluraktif.png') }}" alt="" class="w-8 h-8 object-contain">
        </div>
        <p class="text-[18px] font-bold text-[#2B2A28] leading-none mb-2">Prestasi - Gel 1</p>
        <span class="text-[10px] font-semibold text-[#2B2A28] px-3 py-[4px] rounded-full"
              style="background: linear-gradient(90deg, rgba(252,255,203,1) 0%, rgba(229,241,7,1) 100%);">
            12 April - 23 Mei 2026
        </span>
    </div>

    {{-- 4. Perlu Verifikasi --}}
    <div class="stat-card relative rounded-2xl px-4 py-3 transition-all duration-300 cursor-pointer overflow-hidden"
         style="background: linear-gradient(to bottom left, rgba(255,255,255,1) 0%, rgba(255,145,250,1) 100%);
                border: 1px solid rgba(243,243,243,1);
                box-shadow: 0px 4px 4px rgba(161,209,251,0.25);"
         onmouseover="this.style.background='linear-gradient(to bottom left, rgba(255,255,255,1) 0%, rgba(220,60,220,1) 100%)'"
         onmouseout="this.style.background='linear-gradient(to bottom left, rgba(255,255,255,1) 0%, rgba(255,145,250,1) 100%)'">
        <div class="flex items-start justify-between mb-1">
            <p class="text-[12px] font-medium text-slate-400">Perlu Verifikasi</p>
            <img src="{{ asset('ppdb/admin/perluverifikasi.png') }}" alt="" class="w-8 h-8 object-contain">
        </div>
        <p class="text-[28px] font-bold text-[#2B2A28] leading-none mb-2">87</p>
        <span class="text-[10px] font-semibold text-[#2B2A28] px-3 py-[4px] rounded-full"
              style="background: linear-gradient(90deg, rgba(252,255,203,1) 0%, rgba(229,241,7,1) 100%);">
            Berakhir dalam 14 Hari
        </span>
    </div>

</div>

{{-- ===================== CHARTS ===================== --}}
<div class="grid grid-cols-3 gap-4">

    {{-- Grafik Pendaftar --}}
    <div class="col-span-2 bg-white rounded-2xl p-5"
         style="box-shadow: 0px 2px 8px rgba(0,0,0,0.06);">

        <div class="flex items-start justify-between mb-2">
            <div>
                <h2 class="text-[15px] font-bold text-[#2B2A28]">Grafik Pendaftar</h2>
                <div class="flex gap-3 mt-1">
                    <span class="flex items-center gap-1 text-[10px] text-slate-500">
                        <span class="w-2.5 h-2.5 rounded-sm inline-block" style="background:rgba(186,197,0,1)"></span> Prestasi
                    </span>
                    <span class="flex items-center gap-1 text-[10px] text-slate-500">
                        <span class="w-2.5 h-2.5 rounded-sm inline-block" style="background:rgba(248,146,51,1)"></span> Regular
                    </span>
                    <span class="flex items-center gap-1 text-[10px] text-slate-500">
                        <span class="w-2.5 h-2.5 rounded-sm inline-block" style="background:rgba(0,141,166,1)"></span> Afirmasi
                    </span>
                </div>
            </div>
            <div class="flex gap-2">
                <div class="relative">
                    <select class="appearance-none text-[12px] border border-slate-200 rounded-lg pl-3 pr-7 py-1.5 text-slate-600 focus:outline-none cursor-pointer"
                            style="background:rgba(242,253,255,1);">
                        <option>Jalur</option>
                        <option>Prestasi</option>
                        <option>Reguler</option>
                        <option>Afirmasi</option>
                    </select>
                    <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2" width="8" height="5" viewBox="0 0 10 6">
                        <polygon points="0,0 10,0 5,6" fill="rgba(1,188,221,1)"/>
                    </svg>
                </div>
                <div class="relative">
                    <select class="appearance-none text-[12px] border border-slate-200 rounded-lg pl-3 pr-7 py-1.5 text-slate-600 focus:outline-none cursor-pointer"
                            style="background:rgba(242,253,255,1);">
                        <option>Gelombang</option>
                        <option>Gelombang 1</option>
                        <option>Gelombang 2</option>
                    </select>
                    <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2" width="8" height="5" viewBox="0 0 10 6">
                        <polygon points="0,0 10,0 5,6" fill="rgba(1,188,221,1)"/>
                    </svg>
                </div>
                <div class="relative">
                    <select class="appearance-none text-[12px] border border-slate-200 rounded-lg pl-3 pr-7 py-1.5 text-slate-600 focus:outline-none cursor-pointer"
                            style="background:rgba(242,253,255,1);">
                        <option>Waktu</option>
                        <option>Minggu ini</option>
                        <option>Bulan ini</option>
                    </select>
                    <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2" width="8" height="5" viewBox="0 0 10 6">
                        <polygon points="0,0 10,0 5,6" fill="rgba(1,188,221,1)"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="relative h-[250px]">
            <canvas id="grafikPendaftar"></canvas>
        </div>
    </div>

    {{-- Statistik Jalur --}}
    <div class="bg-white rounded-2xl p-5"
         style="box-shadow: 0px 2px 8px rgba(0,0,0,0.06);">

        <h2 class="text-[15px] font-bold text-[#2B2A28] mb-4">Statistik Jalur</h2>

        <div class="flex items-center gap-4">
            {{-- Donut kiri --}}
            <div class="relative w-[130px] h-[130px] flex-shrink-0">
                <canvas id="statistikJalur"></canvas>
            </div>
            {{-- Legend kanan --}}
            <div class="flex flex-col gap-2">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full" style="background:rgba(145,233,249,1)"></div>
                    <span class="text-[12px] text-slate-600">Prestasi : 345</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full" style="background:rgba(0,135,159,1)"></div>
                    <span class="text-[12px] text-slate-600">Regular : 23</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full" style="background:rgba(39,194,222,1)"></div>
                    <span class="text-[12px] text-slate-600">Afirmasi : 129</span>
                </div>
            </div>
        </div>

        <div class="mt-4 w-full rounded-xl p-3" style="background:rgba(255,212,192,0.35);">
            <p class="text-[12px] text-slate-600">Total Pendaftar : <span class="font-bold">497</span></p>
            <p class="text-[12px] text-slate-600">Kuota : <span class="font-bold">1000</span></p>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ====== Bar Chart Grouped ======
    const ctx = document.getElementById('grafikPendaftar').getContext('2d');

    // Gradient Prestasi (kuning-hijau)
    const gradPrestasi = ctx.createLinearGradient(0, 0, 0, 250);
    gradPrestasi.addColorStop(0, 'rgba(244,249,161,1)');
    gradPrestasi.addColorStop(1, 'rgba(186,197,0,1)');

    // Gradient Regular (oranye)
    const gradRegular = ctx.createLinearGradient(0, 0, 0, 250);
    gradRegular.addColorStop(0, 'rgba(255,198,145,1)');
    gradRegular.addColorStop(1, 'rgba(248,146,51,1)');

    // Gradient Afirmasi (cyan)
    const gradAfirmasi = ctx.createLinearGradient(0, 0, 0, 250);
    gradAfirmasi.addColorStop(0, 'rgba(63,226,255,1)');
    gradAfirmasi.addColorStop(1, 'rgba(0,141,166,1)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Ahad'],
            datasets: [
                {
                    label: 'Prestasi',
                    data: [80, 90, 110, 95, 85, 75, 88],
                    backgroundColor: gradPrestasi,
                    borderWidth: 0,
                    borderRadius: 0,
                    barPercentage: 0.8,
                    categoryPercentage: 0.75,
                },
                {
                    label: 'Regular',
                    data: [60, 70, 80, 75, 65, 55, 70],
                    backgroundColor: gradRegular,
                    borderWidth: 0,
                    borderRadius: 0,
                    barPercentage: 0.8,
                    categoryPercentage: 0.75,
                },
                {
                    label: 'Afirmasi',
                    data: [100, 110, 130, 120, 105, 95, 115],
                    backgroundColor: gradAfirmasi,
                    borderWidth: 0,
                    borderRadius: 0,
                    barPercentage: 0.8,
                    categoryPercentage: 0.75,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#2B2A28',
                    bodyColor: '#64748b',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    padding: 10,
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 }, color: '#94a3b8' }
                },
                y: {
                    display: false,
                    grid: { display: false },
                }
            }
        }
    });

    // ====== Donut Chart ======
    const ctx2 = document.getElementById('statistikJalur').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Reguler', 'Afirmasi', 'Prestasi'],
            datasets: [{
                data: [23, 129, 345],
                backgroundColor: [
                    'rgba(0,135,159,1)',
                    'rgba(39,194,222,1)',
                    'rgba(145,233,249,1)',
                ],
                borderWidth: 0,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#2B2A28',
                    bodyColor: '#64748b',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                }
            }
        }
    });
</script>
@endpush