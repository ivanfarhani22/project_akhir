@extends('layouts.admin')

@section('title', 'Edit Mata Pelajaran')
@section('icon', 'fas fa-edit')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 mb-6 text-sm text-gray-600">
            <a href="{{ route('admin.classes.index') }}" class="text-red-600 hover:text-red-700 font-medium">
                <i class="fas fa-chalkboard"></i> Kelola Kelas
            </a>
            <span>/</span>
            <a href="{{ route('admin.classes.show', $classSubject->eClass) }}" class="text-red-600 hover:text-red-700 font-medium">
                {{ $classSubject->eClass->name }}
            </a>
            <span>/</span>
            <span class="text-gray-700 font-semibold">Edit {{ $classSubject->subject->name }}</span>
        </div>

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                    <i class="fas fa-edit"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Mata Pelajaran</h1>
            </div>
            <p class="text-gray-600">Ubah guru pengajar atau deskripsi mata pelajaran</p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-600 mt-1"></i>
                    <div>
                        <p class="font-semibold text-red-900">Terjadi kesalahan:</p>
                        @foreach ($errors->all() as $error)
                            <p class="text-red-700 text-sm mt-1">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                <h2 class="text-white font-semibold text-lg flex items-center gap-2">
                    <i class="fas fa-pen-to-square"></i>
                    Form Edit Mata Pelajaran
                </h2>
            </div>

            <!-- Card Body -->
            <div class="p-6 space-y-6">
                <form method="POST" action="{{ route('admin.class-subjects.update', $classSubject) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Mata Pelajaran (Read-only) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Mata Pelajaran
                        </label>
                        <div class="px-4 py-3 bg-gray-100 border-2 border-gray-300 rounded-lg text-gray-700">
                            <i class="fas fa-book text-red-600"></i> {{ $classSubject->subject->name }} ({{ $classSubject->subject->code }})
                        </div>
                    </div>

                    <!-- Guru (dengan Search Select2) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Pilih Guru
                            <span class="text-red-600">*</span>
                        </label>
                        <select 
                            name="teacher_id" 
                            id="teacherSelect" 
                            required
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-red-500 transition"
                        >
                            <option value="">-- Cari & Pilih Guru --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $teacher->id == $classSubject->teacher_id ? 'selected' : '' }}>
                                    {{ $teacher->name }} ({{ $teacher->email }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-2">Guru yang akan mengajar mata pelajaran ini</p>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea 
                            name="description" 
                            rows="3"
                            placeholder="Deskripsi tentang mata pelajaran ini..."
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-red-500 transition resize-none"
                        >{{ old('description', $classSubject->description) }}</textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 justify-between pt-6 border-t border-gray-200">
                        <a 
                            href="{{ route('admin.classes.show', $classSubject->eClass) }}" 
                            class="inline-flex items-center gap-2 px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-50 transition"
                        >
                            <i class="fas fa-arrow-left"></i> Batal
                        </a>
                        <div class="flex gap-3">
                            <form method="POST" action="{{ route('admin.class-subjects.destroy', $classSubject) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 transition">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                            <button 
                                type="submit" 
                                class="inline-flex items-center gap-2 px-6 py-2 bg-red-500 text-white rounded-lg font-semibold text-sm hover:bg-red-600 transition"
                            >
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- CDN Select2 & Styles -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for Teacher
            $('#teacherSelect').select2({
                placeholder: 'Cari & Pilih Guru',
                allowClear: true,
                width: '100%'
            });
        });
    </script>

    <style>
        .select2-container--default .select2-selection--single {
            border: 2px solid #d1d5db !important;
            border-radius: 0.5rem;
            padding: 8px 12px !important;
            font-size: 14px;
            height: auto !important;
        }
        
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .select2-dropdown {
            border: 2px solid #d1d5db !important;
            border-radius: 0.5rem;
        }

        .select2-results__option--highlighted {
            background-color: #ef4444 !important;
        }
    </style>
@endsection
