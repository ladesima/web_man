@extends('layouts.admin')

@section('title', 'Master PPDB')

@section('content')
<script>
    window.jalurs = @json($jalurs ?? []);
</script>
<div x-data="{

    view: 'jalur',
    tab: 'jadwal',

    showTambahSyarat: false,
    showEditSyarat: false,
    showHapusSyarat: false,

    editSyarat: {},
    deleteSyaratId: null,

    selectedJalurId: null,
    selectedJalurNama: null,

    showTambahJalur: false,
    showEditJalur: false,

    showHapusJalur: false,
    showHapusTahapan: false,

    showTambahJadwal: false,
    showEditTahapan: false,

    statusForm: 'aktif',
    statusEdit: 'aktif',

    deleteId: null,
    deleteTahapanId: null,

    editData: {},
    editTahapan: {},

    get selectedJalur() {
        if (!window.jalurs) return null
        return window.jalurs.find(j => j.id === this.selectedJalurId) || null
    }
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
                    @foreach($jalurs as $j)
<tr class="hover:bg-slate-50 transition-all">

    {{-- JALUR --}}
    <td class="text-center py-4 px-6 text-[13px] font-medium text-[#2B2A28]">
        {{ ucfirst($j->jalur) }}
    </td>

    {{-- STATUS --}}
    <td class="text-center py-4 px-6">
        @if($j->is_active)
            <span class="px-4 py-1 text-[11px] font-semibold"
                  style="background:#DCFCE7; color:#16A34A; border:1px solid #16A34A; border-radius:4px;">
                Aktif
            </span>
        @else
            <span class="px-4 py-1 text-[11px] font-semibold"
                  style="background:#FEE2E2; color:#EF4444; border:1px solid #EF4444; border-radius:4px;">
                Tidak Aktif
            </span>
        @endif
    </td>

    {{-- GELOMBANG --}}
    <td class="text-center py-4 px-6 text-[13px] text-[#2B2A28]">
        {{ $j->gelombang }}
    </td>

    {{-- KUOTA --}}
    <td class="text-center py-4 px-6 text-[13px] text-[#2B2A28]">
        {{ $j->kuota }}
    </td>

    {{-- JADWAL --}}
    <td class="text-center py-4 px-6 text-[12px] text-[#2B2A28]">
        @if($j->tanggal_mulai && $j->tanggal_selesai)
{{ $j->tanggal_mulai }} - {{ $j->tanggal_selesai }}
        @else
            -
        @endif
    </td>

    {{-- AKSI --}}
    <td class="text-center py-4 px-6">
        <div class="flex items-center justify-center gap-2">

            {{-- DETAIL --}}
            <button @click="
    view = 'detail';
    tab = 'jadwal';
    selectedJalurId = {{ $j->id }};
"
                    class="w-7 h-7 flex items-center justify-center">
                <img src="{{ asset('ppdb/admin/detail.png') }}" class="w-7 h-7">
            </button>

            {{-- EDIT --}}
<button 
    @click="
        showEditJalur = true;
        editData = {
            id: {{ $j->id }},
            jalur: '{{ $j->jalur }}',
            status: '{{ $j->is_active ? 'aktif' : 'tidak' }}',
            gelombang: '{{ $j->gelombang }}',
            kuota: {{ $j->kuota }},
            tanggal_mulai: '{{ $j->tanggal_mulai }}',
            tanggal_selesai: '{{ $j->tanggal_selesai }}'
        };
    "
    class="w-7 h-7 flex items-center justify-center">
    <img src="{{ asset('ppdb/admin/edit.png') }}" class="w-7 h-7">
</button>

            {{-- HAPUS --}}
            <button 
    @click="
        showHapusJalur = true;
        deleteId = {{ $j->id }};
    "
    class="w-7 h-7 flex items-center justify-center">
    <img src="{{ asset('ppdb/admin/hapus.png') }}" class="w-7 h-7">
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
    <div class="bg-white rounded-2xl p-5"
         style="box-shadow: 0px 2px 8px rgba(0,0,0,0.06);">

        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-5">

            {{-- NAMA JALUR --}}
            <h3 class="text-[16px] font-bold text-[#27C2DE]"
    x-text="selectedJalur ? selectedJalur.jalur.charAt(0).toUpperCase() + selectedJalur.jalur.slice(1) : ''">
</h3>

            {{-- BUTTON TAMBAH --}}
            <button 
    @click="showTambahJadwal = true"
    class="flex items-center justify-center gap-2 px-5 py-2.5 bg-[#27C2DE] rounded-lg text-white text-[15px] font-medium tracking-wide transition-colors hover:bg-[#22abc4]"
>
    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    Jadwal
</button>

        </div>

        {{-- LIST JADWAL --}}
        <template x-if="selectedJalur">

            <div class="grid grid-cols-2 gap-3">

                <template x-for="jd in selectedJalur.tahapans" :key="jd.id">

                    <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 overflow-hidden relative">

                        {{-- STRIP WARNA --}}
                        <div class="absolute left-0 top-0 bottom-0 w-[5px]"
                             :style="{
                                background:
                                    jd.nama_tahapan === 'Jadwal Pendaftaran' ? '#27C2DE' :
                                    jd.nama_tahapan === 'Seleksi Administrasi' ? '#27C2DE' :
                                    jd.nama_tahapan === 'Pengumuman' ? '#C6E82A' :
                                    jd.nama_tahapan === 'Daftar Ulang' ? '#FFB347' :
                                    '#27C2DE'
                             }">
                        </div>

                        {{-- KONTEN --}}
                        <div class="flex items-center gap-3 pl-3">

                            {{-- ICON --}}
                            <img 
                                :src="
                                    jd.nama_tahapan === 'Jadwal Pendaftaran' ? '/ppdb/admin/jadwalpendaftaran.png' :
                                    jd.nama_tahapan === 'Seleksi Administrasi' ? '/ppdb/admin/seleksiadminstarsi.png' :
                                    jd.nama_tahapan === 'Pengumuman' ? '/ppdb/admin/pengumuman.png' :
                                    jd.nama_tahapan === 'Daftar Ulang' ? '/ppdb/admin/daftarulang.png' :
                                    '/ppdb/admin/jadwalpendaftaran.png'
                                "
                                class="w-10 h-10 object-contain flex-shrink-0"
                            >

                            {{-- TEXT --}}
                            <div>
                                <p class="text-[12px] font-semibold text-[#2B2A28]"
                                   x-text="jd.nama_tahapan"></p>

                                <p class="text-[11px] text-slate-400">
                                    <span x-text="jd.tanggal_mulai"></span> -
                                    <span x-text="jd.tanggal_selesai"></span>
                                </p>
                            </div>

                        </div>

                        {{-- ACTION --}}
                        <div class="flex gap-1.5">

                            {{-- EDIT --}}
<button 
    @click="
        showEditTahapan = true;
        editTahapan = {
            id: jd.id,
            nama_tahapan: jd.nama_tahapan,
            tanggal_mulai: jd.tanggal_mulai,
            tanggal_selesai: jd.tanggal_selesai,
            jalur_id: selectedJalurId
        };
    "
    class="flex items-center justify-center">
    <img src="{{ asset('ppdb/admin/edit.png') }}" class="w-7 h-7">
</button>

{{-- DELETE --}}
<button 
    @click="
        showHapusTahapan = true;
        deleteTahapanId = jd.id;
    "
    class="flex items-center justify-center">
    <img src="{{ asset('ppdb/admin/hapus.png') }}" class="w-7 h-7">
</button>


                        </div>

                    </div>

                </template>

            </div>

        </template>

    </div>
</div>


        {{-- TAB PERSYARATAN --}}
        <div x-show="tab === 'persyaratan'" >
            <div class="flex justify-end mb-4">
                <button 
    @click="showTambahSyarat = true"
    class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-white text-[12px] font-semibold"
    style="background:#27C2DE;">
    
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor">
        <path stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    Tambah Syarat
</button>
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
                            <th class="text-center py-3 px-5 text-[12px] font-semibold text-[#2B2A28]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
@foreach($master->syarats as $s)
<tr class="hover:bg-slate-50 transition-all">

    <td class="py-3.5 px-5 text-[12px] text-[#2B2A28]">
        {{ $s->nama }}
    </td>

    <td class="text-center text-[12px]">
        {{ ucfirst($s->tipe) }}
    </td>

    <td class="text-center text-[12px]">
        {{ $s->format ?? '-' }}
    </td>

    <td class="text-center text-[12px]">
        {{ $s->ukuran ?? '-' }}
    </td>

    <td class="text-center text-[12px]">
        {{ ucfirst($s->kebutuhan) }}
    </td>

    {{-- 🔥 AKSI --}}
    <td class="text-center">
        <div class="flex justify-center gap-2">

            {{-- EDIT --}}
            <button 
                @click="
                    showEditSyarat = true;
                    editSyarat = {
                        id: {{ $s->id }},
                        nama: '{{ $s->nama }}',
                        tipe: '{{ $s->tipe }}',
                        format: '{{ $s->format }}',
                        ukuran: '{{ $s->ukuran }}',
                        kebutuhan: '{{ $s->kebutuhan }}',
                        master_id: {{ $master->id }}
                    };
                ">
                <img src="{{ asset('ppdb/admin/edit.png') }}" class="w-6">
            </button>

            {{-- DELETE --}}
            <button 
                @click="
                    showHapusSyarat = true;
                    deleteSyaratId = {{ $s->id }};
                ">
                <img src="{{ asset('ppdb/admin/hapus.png') }}" class="w-6">
            </button>

        </div>
    </td>

</tr>
@endforeach
</tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== MODAL TAMBAH JALUR ===== --}}
    <form method="POST" action="{{ route('admin.jalur.store') }}">
@csrf
<input type="hidden" name="master_id" value="{{ $master->id }}">

<div x-show="showTambahJalur" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center"
     style="background:rgba(0,0,0,0.35);">

    <div @click.outside="showTambahJalur = false"
         class="bg-white rounded-2xl p-8 w-full max-w-md shadow-2xl">

        {{-- TITLE --}}
        <h2 class="text-center text-[18px] font-semibold text-[#2B2A28] mb-6">
            Tambah Jalur
        </h2>

        {{-- JALUR --}}
        <div class="mb-5">
            <label class="text-[13px] text-[#2B2A28]">Jalur</label>
            <select name="jalur"
                    class="w-full mt-1 px-4 py-3 rounded-xl text-[13px] outline-none"
                    style="background:#F4F6FB;">
                <option value="prestasi">Prestasi</option>
                <option value="reguler">Reguler</option>
                <option value="afirmasi">Afirmasi</option>
            </select>
        </div>

        {{-- STATUS --}}
        <div class="mb-5">
            <label class="text-[13px] text-[#2B2A28]">Status</label>

            <div class="flex gap-3 mt-2">

                {{-- AKTIF --}}
                <label>
                    <input type="radio" name="status" value="aktif" class="hidden" x-model="statusForm">
                    <span :class="statusForm === 'aktif' ? 'bg-[#DCFCE7] text-green-600 border-green-600' : 'bg-[#F4F6FB] text-gray-400 border-gray-200'"
                          class="px-4 py-1.5 text-[12px] rounded-md border font-semibold cursor-pointer">
                        Aktif
                    </span>
                </label>

                {{-- TIDAK AKTIF --}}
                <label>
                    <input type="radio" name="status" value="tidak" class="hidden" x-model="statusForm">
                    <span :class="statusForm === 'tidak' ? 'bg-[#FEE2E2] text-red-500 border-red-500' : 'bg-[#F4F6FB] text-gray-400 border-gray-200'"
                          class="px-4 py-1.5 text-[12px] rounded-md border font-semibold cursor-pointer">
                        Tidak Aktif
                    </span>
                </label>

            </div>
        </div>

        {{-- GELOMBANG --}}
        <div class="mb-5">
            <label class="text-[13px] text-[#2B2A28]">Gelombang</label>

            <div class="relative mt-1">
                <select name="gelombang"
                        class="w-full px-4 py-3 rounded-xl text-[13px] outline-none appearance-none"
                        style="background:#F4F6FB;">
                    <option value="I">I</option>
                    <option value="II">II</option>
                    <option value="III">III</option>
                </select>

                {{-- ICON --}}
                <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>

        {{-- KUOTA --}}
        <div class="mb-5">
            <label class="text-[13px] text-[#2B2A28]">Kuota</label>
            <input type="number" name="kuota"
                   class="w-full mt-1 px-4 py-3 rounded-xl text-[13px] outline-none"
                   style="background:#F4F6FB;">
        </div>

        {{-- JADWAL --}}
<div class="mb-6">
    <label class="text-[13px] text-[#2B2A28]">Jadwal</label>

    <div class="grid grid-cols-2 gap-3 mt-1">

        {{-- TANGGAL MULAI --}}
        <div class="relative">
            <input type="date" name="tanggal_mulai"
                   class="w-full px-4 py-3 rounded-xl text-[13px] outline-none"
                   style="background:#F4F6FB;">
        </div>

        {{-- TANGGAL SELESAI --}}
        <div class="relative">
            <input type="date" name="tanggal_selesai"
                   class="w-full px-4 py-3 rounded-xl text-[13px] outline-none"
                   style="background:#F4F6FB;">
        </div>

    </div>
</div>

        {{-- BUTTON --}}
        <div class="text-center">
            <button type="submit"
                    class="px-10 py-2.5 rounded-xl text-white text-[13px] font-semibold hover:opacity-90 transition"
                    style="background:#27C2DE;">
                Simpan
            </button>
        </div>

    </div>
</div>
</form>


    {{-- ===== MODAL EDIT JALUR ===== --}}
    <form method="POST" x-ref="formEdit">
    @csrf
    @method('PUT')

<div x-show="showEditJalur" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center"
     style="background:rgba(0,0,0,0.35);">

    <div @click.outside="showEditJalur = false"
         class="bg-white rounded-2xl p-8 w-full max-w-md shadow-2xl">

        <h2 class="text-center text-[18px] font-semibold mb-6">Edit Jalur</h2>

        {{-- JALUR --}}
        <select name="jalur" x-model="editData.jalur"
            class="w-full mb-4 px-4 py-3 rounded-xl bg-[#F4F6FB]">
            <option value="prestasi">Prestasi</option>
            <option value="reguler">Reguler</option>
            <option value="afirmasi">Afirmasi</option>
        </select>

        {{-- STATUS --}}
        <div class="flex gap-3 mb-4">
            {{-- AKTIF --}}
    <label class="cursor-pointer">
        <input type="radio" name="status" value="aktif" x-model="editData.status" hidden>

        <span
            class="px-4 py-1.5 text-[12px] rounded-md border font-semibold transition-all duration-200"
            :class="editData.status === 'aktif' 
                ? 'bg-green-100 text-green-600 border-green-600 shadow-sm scale-105' 
                : 'bg-gray-100 text-gray-400 border-gray-200 hover:bg-green-50 hover:text-green-500 hover:border-green-400'">
            Aktif
        </span>
    </label>

    {{-- TIDAK AKTIF --}}
    <label class="cursor-pointer">
        <input type="radio" name="status" value="tidak" x-model="editData.status" hidden>

        <span
            class="px-4 py-1.5 text-[12px] rounded-md border font-semibold transition-all duration-200"
            :class="editData.status === 'tidak' 
                ? 'bg-red-100 text-red-500 border-red-500 shadow-sm scale-105' 
                : 'bg-gray-100 text-gray-400 border-gray-200 hover:bg-red-50 hover:text-red-500 hover:border-red-400'">
            Tidak Aktif
        </span>
    </label>
        </div>

        {{-- GELOMBANG --}}
        <select name="gelombang" x-model="editData.gelombang"
            class="w-full mb-4 px-4 py-3 rounded-xl bg-[#F4F6FB]">
            <option>I</option>
            <option>II</option>
            <option>III</option>
        </select>

        {{-- KUOTA --}}
        <input type="number" name="kuota" x-model="editData.kuota"
            class="w-full mb-4 px-4 py-3 rounded-xl bg-[#F4F6FB]">

        {{-- TANGGAL --}}
        <div class="grid grid-cols-2 gap-3 mb-4">
            <input type="date" name="tanggal_mulai" x-model="editData.tanggal_mulai"
                class="px-4 py-3 rounded-xl bg-[#F4F6FB]">

            <input type="date" name="tanggal_selesai" x-model="editData.tanggal_selesai"
                class="px-4 py-3 rounded-xl bg-[#F4F6FB]">
        </div>

        <div class="text-center">
            <button type="button"
    @click="
        $refs.formEdit.action = '/admin/jalur/' + editData.id;
        $refs.formEdit.submit();
    "
    class="px-10 py-2 bg-[#27C2DE] text-white rounded-xl">
    Simpan
</button>
        </div>

    </div>
</div>
</form>

    {{-- ===== MODAL HAPUS JALUR ===== --}}
    <form method="POST" x-ref="formDelete">
@csrf
@method('DELETE')

<div x-show="showHapusJalur" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center"
     style="background:rgba(0,0,0,0.35);">

    <div @click.outside="showHapusJalur = false"
         class="relative w-full max-w-sm" x-transition>

        <img src="{{ asset('ppdb/admin/delate.png') }}" class="w-full">

        <div class="absolute bottom-0 left-0 right-0 pb-8 px-8 text-center">

            <h3 class="text-[15px] font-bold mb-2">
                Apa anda yakin ingin menghapus data ini?
            </h3>

            <p class="text-[12px] text-slate-400 mb-5">
                Tindakan ini tidak dapat dibatalkan
            </p>

            <div class="flex gap-3 justify-center">

                <button type="button"
                        @click="showHapusJalur = false"
                        class="px-6 py-2 border rounded-xl">
                    Batal
                </button>

                <button type="button"
                        @click="
                            $refs.formDelete.action = '/admin/jalur/' + deleteId;
                            $refs.formDelete.submit();
                        "
                        class="px-6 py-2 bg-red-500 text-white rounded-xl">
                    Ya, Hapus
                </button>

            </div>
        </div>

    </div>
</div>
</form>

<form method="POST" action="{{ route('admin.tahapan.store') }}">
@csrf

<input type="hidden" name="jalur_id" x-bind:value="selectedJalurId">

<div x-show="showTambahJadwal" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center"
     style="background:rgba(0,0,0,0.35);">

    <div @click.outside="showTambahJadwal = false"
         class="bg-white rounded-2xl p-8 w-full max-w-md shadow-2xl">

        {{-- TITLE --}}
        <h2 class="text-center text-[18px] font-semibold text-[#2B2A28] mb-6">
            Tambah Timeline
        </h2>

        {{-- KEGIATAN --}}
        <div class="mb-5">
            <label class="text-[13px] text-[#2B2A28]">Kegiatan</label>

            <select name="nama_tahapan"
                    class="w-full mt-1 px-4 py-3 rounded-xl text-[13px] outline-none"
                    style="background:#F4F6FB;">

                <option value="Jadwal Pendaftaran">Jadwal Pendaftaran</option>
                <option value="Seleksi Administrasi">Seleksi Administrasi</option>
                <option value="Pengumuman">Pengumuman</option>
                <option value="Daftar Ulang">Daftar Ulang</option>
            </select>
        </div>

        {{-- TANGGAL --}}
        <div class="mb-6">
            <label class="text-[13px] text-[#2B2A28]">Jadwal</label>

            <div class="grid grid-cols-2 gap-3 mt-1">

                <input type="date" name="tanggal_mulai"
                       class="w-full px-4 py-3 rounded-xl text-[13px] outline-none"
                       style="background:#F4F6FB;">

                <input type="date" name="tanggal_selesai"
                       class="w-full px-4 py-3 rounded-xl text-[13px] outline-none"
                       style="background:#F4F6FB;">
            </div>
        </div>

        {{-- BUTTON --}}
        <div class="text-center">
            <button type="submit"
                    class="px-10 py-2.5 rounded-xl text-white text-[13px] font-semibold hover:opacity-90 transition"
                    style="background:#27C2DE;">
                Simpan
            </button>
        </div>

    </div>
</div>

</form>
<form method="POST" x-ref="formEditTahapan">
    @csrf
    @method('PUT')

<div x-show="showEditTahapan" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center"
     style="background:rgba(0,0,0,0.35);">

    <div @click.outside="showEditTahapan = false"
         class="bg-white rounded-2xl p-8 w-full max-w-md shadow-2xl">

        <h2 class="text-center text-[18px] font-semibold mb-6">
            Edit Timeline
        </h2>

        {{-- KEGIATAN --}}
        <select name="nama_tahapan" x-model="editTahapan.nama_tahapan"
                class="w-full mb-4 px-4 py-3 rounded-xl bg-[#F4F6FB]">
            <option value="Jadwal Pendaftaran">Jadwal Pendaftaran</option>
            <option value="Seleksi Administrasi">Seleksi Administrasi</option>
            <option value="Pengumuman">Pengumuman</option>
            <option value="Daftar Ulang">Daftar Ulang</option>
        </select>

        {{-- TANGGAL --}}
        <div class="grid grid-cols-2 gap-3 mb-4">
            <input type="date" name="tanggal_mulai"
                   x-model="editTahapan.tanggal_mulai"
                   class="px-4 py-3 rounded-xl bg-[#F4F6FB]">

            <input type="date" name="tanggal_selesai"
                   x-model="editTahapan.tanggal_selesai"
                   class="px-4 py-3 rounded-xl bg-[#F4F6FB]">
        </div>

        <input type="hidden" name="jalur_id" :value="editTahapan.jalur_id">

        <div class="text-center">
            <button type="button"
                @click="
                    $refs.formEditTahapan.action = '/admin/tahapan/' + editTahapan.id;
                    $refs.formEditTahapan.submit();
                "
                class="px-10 py-2 bg-[#27C2DE] text-white rounded-xl">
                Simpan
            </button>
        </div>

    </div>
</div>
</form>
<form method="POST" x-ref="formDeleteTahapan">
@csrf
@method('DELETE')

<div x-show="showHapusTahapan" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center"
     style="background:rgba(0,0,0,0.35);">

    <div class="bg-white rounded-xl p-6 text-center w-80">

        <h3 class="font-bold mb-2">Hapus Timeline?</h3>
        <p class="text-sm text-gray-400 mb-4">
            Data tidak bisa dikembalikan
        </p>

        <div class="flex gap-3 justify-center">

            <button type="button"
                    @click="showHapusTahapan = false"
                    class="px-4 py-2 border rounded">
                Batal
            </button>

            <button type="button"
                    @click="
                        $refs.formDeleteTahapan.action = '/admin/tahapan/' + deleteTahapanId;
                        $refs.formDeleteTahapan.submit();
                    "
                    class="px-4 py-2 bg-red-500 text-white rounded">
                Hapus
            </button>

        </div>

    </div>
</div>
</form>
{{-- MODAL TAMBAH SYARAT --}}
<form method="POST" action="{{ route('admin.syarat.store') }}">
@csrf

<input type="hidden" name="master_id" value="{{ $master->id }}">

<div x-show="showTambahSyarat" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">

    <div @click.outside="showTambahSyarat = false"
         class="bg-white w-full max-w-3xl rounded-2xl p-8 shadow-xl">

        {{-- HEADER --}}
        <div class="mb-6">
            <h2 class="text-[18px] font-semibold text-[#27C2DE]">
                Tambah Syarat
            </h2>
            <p class="text-[12px] text-gray-400">
                Harap, syarat yang kamu masukkan sesuai dan detail
            </p>
        </div>

        {{-- FORM GRID --}}
        <div class="grid grid-cols-2 gap-4">

            {{-- NAMA --}}
            <div>
                <label class="text-[12px]">Nama Persyaratan</label>
                <input type="text" name="nama"
                    class="w-full mt-1 px-4 py-3 rounded-xl bg-[#F4F6FB] text-[13px] outline-none">
            </div>

            {{-- UKURAN --}}
            <div>
                <label class="text-[12px]">Ukuran</label>
                <input type="text" name="ukuran"
                    class="w-full mt-1 px-4 py-3 rounded-xl bg-[#F4F6FB] text-[13px] outline-none">
            </div>

            {{-- TIPE --}}
            <div>
                <label class="text-[12px]">Tipe Input</label>
                <select name="tipe"
                    class="w-full mt-1 px-4 py-3 rounded-xl bg-[#F4F6FB] text-[13px] outline-none">
                    <option value="teks">Teks</option>
                    <option value="file">File</option>
                </select>
            </div>

            {{-- KEBUTUHAN --}}
            <div>
                <label class="text-[12px]">Kebutuhan</label>
                <select name="kebutuhan"
                    class="w-full mt-1 px-4 py-3 rounded-xl bg-[#F4F6FB] text-[13px] outline-none">
                    <option value="wajib">Wajib</option>
                    <option value="opsional">Opsional</option>
                </select>
            </div>

            {{-- FORMAT --}}
            <div>
                <label class="text-[12px]">Format File</label>
                <select name="format"
    class="w-full mt-1 px-4 py-3 rounded-xl bg-[#F4F6FB] text-[13px] outline-none">

    <option value="">-</option>
    <option value="PDF">PDF</option>
    <option value="PDF,JPG">PDF, JPG</option>
    <option value="PDF,PNG,JPG">PDF, PNG, JPG</option>

</select>
            </div>

            {{-- CATATAN --}}
            <div>
                <label class="text-[12px]">Catatan</label>
                <textarea name="catatan"
                    class="w-full mt-1 px-4 py-3 rounded-xl bg-[#F4F6FB] text-[13px] outline-none h-[80px]"></textarea>
            </div>

        </div>

        {{-- BUTTON --}}
        <div class="text-center mt-6">
            <button class="px-10 py-2.5 bg-[#27C2DE] text-white text-[13px] rounded-xl hover:bg-[#22abc4] transition">
                Simpan
            </button>
        </div>

    </div>
</div>
</form>

{{-- MODAL EDIT SYARAT --}}
<form method="POST" x-ref="formEditSyarat">
@csrf
@method('PUT')

<div x-show="showEditSyarat" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">

    <div @click.outside="showEditSyarat = false"
         class="bg-white w-full max-w-3xl rounded-2xl p-8 shadow-xl">

        {{-- HEADER --}}
        <div class="mb-6">
            <h2 class="text-[18px] font-semibold text-[#27C2DE]">
                Edit Syarat
            </h2>
        </div>

        {{-- GRID --}}
        <div class="grid grid-cols-2 gap-4">

            <input type="text" name="nama" x-model="editSyarat.nama"
                class="px-4 py-3 rounded-xl bg-[#F4F6FB] text-[13px]">

            <input type="text" name="ukuran" x-model="editSyarat.ukuran"
                class="px-4 py-3 rounded-xl bg-[#F4F6FB] text-[13px]">

            <select name="tipe" x-model="editSyarat.tipe"
                class="px-4 py-3 rounded-xl bg-[#F4F6FB] text-[13px]">
                <option value="teks">Teks</option>
                <option value="file">File</option>
            </select>

            <select name="kebutuhan" x-model="editSyarat.kebutuhan"
                class="px-4 py-3 rounded-xl bg-[#F4F6FB] text-[13px]">
                <option value="wajib">Wajib</option>
                <option value="opsional">Opsional</option>
            </select>

            <select name="format" x-model="editSyarat.format"
    class="w-full mt-1 px-4 py-3 rounded-xl bg-[#F4F6FB] text-[13px] outline-none">

    <option value="">-</option>
    <option value="PDF">PDF</option>
    <option value="PDF,JPG">PDF, JPG</option>
    <option value="PDF,PNG,JPG">PDF, PNG, JPG</option>

</select>

            <textarea name="catatan" x-model="editSyarat.catatan"
                class="px-4 py-3 rounded-xl bg-[#F4F6FB] text-[13px] h-[80px]"></textarea>

        </div>

        {{-- BUTTON --}}
        <div class="text-center mt-6">
            <button type="button"
                @click="
                    $refs.formEditSyarat.action = '/admin/syarat/' + editSyarat.id;
                    $refs.formEditSyarat.submit();
                "
                class="px-10 py-2.5 bg-[#27C2DE] text-white rounded-xl">
                Update
            </button>
        </div>

    </div>
</div>
</form>

{{-- MODAL HAPUS SYARAT --}}
<form method="POST" x-ref="formDeleteSyarat">
@csrf
@method('DELETE')

<div x-show="showHapusSyarat"
     class="fixed inset-0 z-50 flex items-center justify-center"
     style="background:rgba(0,0,0,0.35);">

    <div class="bg-white p-6 rounded-xl text-center">

        <p class="mb-4">Yakin hapus data?</p>

        <button type="button"
            @click="showHapusSyarat = false"
            class="mr-2 px-4 py-2 border">
            Batal
        </button>

        <button type="button"
            @click="
                $refs.formDeleteSyarat.action = '/admin/syarat/' + deleteSyaratId;
                $refs.formDeleteSyarat.submit();
            "
            class="px-4 py-2 bg-red-500 text-white">
            Hapus
        </button>

    </div>
</div>
</form>
</div>
@endsection