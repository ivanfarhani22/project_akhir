@extends('layouts.admin')

@section('title', 'Edit Materi Pembelajaran')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center space-x-2 mb-6">
        <i class="fas fa-book text-red-600"></i>
        <span class="text-gray-600">Admin</span>
        <span class="text-gray-400">/</span>
        <span class="text-gray-600">
            <a href="{{ route('admin.materials.index') }}" class="hover:text-red-600">Materi Pembelajaran</a>
        </span>
        <span class="text-gray-400">/</span>
        <span class="font-semibold text-gray-800">Edit Materi</span>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-800 mb-2">Edit Materi Pembelajaran</h1>
        <p class="text-gray-600">Perbarui informasi materi pembelajaran</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <i class="fas fa-edit"></i>Form Edit Materi
            </h2>
        </div>

        <div class="p-6 sm:p-8">
            <form action="{{ route('admin.materials.update', $material) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Kelas Info (Read-only) -->
                <div>
                    <label for="e_class_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-door-open text-red-600 mr-2"></i>Kelas
                    </label>
                    <input type="text" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed" 
                        value="{{ $material->eClass->name }}" disabled>
                    <p class="text-gray-500 text-xs mt-1">Tidak dapat diubah</p>
                </div>

                <!-- Judul Materi -->
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-heading text-red-600 mr-2"></i>Judul Materi <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="title" id="title" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none @error('title') border-red-500 @enderror" 
                        value="{{ old('title', $material->title) }}" required>
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-align-left text-red-600 mr-2"></i>Deskripsi
                    </label>
                    <textarea name="description" id="description" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none @error('description') border-red-500 @enderror" 
                        rows="4">{{ old('description', $material->description) }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload -->
                <div>
                    <label for="file" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-file text-red-600 mr-2"></i>File Materi (Biarkan kosong jika tidak diubah)
                    </label>

                    @if($material->file_path)
                        <div class="mb-4 p-4 bg-blue-50 border-2 border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-700 font-semibold mb-2">
                                <i class="fas fa-file-download mr-2"></i>File saat ini:
                            </p>
                            <a href="{{ route('admin.materials.download', $material) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline font-semibold">
                                {{ basename($material->file_path) }}
                            </a>
                        </div>
                    @endif

                    <input type="file" name="file" id="file" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none @error('file') border-red-500 @enderror" 
                        accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.mp4,.mkv">
                    <p class="text-gray-600 text-xs mt-2">
                        <i class="fas fa-info-circle mr-1"></i>Format: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, JPG, PNG, MP4, MKV (Maks. 100MB)
                    </p>
                    @error('file')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold px-6 py-3 rounded-lg hover:from-red-700 hover:to-red-800 transition flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Update Materi
                    </button>
                    <a href="{{ route('admin.materials.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-6 py-3 rounded-lg transition flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
