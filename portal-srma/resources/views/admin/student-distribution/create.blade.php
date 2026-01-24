@extends('layouts.admin')

@section('title', 'Tambah Persebaran Siswa')
@section('page-title', 'Tambah Persebaran Siswa')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">Tambah Data Persebaran Siswa</h2>
        </div>
        
        <form action="{{ route('admin.student-distribution.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajaran <span class="text-red-500">*</span></label>
                <input type="text" name="academic_year" value="{{ old('academic_year', date('Y').'/'.(date('Y')+1)) }}" required placeholder="contoh: 2024/2025"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                @error('academic_year')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan/Wilayah <span class="text-red-500">*</span></label>
                <input type="text" name="district" value="{{ old('district') }}" required placeholder="contoh: Kecamatan Nglegok"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                @error('district')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Siswa <span class="text-red-500">*</span></label>
                <input type="number" name="student_count" value="{{ old('student_count', 0) }}" min="0" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                @error('student_count')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex items-center space-x-4 pt-4">
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    Simpan
                </button>
                <a href="{{ route('admin.student-distribution.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
