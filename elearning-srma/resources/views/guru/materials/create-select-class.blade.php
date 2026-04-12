@extends('layouts.guru')

@section('title', 'Upload Materi')
@section('icon', 'fas fa-book')

@section('content')
    <!-- PAGE HEADER -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3 mb-2">
            <i class="fas fa-book text-amber-500"></i>
            Upload Materi
        </h1>
        <p class="text-gray-600 text-sm">Pilih kelas untuk upload materi pembelajaran</p>
    </div>

    @if($classes->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @foreach($classes as $class)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            {{ $class->name }}
                        </h3>
                        <p class="text-sm text-gray-600 mb-1">
                            <strong>Mata Pelajaran:</strong> {{ $class->subject->name }}
                        </p>
                        <p class="text-xs text-gray-500 mb-6">
                            {{ $class->students->count() }} siswa
                        </p>

                        <a href="{{ route('guru.materials.index', ['class_id' => $class->id]) }}" class="w-full bg-[#A41E35] hover:bg-[#7D1627] text-white font-medium py-2 px-4 rounded-lg text-sm transition inline-flex justify-center items-center gap-2">
                            <i class="fas fa-eye"></i> Lihat Materi Kelas
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="py-16 px-6 text-center">
                <i class="fas fa-inbox text-gray-300 text-6xl mb-4 block"></i>
                <p class="text-gray-600 text-base">Anda belum mengajar kelas apapun</p>
            </div>
        </div>
    @endif
@endsection
