@extends('layouts.panitia')

@section('title', 'Detail Pesan')

@section('content')
<style>
.card-shadow {
    box-shadow: 0px 4px 4px 0px rgba(161, 209, 251, 0.25);
}
.form-input {
    width: 100%;
    padding: 10px 14px;
    font-size: 12px;
    color: #2B2A28;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    outline: none;
    transition: border 0.2s, box-shadow 0.2s;
}
.form-input:focus {
    border-color: #27C2DE;
    box-shadow: 0 0 0 3px rgba(39,194,222,0.10);
    background: white;
}
.form-label {
    font-size: 12px;
    font-weight: 600;
    color: #2B2A28;
    margin-bottom: 6px;
    display: block;
}
.toolbar-btn {
    padding: 4px 8px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    transition: background 0.15s;
    background: transparent;
    border: none;
    color: white;
}
.toolbar-btn:hover { background: rgba(255,255,255,0.15); }
#editor-detail {
    min-height: 260px;
    padding: 16px;
    font-size: 12px;
    color: #2B2A28;
    outline: none;
    line-height: 1.8;
    background: #DFEAF2; /* ← ganti */
}
#editor-detail:empty:before {
    content: attr(data-placeholder);
    color: #CBD5E1;
}
</style>

<div x-data="{
    charCount: 0,
    updateCount() {
        this.charCount = document.getElementById('editor-detail')?.innerText?.length ?? 0;
    }
}">

    {{-- ===== BREADCRUMB ===== --}}
    <div class="flex items-center gap-2 mb-5 text-[12px] text-slate-400">
        <a href="{{ route('admin.operasional.pengumuman') }}" class="hover:text-[#27C2DE] transition-colors">Pengumuman</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('admin.operasional.pengumuman.review') }}" class="hover:text-[#27C2DE] transition-colors">Riview Email</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-[#27C2DE] font-semibold">Detail Pesan</span>
    </div>

    {{-- ===== FORM CARD ===== --}}
    <div class="bg-white rounded-2xl p-6 card-shadow">

        {{-- Row 1: Dari & Email Pengirim --}}
        <div class="grid grid-cols-2 gap-5 mb-5">
            <div>
                <label class="form-label">Dari (Nama Sekolah)</label>
                <input type="text" class="form-input" value="Panitia, MAN Jeneponto" readonly
                       style="background:#F1F5F9; color:#94A3B8; cursor:not-allowed;">
            </div>
            <div>
                <label class="form-label">Email Pengirim (Nama Sekolah)</label>
                <input type="text" class="form-input" value="PPDB@manjeneponto.sch.id" readonly
                       style="background:#F1F5F9; color:#94A3B8; cursor:not-allowed;">
            </div>
        </div>

        {{-- Penerima --}}
        <div class="mb-5">
            <label class="form-label">Penerima</label>
            <div class="relative">
                <select class="form-input appearance-none pr-8">
                    <option value="" disabled>Pilih Penerima</option>
                    <option value="tidak_lulus" selected>Tidak Lulus</option>
                    <option value="lulus">Lulus</option>
                    <option value="perbaikan">Perlu Perbaikan</option>
                    <option value="semua">Semua Peserta</option>
                </select>
                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>

        {{-- Subjek/Judul --}}
        <div class="mb-5">
            <label class="form-label">Subjek/Judul</label>
            <input type="text" class="form-input"
                   value="Selamat, Anda adalah Bagian dari MAN JENEPONTO 🤩">
        </div>

        {{-- Isi Pesan --}}
        <div class="mb-6">
            <label class="form-label">Isi Pesan</label>
            <div style="border: 1px solid #E2E8F0; border-radius: 8px; overflow: hidden;">

                {{-- Toolbar --}}
                <div class="flex items-center gap-1 px-3 py-2"
                style="background: #827E78; border-bottom: 1px solid #9B9792;">

                    <button type="button" onclick="document.execCommand('undo')" class="toolbar-btn" title="Undo">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                    </button>
                    <button type="button" onclick="document.execCommand('redo')" class="toolbar-btn" title="Redo">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 10H11a8 8 0 00-8 8v2M21 10l-6 6m6-6l-6-6"/>
                        </svg>
                    </button>

                    <div style="width:1px; height:16px; background:#6B7280; margin:0 4px;"></div>

                    <select onchange="document.execCommand('fontName', false, this.value)"
                            class="text-[11px] px-2 py-1 rounded"
                            style="background:#6B7280; border:none; outline:none; color:white;">
                        <option value="Helvetica">Helvetica</option>
                        <option value="Arial">Arial</option>
                        <option value="Georgia">Georgia</option>
                    </select>

                    <div style="width:1px; height:16px; background:#6B7280; margin:0 4px;"></div>

                    <button type="button" onclick="document.execCommand('justifyLeft')" class="toolbar-btn" title="Kiri">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h14"/>
                        </svg>
                    </button>
                    <button type="button" onclick="document.execCommand('justifyCenter')" class="toolbar-btn" title="Tengah">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M7 12h10M5 18h14"/>
                        </svg>
                    </button>
                    <button type="button" onclick="document.execCommand('justifyRight')" class="toolbar-btn" title="Kanan">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M10 12h10M6 18h14"/>
                        </svg>
                    </button>
                    <button type="button" onclick="document.execCommand('justifyFull')" class="toolbar-btn" title="Rata kanan-kiri">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div style="width:1px; height:16px; background:#6B7280; margin:0 4px;"></div>

                    <button type="button" onclick="document.execCommand('bold')" class="toolbar-btn font-bold" style="font-size:13px;" title="Bold">B</button>
                    <button type="button" onclick="document.execCommand('italic')" class="toolbar-btn italic" style="font-size:13px;" title="Italic">I</button>
                    <button type="button" onclick="document.execCommand('underline')" class="toolbar-btn underline" style="font-size:13px;" title="Underline">U</button>
                </div>

                {{-- Editor Area --}}
                <div id="editor-detail"
                     contenteditable="true"
                     data-placeholder="Tulis isi pesan di sini..."
                     @input="updateCount()">
                    <p>Yth. Calon Peserta Didik,</p>
                    <p>Dengan hormat,</p>
                    <br>
                    <p>Berdasarkan hasil seleksi Penerimaan Peserta Didik Baru (PPDB) MAN Jeneponto, kami dengan ini menyampaikan bahwa Saudara/i dinyatakan LULUS sebagai calon siswa MAN Jeneponto.</p>
                    <p>Selanjutnya, Saudara/i diharapkan untuk segera melakukan proses daftar ulang sesuai dengan jadwal dan ketentuan yang berlaku. Informasi terkait tahapan daftar ulang dapat dilihat melalui akun pendaftaran masing-masing pada sistem PPDB.</p>
                    <br>
                    <p>Kami mengucapkan selamat atas pencapaian yang telah diraih. Semoga dapat menjadi awal yang baik untuk menempuh pendidikan di MAN Jeneponto.</p>
                    <p>Demikian informasi ini kami sampaikan. Atas perhatian dan kerja samanya, kami ucapkan terima kasih.</p>
                    <br>
                    <p>Hormat kami,</p>
                    <p>Panitia PPDB</p>
                    <p>MAN Jeneponto</p>
                </div>
            </div>
            <p class="text-[11px] text-slate-400 mt-1.5" x-text="charCount + '/10000'">0/10000</p>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-center gap-3">
            <a href="{{ route('admin.operasional.pengumuman.review') }}"
               class="px-8 py-2.5 text-[12px] font-semibold text-[#2B2A28] hover:bg-slate-50 transition-all"
               style="border-radius:8px; border:1px solid #D4D4D4;">
                Kembali
            </a>
            <button type="button"
                    class="px-10 py-2.5 text-white text-[12px] font-semibold hover:opacity-90 transition-all active:scale-95"
                    style="background:#27C2DE; border-radius:8px;">
                Simpan
            </button>
        </div>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const editor = document.getElementById('editor-detail');
        if (editor) {
            editor.dispatchEvent(new Event('input'));
        }
    });
</script>
@endsection