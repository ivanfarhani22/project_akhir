@extends('layouts.guru')

@section('title', 'Edit Materi')
@section('icon', 'fas fa-edit')

@section('content')
    <!-- PAGE HEADER -->
    <div class="mb-8">
        <p class="text-gray-600 text-sm mb-2">Edit Materi</p>
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3 mb-2">
            <i class="fas fa-edit text-amber-500"></i>
            {{ $material->title }}
        </h1>
        <p class="text-gray-600 text-sm">Update atau upload versi materi baru</p>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="font-bold text-gray-900 text-lg">Edit Materi Pembelajaran</h2>
            </div>
            <div class="p-6">
                <!-- INFO BOX -->
                <div class="bg-blue-50 px-4 py-3 rounded-lg mb-6 border-l-4 border-blue-500">
                    <p class="text-sm text-blue-900">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Info:</strong> Jika Anda upload file baru, versi materi akan otomatis bertambah ({{ $material->version }} → {{ $material->version + 1 }})
                    </p>
                </div>

                <form method="POST" action="{{ route('guru.materials.update', $material) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- TITLE FIELD -->
                    <div class="mb-6">
                        <label for="title" class="block font-semibold text-gray-900 mb-2">
                            Judul Materi <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="title" 
                            id="title"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition"
                            value="{{ old('title', $material->title) }}" 
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
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition resize-none"
                            rows="4"
                        >{{ old('description', $material->description) }}</textarea>
                        @error('description')
                            <span class="text-red-600 text-xs mt-2 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- CURRENT FILE -->
                    <div class="mb-8">
                        <label class="block font-semibold text-gray-900 mb-3">File Saat Ini</label>
                        <div class="bg-gray-100 px-4 py-3 rounded-lg mb-4 flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">
                                    <i class="fas fa-file mr-2"></i> {{ $material->file_type }}
                                </p>
                                <p class="text-xs text-gray-600 mt-1">
                                    Versi {{ $material->version }} • Dibuat {{ $material->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- FILE UPLOAD FIELD -->
                        <label for="file" class="block font-semibold text-gray-900 mb-3">
                            Upload File Baru (Opsional)
                        </label>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-8 text-center bg-gray-50 hover:border-amber-400 hover:bg-amber-50 transition cursor-pointer" id="dropzone">
                            <i class="fas fa-cloud-upload-alt text-amber-500 text-5xl mb-4 block"></i>
                            <p class="font-semibold text-gray-900 mb-1">Klik atau drag file di sini</p>
                            <p class="text-gray-600 text-sm">PDF, DOC, XLS, PPT, ZIP (Max 10MB)</p>
                            <input 
                                type="file" 
                                name="file" 
                                id="file"
                                class="hidden"
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
                            <i class="fas fa-save"></i> Update Materi
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
