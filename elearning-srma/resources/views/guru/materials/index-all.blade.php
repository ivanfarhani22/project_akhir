@extends('layouts.guru')

@section('title', 'Materi Pembelajaran')
@section('icon', 'fas fa-book')

@section('content')
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
        <h1 class="text-xl font-bold text-gray-900">Materi Pembelajaran</h1>
        <p class="text-gray-600 text-sm mt-2">Untuk saat ini, materi ditampilkan per kelas. Silakan pilih kelas terlebih dahulu.</p>
        <div class="mt-4">
            <a href="{{ route('guru.materials.create') }}" class="inline-flex items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-medium py-2 px-5 rounded-lg text-sm transition">
                <i class="fas fa-chalkboard"></i> Pilih Kelas
            </a>
        </div>
    </div>
@endsection