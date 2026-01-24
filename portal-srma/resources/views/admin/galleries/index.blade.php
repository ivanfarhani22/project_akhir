@extends('layouts.admin')

@section('title', 'Kelola Galeri')
@section('page-title', 'Kelola Galeri')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Galeri Foto</h2>
                <p class="text-sm text-gray-500">Kelola foto dokumentasi kegiatan</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.galleries.categories') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Kategori
                </a>
                <a href="{{ route('admin.galleries.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Foto
                </a>
            </div>
        </div>
    </div>
    
    <!-- Filter -->
    <div class="p-6 border-b border-gray-100">
        <form action="{{ route('admin.galleries.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                Filter
            </button>
        </form>
    </div>
    
    <!-- Gallery Grid -->
    <div class="p-6">
        @if($galleries->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($galleries as $gallery)
            <div class="group relative aspect-square bg-gray-100 rounded-lg overflow-hidden">
                <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center space-x-2">
                    <a href="{{ route('admin.galleries.edit', $gallery) }}" class="p-2 bg-white rounded-lg text-gray-700 hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </a>
                    <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" class="inline" onsubmit="return confirm('Hapus foto ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 bg-white rounded-lg text-red-600 hover:bg-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
                <div class="absolute bottom-0 left-0 right-0 p-3 bg-gradient-to-t from-black/70 to-transparent">
                    <p class="text-white text-sm font-medium truncate">{{ $gallery->title }}</p>
                    @if($gallery->category)
                    <p class="text-white/70 text-xs">{{ $gallery->category->name }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $galleries->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-gray-500">Belum ada foto. <a href="{{ route('admin.galleries.create') }}" class="text-primary-600 hover:underline">Tambah foto pertama</a></p>
        </div>
        @endif
    </div>
</div>
@endsection
