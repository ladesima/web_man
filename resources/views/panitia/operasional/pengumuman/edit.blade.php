@extends('layouts.panitia')

@section('title', 'Edit Pesan')

@section('content')
<form method="POST" action="{{ route('panitia.operasional.pengumuman.template.update', $template->id) }}">
@csrf
@method('PUT')

<style>
.card-shadow {
    box-shadow: 0px 4px 4px 0px rgba(161, 209, 251, 0.25);
}
.form-input {
    width: 100%;
    padding: 10px 14px;
    font-size: 12px;
    color: #2B2A28;
    background: #F5F7FF;
    border: 1px solid #DFEAF2;
    border-radius: 8px;
}
.form-label {
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 6px;
}
#editor-area {
    min-height: 220px;
    padding: 14px;
    font-size: 12px;
    background: #DFEAF2;
}
</style>

<div x-data="editorData()" x-init="init()">

    {{-- BREADCRUMB --}}
    <div class="mb-5 text-[12px] text-slate-400">
        Edit Template
    </div>

    <div class="bg-white rounded-2xl p-6 card-shadow">

        {{-- PENERIMA --}}
        <div class="mb-5">
            <label class="form-label">Penerima</label>
            <select name="penerima" class="form-input" required>
                <option value="lulus" {{ $template->penerima == 'lulus' ? 'selected' : '' }}>Lulus</option>
                <option value="tidak_lulus" {{ $template->penerima == 'tidak_lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                <option value="perlu_perbaikan" {{ $template->penerima == 'perlu_perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                <option value="semua" {{ $template->penerima == 'semua' ? 'selected' : '' }}>Semua</option>
            </select>
        </div>

        {{-- JUDUL --}}
        <div class="mb-5">
            <label class="form-label">Judul</label>
            <input type="text" name="judul" class="form-input"
                   value="{{ $template->judul }}" required>
        </div>

        {{-- EDITOR --}}
        <div class="mb-5">
            <label class="form-label">Isi Pesan</label>

            <div id="editor-area"
                 contenteditable="true"
                 @input="updateCount()"></div>

            <textarea name="isi" id="isi-hidden" hidden></textarea>

            <p class="text-[11px] text-slate-400 mt-1"
               x-text="charCount + ' karakter'"></p>
        </div>

        {{-- BUTTON --}}
        <div class="flex justify-center">
            <button type="submit"
                class="px-10 py-2 text-white text-[12px]"
                style="background:#41D1EA; border-radius:4px;">
                Update
            </button>
        </div>

    </div>
</div>
@endsection

<script>
function editorData() {
    return {
        charCount: 0,

        updateCount() {
            let editor = document.getElementById('editor-area');
            this.charCount = editor.innerText.length;

            document.getElementById('isi-hidden').value = editor.innerHTML;
        },

        init() {
            let editor = document.getElementById('editor-area');

            // ✅ AMAN (TIDAK AKAN ERROR)
            editor.innerHTML = @json($template->isi);

            this.updateCount();
        }
    }
}
</script>