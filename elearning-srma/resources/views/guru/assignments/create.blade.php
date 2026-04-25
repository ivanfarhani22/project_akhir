@extends('layouts.guru')
@section('title', 'Buat Tugas')
@section('icon', 'fas fa-tasks')

@section('content')

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-tasks mr-1"></i> Guru / Tugas / Buat</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-plus-circle text-[#A41E35] mr-2"></i>Buat Tugas Baru</h1>
    <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full mt-1">
        <i class="fas fa-door-open"></i> Kelas: <strong class="text-gray-700">{{ $class->name }}</strong>
        <span class="mx-1 text-gray-300">•</span> {{ $class->subject->name }}
    </span>
</div>

@if(empty($classSubjectId))
    <div class="flex items-start gap-3 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-xl mb-6">
        <i class="fas fa-exclamation-triangle mt-0.5 flex-shrink-0"></i>
        <p class="text-sm font-semibold">Mapel untuk kelas ini belum terhubung ke guru Anda, tugas tidak bisa dibuat.</p>
    </div>
@endif

@if($errors->any())
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6">
        <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
        <ul class="text-sm space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-bold text-gray-900">Form Buat Tugas</h2>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('guru.assignments.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="e_class_id" value="{{ $class->id }}">
                <input type="hidden" name="class_subject_id" value="{{ $classSubjectId }}">

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Tugas <span class="text-red-500">*</span></label>
                    <input type="text" name="title" placeholder="Contoh: Latihan Soal Chapter 5 - Fungsi Kuadrat"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition"
                        value="{{ old('title') }}" required>
                    @error('title')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="4" placeholder="Jelaskan detail tugas, instruksi, dan kriteria penilaian..."
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition resize-none">{{ old('description') }}</textarea>
                    @error('description')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Batas Waktu <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="deadline"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition"
                        value="{{ old('deadline') }}" required>
                    @error('deadline')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">File Soal <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <div id="dropzone" class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center bg-gray-50 hover:border-[#A41E35] hover:bg-red-50 transition cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-300 mb-3 block"></i>
                        <p class="text-sm font-semibold text-gray-700 mb-1">Klik atau drag file di sini</p>
                        <p class="text-xs text-gray-400">PDF, DOC, XLS, PPT, ZIP — Maks. 10MB</p>
                        <input type="file" name="file" id="file" class="hidden">
                    </div>
                    <p class="text-emerald-600 text-xs mt-2 font-medium" id="filename"></p>
                    @error('file')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ url()->previous() ?? route('guru.assignments.index') }}"
                       class="flex-1 inline-flex justify-center items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-xl text-sm transition">
                        <i class="fas fa-arrow-left text-xs"></i> Kembali
                    </a>
                    <button type="submit"
                        class="flex-1 inline-flex justify-center items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold py-2.5 px-6 rounded-xl text-sm transition shadow-md hover:shadow-lg">
                        <i class="fas fa-save text-xs"></i> Simpan Tugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const dropzone = document.getElementById('dropzone'), fileInput = document.getElementById('file'), filename = document.getElementById('filename');
    const MAX = 10 * 1024 * 1024;
    dropzone.addEventListener('click', () => fileInput.click());
    dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('border-[#A41E35]','bg-red-50'); });
    dropzone.addEventListener('dragleave', () => dropzone.classList.remove('border-[#A41E35]','bg-red-50'));
    dropzone.addEventListener('drop', e => { e.preventDefault(); dropzone.classList.remove('border-[#A41E35]','bg-red-50'); fileInput.files = e.dataTransfer.files; updateFilename(); });
    fileInput.addEventListener('change', updateFilename);
    function updateFilename() {
        const f = fileInput.files[0];
        if (!f) return;
        if (f.size > MAX) { alert('Ukuran file terlalu besar. Maksimal 10 MB.'); fileInput.value = ''; filename.textContent = ''; return; }
        filename.textContent = `✓ ${f.name} (${(f.size/1048576).toFixed(2)} MB)`;
    }
</script>
@endsection