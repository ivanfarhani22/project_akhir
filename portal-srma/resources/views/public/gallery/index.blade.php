@extends('layouts.public')

@section('title', 'Galeri Foto - SRMA 25 Lamongan')

@section('content')
<!-- Page Header -->
<section class="bg-gray-800 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm mb-4">
            <ol class="flex items-center space-x-2 text-gray-400">
                <li><a href="{{ route('home') }}" class="hover:text-white">Beranda</a></li>
                <li><span>/</span></li>
                <li><span class="text-white">Galeri</span></li>
            </ol>
        </nav>
        <h1 class="text-3xl md:text-4xl font-bold text-white">Galeri Foto</h1>
        <p class="text-gray-400 mt-2">Dokumentasi kegiatan SRMA 25 Lamongan</p>
    </div>
</section>

<!-- Category Filter -->
<section class="py-6 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('galeri.index') }}" 
               class="px-4 py-2 rounded-full text-sm font-medium {{ !request('category') ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                Semua
            </a>
            @foreach($categories as $category)
            <a href="{{ route('galeri.index', ['category' => $category->slug]) }}" 
               class="px-4 py-2 rounded-full text-sm font-medium {{ request('category') === $category->slug ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                {{ $category->name }} ({{ $category->galleries_count }})
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Gallery Grid -->
<section class="py-12 bg-gray-50" x-data="{ lightbox: false, currentImage: '', currentTitle: '' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($galleries->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($galleries as $gallery)
            <div class="group relative aspect-square bg-gray-200 rounded-xl overflow-hidden cursor-pointer"
                 @click="lightbox = true; currentImage = '{{ $gallery->image_url }}'; currentTitle = '{{ $gallery->title }}'">
                <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                    <div class="absolute bottom-0 left-0 right-0 p-4">
                        <h3 class="text-white font-medium text-sm line-clamp-2">{{ $gallery->title }}</h3>
                        @if($gallery->category)
                        <p class="text-white/70 text-xs mt-1">{{ $gallery->category->name }}</p>
                        @endif
                    </div>
                </div>
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                        </svg>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $galleries->withQueryString()->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-700 mb-2">Belum ada foto</h3>
            <p class="text-gray-500">Foto akan segera ditambahkan.</p>
        </div>
        @endif
    </div>
    
    <!-- Lightbox -->
    <div x-show="lightbox" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/90"
         @click.self="lightbox = false"
         @keydown.escape.window="lightbox = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <button @click="lightbox = false" class="absolute top-4 right-4 text-white hover:text-gray-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div class="max-w-4xl max-h-[90vh] p-4">
            <img :src="currentImage" :alt="currentTitle" class="max-w-full max-h-[80vh] rounded-lg mx-auto">
            <p x-text="currentTitle" class="text-white text-center mt-4"></p>
        </div>
    </div>
</section>
@endsection
