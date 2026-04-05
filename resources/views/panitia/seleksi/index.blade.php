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
    // =========================
    // STATE
    // =========================
    showModal: false,
    modalMode: 'input',
    selectedPeserta: null,
    search: '',
filterJalur: '',
filterGelombang: '',
filterStatus: '',

    form: {
        nilaiRapor: '',
        nilaiPrestasi: ''
    },

    // =========================
    // DATA DARI BACKEND
    // =========================
    peserta: @js($pesertas),

    // =========================
    // HITUNG NILAI
    // =========================
    get nilaiTotalCalc() {
    const r = parseFloat(this.form.nilaiRapor);
    const p = parseFloat(this.form.nilaiPrestasi);

    if (this.selectedPeserta?.jalur === 'prestasi') {
        if (isNaN(r) || isNaN(p)) return null;
        return Math.round((r + p) / 2);
    }

    // 🔥 REGULER & AFIRMASI
    if (isNaN(r)) return null;
    return r;
},

    // =========================
    // STATUS (MODAL)
    // =========================
    get statusCalc() {
        const t = this.nilaiTotalCalc;

        if (t >= 80) return 'Lulus';
if (t >= 75 && t <= 79) return 'Memenuhi Syarat';
return 'Tidak Lulus';
    },

    get statusBgCalc() {
        const s = this.statusCalc;

        if (s === 'Lulus')
            return 'background:#DCFCE7; color:#16A34A; border:1px solid #16A34A; border-radius:8px;';
        if (s === 'Memenuhi Syarat')
            return 'background:#FEF3C7; color:#D97706; border:1px solid #D97706; border-radius:8px;';
        if (s === 'Tidak Lulus')
            return 'background:#FFEBEE; color:#C62828; border:1px solid #C62828; border-radius:8px;';

        return 'background:#F1F5F9; color:#64748B; border:1px solid #CBD5E1; border-radius:8px;';
    },

    // =========================
    // STATUS (TABLE)
    // =========================
    getStatus(p) {
        if (p.nilaiTotal === null) return 'Belum Dinilai';
        if (p.nilaiTotal >= 80) return 'Lulus';
if (p.nilaiTotal >= 75 && p.nilaiTotal <= 79) return 'Memenuhi Syarat';
        return 'Tidak Lulus';
    },

    getStatusStyle(p) {
        const s = this.getStatus(p);

        if (s === 'Lulus')
            return 'background:#DCFCE7; color:#16A34A; border:1px solid #16A34A; border-radius:4px;';
        if (s === 'Memenuhi Syarat')
            return 'background:#FEF3C7; color:#D97706; border:1px solid #D97706; border-radius:4px;';
        if (s === 'Tidak Lulus')
            return 'background:#FFEBEE; color:#C62828; border:1px solid #C62828; border-radius:4px;';

        return 'background:#F1F5F9; color:#64748B; border:1px solid #CBD5E1; border-radius:4px;';
    },

    // =========================
    // OPEN MODAL
    // =========================
    openInput(p) {
        this.selectedPeserta = p;
        this.form.nilaiRapor = '';
        this.form.nilaiPrestasi = '';
        this.modalMode = 'input';
        this.showModal = true;

        this.$nextTick(() => this.updateButton());
    },

    openEdit(p) {
        this.selectedPeserta = p;
        this.form.nilaiRapor = p.nilaiRapor ?? '';
        this.form.nilaiPrestasi = p.nilaiPrestasi ?? '';
        this.modalMode = 'edit';
        this.showModal = true;

        this.$nextTick(() => this.updateButton());
    },

    openView(p) {
        this.selectedPeserta = p;
        this.form.nilaiRapor = p.nilaiRapor ?? '';
        this.form.nilaiPrestasi = p.nilaiPrestasi ?? '';
        this.modalMode = 'view';
        this.showModal = true;
    },

    // =========================
    // SIMPAN KE DATABASE
    // =========================
    saveNilai() {
        if (!this.selectedPeserta) return;

        fetch(`/panitia/seleksi/${this.selectedPeserta.id}/nilai`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                nilai_rapor: this.form.nilaiRapor,
                nilai_prestasi: this.form.nilaiPrestasi
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {

                let idx = this.peserta.findIndex(p => p.id === this.selectedPeserta.id);

                if (idx !== -1) {
                    this.peserta[idx].nilaiRapor = data.data.nilai_rapor;
                    this.peserta[idx].nilaiPrestasi = data.data.nilai_prestasi;
                    this.peserta[idx].nilaiTotal = data.data.nilai_total;
                }

                this.showModal = false;
            }
        })
        .catch(err => {
            console.error(err);
            alert('Gagal menyimpan nilai');
        });
    },

    // =========================
    // BUTTON STATE
    // =========================
    updateButton() {
        const btn = document.getElementById('btnSimpanNilai');
        if (!btn) return;

        const valid = this.nilaiTotalCalc !== null;

        btn.style.backgroundColor = valid ? '#27C2DE' : '#C4F4FD';
        btn.style.cursor = valid ? 'pointer' : 'not-allowed';
    },
    get filteredPeserta() {
    return this.peserta.filter(p => {

        // SEARCH (nama + NISN)
        const matchSearch =
            p.nama.toLowerCase().includes(this.search.toLowerCase()) ||
            p.nisn.toLowerCase().includes(this.search.toLowerCase());

        // JALUR
        const matchJalur =
            this.filterJalur === '' ||
            p.jalur === this.filterJalur;

        // GELOMBANG
        const matchGelombang =
            this.filterGelombang === '' ||
            p.gelombang === this.filterGelombang;

        // STATUS
        let status = this.getStatus(p);

        const matchStatus =
            this.filterStatus === '' ||
            status === this.filterStatus;

        return matchSearch && matchJalur && matchGelombang && matchStatus;
    });
},
get totalPeserta() {
    return this.peserta.length;
},

get totalLulus() {
    return this.peserta.filter(p => this.getStatus(p) === 'Lulus').length;
},

get totalTidakLulus() {
    return this.peserta.filter(p => this.getStatus(p) === 'Tidak Lulus').length;
},

get totalSanggah() {
    return this.peserta.filter(p => this.getStatus(p) === 'Memenuhi Syarat').length;
}
}"
x-effect="updateButton()"
>

    {{-- Pantau perubahan form secara reaktif --}}
    <span x-effect="
        // Dipicu setiap kali nilaiRapor atau nilaiPrestasi berubah
        form.nilaiRapor; form.nilaiPrestasi;
        updateButton();
    " class="hidden"></span>
     {{-- ===== STAT CARDS ===== --}}
    <div class="grid grid-cols-4 gap-3 mb-5">
        <div class="relative rounded-2xl px-4 py-4 overflow-hidden transition-all duration-200"
             style="background: linear-gradient(to bottom left, #FAFEFF 0%, #59DEFF 100%); border: 0.66px solid #F3F3F3; box-shadow: 0px 2.62px 2.62px 0px rgba(161,209,251,0.25); filter: saturate(0.45) brightness(1.08);"
             onmouseenter="this.style.filter='saturate(1) brightness(1)'; this.style.boxShadow='0px 6px 14px rgba(0,0,0,0.10)'"
             onmouseleave="this.style.filter='saturate(0.45) brightness(1.08)'; this.style.boxShadow='0px 2.62px 2.62px 0px rgba(161,209,251,0.25)'">
            <div class="flex items-start justify-between mb-2">
                <p class="text-[11px] font-semibold" style="color:#0099B8;">Total Peserta diumumkan</p>
                <img src="{{ asset('ppdb/admin/operasional/totalpendaftar.png') }}" class="w-7 h-7 object-contain">
            </div>
            <p class="text-[26px] font-bold text-[#2B2A28]" x-text="totalPeserta"></p>
        </div>

        <div class="relative rounded-2xl px-4 py-4 overflow-hidden transition-all duration-200"
             style="background: linear-gradient(to bottom left, #FAFEFF 0%, #88FFC4 100%); border: 0.66px solid #F3F3F3; box-shadow: 0px 2.62px 2.62px 0px rgba(161,209,251,0.25); filter: saturate(0.45) brightness(1.08);"
             onmouseenter="this.style.filter='saturate(1) brightness(1)'; this.style.boxShadow='0px 6px 14px rgba(0,0,0,0.10)'"
             onmouseleave="this.style.filter='saturate(0.45) brightness(1.08)'; this.style.boxShadow='0px 2.62px 2.62px 0px rgba(161,209,251,0.25)'">
            <div class="flex items-start justify-between mb-2">
                <p class="text-[11px] font-semibold" style="color:#15803D;">Lulus</p>
                <img src="{{ asset('ppdb/admin/operasional/berkasvalid.png') }}" class="w-7 h-7 object-contain">
            </div>
            <p class="text-[26px] font-bold text-[#2B2A28]" x-text="totalLulus"></p>
        </div>

        <div class="relative rounded-2xl px-4 py-4 overflow-hidden transition-all duration-200"
             style="background: linear-gradient(to bottom left, #FAFEFF 0%, #FF9696 100%); border: 0.66px solid #F3F3F3; box-shadow: 0px 2.62px 2.62px 0px rgba(161,209,251,0.25); filter: saturate(0.45) brightness(1.08);"
             onmouseenter="this.style.filter='saturate(1) brightness(1)'; this.style.boxShadow='0px 6px 14px rgba(0,0,0,0.10)'"
             onmouseleave="this.style.filter='saturate(0.45) brightness(1.08)'; this.style.boxShadow='0px 2.62px 2.62px 0px rgba(161,209,251,0.25)'">
            <div class="flex items-start justify-between mb-2">
                <p class="text-[11px] font-semibold" style="color:#DC2626;">Tidak Lulus</p>
                <img src="{{ asset('ppdb/admin/operasional/berkasditolak.png') }}" class="w-7 h-7 object-contain">
            </div>
            <p class="text-[26px] font-bold text-[#2B2A28]" x-text="totalTidakLulus"></p>
        </div>

        <div class="relative rounded-2xl px-4 py-4 overflow-hidden transition-all duration-200"
             style="background: linear-gradient(to bottom left, #FAFEFF 0%, #FFD59E 100%); border: 0.66px solid #F3F3F3; box-shadow: 0px 2.62px 2.62px 0px rgba(161,209,251,0.25); filter: saturate(0.45) brightness(1.08);"
             onmouseenter="this.style.filter='saturate(1) brightness(1)'; this.style.boxShadow='0px 6px 14px rgba(0,0,0,0.10)'"
             onmouseleave="this.style.filter='saturate(0.45) brightness(1.08)'; this.style.boxShadow='0px 2.62px 2.62px 0px rgba(161,209,251,0.25)'">
            <div class="flex items-start justify-between mb-2">
                <p class="text-[11px] font-semibold" style="color:#D97706;">Memenuhi Syarat</p>
                <img src="{{ asset('ppdb/admin/operasional/menunggu.png') }}" class="w-7 h-7 object-contain">
            </div>
            <p class="text-[26px] font-bold text-[#2B2A28]" x-text="totalSanggah"></p>
        </div>
    </div>

    {{-- ===== FILTER & SEARCH ===== --}}
    <div class="flex gap-3 mb-4 items-center w-full">
        <div class="relative flex-[2]">
            <input type="text" placeholder="Cari nama/NISN" x-model="search"
                   class="w-full pl-9 pr-4 py-2.5 text-[12px] border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#27C2DE] card-shadow"
                   style="border-radius:8px;">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <div class="relative flex-1">
            <select class="appearance-none w-full pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none card-shadow" x-model="filterJalur"
                    style="border-radius:8px;">
                <option value="">Jalur</option>
    <option value="prestasi">Prestasi</option>
    <option value="reguler">Reguler</option>
    <option value="afirmasi">Afirmasi</option>
            </select>
            <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
        
        <div class="relative flex-1">
            <select class="appearance-none w-full pl-4 pr-8 py-2.5 text-[12px] border border-slate-200 bg-white text-slate-600 focus:outline-none card-shadow" x-model="filterStatus"
                    style="border-radius:8px;">
                <option value="">Status</option>
                <option value="Lulus">Lulus</option>
                <option value="Memenuhi Syarat">Memenuhi Syarat</option>
                <option value="Tidak Lulus">Tidak Lulus</option>
                <option value="Belum Dinilai">Belum Dinilai</option>
            </select>
            <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>

    {{-- ===== TABEL UTAMA ===== --}}
    <div class="bg-white rounded-2xl overflow-hidden card-shadow">
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-[1400px] border-separate border-spacing-0">
                <thead>
                    <tr style="background:#C4F4FD;">
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] sticky left-0 z-20 bg-[#C4F4FD]" style="min-width:40px;">No</th>
                        <th class="text-left py-3 px-4 text-[12px] font-semibold text-[#2B2A28] sticky left-10 z-20 bg-[#C4F4FD]" style="min-width:160px; box-shadow: 4px 0 4px 0 rgba(161,209,251,0.25);">Nama Peserta</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:100px;">Nilai Rapor</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:120px;">Nilai Prestasi</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:100px;">Nilai Total</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:130px;">Status Seleksi</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:90px;">Jalur</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:120px;">NISN</th>
                        <th class="text-left py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:140px;">Asal Sekolah</th>
                        <th class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28] whitespace-nowrap" style="min-width:100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="(p, i) in filteredPeserta" :key="p.id">
                        <tr class="hover:bg-slate-50 transition-all">
                            <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28] sticky left-0 z-10 bg-white" x-text="i+1"></td>
                            <td class="py-3 px-4 text-[12px] font-medium text-[#2B2A28] sticky left-10 z-10 bg-white" style="box-shadow: 4px 0 4px 0 rgba(161,209,251,0.25);" x-text="p.nama"></td>
                            <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28]"><span x-text="p.nilaiRapor !== null ? p.nilaiRapor : '–'"></span></td>
                            <td class="text-center py-3 px-4 text-[12px] text-[#2B2A28]"><span x-text="p.nilaiPrestasi !== null ? p.nilaiPrestasi : '–'"></span></td>
                            <td class="text-center py-3 px-4 text-[12px] font-semibold text-[#2B2A28]"><span x-text="p.nilaiTotal !== null ? p.nilaiTotal : '–'"></span></td>
                            <td class="text-center py-3 px-4"><span class="px-3 py-1 text-[11px] font-semibold" :style="getStatusStyle(p)" x-text="getStatus(p)"></span></td>
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
            <li class="flex items-center gap-2 text-[12px] text-[#575551]"><span>•</span> Lulus = 80-100</li>
            <li class="flex items-center gap-2 text-[12px] text-[#575551]"><span>•</span> Memenuhi Syarat = 75-79</li>
            <li class="flex items-center gap-2 text-[12px] text-[#575551]"><span>•</span> Tidak Lulus = &lt;0-74</li>
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
                        <template x-if="selectedPeserta && selectedPeserta.jalur === 'prestasi'">
    <div class="flex items-center gap-4">
        <label class="text-[12px] font-medium text-[#575551]" style="min-width:100px;">
            Nilai Prestasi
        </label>
        <input x-model="form.nilaiPrestasi"
               :readonly="modalMode === 'view'"
               type="number" min="0" max="100"
               class="flex-1 px-3 py-2 text-[13px] text-center font-semibold
               focus:outline-none focus:ring-2 focus:ring-[#27C2DE]
               bg-[#F5F7FF] border-[#DFEAF2]"
               style="border-radius:10px; border:1px solid #DFEAF2;">
    </div>
</template>
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
                        <p class="text-[10px] text-[#575551]">• Lulus = 80-100</p>
                        <p class="text-[10px] text-[#575551]">• Memenuhi Syarat = 75-79</p>
                        <p class="text-[10px] text-[#575551]">• Tidak Lulus = &lt;0-75</p>
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

        if (selectedPeserta?.jalur === 'prestasi') {
            alert('Harap lengkapi nilai rapor dan prestasi!');
        } else {
            alert('Harap isi nilai rapor!');
        }

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