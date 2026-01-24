@extends('layouts.admin')

@section('title', 'Tambah Banner')
@section('page-title', 'Tambah Banner')

@section('content')
<form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="mb-6" x-data="{ preview: null }">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Gambar Banner <span class="text-red-500">*</span></label>
                <div class="mb-4">
                    <div class="w-full aspect-[3/1] bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden" x-show="!preview">
                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <img x-show="preview" :src="preview" class="w-full aspect-[3/1] object-cover rounded-lg" x-cloak>
                </div>
                <input type="file" name="image" id="image" accept="image/*" required class="hidden"
                       @change="preview = URL.createObjectURL($event.target.files[0])">
                <label for="image" class="block w-full px-4 py-2 bg-gray-100 text-gray-700 text-center rounded-lg cursor-pointer hover:bg-gray-200 transition-colors">
                    Pilih Gambar (1920x640 px recommended)
                </label>
                @error('image')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                       placeholder="Judul yang tampil di banner">
            </div>
            
            <div class="mb-6">
                <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                       placeholder="Teks tambahan">
            </div>
            
            <!-- <div class="mb-6">
                <label for="link" class="block text-sm font-medium text-gray-700 mb-2">Link</label>
                <input type="url" name="link" id="link" value="{{ old('link') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                       placeholder="https://...">
            </div> -->
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                    <input type="number" name="order" id="order" value="{{ old('order', 0) }}" min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div class="flex items-end pb-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-700">Aktifkan</span>
                    </label>
                </div>
            </div>
            
            <div class="flex space-x-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    Simpan
                </button>
                <a href="{{ route('admin.banners.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Batal
                </a>
            </div>
        </div>
    </div>
</form>
@endsection
