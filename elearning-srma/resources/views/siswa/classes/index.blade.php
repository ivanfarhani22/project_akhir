@extends('layouts.siswa')

@section('title', 'Kelas Saya')
@section('icon', 'fas fa-chalkboard')

@section('content')
    <!-- PAGE HEADER -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3 mb-2">
            <i class="fas fa-chalkboard text-blue-500"></i>
            Kelas Saya
        </h1>
        <p class="text-gray-600 text-sm">Daftar kelas yang Anda ikuti</p>
    </div>

    @if($classes->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @foreach($classes as $class)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start gap-3 mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $class->name }}</h3>
                                <p class="text-gray-600 text-sm">{{ $class->subject->name }}</p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 whitespace-nowrap">
                                {{ $class->students->count() }} Siswa
                            </span>
                        </div>

                        <p class="text-gray-700 text-sm mb-2">
                            <strong>Guru:</strong> {{ $class->teacher->name }}
                        </p>
                        <p class="text-gray-600 text-sm mb-6 line-clamp-2">
                            {{ $class->description }}
                        </p>

                        <div class="border-t border-gray-200 pt-4 flex gap-2">
                            <a href="#" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg text-sm transition text-center inline-flex items-center justify-center gap-2">
                                <i class="fas fa-book"></i> Materi
                            </a>
                            <a href="#" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg text-sm transition text-center inline-flex items-center justify-center gap-2">
                                <i class="fas fa-tasks"></i> Tugas
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-12 text-center">
            <i class="fas fa-inbox text-gray-300 text-5xl mb-4 block"></i>
            <p class="text-gray-600 text-base">Anda belum terdaftar di kelas apapun</p>
        </div>
    @endif
@endsection