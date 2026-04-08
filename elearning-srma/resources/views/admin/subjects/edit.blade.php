@extends('layouts.admin')

@section('title', 'Edit Mata Pelajaran')
@section('icon', 'fas fa-edit')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <p class="text-gray-500 text-xs sm:text-sm mb-2">Manajemen Mata Pelajaran</p>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center gap-3">
            <i class="fas fa-edit text-red-500"></i>
            Edit Mata Pelajaran
        </h1>
        <p class="text-gray-500 text-xs sm:text-sm mt-2 truncate">{{ $subject->name }}</p>
    </div>

    <!-- Error Alert -->
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 p-3 sm:p-4 mb-6 rounded">
            <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
            <div>
                <strong class="text-red-900 text-xs sm:text-sm">Terjadi kesalahan:</strong>
                <div class="text-red-800 text-xs mt-1">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-3 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h2 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-3">
                    <i class="fas fa-book text-red-500"></i>
                    Form Edit Mata Pelajaran
                </h2>
            </div>

            <div class="p-3 sm:p-6">
                <form method="POST" action="{{ route('admin.subjects.update', $subject) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Nama Mata Pelajaran -->
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                            Nama Mata Pelajaran <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            value="{{ old('name', $subject->name) }}" 
                            placeholder="Misal: Matematika, Bahasa Inggris"
                            class="w-full px-3 sm:px-4 py-2 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition"
                            required
                        >
                    </div>

                    <!-- Kode Mata Pelajaran -->
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                            Kode Mata Pelajaran <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="code" 
                            id="code" 
                            value="{{ old('code', $subject->code) }}" 
                            placeholder="Misal: MAT, IPA, BIN"
                            maxlength="10"
                            class="w-full px-3 sm:px-4 py-2 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition uppercase"
                            required
                        >
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                            Deskripsi (Opsional)
                        </label>
                        <textarea 
                            name="description" 
                            id="description"
                            rows="4"
                            placeholder="Deskripsi singkat tentang mata pelajaran ini..."
                            class="w-full px-3 sm:px-4 py-2 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition resize-vertical"
                        >{{ old('description', $subject->description) }}</textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button 
                            type="submit" 
                            class="flex-1 inline-flex items-center justify-center gap-2 bg-red-500 text-white px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition"
                        >
                            <i class="fas fa-save"></i> <span class="hidden sm:inline">Perbarui Mata Pelajaran</span><span class="sm:hidden">Perbarui</span>
                        </button>
                        <a 
                            href="{{ route('admin.subjects.index') }}" 
                            class="flex-1 inline-flex items-center justify-center gap-2 bg-gray-300 text-gray-900 px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-gray-400 transition"
                        >
                            <i class="fas fa-arrow-left"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
