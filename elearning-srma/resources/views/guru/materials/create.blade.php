@extends('layouts.guru')

@section('title', 'Upload Materi Baru')
@section('icon', 'fas fa-book')

@section('content')
    <!-- PAGE HEADER -->
    <div class="mb-8">
        <p class="text-gray-600 text-sm mb-2">Upload Materi</p>
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3 mb-2">
            <i class="fas fa-cloud-upload-alt text-amber-500"></i>
            Tambah Materi Pembelajaran
        </h1>
        <p class="text-gray-600 text-sm">Kelas: <strong>{{ $class->name }}</strong> • {{ $class->subject->name }}</p>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="font-bold text-gray-900 text-lg">Formulir Upload Materi</h2>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('guru.materials.store') }}" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="e_class_id" value="{{ $class->id }}">

                    <!-- TITLE FIELD -->
                    <div class="mb-6">
                        <label for="title" class="block font-semibold text-gray-900 mb-2">
                            Judul Materi <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="title" 
                            id="title"
                            placeholder="Contoh: Bab 1 - Pengenalan Sistem"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition"
                            value="{{ old('title') }}" 
                            required
                        >
                        @error('title')
                            <span class="text-red-600 text-xs mt-2 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- DESCRIPTION FIELD -->
                    <div class="mb-6">
                        <label for="description" class="block font-semibold text-gray-900 mb-2">
                            Deskripsi
                        </label>
                        <textarea 
                            name="description" 
                            id="description"
                            placeholder="Jelaskan materi ini untuk siswa..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition resize-none"
                            rows="4"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <span class="text-red-600 text-xs mt-2 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- FILE UPLOAD FIELD -->
                    <div class="mb-8">
                        <label for="file" class="block font-semibold text-gray-900 mb-3">
                            File Materi <span class="text-red-500">*</span>
                        </label>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-8 text-center bg-gray-50 hover:border-amber-400 hover:bg-amber-50 transition cursor-pointer" id="dropzone">
                            <i class="fas fa-cloud-upload-alt text-amber-500 text-5xl mb-4 block"></i>
                            <p class="font-semibold text-gray-900 mb-1">Klik atau drag file di sini</p>
                            <p class="text-gray-600 text-sm mb-2">PDF, DOC, XLS, PPT, ZIP (Max 10MB)</p>
                            <p class="text-gray-500 text-xs">Format yang didukung: PDF, DOCX, XLSX, PPTX, ZIP, RAR</p>
                            <input 
                                type="file" 
                                name="file" 
                                id="file"
                                class="hidden"
                                required
                            >
                        </div>
                        <p class="text-green-600 text-xs mt-3 font-medium" id="filename"></p>
                        @error('file')
                            <span class="text-red-600 text-xs mt-2 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="flex flex-col sm:flex-row gap-3 mt-8">
                        <a href="{{ url()->previous() ?? route('guru.materials.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-900 font-medium py-2 px-6 rounded-lg text-sm transition inline-flex justify-center items-center gap-2">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit"
                            class="flex-1 bg-[#A41E35] hover:bg-[#7D1627] text-white font-medium py-2 px-6 rounded-lg text-sm transition inline-flex justify-center items-center gap-2">
                            <i class="fas fa-save"></i> Simpan Materi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('file');
        const filename = document.getElementById('filename');

        dropzone.addEventListener('click', () => fileInput.click());

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('border-amber-500', 'bg-amber-50');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('border-amber-500', 'bg-amber-50');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-amber-500', 'bg-amber-50');
            fileInput.files = e.dataTransfer.files;
            updateFilename();
        });

        fileInput.addEventListener('change', updateFilename);

        function updateFilename() {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                filename.textContent = `✓ File dipilih: ${file.name} (${sizeMB} MB)`;
            } else {
                filename.textContent = '';
            }
        }
    </script>
@endsection
