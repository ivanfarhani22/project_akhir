@extends('layouts.admin')

@section('title', 'Edit Berita')
@section('page-title', 'Edit Berita')

@section('content')
<form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Berita <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title', $news->title) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('title') border-red-500 @enderror"
                           placeholder="Masukkan judul berita">
                    @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">Ringkasan</label>
                    <textarea name="excerpt" id="excerpt" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('excerpt') border-red-500 @enderror"
                              placeholder="Ringkasan singkat berita (opsional)">{{ old('excerpt', $news->excerpt) }}</textarea>
                    @error('excerpt')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Konten <span class="text-red-500">*</span></label>
                    <textarea name="content" id="content" rows="15" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('content') border-red-500 @enderror"
                              placeholder="Tulis konten berita...">{{ old('content', $news->content) }}</textarea>
                    @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Publish Settings -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Publikasi</h3>
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $news->status === 'published') ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-700">Publikasikan</span>
                    </label>
                </div>
                
                <div class="text-sm text-gray-500 mb-4">
                    <p>Dibuat: {{ $news->created_at->format('d M Y H:i') }}</p>
                    <p>Views: {{ number_format($news->views) }}</p>
                </div>
                
                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        Update
                    </button>
                    <a href="{{ route('admin.news.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </a>
                </div>
            </div>
            
            <!-- Featured Image -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Gambar</h3>
                
                <div x-data="{ preview: '{{ $news->thumbnail ? Storage::url($news->thumbnail) : '' }}' }">
                    <div class="mb-4">
                        <div class="w-full aspect-video bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden" x-show="!preview">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <img x-show="preview" :src="preview" class="w-full aspect-video object-cover rounded-lg" x-cloak>
                    </div>
                    
                    @if($news->thumbnail)
                    <label class="flex items-center mb-3">
                        <input type="checkbox" name="remove_image" value="1"
                               class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <span class="ml-2 text-sm text-red-600">Hapus gambar</span>
                    </label>
                    @endif
                    
                    <input type="file" name="image" id="image" accept="image/*" class="hidden"
                           @change="preview = URL.createObjectURL($event.target.files[0])">
                    <label for="image" class="block w-full px-4 py-2 bg-gray-100 text-gray-700 text-center rounded-lg cursor-pointer hover:bg-gray-200 transition-colors">
                        {{ $news->thumbnail ? 'Ganti Gambar' : 'Pilih Gambar' }}
                    </label>
                    <p class="text-xs text-gray-500 mt-2">JPG, PNG, WEBP (Max 2MB)</p>
                    @error('image')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
