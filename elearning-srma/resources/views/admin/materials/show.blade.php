@extends('layouts.admin')

@section('title', 'Detail Materi')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center space-x-2 mb-6">
        <i class="fas fa-book text-red-600"></i>
        <span class="text-gray-600">Admin</span>
        <span class="text-gray-400">/</span>
        <span class="text-gray-600">
            <a href="{{ route('admin.materials.index') }}" class="hover:text-red-600">Materi Pembelajaran</a>
        </span>
        <span class="text-gray-400">/</span>
        <span class="font-semibold text-gray-800">Detail Materi</span>
    </div>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800 mb-2">{{ $material->title }}</h1>
            <p class="text-gray-600">Informasi lengkap materi pembelajaran</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2">
            <a href="{{ route('admin.materials.download', $material) }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                <i class="fas fa-download"></i> Download
            </a>
            <a href="{{ route('admin.materials.edit', $material) }}" class="inline-flex items-center gap-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('admin.materials.destroy', $material) }}" method="POST" class="inline" 
                onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </form>
            <a href="{{ route('admin.materials.index') }}" class="inline-flex items-center gap-2 bg-gray-400 hover:bg-gray-500 text-white font-semibold px-6 py-3 rounded-lg transition">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Main Info -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-book"></i>Informasi Materi
                    </h2>
                </div>

                <!-- Content -->
                <div class="p-6 space-y-6">
                    <!-- Kelas -->
                    <div class="pb-6 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">
                            <i class="fas fa-door-open text-red-600 mr-2"></i>Kelas
                        </h3>
                        <p class="text-lg font-semibold text-gray-800">
                            <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                                {{ $material->eClass->name }}
                            </span>
                        </p>
                    </div>

                    <!-- Deskripsi -->
                    <div class="pb-6 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">
                            <i class="fas fa-align-left text-red-600 mr-2"></i>Deskripsi
                        </h3>
                        <p class="text-gray-700 leading-relaxed">
                            @if($material->description)
                                {{ $material->description }}
                            @else
                                <span class="text-gray-400 italic">Tidak ada deskripsi</span>
                            @endif
                        </p>
                    </div>

                    <!-- File -->
                    <div class="pb-6 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">
                            <i class="fas fa-file text-red-600 mr-2"></i>File
                        </h3>
                        @if($material->file_path)
                            <a href="{{ route('admin.materials.download', $material) }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold mb-2">
                                <i class="fas fa-download"></i> {{ basename($material->file_path) }}
                            </a>
                            <br>
                            <small class="text-gray-500">
                                <i class="fas fa-database mr-1"></i>Ukuran: {{ formatFileSize($material->file_size ?? 0) }}
                            </small>
                        @else
                            <span class="text-gray-400 italic">File tidak tersedia</span>
                        @endif
                    </div>

                    <!-- Timestamps -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-600 mb-1">
                                <i class="fas fa-calendar-plus text-red-600 mr-2"></i>Dibuat
                            </h3>
                            <p class="text-gray-700">{{ $material->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-600 mb-1">
                                <i class="fas fa-calendar-check text-red-600 mr-2"></i>Diupdate
                            </h3>
                            <p class="text-gray-700">{{ $material->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar Info -->
        <div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-circle-info"></i>Informasi
                    </h2>
                </div>

                <!-- Content -->
                <div class="p-6 space-y-6">
                    <!-- Status -->
                    <div class="pb-6 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>Status
                        </h3>
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                            Aktif
                        </span>
                    </div>

                    <!-- Uploader -->
                    <div class="pb-6 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">
                            <i class="fas fa-user-circle text-blue-600 mr-2"></i>Upload Oleh
                        </h3>
                        <p class="text-gray-700 font-semibold">{{ $material->uploadedBy->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $material->uploadedBy->email ?? '' }}</p>
                    </div>

                    <!-- Material ID -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">
                            <i class="fas fa-hashtag text-gray-600 mr-2"></i>ID Materi
                        </h3>
                        <code class="block bg-gray-100 px-3 py-2 rounded font-mono text-xs text-gray-700 break-all">
                            {{ $material->id }}
                        </code>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 bg-blue-50 border-2 border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-700 font-semibold mb-3">
                    <i class="fas fa-lightbulb mr-2"></i>Aksi Cepat
                </p>
                <div class="space-y-2">
                    <a href="{{ route('admin.materials.download', $material) }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm">
                        <i class="fas fa-download mr-2"></i>Unduh Sekarang
                    </a>
                    <a href="{{ route('admin.materials.edit', $material) }}" class="block w-full text-center bg-blue-100 hover:bg-blue-200 text-blue-700 font-semibold px-4 py-2 rounded-lg transition text-sm">
                        <i class="fas fa-edit mr-2"></i>Edit Materi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
