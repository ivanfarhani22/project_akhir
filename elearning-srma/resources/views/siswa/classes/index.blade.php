@extends('layouts.siswa')
@section('title', 'Kelas Saya')
@section('icon', 'fas fa-chalkboard')

@section('content')

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-chalkboard mr-1"></i> Siswa / Kelas</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-chalkboard text-blue-500 mr-2"></i>Kelas Saya</h1>
    <p class="text-sm text-gray-500 mt-1">Daftar kelas yang Anda ikuti</p>
</div>

@if($classes->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($classes as $class)
            <div class="group bg-white rounded-2xl border-2 border-gray-100 hover:border-blue-400 hover:shadow-lg transition-all duration-200 overflow-hidden flex flex-col">
                <div class="h-1 bg-gradient-to-r from-blue-500 to-indigo-400"></div>
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex justify-between items-start gap-3 mb-3">
                        <div class="min-w-0">
                            <h3 class="text-base font-bold text-gray-900 truncate">{{ $class->name }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5"><i class="fas fa-book-open mr-1"></i>{{ $class->subject->name }}</p>
                        </div>
                        <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 border border-blue-100 text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap flex-shrink-0">
                            <i class="fas fa-users text-[10px]"></i> {{ $class->students->count() }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mb-1"><i class="fas fa-chalkboard-teacher mr-1"></i>{{ $class->teacher->name }}</p>
                    @if($class->description)
                        <p class="text-xs text-gray-400 line-clamp-2 mb-4">{{ $class->description }}</p>
                    @endif
                    <div class="flex gap-2 mt-auto pt-3 border-t border-gray-100">
                        <a href="{{ route('siswa.subjects.show', $class->id) }}"
                           class="flex-1 inline-flex justify-center items-center gap-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold py-2.5 px-3 rounded-xl transition">
                            <i class="fas fa-book text-[10px]"></i> Materi
                        </a>
                        <a href="{{ route('siswa.assignments.index') }}?class={{ $class->id }}"
                           class="flex-1 inline-flex justify-center items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold py-2.5 px-3 rounded-xl transition">
                            <i class="fas fa-tasks text-[10px]"></i> Tugas
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-chalkboard text-3xl text-gray-300"></i>
            </div>
            <p class="text-gray-500 text-sm">Anda belum terdaftar di kelas apapun.</p>
        </div>
    </div>
@endif
@endsection