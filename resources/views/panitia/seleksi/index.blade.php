@extends('layouts.panitia')

@section('title', 'Seleksi Nilai')

@section('content')
<style>
.custom-check {
    appearance: none;
    -webkit-appearance: none;
    width: 20px;
    height: 20px;
    border: none;
    border-radius: 4px;
    background: white;
    cursor: pointer;
    position: relative;
    box-shadow: 0 0 0 0.75px #2B2A28;
}
.custom-check:checked {
    background: white;
    border: none;
    box-shadow: 0 0 0 0.75px #2B2A28;
}
.custom-check:checked::after {
    content: "";
    position: absolute;
    left: 5px;
    top: 2px;
    width: 7px;
    height: 11px;
    border: 2px solid #27C2DE;
    border-top: none;
    border-left: none;
    transform: rotate(45deg);
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.card-shadow {
    box-shadow: 0px 4px 4px 0px rgba(161, 209, 251, 0.25);
}
</style>

<div x-data="{
    showModal: false,
    modalMode: 'input',
    selectedPeserta: null,
    form: { nilaiRapor: '', nilaiPrestasi: '' },

    peserta: [
        { id:1, nama:'Ahmad Sahroni',   gelombang:'I', jalur:'Prestasi', nisn:'121731871', sekolah:'SMP 1 Makassar', nilaiRapor:70,   nilaiPrestasi:76,   nilaiTotal:72,   noPendaftaran:'12123455' },
        { id:2, nama:'Muhammad Naufal', gelombang:'I', jalur:'Prestasi', nisn:'121731871', sekolah:'SMP 1 Makassar', nilaiRapor:null, nilaiPrestasi:null, nilaiTotal:null, noPendaftaran:'12123456' },
        { id:3, nama:'Zahara Liberty',  gelombang:'I', jalur:'Prestasi', nisn:'121731871', sekolah:'SMP 1 Makassar', nilaiRapor:99,   nilaiPrestasi:87,   nilaiTotal:92,   noPendaftaran:'12123457' },
        { id:4, nama:'Zony Erikson',    gelombang:'I', jalur:'Prestasi', nisn:'121731871', sekolah:'SMP 1 Makassar', nilaiRapor:65,   nilaiPrestasi:66,   nilaiTotal:60,   noPendaftaran:'12123458' },
    ],

    get nilaiTotalCalc() {
        const r = parseFloat(this.form.nilaiRapor);
        const p = parseFloat(this.form.nilaiPrestasi);
        if (isNaN(r) || isNaN(p)) return null;
        return Math.round((r + p) / 2);
    },
    get statusCalc() {
        const t = this.nilaiTotalCalc;
        if (t === null) return '';
        if (t >= 95) return 'Lulus';
        if (t >= 76 && t <= 84) return 'Masa Sanggah';
        if (t < 73)  return 'Tidak Lulus';
        return 'Perlu Ditinjau';
    },
    get statusBgCalc() {
        const s = this.statusCalc;
        if (s === 'Lulus')        return 'background:#DCFCE7; color:#16A34A; border:1px solid #16A34A; border-radius:8px;';
        if (s === 'Masa Sanggah') return 'background:#FEF3C7; color:#D97706; border:1px solid #D97706; border-radius:8px;';
        if (s === 'Tidak Lulus')  return 'background:#FFEBEE; color:#C62828; border:1px solid #C62828; border-radius:8px;';
        return 'background:#F1F5F9; color:#64748B; border:1px solid #CBD5E1; border-radius:8px;';
    },

    getStatus(p) {
        if (p.nilaiTotal === null) return 'Belum Dinilai';
        if (p.nilaiTotal >= 95)   return 'Lulus';
        if (p.nilaiTotal >= 76 && p.nilaiTotal <= 84) return 'Masa Sanggah';
        if (p.nilaiTotal < 73)    return 'Tidak Lulus';
        return 'Perlu Ditinjau';
    },
    getStatusStyle(p) {
        const s = this.getStatus(p);
        if (s === 'Lulus')        return 'background:#DCFCE7; color:#16A34A; border:1px solid #16A34A; border-radius:4px;';
        if (s === 'Masa Sanggah') return 'background:#FEF3C7; color:#D97706; border:1px solid #D97706; border-radius:4px;';
        if (s === 'Tidak Lulus')  return 'background:#FFEBEE; color:#C62828; border:1px solid #C62828; border-radius:4px;';
        return 'background:#F1F5F9; color:#64748B; border:1px solid #CBD5E1; border-radius:4px;';
    },

    openInput(p) {
        this.selectedPeserta = p;
        this.form.nilaiRapor    = '';
        this.form.nilaiPrestasi = '';
        this.modalMode = 'input';
        this.showModal = true;
        this.$nextTick(() => this._updateSaveBtn());
    },
    openEdit(p) {
        this.selectedPeserta = p;
        this.form.nilaiRapor    = p.nilaiRapor    ?? '';
        this.form.nilaiPrestasi = p.nilaiPrestasi ?? '';
        this.modalMode = 'edit';
        this.showModal = true;
        this.$nextTick(() => this._updateSaveBtn());
    },
    openView(p) {
        this.selectedPeserta = p;
        this.form.nilaiRapor    = p.nilaiRapor    ?? '';
        this.form.nilaiPrestasi = p.nilaiPrestasi ?? '';
        this.modalMode = 'view';
        this.showModal = true;
    },
    saveNilai() {
        const idx = this.peserta.findIndex(p => p.id === this.selectedPeserta.id);
        if (idx !== -1) {
            this.peserta[idx].nilaiRapor    = parseFloat(this.form.nilaiRapor);
            this.peserta[idx].nilaiPrestasi = parseFloat(this.form.nilaiPrestasi);
            this.peserta[idx].nilaiTotal    = this.nilaiTotalCalc;
        }
        this.showModal = false;
    },

    // Dipanggil setiap kali form berubah (via x-effect)
    _updateSaveBtn() {
        const btn = document.getElementById('btnSimpanNilai');
        if (!btn) return;
        const valid = this.nilaiTotalCalc !== null;
        btn.dataset.valid = valid ? 'true' : 'false';
        // Jika valid → langsung biru tua otomatis tanpa perlu hover
        // Jika tidak valid → biru muda, hover baru berubah biru tua
        btn.style.backgroundColor = valid ? '#27C2DE' : '#C4F4FD';
        btn.style.cursor = valid ? 'pointer' : 'not-allowed';
        btn.style.opacity = '1';
    }
}">

    {{-- Pantau perubahan form secara reaktif --}}
    <span x-effect="
        // Dipicu setiap kali nilaiRapor atau nilaiPrestasi berubah
        form.nilaiRapor; form.nilaiPrestasi;
        _updateSaveBtn();
    " class="hidden"></span>

    {{-- ===== FILTER & SEARCH ===== --}}
    <div class="flex gap-3 mb-4 items-center w-full">
        <div class="relative flex-[2]">
            <input type="text" placeholder="Cari nama/NISN"
                   class="w-full pl-9 pr-4 py-2.5 text-[12px] border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#27C2DE] card-shadow"
                   style="border-radius:8px;">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <div class="relative flex-1">
            <select class="appearance-none w-full pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none card-shadow"
                    style="border-radius:8px;">
                <option>Jalur</option>
                <option>Prestasi</option>
                <option>Reguler</option>
                <option>Hafidz</option>
            </select>
            <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
        <div class="relative flex-1">
            <select class="appearance-none w-full pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none card-shadow"
                    style="border-radius:8px;">
                <option>Gelombang</option>
                <option>Gelombang I</option>
                <option>Gelombang II</option>
                <option>Gelombang III</option>
            </select>
            <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
        <div class="relative flex-1">
            <select class="appearance-none w-full pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none card-shadow"
                    style="border-radius:8px;">
                <option>Status</option>
                <option>Lulus</option>
                <option>Masa Sanggah</option>
                <option>Tidak Lulus</option>
                <option>Belum Dinilai</option>
            </select>
            <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>

    {{-- ===== TABEL UTAMA ===== --}}
    <div class="bg-white rounded-2xl overflow-hidden card-shadow">
        <div class="w-full overflow-x-auto no-scrollbar">
            <table class="w-full min-w-[1400px] border-separate border-spacing-0">
                <thead>
                    <tr style="background:#C4F4FD;">
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] sticky left-0 z-20 bg-[#C4F4FD]" style="min-width:40px;">No</th>
                        <th class="text-left py-3 px-4 text-[12px] font-semibold text-[#2B2A28] sticky left-10 z-20 bg-[#C4F4FD]" style="min-width:160px; box-shadow: 4px 0 4px 0 rgba(161,209,251,0.25);">Nama Peserta</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:100px;">Nilai Rapor</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:120px;">Nilai Prestasi</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:100px;">Nilai Total</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:130px;">Status Seleksi</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:100px;">Gelombang</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:90px;">Jalur</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:120px;">NISN</th>
                        <th class="text-left py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:140px;">Asal Sekolah</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="(p, i) in peserta" :key="p.id">
                        <tr class="hover:bg-slate-50 transition-all">
                            <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28] sticky left-0 z-10 bg-white" x-text="i+1"></td>
                            <td class="py-3 px-4 text-[12px] font-medium text-[#2B2A28] sticky left-10 z-10 bg-white" style="box-shadow: 4px 0 4px 0 rgba(161,209,251,0.25);" x-text="p.nama"></td>
                            <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28]"><span x-text="p.nilaiRapor !== null ? p.nilaiRapor : '–'"></span></td>
                            <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28]"><span x-text="p.nilaiPrestasi !== null ? p.nilaiPrestasi : '–'"></span></td>
                            <td class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]"><span x-text="p.nilaiTotal !== null ? p.nilaiTotal : '–'"></span></td>
                            <td class="text-center py-3 px-4"><span class="px-3 py-1 text-[11px] font-semibold" :style="getStatusStyle(p)" x-text="getStatus(p)"></span></td>
                            <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28]" x-text="p.gelombang"></td>
                            <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28]" x-text="p.jalur"></td>
                            <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28] font-mono" x-text="p.nisn"></td>
                            <td class="py-3 px-4 text-[12px] text-[#2B2A28]" x-text="p.sekolah"></td>
                            <td class="text-center py-3 px-4 whitespace-nowrap">
                                <template x-if="p.nilaiTotal === null">
                                    <button @click="openInput(p)"
                                            class="inline-flex items-center justify-center w-8 h-8 transition-all hover:opacity-80"
                                            style="background:#EEF9FC; border:1px solid #27C2DE; border-radius:6px;">
                                        <img src="{{ asset('ppdb/admin/operasional/icon_input.png') }}" alt="Input Nilai" class="w-4 h-4 object-contain">
                                    </button>
                                </template>
                                <template x-if="p.nilaiTotal !== null">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button @click="openView(p)"
                                                class="w-8 h-8 flex items-center justify-center transition-all hover:opacity-80"
                                                style="background:#EEF9FC; border:1px solid #27C2DE; border-radius:6px;">
                                            <img src="{{ asset('ppdb/admin/operasional/icon_view.png') }}" alt="Lihat" class="w-4 h-4 object-contain">
                                        </button>
                                        <button @click="openEdit(p)"
                                                class="w-8 h-8 flex items-center justify-center transition-all hover:opacity-80"
                                                style="background:#F0FDF4; border:1px solid #16A34A; border-radius:6px;">
                                            <img src="{{ asset('ppdb/admin/operasional/icon_edit.png') }}" alt="Edit" class="w-4 h-4 object-contain">
                                        </button>
                                    </div>
                                </template>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== KETERANGAN ===== --}}
    <div class="bg-white rounded-2xl px-5 py-4 mt-4 card-shadow">
        <p class="text-[12px] text-[#575551] mb-1.5">Status seleksi ditentukan otomatis berdasarkan ketentuan penilaian yang berlaku</p>
        <ul class="space-y-1 mb-2">
            <li class="flex items-center gap-2 text-[12px] text-[#575551]"><span>•</span> Lulus = 95</li>
            <li class="flex items-center gap-2 text-[12px] text-[#575551]"><span>•</span> Masa Sanggah = 76-84</li>
            <li class="flex items-center gap-2 text-[12px] text-[#575551]"><span>•</span> Tidak Lulus = &lt;73</li>
        </ul>
        <p class="text-[12px] text-[#575551]">Nilai Prestasi sesuai jalur pendaftaran dapat mencakup, nilai lomba, sertifikat dan hafalan Al-Qur'an</p>
    </div>

    {{-- ===== POPUP DINAMIS ===== --}}
    <template x-teleport="body">
        <div x-show="showModal" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center"
             style="background:rgba(0,0,0,0.35);">
            <div @click.outside="showModal = false"
                 class="bg-white rounded-2xl w-full max-w-sm shadow-2xl overflow-hidden"
                 x-transition>

                {{-- Header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                    <h3 class="text-[14px] font-bold text-[#2B2A28]">
                        <span x-text="modalMode === 'input' ? 'Input Nilai Seleksi' : (modalMode === 'edit' ? 'Edit Nilai' : 'Lihat Detail')"></span>
                    </h3>
                    <button @click="showModal = false"
                            class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="px-5 py-4 space-y-4">

                    {{-- No Pendaftaran --}}
                    <p class="text-[11px] text-slate-400">
                        No Pendaftaran : <span class="font-semibold text-[#2B2A28]" x-text="selectedPeserta ? selectedPeserta.noPendaftaran : ''"></span>
                    </p>

                    {{-- Info Peserta --}}
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center text-white font-bold text-lg shrink-0"
                             style="background: linear-gradient(135deg, #27C2DE 0%, #00758A 100%);">
                            <span x-text="selectedPeserta ? selectedPeserta.nama.charAt(0) : ''"></span>
                        </div>
                        <div>
                            <p class="font-bold text-[14px] text-[#2B2A28]" x-text="selectedPeserta ? selectedPeserta.nama : ''"></p>
                            <p class="text-[11px] text-slate-400 mt-0.5">NISN : <span x-text="selectedPeserta ? selectedPeserta.nisn : ''"></span></p>
                            <div class="flex gap-1.5 mt-1.5">
                                <span class="px-2 py-0.5 text-[10px] font-semibold"
                                      style="background:#EEF9FC; border:1px solid #27C2DE; border-radius:4px; color:#27C2DE;"
                                      x-text="selectedPeserta ? selectedPeserta.jalur : ''"></span>
                                <span class="px-2 py-0.5 text-[10px] font-semibold"
                                      style="background:#FFFBEB; border:1px solid #D97706; border-radius:4px; color:#D97706;"
                                      x-text="selectedPeserta ? 'Gelombang ' + selectedPeserta.gelombang : ''"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Form Nilai --}}
                    <div class="space-y-3">
                        <div class="flex items-center gap-4">
                            <label class="text-[12px] font-medium text-[#575551] shrink-0 whitespace-nowrap" style="min-width:100px;">Nilai Rapor</label>
                            <input x-model="form.nilaiRapor"
                                   :readonly="modalMode === 'view'"
                                   type="number" min="0" max="100" placeholder="–"
                                   class="flex-1 px-3 py-2 text-[13px] text-center font-semibold focus:outline-none focus:ring-2 focus:ring-[#27C2DE] transition-all bg-[#F5F7FF] border-[#DFEAF2]"
                                   style="border-radius:10px; border:1px solid #DFEAF2;">
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="text-[12px] font-medium text-[#575551] shrink-0 whitespace-nowrap" style="min-width:100px;">Nilai Prestasi</label>
                            <input x-model="form.nilaiPrestasi"
                                   :readonly="modalMode === 'view'"
                                   type="number" min="0" max="100" placeholder="–"
                                   class="flex-1 px-3 py-2 text-[13px] text-center font-semibold focus:outline-none focus:ring-2 focus:ring-[#27C2DE] transition-all bg-[#F5F7FF] border-[#DFEAF2]"
                                   style="border-radius:10px; border:1px solid #DFEAF2;">
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="text-[12px] font-medium text-[#575551] shrink-0 whitespace-nowrap" style="min-width:100px;">Nilai Total</label>
                            <div class="flex-1 px-3 py-2 text-[13px] text-center font-bold bg-slate-50 border border-slate-200"
                                 style="border-radius:8px; color:#00758A;">
                                <span x-text="nilaiTotalCalc !== null ? nilaiTotalCalc : '–'"></span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="text-[12px] font-medium text-[#575551] shrink-0 whitespace-nowrap" style="min-width:100px;">Status</label>
                            <div class="flex-1 px-3 py-2 text-[13px] text-center font-semibold transition-all"
                                 :style="nilaiTotalCalc !== null ? statusBgCalc : 'background:#F1F5F9; color:#64748B; border:1px solid #CBD5E1; border-radius:8px;'">
                                <span x-text="statusCalc || '–'"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div class="px-3 py-2.5" style="background:#F8FBFF; border:1px solid #E2E8F0; border-radius:8px;">
                        <p class="text-[10px] text-[#575551]">• Lulus = 95</p>
                        <p class="text-[10px] text-[#575551]">• Masa Sanggah = 76-84</p>
                        <p class="text-[10px] text-[#575551]">• Tidak Lulus = &lt;73</p>
                    </div>
                </div>

                {{-- Footer Buttons --}}
                <div class="px-5 pb-5 flex gap-3">
                    <button @click="showModal = false"
                            class="flex-1 py-2.5 text-[13px] font-semibold text-[#2B2A28] hover:bg-slate-50 transition-all"
                            style="border-radius:8px; border:1px solid #A5A5A5; background:#FBFAF7;">
                        Batal
                    </button>

                    {{-- Tombol Simpan dengan logika hover sama seperti FAQ --}}
                    <button x-show="modalMode !== 'view'"
                            id="btnSimpanNilai"
                            data-valid="false"
                            @click="
                                if (nilaiTotalCalc === null) {
                                    alert('Harap lengkapi nilai rapor dan prestasi terlebih dahulu!');
                                } else {
                                    saveNilai();
                                }
                            "
                            @mouseenter="
                                if ($el.dataset.valid !== 'true') {
                                    $el.style.backgroundColor = '#27C2DE';
                                }
                            "
                            @mouseleave="
                                if ($el.dataset.valid !== 'true') {
                                    $el.style.backgroundColor = '#C4F4FD';
                                }
                            "
                            class="flex-1 py-2.5 text-white text-[13px] font-semibold transition-colors duration-200"
                            style="border-radius:8px; background:#C4F4FD;">
                        <span x-text="modalMode === 'input' ? 'Simpan Nilai' : 'Simpan Perubahan'"></span>
                    </button>
                </div>

            </div>
        </div>
    </template>

</div>
@endsection