@extends('layouts.guru')

@section('title', 'Kelas Saya')
@section('icon', 'fas fa-chalkboard')

@section('content')
    <!-- PAGE HEADER -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3 mb-2">
            <i class="fas fa-chalkboard text-amber-500"></i>
            Kelas Saya
        </h1>
        <p class="text-gray-600 text-sm">Kelola kelas dan siswa Anda</p>
    </div>

    @if($classSubjects->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @foreach($classSubjects as $cs)
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition border border-gray-100 overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start gap-3 mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">
                                    {{ $cs->eClass->name }}
                                </h3>
                                <span class="inline-block bg-amber-100 text-amber-800 text-xs font-semibold px-3 py-1 rounded-full">
                                    {{ $cs->subject->name }}
                                </span>
                            </div>
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full whitespace-nowrap">
                                {{ $cs->eClass->students->count() }} Siswa
                            </span>
                        </div>

                        <p class="text-gray-600 text-sm mb-4">
                            {{ Str::limit($cs->eClass->description, 100) }}
                        </p>

                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-600 font-medium">Materi</p>
                                    <p class="text-2xl font-bold text-amber-600">{{ $cs->eClass->materials->count() }}</p>
                                </div>
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-600 font-medium">Tugas</p>
                                    <p class="text-2xl font-bold text-blue-600">{{ $cs->eClass->assignments->count() }}</p>
                                </div>
                            </div>

                            <div class="flex gap-2 mt-4">
                                <a href="{{ route('guru.materials.index', ['class' => $cs->eClass->id]) }}" class="flex-1 bg-[#A41E35] hover:bg-[#7D1627] text-white font-medium py-2 px-3 rounded-lg text-sm transition text-center">
                                    <i class="fas fa-book mr-1"></i> Materi
                                </a>
                                <a href="{{ route('guru.assignments.index', ['class' => $cs->eClass->id]) }}" class="flex-1 bg-[#A41E35] hover:bg-[#7D1627] text-white font-medium py-2 px-3 rounded-lg text-sm transition text-center">
                                    <i class="fas fa-tasks mr-1"></i> Tugas
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm p-12 text-center border border-gray-100">
            <i class="fas fa-inbox text-gray-300 text-5xl mb-4 block"></i>
            <p class="text-gray-600 text-base">Anda belum mengajar kelas apapun</p>
        </div>
    @endif
@endsection
