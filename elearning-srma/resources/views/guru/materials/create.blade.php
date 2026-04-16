@extends('layouts.guru')

@section('title', 'Upload Materi Baru')
@section('icon', 'fas fa-book')

@section('content')

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-book mr-1"></i> Guru / Materi / Upload</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-cloud-upload-alt text-[#A41E35] mr-2"></i>Tambah Materi</h1>
    <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full mt-1">
        <i class="fas fa-door-open"></i> Kelas: <strong class="text-gray-700">{{ $class->name }}</strong>
        <span class="mx-1 text-gray-300">•</span> {{ $class->subject->name }}
    </span>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-bold text-gray-900">Formulir Upload Materi</h2>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('guru.materials.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="e_class_id" value="{{ $class->id }}">

                <div class="mb-5">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Materi <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" placeholder="Contoh: Bab 1 - Pengenalan Sistem"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition"
                        value="{{ old('title') }}" required>
                    @error('title')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div class="mb-5">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" id="description" rows="4" placeholder="Jelaskan materi ini untuk siswa..."
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition resize-none">{{ old('description') }}</textarea>
                    @error('description')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">File Materi <span class="text-red-500">*</span></label>
                    <div id="dropzone" class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center bg-gray-50 hover:border-[#A41E35] hover:bg-red-50 transition cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-300 mb-3 block"></i>
                        <p class="text-sm font-semibold text-gray-700 mb-1">Klik atau drag file di sini</p>
                        <p class="text-xs text-gray-400">PDF, DOC, XLS, PPT, ZIP — Maks. 10MB</p>
                        <input type="file" name="file" id="file" class="hidden" required>
                    </div>
                    <p class="text-emerald-600 text-xs mt-2 font-medium" id="filename"></p>
                    @error('file')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ url()->previous() ?? route('guru.materials.index') }}"
                       class="flex-1 inline-flex justify-center items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-xl text-sm transition">
                        <i class="fas fa-arrow-left text-xs"></i> Kembali
                    </a>
                    <button type="submit"
                        class="flex-1 inline-flex justify-center items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold py-2.5 px-6 rounded-xl text-sm transition shadow-md hover:shadow-lg">
                        <i class="fas fa-save text-xs"></i> Simpan Materi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('file');
    const filename  = document.getElementById('filename');
    dropzone.addEventListener('click', () => fileInput.click());
    dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('border-[#A41E35]','bg-red-50'); });
    dropzone.addEventListener('dragleave', () => dropzone.classList.remove('border-[#A41E35]','bg-red-50'));
    dropzone.addEventListener('drop', e => { e.preventDefault(); dropzone.classList.remove('border-[#A41E35]','bg-red-50'); fileInput.files = e.dataTransfer.files; updateFilename(); });
    fileInput.addEventListener('change', updateFilename);
    function updateFilename() {
        if (fileInput.files.length) {
            const f = fileInput.files[0];
            filename.textContent = `✓ ${f.name} (${(f.size/1048576).toFixed(2)} MB)`;
        }
    }
</script>
@endsection