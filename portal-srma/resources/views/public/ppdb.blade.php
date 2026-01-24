@extends('layouts.public')

@section('title', 'PPDB - SRMA 25 Lamongan')

@section('content')
<!-- Page Header -->
<section class="bg-gray-800 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm mb-4">
            <ol class="flex items-center space-x-2 text-gray-400">
                <li><a href="{{ route('home') }}" class="hover:text-white">Beranda</a></li>
                <li><span>/</span></li>
                <li><span class="text-white">PPDB</span></li>
            </ol>
        </nav>
        <h1 class="text-3xl md:text-4xl font-bold text-white">Penerimaan Peserta Didik Baru</h1>
    </div>
</section>

<!-- Content -->
<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm p-8 md:p-12 text-center">
            <!-- Icon -->
            <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            
            <!-- Title -->
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">
                Informasi Penting
            </h2>
            
            <!-- Main Message -->
            <div class="bg-primary-50 border border-primary-200 rounded-xl p-6 mb-8">
                <p class="text-lg text-gray-700 leading-relaxed">
                    <strong class="text-primary-700">Sekolah Rakyat tidak membuka Penerimaan Peserta Didik Baru (PPDB) secara umum.</strong>
                </p>
                <p class="text-gray-600 mt-3">
                    Peserta didik dipilih melalui <strong>rekomendasi Dinas Sosial Kabupaten Lamongan</strong>.
                </p>
            </div>
            
            <!-- Additional Info -->
            <div class="text-left space-y-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Tentang Sekolah Rakyat</h3>
                        <p class="text-gray-600 mt-1">
                            SRMA 25 Lamongan adalah Sekolah Rakyat setingkat SMA yang berada di bawah naungan Kementerian Sosial Republik Indonesia. Sekolah ini diperuntukkan bagi anak-anak dari keluarga kurang mampu (Desil 1 & Desil 2).
                        </p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Syarat Penerimaan</h3>
                        <p class="text-gray-600 mt-1">
                            Calon peserta didik harus mendapatkan rekomendasi dari Dinas Sosial Kabupaten Lamongan berdasarkan kriteria ekonomi keluarga yang telah ditetapkan.
                        </p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Informasi Lebih Lanjut</h3>
                        <p class="text-gray-600 mt-1">
                            Untuk informasi lebih lanjut mengenai prosedur rekomendasi, silakan menghubungi Dinas Sosial Kabupaten Lamongan atau kunjungi halaman kontak kami.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- CTA -->
            <div class="mt-10 pt-8 border-t">
                <a href="{{ route('kontak') }}" class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
