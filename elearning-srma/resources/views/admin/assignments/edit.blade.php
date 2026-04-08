@extends('layouts.admin')

@section('title', 'Edit Tugas')
@section('icon', 'fas fa-tasks')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 mb-8 text-sm text-gray-600">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600 transition">Dashboard</a>
        <span class="text-gray-400">/</span>
        <a href="{{ route('admin.assignments.index') }}" class="hover:text-red-600 transition">Tugas</a>
        <span class="text-gray-400">/</span>
        <span class="text-red-600 font-semibold">Edit Tugas</span>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            <span class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                <i class="fas fa-pencil"></i>
            </span>
            Edit Tugas
        </h1>
        <p class="text-gray-600 mt-2">{{ $assignment->title }}</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
            <h2 class="text-white font-semibold text-lg flex items-center gap-2">
                <i class="fas fa-pen-to-square"></i>
                Form Edit Tugas
            </h2>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <form action="{{ route('admin.assignments.update', $assignment) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Kelas & Mata Pelajaran Grid (Read-only) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Kelas
                        </label>
                        <input type="text" disabled class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm bg-gray-100 text-gray-600" 
                            value="{{ $assignment->classSubject->eClass->name }}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Mata Pelajaran
                        </label>
                        <input type="text" disabled class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm bg-gray-100 text-gray-600" 
                            value="{{ $assignment->classSubject->subject->name }}">
                    </div>
                </div>

                <!-- Judul Tugas -->
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                        Judul Tugas <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="title" id="title" placeholder="Masukkan judul tugas" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('title') border-red-500 @enderror" 
                        value="{{ old('title', $assignment->title) }}" required>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Deskripsi <span class="text-red-600">*</span>
                    </label>
                    <textarea name="description" id="description" rows="4" placeholder="Masukkan deskripsi tugas" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition resize-none @error('description') border-red-500 @enderror" required>{{ old('description', $assignment->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deadline & Nilai Maksimal Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="deadline" class="block text-sm font-semibold text-gray-700 mb-2">
                            Deadline <span class="text-red-600">*</span>
                        </label>
                        <input type="datetime-local" name="deadline" id="deadline" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('deadline') border-red-500 @enderror" 
                            value="{{ old('deadline', $assignment->deadline ? $assignment->deadline->format('Y-m-d\TH:i') : '') }}" required>
                        @error('deadline')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="max_score" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nilai Maksimal <span class="text-red-600">*</span>
                        </label>
                        <input type="number" name="max_score" id="max_score" placeholder="100" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('max_score') border-red-500 @enderror" 
                            value="{{ old('max_score', $assignment->max_score) }}" min="1" required>
                        @error('max_score')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.assignments.index') }}" class="inline-flex items-center gap-2 px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-50 transition">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="ml-auto inline-flex items-center gap-2 px-6 py-2 bg-red-500 text-white rounded-lg font-semibold text-sm hover:bg-red-600 transition">
                        <i class="fas fa-save"></i> Perbarui Tugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
