@extends('layouts.admin')

@section('title', 'Tambah Tenaga Kependidikan')
@section('page-title', 'Tambah Tenaga Kependidikan')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">Tambah Data Tenaga Kependidikan</h2>
        </div>
        
        <form action="{{ route('admin.staff.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto</label>
                <input type="file" name="photo" accept="image/*" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                @error('photo')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">NIP</label>
                <input type="text" name="nip" value="{{ old('nip') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                @error('nip')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Posisi/Jabatan <span class="text-red-500">*</span></label>
                <input type="text" name="position" value="{{ old('position') }}" required placeholder="contoh: Tata Usaha, Operator, Pustakawan"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                @error('position')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <p class="text-sm text-gray-500 mt-1">Urutan tampil (0 = paling atas)</p>
            </div>
            
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked
                       class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                <label for="is_active" class="ml-2 text-sm text-gray-700">Aktif</label>
            </div>
            
            <div class="flex items-center space-x-4 pt-4">
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    Simpan
                </button>
                <a href="{{ route('admin.staff.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
