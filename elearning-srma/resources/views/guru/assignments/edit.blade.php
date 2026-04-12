@extends('layouts.guru')

@section('title', 'Edit Tugas')
@section('icon', 'fas fa-edit')

@section('content')
    <!-- PAGE HEADER -->
    <div class="mb-8">
        <p class="text-gray-600 text-sm mb-2">Manajemen Tugas</p>
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3 mb-2">
            <i class="fas fa-edit text-blue-500"></i>
            Edit Tugas
        </h1>
        <p class="text-gray-600 text-sm">{{ $assignment->title }}</p>
    </div>

    <!-- ERROR ALERT -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <p class="text-red-900 font-semibold flex items-center gap-2 mb-2">
                <i class="fas fa-exclamation-circle"></i>
                Terjadi kesalahan:
            </p>
            <ul class="text-red-800 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                    <i class="fas fa-tasks text-blue-500"></i>
                    Form Edit Tugas
                </h2>
            </div>

            <div class="p-6">
                <!-- INFO BOX -->
                <div class="bg-blue-50 px-4 py-3 rounded-lg mb-6 border-l-4 border-blue-500">
                    <p class="text-sm text-blue-900">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Info:</strong> Edit hanya akan mengubah tugas ini. Siswa yang sudah mengumpulkan akan tetap memiliki submission mereka.
                    </p>
                </div>

                <form method="POST" action="{{ route('guru.assignments.update', $assignment) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- TITLE FIELD -->
                    <div class="mb-6">
                        <label class="block font-semibold text-gray-900 mb-2">
                            Judul Tugas <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="title" 
                            id="title" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                            value="{{ old('title', $assignment->title) }}" 
                            placeholder="Contoh: Latihan Soal Chapter 5 - Fungsi Kuadrat"
                            required
                        >
                    </div>

                    <!-- DESCRIPTION FIELD -->
                    <div class="mb-6">
                        <label class="block font-semibold text-gray-900 mb-2">
                            Deskripsi
                        </label>
                        <textarea 
                            name="description" 
                            id="description"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition resize-none"
                            rows="4"
                            placeholder="Jelaskan detail tugas, instruksi, dan kriteria penilaian..."
                        >{{ old('description', $assignment->description) }}</textarea>
                    </div>

                    <!-- DEADLINE FIELD -->
                    <div class="mb-6">
                        <label class="block font-semibold text-gray-900 mb-2">
                            Batas Waktu <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="datetime-local" 
                            name="deadline" 
                            id="deadline"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                            value="{{ old('deadline', $assignment->deadline->format('Y-m-d\TH:i')) }}" 
                            required
                        >
                    </div>

                    <!-- MAX SCORE FIELD -->
                    <div class="mb-6">
                        <label class="block font-semibold text-gray-900 mb-2">
                            Nilai Maksimal <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            name="max_score"
                            id="max_score"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                            value="{{ old('max_score', $assignment->max_score ?? 100) }}"
                            min="1"
                            required
                        >
                    </div>

                    <!-- FILE UPLOAD FIELD -->
                    <div class="mb-8">
                        <label class="block font-semibold text-gray-900 mb-3">
                            File Soal (Opsional)
                        </label>
                        
                        @if($assignment->file_path)
                            <div class="bg-gray-100 px-4 py-3 rounded-lg mb-4 flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">
                                        <i class="fas fa-file mr-2"></i> File Saat Ini
                                    </p>
                                    <p class="text-xs text-gray-600 mt-1">
                                        {{ strtoupper(pathinfo($assignment->file_path, PATHINFO_EXTENSION)) }}
                                    </p>
                                </div>
                                @php
                                    $downloadUrl = str_starts_with($assignment->file_path, 'storage/')
                                        ? asset($assignment->file_path)
                                        : asset('storage/' . ltrim($assignment->file_path, '/'));
                                @endphp
                                <a href="{{ $downloadUrl }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-1.5 px-3 rounded text-xs transition inline-flex items-center gap-1">
                                    <i class="fas fa-download"></i> Download Lampiran
                                </a>
                            </div>
                        @endif

                        <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-8 text-center bg-gray-50 hover:border-blue-400 hover:bg-blue-50 transition cursor-pointer" id="dropzone">
                            <i class="fas fa-cloud-upload-alt text-blue-500 text-5xl mb-4 block"></i>
                            <p class="font-semibold text-gray-900 mb-1">Klik atau drag file di sini</p>
                            <p class="text-gray-600 text-sm mb-2">PDF, DOC, XLS, PPT, ZIP (Max 10MB)</p>
                            <p class="text-gray-500 text-xs">Biarkan kosong jika tidak ingin mengubah file</p>
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
                        <a href="{{ url()->previous() ?? route('guru.assignments.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-900 font-medium py-2 px-6 rounded-lg text-sm transition inline-flex justify-center items-center gap-2">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="flex-1 bg-[#A41E35] hover:bg-[#7D1627] text-white font-medium py-2 px-6 rounded-lg text-sm transition inline-flex justify-center items-center gap-2">
                            <i class="fas fa-save"></i> Update Tugas
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
            dropzone.classList.add('border-blue-500', 'bg-blue-50');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('border-blue-500', 'bg-blue-50');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-blue-500', 'bg-blue-50');
            
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                updateFilename();
            }
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
