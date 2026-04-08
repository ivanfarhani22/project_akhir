@extends('layouts.siswa')

@section('title', 'Quiz / Ujian')
@section('icon', 'fas fa-question-circle')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            <i class="fas fa-question-circle text-purple-500"></i>
            Quiz / Ujian
        </h1>
        <p class="text-gray-600 text-sm mt-1">Ikuti quiz dan ujian untuk menguji pemahaman Anda</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-16 text-center">
            <i class="fas fa-wrench text-gray-300 text-6xl mb-4 block"></i>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Fitur Segera Hadir</h2>
            <p class="text-gray-600 text-base mb-4">Fitur Quiz dan Ujian sedang dalam pengembangan</p>
            <p class="text-gray-500 text-sm">Kami sedang mempersiapkan platform quiz yang interaktif dan menyenangkan untuk Anda</p>
        </div>
    </div>

    <!-- INFO CARD -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg shadow-sm border border-green-100 p-6">
        <h3 class="text-green-700 font-bold mb-2 flex items-center gap-2">
            <i class="fas fa-info-circle"></i>
            Informasi
        </h3>
        <p class="text-green-900 text-sm">
            Fitur Quiz dan Ujian akan memungkinkan Anda untuk menguji pemahaman materi pembelajaran melalui pertanyaan interaktif dengan umpan balik otomatis.
        </p>
    </div>
@endsection
