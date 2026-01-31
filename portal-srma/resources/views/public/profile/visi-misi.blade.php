@extends('layouts.public')

@section('title', 'Visi & Misi - SRMA 25 Lamongan')

@section('content')
<!-- Page Header -->
<section class="bg-gray-800 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm mb-4">
            <ol class="flex items-center space-x-2 text-gray-400">
                <li><a href="{{ route('home') }}" class="hover:text-white">Beranda</a></li>
                <li><span>/</span></li>
                <li><span class="text-white">Profil</span></li>
                <li><span>/</span></li>
                <li><span class="text-white">Visi & Misi</span></li>
            </ol>
        </nav>
        <h1 class="text-3xl md:text-4xl font-bold text-white">Visi & Misi</h1>
    </div>
</section>

<!-- Content -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                @include('partials.profile-sidebar')
            </div>
            
            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-8">
                <!-- Visi -->
                <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Visi</h2>
                    </div>
                    <div class="prose prose-gray max-w-none">
                        @if($visi)
                            {!! clean($visi, 'simple') !!}
                        @else
                            <div class="bg-gray-50 rounded-lg p-6 text-center">
                                <p class="text-gray-500">Konten akan disesuaikan dengan PPT resmi sekolah.</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Misi -->
                <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Misi</h2>
                    </div>
                    @if($misi)
                    <div class="space-y-3">
                        @foreach(explode("\n", $misi) as $index => $item)
                            @if(trim($item))
                            <div class="flex items-start">
                                <span class="flex-shrink-0 w-7 h-7 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm font-medium mr-3 mt-0.5">{{ $loop->iteration }}</span>
                                <span class="text-gray-700">{{ trim($item, '- ') }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    @else
                    <div class="bg-gray-50 rounded-lg p-6 text-center">
                        <p class="text-gray-500">Konten akan disesuaikan dengan PPT resmi sekolah.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
