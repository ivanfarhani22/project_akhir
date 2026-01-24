@extends('layouts.public')

@section('title', 'Berita - SRMA 25 Lamongan')

@section('content')
<!-- Page Header -->
<section class="bg-gray-800 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm mb-4">
            <ol class="flex items-center space-x-2 text-gray-400">
                <li><a href="{{ route('home') }}" class="hover:text-white">Beranda</a></li>
                <li><span>/</span></li>
                <li><span class="text-white">Berita</span></li>
            </ol>
        </nav>
        <h1 class="text-3xl md:text-4xl font-bold text-white">Berita Sekolah</h1>
        <p class="text-gray-400 mt-2">Informasi dan kegiatan terkini dari SRMA 25 Lamongan</p>
    </div>
</section>

<!-- Search -->
<section class="py-6 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form action="{{ route('berita.index') }}" method="GET" class="max-w-md">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berita..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </form>
    </div>
</section>

<!-- News List -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($news->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($news as $item)
            <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <a href="{{ route('berita.show', $item->slug) }}">
                    <div class="aspect-video bg-gray-200 overflow-hidden">
                        <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" 
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" loading="lazy">
                    </div>
                </a>
                <div class="p-5">
                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <span>{{ $item->published_at?->translatedFormat('d M Y') ?? '-' }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ $item->views }} kali dibaca</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">
                        <a href="{{ route('berita.show', $item->slug) }}" class="hover:text-primary-600">
                            {{ $item->title }}
                        </a>
                    </h3>
                    <p class="text-sm text-gray-500 line-clamp-3">{{ $item->excerpt ?: Str::limit(strip_tags($item->content), 120) }}</p>
                    <a href="{{ route('berita.show', $item->slug) }}" class="inline-flex items-center text-sm text-primary-600 font-medium mt-4 hover:text-primary-700">
                        Baca Selengkapnya
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </article>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $news->withQueryString()->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-700 mb-2">Belum ada berita</h3>
            <p class="text-gray-500">Berita akan segera ditambahkan.</p>
        </div>
        @endif
    </div>
</section>
@endsection
