@extends('layouts.admin')

@section('title', 'Edit Foto')
@section('page-title', 'Edit Foto')

@section('content')
<form action="{{ route('admin.galleries.update', $gallery) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Foto <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title', $gallery->title) }}" required
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
                    <option value="{{ $category->id }}" {{ old('category_id', $gallery->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6" x-data="{ preview: '{{ $gallery->image_url }}' }">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Foto</label>
                <div class="mb-4">
                    <img :src="preview" class="w-full aspect-video object-cover rounded-lg">
                </div>
                <input type="file" name="image" id="image" accept="image/*" class="hidden"
                       @change="preview = URL.createObjectURL($event.target.files[0])">
                <label for="image" class="block w-full px-4 py-2 bg-gray-100 text-gray-700 text-center rounded-lg cursor-pointer hover:bg-gray-200 transition-colors">
                    Ganti Foto
                </label>
                @error('image')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('description', $gallery->description) }}</textarea>
            </div>
            
            <div class="flex space-x-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    Update
                </button>
                <a href="{{ route('admin.galleries.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Batal
                </a>
            </div>
        </div>
    </div>
</form>
@endsection
