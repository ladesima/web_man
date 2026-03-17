@extends('layouts.admin')

@section('title', 'Master PPDB')

@section('content')
<div x-data="{
    view: 'jalur',
    tab: 'jadwal',
    showTambahJalur: false,
    showEditJalur: false,
    showHapus: false,
    statusForm: 'aktif',
    statusEdit: 'aktif',
}">

    {{-- ============================= --}}
    {{-- VIEW 1: TABEL LIST JALUR --}}
    {{-- ============================= --}}
    <div x-show="view === 'jalur'">

        <div class="flex justify-end mb-4">
            <button @click="showTambahJalur = true"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-white text-[13px] font-semibold"
                    style="background:#27C2DE;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Jalur
            </button>
        </div>

        <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow: 0px 2px 8px rgba(0,0,0,0.06);">
            <table class="w-full">
                <thead>
                    <tr style="background:#C4F4FD;">
                        <th class="text-center py-3 px-6 text-[13px] font-semibold text-[#2B2A28]">Jalur</th>
                        <th class="text-center py-3 px-6 text-[13px] font-semibold text-[#2B2A28]">Status</th>
                        <th class="text-center py-3 px-6 text-[13px] font-semibold text-[#2B2A28]">Gelombang</th>
                        <th class="text-center py-3 px-6 text-[13px] font-semibold text-[#2B2A28]">Kuota</th>
                        <th class="text-center py-3 px-6 text-[13px] font-semibold text-[#2B2A28]">Jadwal</th>
                        <th class="text-center py-3 px-6 text-[13px] font-semibold text-[#2B2A28]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php
                    $jalurs = [
                        ['jalur' => 'Prestasi', 'status' => 'aktif',       'gelombang' => 'I',  'kuota' => 300, 'jadwal' => '1 Mei – 15 Mei 2026'],
                        ['jalur' => 'Prestasi', 'status' => 'tidak_aktif', 'gelombang' => 'II', 'kuota' => 300, 'jadwal' => '1 Jun – 15 Jun 2026'],
                        ['jalur' => 'Regular',  'status' => 'tidak_aktif', 'gelombang' => 'I',  'kuota' => 300, 'jadwal' => '1 Mei – 15 Mei 2026'],
                        ['jalur' => 'Regular',  'status' => 'tidak_aktif', 'gelombang' => 'II', 'kuota' => 300, 'jadwal' => '1 Jun – 15 Jun 2026'],
                    ];
                    @endphp
                    @foreach($jalurs as $j)
                    <tr class="hover:bg-slate-50 transition-all">
                        <td class="text-center py-4 px-6 text-[13px] font-medium text-[#2B2A28]">{{ $j['jalur'] }}</td>
                        <td class="text-center py-4 px-6">
                            @if($j['status'] === 'aktif')
                                <span class="px-4 py-1 text-[11px] font-semibold"
                                      style="background:#DCFCE7; color:#16A34A; border:1px solid #16A34A; border-radius:4px;">Aktif</span>
                            @else
                                <span class="px-4 py-1 text-[11px] font-semibold"
                                      style="background:#FEE2E2; color:#EF4444; border:1px solid #EF4444; border-radius:4px;">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="text-center py-4 px-6 text-[13px] text-[#2B2A28]">{{ $j['gelombang'] }}</td>
                        <td class="text-center py-4 px-6 text-[13px] text-[#2B2A28]">{{ $j['kuota'] }}</td>
                        <td class="text-center py-4 px-6 text-[12px] text-[#2B2A28]">{{ $j['jadwal'] }}</td>
                        <td class="text-center py-4 px-6">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="view = 'detail'; tab = 'jadwal'"
                                        class="w-7 h-7 flex items-center justify-center">
                                    <img src="{{ asset('ppdb/admin/detail.png') }}" alt="detail" class="w-7 h-7 object-contain">
                                </button>
                                <button @click="showEditJalur = true"
                                        class="w-7 h-7 flex items-center justify-center">
                                    <img src="{{ asset('ppdb/admin/edit.png') }}" alt="edit" class="w-7 h-7 object-contain">
                                </button>
                                <button @click="showHapus = true"
                                        class="w-7 h-7 flex items-center justify-center">
                                    <img src="{{ asset('ppdb/admin/hapus.png') }}" alt="hapus" class="w-7 h-7 object-contain">
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ============================= --}}
    {{-- VIEW 2: DETAIL TAB --}}
    {{-- ============================= --}}
    <div x-show="view === 'detail'" x-cloak>

        <div class="flex items-center gap-2 mb-5">
            <button @click="view = 'jalur'"
                    class="w-8 h-8 rounded-full flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition-all">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <div class="flex gap-1">
                <button @click="tab = 'jadwal'"
                        :class="tab === 'jadwal' ? 'text-white font-semibold' : 'text-slate-500 border border-slate-200 bg-white'"
                        :style="tab === 'jadwal' ? 'background:#27C2DE;' : ''"
                        class="px-5 py-1.5 rounded-full text-[12px] transition-all">
                    Jadwal
                </button>
                <button @click="tab = 'persyaratan'"
                        :class="tab === 'persyaratan' ? 'text-white font-semibold' : 'text-slate-500 border border-slate-200 bg-white'"
                        :style="tab === 'persyaratan' ? 'background:#27C2DE;' : ''"
                        class="px-5 py-1.5 rounded-full text-[12px] transition-all">
                    Persyaratan
                </button>
            </div>
        </div>

        {{-- TAB JADWAL --}}
        <div x-show="tab === 'jadwal'">
            <div class="bg-white rounded-2xl p-5" style="box-shadow: 0px 2px 8px rgba(0,0,0,0.06);">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-[16px] font-bold text-[#27C2DE]">Prestasi</h3>
                    <button class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-white text-[12px] font-semibold"
                            style="background:#27C2DE;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Jadwal
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    @php
                    $jadwals = [
                        ['nama' => 'Jadwal Pendaftaran',   'tanggal' => '1 Mei – 15 Mei 2026',  'warna' => '#27C2DE', 'icon' => 'jadwalpendaftaran.png'],
                        ['nama' => 'Pengumuman',           'tanggal' => '20 Mei 2026',           'warna' => '#C6E82A', 'icon' => 'pengumuman.png'],
                        ['nama' => 'Seleksi Administrasi', 'tanggal' => '15 Mei – 20 Mei 2026', 'warna' => '#27C2DE', 'icon' => 'seleksiadminstarsi.png'],
                        ['nama' => 'Daftar Ulang',         'tanggal' => '21 Mei – 28 Mei 2026', 'warna' => '#FFB347', 'icon' => 'daftarulang.png'],
                    ];
                    @endphp
                    @foreach($jadwals as $jd)
                    <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 overflow-hidden relative">
                        <div class="absolute left-0 top-0 bottom-0 w-[5px]"
                             style="background:{{ $jd['warna'] }};"></div>
                        <div class="flex items-center gap-3 pl-3">
                            <img src="{{ asset('ppdb/admin/' . $jd['icon']) }}" alt=""
                                 class="w-10 h-10 object-contain flex-shrink-0">
                            <div>
                                <p class="text-[12px] font-semibold text-[#2B2A28]">{{ $jd['nama'] }}</p>
                                <p class="text-[11px] text-slate-400">{{ $jd['tanggal'] }}</p>
                            </div>
                        </div>
                        <div class="flex gap-1.5">
                            <button class="flex items-center justify-center">
                                <img src="{{ asset('ppdb/admin/edit.png') }}" alt="edit" class="w-7 h-7 object-contain">
                            </button>
                            <button @click="showHapus = true"
                                    class="flex items-center justify-center">
                                <img src="{{ asset('ppdb/admin/hapus.png') }}" alt="hapus" class="w-7 h-7 object-contain">
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- TAB PERSYARATAN --}}
        <div x-show="tab === 'persyaratan'" x-cloak>
            <div class="flex justify-end mb-4">
                <a href="{{ route('admin.master.tambah-syarat', $tahun) }}"
                   class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-white text-[12px] font-semibold"
                   style="background:#27C2DE;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Syarat
                </a>
            </div>
            <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow: 0px 2px 8px rgba(0,0,0,0.06);">
                <table class="w-full">
                    <thead>
                        <tr style="background:#C4F4FD;">
                            <th class="text-left py-3 px-5 text-[12px] font-semibold text-[#2B2A28]">Nama Persyaratan</th>
                            <th class="text-center py-3 px-5 text-[12px] font-semibold text-[#2B2A28]">Tipe Input</th>
                            <th class="text-center py-3 px-5 text-[12px] font-semibold text-[#2B2A28]">Format File</th>
                            <th class="text-center py-3 px-5 text-[12px] font-semibold text-[#2B2A28]">Ukuran</th>
                            <th class="text-center py-3 px-5 text-[12px] font-semibold text-[#2B2A28]">Kebutuhan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php
                        $syarats = [
                            ['nama' => 'Nama Lengkap',                    'tipe' => 'Teks', 'format' => '-',           'ukuran' => '30 Karakter', 'kebutuhan' => 'Wajib'],
                            ['nama' => 'NISN',                            'tipe' => 'Teks', 'format' => '-',           'ukuran' => '16 Karakter', 'kebutuhan' => 'Wajib'],
                            ['nama' => 'Akta lahir',                      'tipe' => 'File', 'format' => 'PDF,JPG,PNG', 'ukuran' => '2 Mb',        'kebutuhan' => 'Wajib'],
                            ['nama' => 'Sertifikat Juara (Kab,Prov,Nas)', 'tipe' => 'File', 'format' => 'PDF,JPG,PNG', 'ukuran' => '2 Mb',        'kebutuhan' => 'Opsional'],
                        ];
                        @endphp
                        @foreach($syarats as $s)
                        <tr class="hover:bg-slate-50 transition-all">
                            <td class="py-3.5 px-5 text-[12px] text-[#2B2A28]">{{ $s['nama'] }}</td>
                            <td class="text-center py-3.5 px-5 text-[12px] text-[#2B2A28]">{{ $s['tipe'] }}</td>
                            <td class="text-center py-3.5 px-5 text-[12px] text-[#2B2A28]">{{ $s['format'] }}</td>
                            <td class="text-center py-3.5 px-5 text-[12px] text-[#2B2A28]">{{ $s['ukuran'] }}</td>
                            <td class="text-center py-3.5 px-5 text-[12px] text-[#2B2A28]">{{ $s['kebutuhan'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== MODAL TAMBAH JALUR ===== --}}
    <div x-show="showTambahJalur" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center"
         style="background:rgba(0,0,0,0.35);">
        <div @click.outside="showTambahJalur = false"
             class="bg-white rounded-2xl p-8 w-full max-w-sm shadow-2xl" x-transition>
            <h2 class="text-[16px] font-bold text-[#2B2A28] text-center mb-6">Tambah Jalur</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-[12px] font-medium text-[#2B2A28] mb-1.5">Jalur</label>
                    <input type="text" class="w-full rounded-lg px-3 py-2.5 text-[13px] border-0 focus:outline-none focus:ring-2 focus:ring-[#27C2DE]" style="background:#F5F7FF;">
                </div>
                <div>
                    <label class="block text-[12px] font-medium text-[#2B2A28] mb-1.5">Status</label>
                    <div class="flex gap-2">
                        <button type="button" @click="statusForm = 'aktif'"
                                :style="statusForm === 'aktif' ? 'background:#DCFCE7; color:#16A34A; border:1px solid #16A34A;' : 'background:#F5F7FF; color:#aaa; border:1px solid #e2e8f0;'"
                                style="border-radius:4px;" class="px-5 py-1.5 text-[12px] font-semibold transition-all">Aktif</button>
                        <button type="button" @click="statusForm = 'tidak'"
                                :style="statusForm === 'tidak' ? 'background:#FEE2E2; color:#EF4444; border:1px solid #EF4444;' : 'background:#F5F7FF; color:#aaa; border:1px solid #e2e8f0;'"
                                style="border-radius:4px;" class="px-5 py-1.5 text-[12px] font-semibold transition-all">Tidak Aktif</button>
                    </div>
                </div>
                <div>
                    <label class="block text-[12px] font-medium text-[#2B2A28] mb-1.5">Gelombang</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg px-3 py-2.5 text-[13px] border-0 focus:outline-none focus:ring-2 focus:ring-[#27C2DE]" style="background:#F5F7FF;">
                            <option value=""></option>
                            <option>I</option>
                            <option>II</option>
                        </select>
                        <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <label class="block text-[12px] font-medium text-[#2B2A28] mb-1.5">Kuota</label>
                    <input type="number" class="w-full rounded-lg px-3 py-2.5 text-[13px] border-0 focus:outline-none focus:ring-2 focus:ring-[#27C2DE]" style="background:#F5F7FF;">
                </div>
                <div>
                    <label class="block text-[12px] font-medium text-[#2B2A28] mb-1.5">Jadwal</label>
                    <div class="relative">
                        <input type="text" placeholder="Pilih tanggal"
                               class="w-full rounded-lg px-3 py-2.5 pr-10 text-[13px] border-0 focus:outline-none focus:ring-2 focus:ring-[#27C2DE]" style="background:#F5F7FF;">
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex justify-center pt-2">
                    <button class="px-10 py-2.5 rounded-xl text-white text-[13px] font-semibold" style="background:#27C2DE;">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MODAL EDIT JALUR ===== --}}
    <div x-show="showEditJalur" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center"
         style="background:rgba(0,0,0,0.35);">
        <div @click.outside="showEditJalur = false"
             class="bg-white rounded-2xl p-8 w-full max-w-sm shadow-2xl" x-transition>
            <h2 class="text-[16px] font-bold text-[#2B2A28] text-center mb-6">Edit Jalur</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-[12px] font-medium text-[#2B2A28] mb-1.5">Jalur</label>
                    <input type="text" value="Prestasi" class="w-full rounded-lg px-3 py-2.5 text-[13px] border-0 focus:outline-none focus:ring-2 focus:ring-[#27C2DE]" style="background:#F5F7FF;">
                </div>
                <div>
                    <label class="block text-[12px] font-medium text-[#2B2A28] mb-1.5">Status</label>
                    <div class="flex gap-2">
                        <button type="button" @click="statusEdit = 'aktif'"
                                :style="statusEdit === 'aktif' ? 'background:#DCFCE7; color:#16A34A; border:1px solid #16A34A;' : 'background:#F5F7FF; color:#aaa; border:1px solid #e2e8f0;'"
                                style="border-radius:4px;" class="px-5 py-1.5 text-[12px] font-semibold transition-all">Aktif</button>
                        <button type="button" @click="statusEdit = 'tidak'"
                                :style="statusEdit === 'tidak' ? 'background:#FEE2E2; color:#EF4444; border:1px solid #EF4444;' : 'background:#F5F7FF; color:#aaa; border:1px solid #e2e8f0;'"
                                style="border-radius:4px;" class="px-5 py-1.5 text-[12px] font-semibold transition-all">Tidak Aktif</button>
                    </div>
                </div>
                <div>
                    <label class="block text-[12px] font-medium text-[#2B2A28] mb-1.5">Gelombang</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg px-3 py-2.5 text-[13px] border-0 focus:outline-none focus:ring-2 focus:ring-[#27C2DE]" style="background:#F5F7FF;">
                            <option selected>I</option>
                            <option>II</option>
                        </select>
                        <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <label class="block text-[12px] font-medium text-[#2B2A28] mb-1.5">Kuota</label>
                    <input type="number" value="300" class="w-full rounded-lg px-3 py-2.5 text-[13px] border-0 focus:outline-none focus:ring-2 focus:ring-[#27C2DE]" style="background:#F5F7FF;">
                </div>
                <div>
                    <label class="block text-[12px] font-medium text-[#2B2A28] mb-1.5">Jadwal</label>
                    <div class="relative">
                        <input type="text" value="1 Mei- 15 Mei 2026"
                               class="w-full rounded-lg px-3 py-2.5 pr-10 text-[13px] border-0 focus:outline-none focus:ring-2 focus:ring-[#27C2DE]" style="background:#F5F7FF;">
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex justify-center pt-2">
                    <button class="px-10 py-2.5 rounded-xl text-white text-[13px] font-semibold" style="background:#27C2DE;">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MODAL HAPUS ===== --}}
    <div x-show="showHapus" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center"
         style="background:rgba(0,0,0,0.35);">
        <div @click.outside="showHapus = false"
             class="relative w-full max-w-sm" x-transition>
            {{-- Gambar sebagai background --}}
            <img src="{{ asset('ppdb/admin/delate.png') }}" alt="" class="w-full">
            {{-- Teks & button di atas gambar --}}
            <div class="absolute bottom-0 left-0 right-0 pb-8 px-8 text-center">
                <h3 class="text-[15px] font-bold text-[#2B2A28] mb-2">
                    Apa anda yakin ingin menghapus data ini?
                </h3>
                <p class="text-[12px] text-slate-400 mb-5">
                    Tindakan ini tidak dapat dibatalkan dan data akan dihapus secara permanen.
                </p>
                <div class="flex gap-3 justify-center">
                    <button @click="showHapus = false"
                            class="px-6 py-2 rounded-xl border border-slate-300 text-[13px] font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                        Batal
                    </button>
                    <button class="px-6 py-2 rounded-xl text-white text-[13px] font-semibold bg-red-500 hover:bg-red-600 transition-all">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection