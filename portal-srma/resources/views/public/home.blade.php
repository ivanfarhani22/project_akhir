@extends('layouts.public')

@section('title', 'Portal SRMA 25 Lamongan - Sekolah Rakyat')

@section('content')
<!-- Hero Section / Banner Slider -->
<section class="relative bg-gray-800">
    <div x-data="{ 
        currentSlide: 0,
        slides: {{ $banners->count() }},
        init() {
            setInterval(() => {
                this.currentSlide = (this.currentSlide + 1) % this.slides;
            }, 5000);
        }
    }" class="relative overflow-hidden">
        @forelse($banners as $index => $banner)
        <div x-show="currentSlide === {{ $index }}" 
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="relative min-h-[450px] md:min-h-[520px] lg:min-h-[560px] flex items-center"
             style="background: linear-gradient(135deg, #374151 0%, #1f2937 50%, #991b1b 100%);">
            @if($banner->image)
            <div class="absolute inset-0">
                <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover opacity-30">
            </div>
            @endif
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
                <div class="max-w-2xl">
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4">
                        {{ $banner->title }}
                    </h1>
                    @if($banner->subtitle)
                    <p class="text-lg md:text-xl text-gray-300 mb-6">
                        {{ $banner->subtitle }}
                    </p>
                    @endif
                    @if($banner->link && $banner->button_text)
                    <a href="{{ $banner->link }}" class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        {{ $banner->button_text }}
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="relative min-h-[450px] md:min-h-[520px] lg:min-h-[560px] flex items-center" style="background: linear-gradient(135deg, #374151 0%, #1f2937 50%, #991b1b 100%);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
                <div class="max-w-2xl">
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4">
                        Selamat Datang di Portal SRMA 25 Lamongan
                    </h1>
                    <p class="text-lg md:text-xl text-gray-300 mb-6">
                        Sekolah Rakyat di bawah naungan Kementerian Sosial Republik Indonesia
                    </p>
                </div>
            </div>
        </div>
        @endforelse
        
        <!-- Slide Indicators -->
        @if($banners->count() > 1)
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            @foreach($banners as $index => $banner)
            <button @click="currentSlide = {{ $index }}" 
                    :class="currentSlide === {{ $index }} ? 'bg-white' : 'bg-white/50'"
                    class="w-3 h-3 rounded-full transition-colors"></button>
            @endforeach
        </div>
        @endif
    </div>
</section>

<!-- Info Banner - PPDB -->
<section class="bg-primary-600 text-white py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-center space-x-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm md:text-base text-center">
                <strong>Pemberitahuan:</strong> Sekolah tidak membuka PPDB umum. Peserta didik berasal dari rekomendasi Dinas Sosial Kabupaten Lamongan.
            </p>
        </div>
    </div>
</section>

<!-- School Stats -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="text-center p-6 rounded-xl bg-gray-50">
                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div class="text-3xl font-bold text-gray-800">{{ $schoolData['total_siswa_laki'] + $schoolData['total_siswa_perempuan'] }}</div>
                <div class="text-sm text-gray-500">Total Siswa</div>
            </div>
            <div class="text-center p-6 rounded-xl bg-gray-50">
                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="text-3xl font-bold text-gray-800">{{ $schoolData['total_guru'] }}</div>
                <div class="text-sm text-gray-500">Tenaga Pendidik</div>
            </div>
            <div class="text-center p-6 rounded-xl bg-gray-50">
                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="text-3xl font-bold text-gray-800">{{ $schoolData['total_staff'] }}</div>
                <div class="text-sm text-gray-500">Tenaga Kependidikan</div>
            </div>
            <div class="text-center p-6 rounded-xl bg-gray-50">
                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="text-3xl font-bold text-gray-800">SMA</div>
                <div class="text-sm text-gray-500">Jenjang Pendidikan</div>
            </div>
        </div>
    </div>
</section>

<!-- Latest News -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Berita Terbaru</h2>
                <p class="text-gray-500 mt-1">Informasi dan kegiatan terkini dari sekolah</p>
            </div>
            <a href="{{ route('berita.index') }}" class="hidden md:inline-flex items-center text-primary-600 font-medium hover:text-primary-700">
                Lihat Semua
                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($latestNews as $news)
            <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <a href="{{ route('berita.show', $news->slug) }}">
                    <div class="aspect-video bg-gray-200 overflow-hidden">
                        <img src="{{ $news->thumbnail_url }}" alt="{{ $news->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" loading="lazy">
                    </div>
                </a>
                <div class="p-5">
                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <span>{{ $news->published_at?->translatedFormat('d M Y') ?? '-' }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ $news->views }} kali dibaca</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">
                        <a href="{{ route('berita.show', $news->slug) }}" class="hover:text-primary-600">
                            {{ $news->title }}
                        </a>
                    </h3>
                    <p class="text-sm text-gray-500 line-clamp-2">{{ $news->excerpt ?: Str::limit(strip_tags($news->content), 100) }}</p>
                </div>
            </article>
            @empty
            <div class="col-span-full text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <p class="text-gray-500">Belum ada berita.</p>
            </div>
            @endforelse
        </div>
        
        <div class="mt-8 text-center md:hidden">
            <a href="{{ route('berita.index') }}" class="inline-flex items-center text-primary-600 font-medium">
                Lihat Semua Berita
                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Announcements & Agenda -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Announcements -->
            <div>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Pengumuman</h2>
                    <a href="{{ route('pengumuman.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Lihat Semua</a>
                </div>
                <div class="space-y-4">
                    @forelse($announcements as $announcement)
                    <a href="{{ route('pengumuman.show', $announcement->slug) }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-start space-x-3">
                            @if($announcement->is_important)
                            <span class="flex-shrink-0 px-2 py-1 bg-primary-100 text-primary-700 text-xs font-medium rounded">Penting</span>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h3 class="font-medium text-gray-800 line-clamp-2">{{ $announcement->title }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $announcement->published_at?->translatedFormat('d M Y') }}</p>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500">Belum ada pengumuman.</p>
                    </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Agenda -->
            <div>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Agenda Mendatang</h2>
                    <a href="{{ route('agenda.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Lihat Semua</a>
                </div>
                <div class="space-y-4">
                    @forelse($upcomingAgendas as $agenda)
                    <a href="{{ route('agenda.show', $agenda->slug) }}" class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex-shrink-0 w-14 h-14 bg-primary-600 text-white rounded-lg flex flex-col items-center justify-center">
                            <span class="text-lg font-bold leading-none">{{ $agenda->start_date->format('d') }}</span>
                            <span class="text-xs uppercase">{{ $agenda->start_date->translatedFormat('M') }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-medium text-gray-800 line-clamp-2">{{ $agenda->title }}</h3>
                            @if($agenda->location)
                            <p class="text-sm text-gray-500 mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                {{ $agenda->location }}
                            </p>
                            @endif
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500">Tidak ada agenda mendatang.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Preview -->
@if($featuredGalleries->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Galeri Foto</h2>
                <p class="text-gray-500 mt-1">Dokumentasi kegiatan sekolah</p>
            </div>
            <a href="{{ route('galeri.index') }}" class="hidden md:inline-flex items-center text-primary-600 font-medium hover:text-primary-700">
                Lihat Semua
                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($featuredGalleries as $gallery)
            <div class="aspect-square rounded-xl overflow-hidden bg-gray-200 group cursor-pointer" 
                 x-data
                 @click="$dispatch('open-lightbox', { src: '{{ $gallery->image_url }}', title: '{{ $gallery->title }}' })">
                <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
            </div>
            @endforeach
        </div>
        
        <div class="mt-8 text-center md:hidden">
            <a href="{{ route('galeri.index') }}" class="inline-flex items-center text-primary-600 font-medium">
                Lihat Semua Foto
                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-16 bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">Akses E-Learning</h2>
        <p class="text-gray-300 mb-8 max-w-2xl mx-auto">
            Platform pembelajaran daring untuk siswa dan guru SRMA 25 Lamongan. Akses materi, tugas, dan kegiatan pembelajaran kapan saja.
        </p>
        @php
            $elearningUrl = \App\Models\Setting::getValue('elearning_url', '#');
        @endphp
        <a href="{{ $elearningUrl }}" target="_blank" class="inline-flex items-center px-8 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            Masuk E-Learning
        </a>
    </div>
</section>

<!-- Lightbox -->
<div x-data="{ open: false, src: '', title: '' }" 
     @open-lightbox.window="open = true; src = $event.detail.src; title = $event.detail.title"
     x-show="open" 
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/90"
     @click.self="open = false"
     @keydown.escape.window="open = false">
    <button @click="open = false" class="absolute top-4 right-4 text-white hover:text-gray-300">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
    <div class="max-w-4xl max-h-[90vh] p-4">
        <img :src="src" :alt="title" class="max-w-full max-h-[80vh] rounded-lg">
        <p x-text="title" class="text-white text-center mt-4"></p>
    </div>
</div>
@endsection
