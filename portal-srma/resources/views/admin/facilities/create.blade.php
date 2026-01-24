@extends('layouts.admin')

@section('title', 'Tambah Sarana Prasarana')
@section('page-title', 'Tambah Sarana Prasarana')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">Tambah Data Sarana Prasarana</h2>
        </div>
        
        <form action="{{ route('admin.facilities.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar</label>
                <input type="file" name="image" accept="image/*" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                @error('image')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="contoh: Ruang Kelas, Laboratorium, Perpustakaan"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="3" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    @error('quantity')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi <span class="text-red-500">*</span></label>
                    <select name="condition" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="baik" {{ old('condition') === 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="cukup" {{ old('condition') === 'cukup' ? 'selected' : '' }}>Cukup</option>
                        <option value="kurang" {{ old('condition') === 'kurang' ? 'selected' : '' }}>Kurang</option>
                    </select>
                    @error('condition')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
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
                <a href="{{ route('admin.facilities.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
