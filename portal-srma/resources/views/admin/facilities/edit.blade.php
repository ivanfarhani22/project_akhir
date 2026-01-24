@extends('layouts.admin')

@section('title', 'Edit Sarana Prasarana')
@section('page-title', 'Edit Sarana Prasarana')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">Edit Data Sarana Prasarana</h2>
        </div>
        
        <form action="{{ route('admin.facilities.update', $facility) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar</label>
                @if($facility->image)
                <div class="mb-3">
                    <img src="{{ $facility->image_url }}" alt="{{ $facility->name }}" class="w-48 h-32 object-cover rounded-lg">
                </div>
                @endif
                <input type="file" name="image" accept="image/*" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah gambar</p>
                @error('image')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $facility->name) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="3" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('description', $facility->description) }}</textarea>
                @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" value="{{ old('quantity', $facility->quantity) }}" min="1" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    @error('quantity')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi <span class="text-red-500">*</span></label>
                    <select name="condition" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="baik" {{ old('condition', $facility->condition) === 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="cukup" {{ old('condition', $facility->condition) === 'cukup' ? 'selected' : '' }}>Cukup</option>
                        <option value="kurang" {{ old('condition', $facility->condition) === 'kurang' ? 'selected' : '' }}>Kurang</option>
                    </select>
                    @error('condition')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                <input type="number" name="order" value="{{ old('order', $facility->order) }}" min="0"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $facility->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                <label for="is_active" class="ml-2 text-sm text-gray-700">Aktif</label>
            </div>
            
            <div class="flex items-center space-x-4 pt-4">
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    Perbarui
                </button>
                <a href="{{ route('admin.facilities.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
