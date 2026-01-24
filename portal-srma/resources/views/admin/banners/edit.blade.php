@extends('layouts.admin')

@section('title', 'Edit Banner')
@section('page-title', 'Edit Banner')

@section('content')
<form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="mb-6" x-data="{ preview: '{{ $banner->image_url }}' }">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Gambar Banner</label>
                <div class="mb-4">
                    <img :src="preview" class="w-full aspect-[3/1] object-cover rounded-lg">
                </div>
                <input type="file" name="image" id="image" accept="image/*" class="hidden"
                       @change="preview = URL.createObjectURL($event.target.files[0])">
                <label for="image" class="block w-full px-4 py-2 bg-gray-100 text-gray-700 text-center rounded-lg cursor-pointer hover:bg-gray-200 transition-colors">
                    Ganti Gambar
                </label>
                @error('image')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul</label>
                <input type="text" name="title" id="title" value="{{ old('title', $banner->title) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            
            <div class="mb-6">
                <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle', $banner->subtitle) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            
            <!-- <div class="mb-6">
                <label for="link" class="block text-sm font-medium text-gray-700 mb-2">Link</label>
                <input type="url" name="link" id="link" value="{{ old('link', $banner->link) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div> -->
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                    <input type="number" name="order" id="order" value="{{ old('order', $banner->order) }}" min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div class="flex items-end pb-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-700">Aktifkan</span>
                    </label>
                </div>
            </div>
            
            <div class="flex space-x-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    Update
                </button>
                <a href="{{ route('admin.banners.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Batal
                </a>
            </div>
        </div>
    </div>
</form>
@endsection
