@extends('layouts.public')

@section('title', 'Pengumuman - SRMA 25 Lamongan')

@section('content')
<!-- Page Header -->
<section class="bg-gray-800 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm mb-4">
            <ol class="flex items-center space-x-2 text-gray-400">
                <li><a href="{{ route('home') }}" class="hover:text-white">Beranda</a></li>
                <li><span>/</span></li>
                <li><span class="text-white">Pengumuman</span></li>
            </ol>
        </nav>
        <h1 class="text-3xl md:text-4xl font-bold text-white">Pengumuman</h1>
        <p class="text-gray-400 mt-2">Informasi penting dari SRMA 25 Lamongan</p>
    </div>
</section>

<!-- Announcements List -->
<section class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($announcements->count() > 0)
        <div class="space-y-4">
            @foreach($announcements as $announcement)
            <a href="{{ route('pengumuman.show', $announcement->slug) }}" 
               class="block bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow {{ $announcement->is_important ? 'border-l-4 border-primary-500' : '' }}">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            @if($announcement->is_important)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-700">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Penting
                            </span>
                            @endif
                            @if($announcement->attachment)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                Lampiran
                            </span>
                            @endif
                            <span class="text-sm text-gray-500">{{ $announcement->published_at?->translatedFormat('d M Y') }}</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 hover:text-primary-600 transition-colors">
                            {{ $announcement->title }}
                        </h3>
                        <p class="text-gray-500 mt-2 line-clamp-2">{{ Str::limit(strip_tags($announcement->content), 150) }}</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $announcements->links() }}
        </div>
        @else
        <div class="text-center py-16 bg-white rounded-xl">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-700 mb-2">Belum ada pengumuman</h3>
            <p class="text-gray-500">Pengumuman akan segera ditambahkan.</p>
        </div>
        @endif
    </div>
</section>
@endsection
