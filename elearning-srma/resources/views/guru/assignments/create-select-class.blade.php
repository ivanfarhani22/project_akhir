@extends('layouts.guru')
@section('title', 'Buat Tugas Baru')
@section('icon', 'fas fa-tasks')

@section('content')

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-tasks mr-1"></i> Guru / Tugas / Buat</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-tasks text-[#A41E35] mr-2"></i>Buat Tugas Baru</h1>
    <p class="text-sm text-gray-500 mt-1">Pilih kelas untuk membuat tugas pembelajaran</p>
</div>

@if($classes->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($classes as $class)
            <div class="group bg-white rounded-2xl border-2 border-gray-100 hover:border-[#A41E35] hover:shadow-lg transition-all duration-200 overflow-hidden">
                <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
                <div class="p-5">
                    <h3 class="text-base font-bold text-gray-900 truncate">{{ $class->name }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5"><i class="fas fa-book-open mr-1"></i>{{ $class->subject->name }}</p>
                    <p class="text-xs text-gray-400 mb-5 mt-0.5"><i class="fas fa-users mr-1"></i>{{ $class->students->count() }} siswa</p>
                    <a href="{{ route('guru.assignments.create', ['class_id' => $class->id]) }}"
                       class="w-full inline-flex justify-center items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white text-sm font-semibold py-2.5 px-4 rounded-xl transition shadow-sm hover:shadow-md">
                        <i class="fas fa-plus text-xs"></i> Buat Tugas
                    </a>
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
            <p class="text-gray-500 text-sm">Anda belum mengajar kelas apapun.</p>
        </div>
    </div>
@endif
@endsection