@extends('layouts.public')

@section('title', $agenda->title . ' - SRMA 25 Lamongan')

@section('content')
<!-- Page Header -->
<section class="bg-gray-800 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm mb-4">
            <ol class="flex items-center space-x-2 text-gray-400">
                <li><a href="{{ route('home') }}" class="hover:text-white">Beranda</a></li>
                <li><span>/</span></li>
                <li><a href="{{ route('agenda.index') }}" class="hover:text-white">Agenda</a></li>
                <li><span>/</span></li>
                <li><span class="text-white line-clamp-1">{{ $agenda->title }}</span></li>
            </ol>
        </nav>
    </div>
</section>

<!-- Content -->
<section class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <article class="bg-white rounded-xl shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="bg-primary-600 text-white p-6">
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $agenda->status === 'upcoming' ? 'bg-blue-500' : '' }}
                        {{ $agenda->status === 'ongoing' ? 'bg-green-500' : '' }}
                        {{ $agenda->status === 'completed' ? 'bg-gray-500' : '' }}
                        {{ $agenda->status === 'cancelled' ? 'bg-red-500' : '' }}">
                        {{ ucfirst($agenda->status) }}
                    </span>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold">{{ $agenda->title }}</h1>
            </div>
            
            <!-- Details -->
            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal</p>
                            <p class="font-medium text-gray-800">{{ $agenda->formatted_date }}</p>
                        </div>
                    </div>
                    
                    @if($agenda->formatted_time)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Waktu</p>
                            <p class="font-medium text-gray-800">{{ $agenda->formatted_time }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($agenda->location)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Lokasi</p>
                            <p class="font-medium text-gray-800">{{ $agenda->location }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                
                @if($agenda->description)
                <div class="border-t pt-6">
                    <h3 class="font-semibold text-gray-800 mb-3">Deskripsi</h3>
                    <div class="prose prose-gray max-w-none">
                        {!! nl2br(e($agenda->description)) !!}
                    </div>
                </div>
                @endif
            </div>
        </article>
        
        <!-- Related Agendas -->
        @if($relatedAgendas->count() > 0)
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Agenda Lainnya</h3>
            <div class="grid md:grid-cols-3 gap-4">
                @foreach($relatedAgendas as $related)
                <a href="{{ route('agenda.show', $related->slug) }}" class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-primary-100 rounded-lg flex flex-col items-center justify-center">
                            <span class="text-sm font-bold text-primary-600">{{ $related->start_date->format('d') }}</span>
                            <span class="text-xs text-primary-500 uppercase">{{ $related->start_date->translatedFormat('M') }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-800 line-clamp-2">{{ $related->title }}</h4>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Back Link -->
        <div class="mt-6">
            <a href="{{ route('agenda.index') }}" class="inline-flex items-center text-gray-600 hover:text-primary-600">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Agenda
            </a>
        </div>
    </div>
</section>
@endsection
