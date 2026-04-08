@extends('layouts.siswa')

@section('title', 'Mata Pelajaran')
@section('icon', 'fas fa-book')

@section('content')
    <!-- PAGE HEADER -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3 mb-2">
            <i class="fas fa-book text-amber-500"></i>
            Mata Pelajaran
        </h1>
        <p class="text-gray-600 text-sm">Daftar mata pelajaran yang Anda pelajari</p>
    </div>

    @if($classes->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @foreach($classes as $class)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $class->subject->name }}</h3>
                        <p class="text-gray-600 text-sm">{{ $class->name }}</p>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <!-- Guru -->
                        <div class="pb-4 border-b border-gray-200">
                            <p class="text-gray-500 text-xs font-semibold mb-1 uppercase">Pengajar</p>
                            <p class="text-gray-900 font-medium">{{ $class->teacher->name }}</p>
                        </div>

                        <!-- Deskripsi -->
                        @if($class->description)
                            <div class="pb-4 border-b border-gray-200">
                                <p class="text-gray-500 text-xs font-semibold mb-1 uppercase">Deskripsi</p>
                                <p class="text-gray-700 text-sm">{{ Str::limit($class->description, 100) }}</p>
                            </div>
                        @endif

                        <!-- Jadwal -->
                        <div class="pb-4 border-b border-gray-200">
                            <p class="text-gray-500 text-xs font-semibold mb-2 uppercase">Jadwal</p>
                            <div class="flex items-center gap-2 text-gray-700 text-sm">
                                <i class="fas fa-calendar text-blue-500"></i>
                                <span>
                                    @if($class->schedules && $class->schedules->count() > 0)
                                        @php $schedule = $class->schedules->first(); @endphp
                                        {{ ucfirst($schedule->day_of_week) }}
                                        @if($schedule->start_time)
                                            • {{ \Carbon\Carbon::createFromTimeString($schedule->start_time)->format('H:i') }}
                                            @if($schedule->end_time)
                                                - {{ \Carbon\Carbon::createFromTimeString($schedule->end_time)->format('H:i') }}
                                            @endif
                                        @endif
                                    @else
                                        TBA
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Statistik -->
                        <div class="grid grid-cols-2 gap-3 pb-4 border-b border-gray-200">
                            @php
                                $materials = $class->materials->count();
                                $assignments = $class->assignments->count();
                            @endphp
                            <div class="text-center">
                                <p class="text-gray-600 text-xs font-medium mb-1">Materi</p>
                                <p class="text-2xl font-bold text-blue-600">{{ $materials }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-gray-600 text-xs font-medium mb-1">Tugas</p>
                                <p class="text-2xl font-bold text-amber-600">{{ $assignments }}</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('siswa.subjects.show', $class->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-3 rounded-lg text-sm transition text-center inline-flex items-center justify-center gap-1">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <a href="{{ route('siswa.assignments.index') }}?class={{ $class->id }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-3 rounded-lg text-sm transition text-center inline-flex items-center justify-center gap-1">
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
            <p class="text-gray-600 text-base mb-3">Anda belum terdaftar di mata pelajaran apapun</p>
            <p class="text-gray-500 text-sm">Hubungi administrator untuk mendaftar ke mata pelajaran</p>
        </div>
    @endif
@endsection