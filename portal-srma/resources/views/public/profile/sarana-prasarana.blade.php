@extends('layouts.public')

@section('title', 'Sarana Prasarana - SRMA 25 Lamongan')

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
                <li><span class="text-white">Sarana Prasarana</span></li>
            </ol>
        </nav>
        <h1 class="text-3xl md:text-4xl font-bold text-white">Sarana Prasarana</h1>
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
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Sarana dan Prasarana</h2>
                    
                    @if($facilities->count() > 0)
                        <div class="grid md:grid-cols-2 gap-6">
                            @foreach($facilities as $facility)
                            <div class="bg-gray-50 rounded-xl overflow-hidden">
                                @if($facility->image)
                                <div class="aspect-video">
                                    <img src="{{ $facility->image_url }}" alt="{{ $facility->name }}" class="w-full h-full object-cover">
                                </div>
                                @else
                                <div class="aspect-video bg-gray-200 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                @endif
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800 mb-2">{{ $facility->name }}</h3>
                                    @if($facility->description)
                                    <p class="text-sm text-gray-600 mb-3">{{ $facility->description }}</p>
                                    @endif
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500">Jumlah: <span class="font-medium text-gray-800">{{ $facility->quantity }}</span></span>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                            @if($facility->condition === 'baik') bg-green-100 text-green-800
                                            @elseif($facility->condition === 'cukup') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ $facility->condition_label }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-6 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <p class="text-gray-500">Data sarana prasarana akan segera ditambahkan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
