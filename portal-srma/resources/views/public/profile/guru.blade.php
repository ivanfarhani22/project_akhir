@extends('layouts.public')

@section('title', 'Guru - SRMA 25 Lamongan')

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
                <li><span class="text-white">Guru</span></li>
            </ol>
        </nav>
        <h1 class="text-3xl md:text-4xl font-bold text-white">Data Guru</h1>
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
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Daftar Guru</h2>
                    
                    @if($teachers->count() > 0)
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($teachers as $teacher)
                            <div class="bg-gray-50 rounded-xl overflow-hidden text-center">
                                <div class="pt-6 px-6">
                                    @if($teacher->photo)
                                    <img src="{{ $teacher->photo_url }}" alt="{{ $teacher->name }}" class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-white shadow-lg">
                                    @else
                                    <div class="w-32 h-32 rounded-full mx-auto bg-gray-300 flex items-center justify-center border-4 border-white shadow-lg">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800">{{ $teacher->name }}</h3>
                                    @if($teacher->nip)
                                    <p class="text-xs text-gray-500 mb-2">NIP: {{ $teacher->nip }}</p>
                                    @endif
                                    @if($teacher->position)
                                    <p class="text-sm text-primary-600 font-medium">{{ $teacher->position }}</p>
                                    @endif
                                    @if($teacher->subject)
                                    <p class="text-sm text-gray-600">{{ $teacher->subject }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-6 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <p class="text-gray-500">Data guru akan segera ditambahkan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
