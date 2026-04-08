@extends('layouts.admin')

@section('title', 'Tambah Materi Pembelajaran')

@section('content')
<div class="max-w-4xl mx-auto px-3 sm:px-4 py-6 sm:py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center space-x-2 mb-6 text-xs sm:text-sm">
        <i class="fas fa-book text-red-600"></i>
        <span class="text-gray-600">Admin</span>
        <span class="text-gray-400">/</span>
        <span class="text-gray-600">
            <a href="{{ route('admin.materials.index') }}" class="hover:text-red-600">Materi Pembelajaran</a>
        </span>
        <span class="text-gray-400">/</span>
        <span class="font-semibold text-gray-800">Tambah Materi</span>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-black text-gray-800 mb-2">Tambah Materi Pembelajaran</h1>
        <p class="text-gray-600 text-xs sm:text-sm">Unggah materi pembelajaran baru ke sistem</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-3 sm:px-6 py-3 sm:py-4">
            <h2 class="text-base sm:text-xl font-bold text-white flex items-center gap-2">
                <i class="fas fa-file-upload"></i><span class="hidden sm:inline">Form Tambah Materi</span><span class="sm:hidden">Tambah Materi</span>
            </h2>
        </div>

        <div class="p-3 sm:p-6 sm:p-8">
            <form action="{{ route('admin.materials.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Kelas Selection -->
                <div>
                    <label for="e_class_id" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-door-open text-red-600 mr-2"></i>Kelas <span class="text-red-600">*</span>
                    </label>
                    <select name="e_class_id" id="e_class_id" class="w-full px-3 sm:px-4 py-2 sm:py-3 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:border-red-600 focus:outline-none @error('e_class_id') border-red-500 @enderror" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" @selected(old('e_class_id') == $class->id)>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('e_class_id')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Judul Materi -->
                <div>
                    <label for="title" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-heading text-red-600 mr-2"></i>Judul Materi <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="title" id="title" class="w-full px-3 sm:px-4 py-2 sm:py-3 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:border-red-600 focus:outline-none @error('title') border-red-500 @enderror" 
                        placeholder="Masukkan judul materi" value="{{ old('title') }}" required>
                    @error('title')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-align-left text-red-600 mr-2"></i>Deskripsi
                    </label>
                    <textarea name="description" id="description" class="w-full px-3 sm:px-4 py-2 sm:py-3 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:border-red-600 focus:outline-none @error('description') border-red-500 @enderror" 
                        rows="4" placeholder="Masukkan deskripsi materi">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload -->
                <div>
                    <label for="file" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-file text-red-600 mr-2"></i>File Materi <span class="text-red-600">*</span>
                    </label>
                    <div class="relative">
                        <input type="file" name="file" id="file" class="w-full px-3 sm:px-4 py-2 sm:py-3 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:border-red-600 focus:outline-none @error('file') border-red-500 @enderror" 
                            accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.mp4,.mkv" required>
                    </div>
                    <p class="text-gray-600 text-xs mt-2">
                        <i class="fas fa-info-circle mr-1"></i>Format: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, JPG, PNG, MP4, MKV (Maks. 100MB)
                    </p>
                    @error('file')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold px-3 sm:px-6 py-2 sm:py-3 rounded-lg text-xs sm:text-sm hover:from-red-700 hover:to-red-800 transition flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> <span class="hidden sm:inline">Simpan Materi</span><span class="sm:hidden">Simpan</span>
                    </button>
                    <a href="{{ route('admin.materials.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-3 sm:px-6 py-2 sm:py-3 rounded-lg text-xs sm:text-sm transition flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
