@extends('layouts.admin')

@section('title', 'Tambah Foto')
@section('page-title', 'Tambah Foto')

@section('content')
<form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Foto <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('title') border-red-500 @enderror">
                @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                <select name="category_id" id="category_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('category_id') border-red-500 @enderror">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6" x-data="{ preview: null }">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Foto <span class="text-red-500">*</span></label>
                <div class="mb-4">
                    <div class="w-full aspect-video bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden" x-show="!preview">
                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <img x-show="preview" :src="preview" class="w-full aspect-video object-cover rounded-lg" x-cloak>
                </div>
                <input type="file" name="image" id="image" accept="image/*" required class="hidden"
                       @change="preview = URL.createObjectURL($event.target.files[0])">
                <label for="image" class="block w-full px-4 py-2 bg-gray-100 text-gray-700 text-center rounded-lg cursor-pointer hover:bg-gray-200 transition-colors">
                    Pilih Foto
                </label>
                @error('image')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('description') }}</textarea>
            </div>
            
            <div class="flex space-x-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    Simpan
                </button>
                <a href="{{ route('admin.galleries.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Batal
                </a>
            </div>
        </div>
    </div>
</form>
@endsection
