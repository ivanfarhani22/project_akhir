@extends('layouts.admin')

@section('title', 'Edit Data Siswa')
@section('page-title', 'Edit Data Siswa')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">Edit Data Siswa</h2>
        </div>
        
        <form action="{{ route('admin.student-data.update', $studentData) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajaran <span class="text-red-500">*</span></label>
                <input type="text" name="academic_year" value="{{ old('academic_year', $studentData->academic_year) }}" required placeholder="contoh: 2024/2025"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                @error('academic_year')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelas <span class="text-red-500">*</span></label>
                <select name="class_name" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">-- Pilih Kelas --</option>
                    <option value="X" {{ old('class_name', $studentData->class_name) === 'X' ? 'selected' : '' }}>Kelas X</option>
                    <option value="XI" {{ old('class_name', $studentData->class_name) === 'XI' ? 'selected' : '' }}>Kelas XI</option>
                    <option value="XII" {{ old('class_name', $studentData->class_name) === 'XII' ? 'selected' : '' }}>Kelas XII</option>
                </select>
                @error('class_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Laki-laki <span class="text-red-500">*</span></label>
                    <input type="number" name="male_count" value="{{ old('male_count', $studentData->male_count) }}" min="0" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    @error('male_count')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Perempuan <span class="text-red-500">*</span></label>
                    <input type="number" name="female_count" value="{{ old('female_count', $studentData->female_count) }}" min="0" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    @error('female_count')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Rombel <span class="text-red-500">*</span></label>
                <input type="number" name="study_groups" value="{{ old('study_groups', $studentData->study_groups) }}" min="1" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                @error('study_groups')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex items-center space-x-4 pt-4">
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    Perbarui
                </button>
                <a href="{{ route('admin.student-data.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
