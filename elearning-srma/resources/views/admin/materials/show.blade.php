@extends('layouts.admin')

@section('title', 'Detail Materi')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-4 py-6 sm:py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center space-x-2 mb-6 text-xs sm:text-sm">
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
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-800 mb-2 break-words">{{ $material->title }}</h1>
            <p class="text-gray-600 text-xs sm:text-sm">Informasi lengkap materi pembelajaran</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <a href="{{ route('admin.materials.download', $material) }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-3 sm:px-6 py-2 sm:py-3 rounded-lg text-xs sm:text-sm transition whitespace-nowrap">
                <i class="fas fa-download"></i> <span class="hidden sm:inline">Download</span>
            </a>
            <a href="{{ route('admin.materials.edit', $material) }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold px-3 sm:px-6 py-2 sm:py-3 rounded-lg text-xs sm:text-sm transition whitespace-nowrap">
                <i class="fas fa-edit"></i> <span class="hidden sm:inline">Edit</span>
            </a>
            <form action="{{ route('admin.materials.destroy', $material) }}" method="POST" class="flex-1 sm:flex-none inline delete-form">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete(event, '{{ $material->title }}')" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white font-semibold px-3 sm:px-6 py-2 sm:py-3 rounded-lg text-xs sm:text-sm transition whitespace-nowrap">
                    <i class="fas fa-trash"></i> <span class="hidden sm:inline">Hapus</span>
                </button>
            </form>
            <a href="{{ route('admin.materials.index') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-gray-400 hover:bg-gray-500 text-white font-semibold px-3 sm:px-6 py-2 sm:py-3 rounded-lg text-xs sm:text-sm transition whitespace-nowrap">
                <i class="fas fa-arrow-left"></i> <span class="hidden sm:inline">Kembali</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        <!-- Left Column - Main Info -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-red-500 to-red-600 px-3 sm:px-6 py-3 sm:py-4">
                    <h2 class="text-base sm:text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-book"></i><span class="hidden sm:inline">Informasi Materi</span><span class="sm:hidden">Info</span>
                    </h2>
                </div>

                <!-- Content -->
                <div class="p-3 sm:p-6 space-y-6">
                    <!-- Kelas -->
                    <div class="pb-6 border-b border-gray-200">
                        <h3 class="text-xs sm:text-sm font-semibold text-gray-600 mb-2">
                            <i class="fas fa-door-open text-red-600 mr-2"></i>Kelas
                        </h3>
                        <p class="text-sm sm:text-lg font-semibold text-gray-800">
                            <span class="inline-block px-2 sm:px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap">
                                {{ $material->eClass->name }}
                            </span>
                        </p>
                    </div>

                    <!-- Deskripsi -->
                    <div class="pb-6 border-b border-gray-200">
                        <h3 class="text-xs sm:text-sm font-semibold text-gray-600 mb-2">
                            <i class="fas fa-align-left text-red-600 mr-2"></i>Deskripsi
                        </h3>
                        <p class="text-gray-700 leading-relaxed text-xs sm:text-sm">
                            @if($material->description)
                                {{ $material->description }}
                            @else
                                <span class="text-gray-400 italic">Tidak ada deskripsi</span>
                            @endif
                        </p>
                    </div>

                    <!-- File -->
                    <div class="pb-6 border-b border-gray-200">
                        <h3 class="text-xs sm:text-sm font-semibold text-gray-600 mb-2">
                            <i class="fas fa-file text-red-600 mr-2"></i>File
                        </h3>
                        @if($material->file_path)
                            <a href="{{ route('admin.materials.download', $material) }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold mb-2 text-xs sm:text-sm break-all">
                                <i class="fas fa-download flex-shrink-0"></i> {{ basename($material->file_path) }}
                            </a>
                            <br>
                            <small class="text-gray-500 text-xs">
                                <i class="fas fa-database mr-1"></i>Ukuran: {{ \App\Services\FileUploadService::formatFileSize((int) ($material->file_size ?? 0)) }}
                            </small>
                        @else
                            <span class="text-gray-400 italic text-xs sm:text-sm">File tidak tersedia</span>
                        @endif
                    </div>

                    <!-- Timestamps -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-xs sm:text-sm font-semibold text-gray-600 mb-1">
                                <i class="fas fa-calendar-plus text-red-600 mr-2"></i>Dibuat
                            </h3>
                            <p class="text-gray-700 text-xs sm:text-sm">{{ $material->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <h3 class="text-xs sm:text-sm font-semibold text-gray-600 mb-1">
                                <i class="fas fa-calendar-check text-red-600 mr-2"></i>Diupdate
                            </h3>
                            <p class="text-gray-700 text-xs sm:text-sm">{{ $material->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar Info -->
        <div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-3 sm:px-6 py-3 sm:py-4">
                    <h2 class="text-base sm:text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-circle-info"></i><span class="hidden sm:inline">Informasi</span>
                    </h2>
                </div>

                <!-- Content -->
                <div class="p-3 sm:p-6 space-y-6">
                    <!-- Status -->
                    <div class="pb-6 border-b border-gray-200">
                        <h3 class="text-xs sm:text-sm font-semibold text-gray-600 mb-2">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>Status
                        </h3>
                        <span class="inline-block px-2 sm:px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs sm:text-sm font-semibold">
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


        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(event, name) {
    event.preventDefault();
    const form = event.target.closest('form');
    showConfirmation(`Yakin ingin menghapus materi "${name}"?`, 'Konfirmasi Hapus', function() {
        form.submit();
    });
}
</script>
@endpush
