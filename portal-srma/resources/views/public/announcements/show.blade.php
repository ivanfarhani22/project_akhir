@extends('layouts.public')

@section('title', $announcement->title . ' - SRMA 25 Lamongan')

@section('content')
<!-- Page Header -->
<section class="bg-gray-800 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm mb-4">
            <ol class="flex items-center space-x-2 text-gray-400">
                <li><a href="{{ route('home') }}" class="hover:text-white">Beranda</a></li>
                <li><span>/</span></li>
                <li><a href="{{ route('pengumuman.index') }}" class="hover:text-white">Pengumuman</a></li>
                <li><span>/</span></li>
                <li><span class="text-white line-clamp-1">{{ $announcement->title }}</span></li>
            </ol>
        </nav>
    </div>
</section>

<!-- Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <article class="bg-white rounded-xl shadow-sm p-6 md:p-8 {{ $announcement->is_important ? 'border-l-4 border-primary-500' : '' }}">
            <div class="flex items-center gap-2 mb-4">
                @if($announcement->is_important)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-700">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Pengumuman Penting
                </span>
                @endif
            </div>
            
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">{{ $announcement->title }}</h1>
            
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-6 pb-6 border-b">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $announcement->published_at?->translatedFormat('d F Y') ?? '-' }}
                </span>
                @if($announcement->expired_at)
                <span class="flex items-center text-orange-600">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Berlaku sampai {{ $announcement->expired_at->translatedFormat('d F Y') }}
                </span>
                @endif
            </div>
            
            <div class="prose prose-gray max-w-none">
                {!! nl2br(e($announcement->content)) !!}
            </div>
            
            {{-- File Lampiran --}}
            @if($announcement->attachment)
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    File Lampiran
                </h3>
                
                @php
                    $extension = pathinfo($announcement->attachment, PATHINFO_EXTENSION);
                    $isPdf = strtolower($extension) === 'pdf';
                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                    $isDocument = in_array(strtolower($extension), ['doc', 'docx']);
                    $isSpreadsheet = in_array(strtolower($extension), ['xls', 'xlsx']);
                    
                    $iconColor = 'text-gray-500';
                    $bgColor = 'bg-gray-100';
                    if ($isPdf) {
                        $iconColor = 'text-red-500';
                        $bgColor = 'bg-red-50';
                    } elseif ($isImage) {
                        $iconColor = 'text-blue-500';
                        $bgColor = 'bg-blue-50';
                    } elseif ($isDocument) {
                        $iconColor = 'text-blue-600';
                        $bgColor = 'bg-blue-50';
                    } elseif ($isSpreadsheet) {
                        $iconColor = 'text-green-600';
                        $bgColor = 'bg-green-50';
                    }
                @endphp
                
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 {{ $bgColor }} rounded-xl">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        {{-- Icon based on file type --}}
                        <div class="flex-shrink-0 w-12 h-12 {{ $bgColor }} rounded-lg flex items-center justify-center border border-gray-200">
                            @if($isPdf)
                            <svg class="w-8 h-8 {{ $iconColor }}" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM10.5 11v1h1v1h-1v2H9v-2H8v-1h1v-1h1.5zm3.5 0c.8 0 1.5.7 1.5 1.5v2c0 .8-.7 1.5-1.5 1.5h-1.5v-5H14zm-5 0v5H7.5v-2H7v-1h.5v-2H9zm5 1v3h.5c.3 0 .5-.2.5-.5v-2c0-.3-.2-.5-.5-.5H14z"/>
                            </svg>
                            @elseif($isImage)
                            <svg class="w-8 h-8 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            @elseif($isDocument)
                            <svg class="w-8 h-8 {{ $iconColor }}" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm4 18H6V4h7v5h5v11zM8 12h8v1H8v-1zm0 3h8v1H8v-1zm0 3h5v1H8v-1z"/>
                            </svg>
                            @elseif($isSpreadsheet)
                            <svg class="w-8 h-8 {{ $iconColor }}" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm4 18H6V4h7v5h5v11zM8 11h3v2H8v-2zm5 0h3v2h-3v-2zm-5 4h3v2H8v-2zm5 0h3v2h-3v-2z"/>
                            </svg>
                            @else
                            <svg class="w-8 h-8 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            @endif
                        </div>
                        
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-800 truncate">
                                {{ $announcement->attachment_name ?? basename($announcement->attachment) }}
                            </p>
                            <p class="text-xs text-gray-500 uppercase">{{ $extension }} File</p>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 flex-shrink-0">
                        @if($isImage)
                        <a href="{{ Storage::url($announcement->attachment) }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat
                        </a>
                        @endif
                        <a href="{{ Storage::url($announcement->attachment) }}" 
                           download="{{ $announcement->attachment_name ?? basename($announcement->attachment) }}"
                           class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
                
                {{-- Preview for images --}}
                @if($isImage)
                <div class="mt-4">
                    <img src="{{ Storage::url($announcement->attachment) }}" 
                         alt="{{ $announcement->attachment_name ?? 'Lampiran' }}"
                         class="max-w-full h-auto rounded-lg shadow-sm border border-gray-200">
                </div>
                @endif
                
                {{-- Preview for PDF --}}
                @if($isPdf)
                <div class="mt-4">
                    <div class="aspect-[4/3] w-full rounded-lg overflow-hidden border border-gray-200">
                        <iframe src="{{ Storage::url($announcement->attachment) }}" 
                                class="w-full h-full"
                                title="PDF Preview"></iframe>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 text-center">
                        Jika preview tidak muncul, silakan <a href="{{ Storage::url($announcement->attachment) }}" target="_blank" class="text-primary-600 hover:underline">klik di sini</a> untuk membuka file.
                    </p>
                </div>
                @endif
            </div>
            @endif
        </article>
        
        <!-- Back Link -->
        <div class="mt-6">
            <a href="{{ route('pengumuman.index') }}" class="inline-flex items-center text-gray-600 hover:text-primary-600">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Daftar Pengumuman
            </a>
        </div>
    </div>
</section>
@endsection
