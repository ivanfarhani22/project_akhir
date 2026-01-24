@extends('layouts.public')

@section('title', 'Tenaga Kependidikan - SRMA 25 Lamongan')

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
                <li><span class="text-white">Tenaga Kependidikan</span></li>
            </ol>
        </nav>
        <h1 class="text-3xl md:text-4xl font-bold text-white">Tenaga Kependidikan</h1>
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
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Daftar Tenaga Kependidikan</h2>
                    
                    @if($staff->count() > 0)
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($staff as $member)
                            <div class="bg-gray-50 rounded-xl overflow-hidden text-center">
                                <div class="pt-6 px-6">
                                    @if($member->photo)
                                    <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-white shadow-lg">
                                    @else
                                    <div class="w-32 h-32 rounded-full mx-auto bg-gray-300 flex items-center justify-center border-4 border-white shadow-lg">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800">{{ $member->name }}</h3>
                                    @if($member->nip)
                                    <p class="text-xs text-gray-500 mb-2">NIP: {{ $member->nip }}</p>
                                    @endif
                                    @if($member->position)
                                    <p class="text-sm text-primary-600 font-medium">{{ $member->position }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-6 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p class="text-gray-500">Data tenaga kependidikan akan segera ditambahkan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
