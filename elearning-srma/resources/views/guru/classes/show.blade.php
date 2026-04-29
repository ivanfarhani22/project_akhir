@extends('layouts.guru')

@section('title', 'Detail Kelas')
@section('icon', 'fas fa-chalkboard')

@section('content')

@php
    $class = $classSubject->eClass;
@endphp

<div class="mb-6">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">
        <i class="fas fa-chalkboard mr-1"></i> Guru / Kelas / Detail
    </p>

    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <h1 class="text-2xl font-extrabold text-gray-900 truncate">
                <i class="fas fa-chalkboard text-[#A41E35] mr-2"></i>{{ $class->name }}
            </h1>
            <div class="flex flex-wrap items-center gap-2 mt-2">
                <span class="inline-flex items-center gap-2 bg-amber-50 text-amber-800 border border-amber-200 text-xs font-semibold px-3 py-1 rounded-full">
                    <i class="fas fa-book"></i> {{ $classSubject->subject->name }}
                </span>
                <span class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 border border-blue-100 text-xs font-semibold px-3 py-1 rounded-full">
                    <i class="fas fa-users"></i> {{ $class->students->count() }} Siswa
                </span>
            </div>

            @if($class->description)
                <p class="text-sm text-gray-500 mt-2">{{ $class->description }}</p>
            @endif
        </div>

        <a href="{{ route('guru.classes.index') }}"
           class="inline-flex items-center gap-2 bg-white border border-gray-200 hover:border-gray-300 text-gray-700 text-sm font-semibold px-4 py-2 rounded-xl transition">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

{{-- Quick stats --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-200 p-4">
        <p class="text-xs text-gray-400 font-semibold">Siswa</p>
        <p class="text-2xl font-extrabold text-gray-900 mt-1">{{ $class->students->count() }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 p-4">
        <p class="text-xs text-gray-400 font-semibold">Materi (Mapel ini)</p>
        <p class="text-2xl font-extrabold text-[#A41E35] mt-1">{{ $materials->count() }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 p-4">
        <p class="text-xs text-gray-400 font-semibold">Tugas (Mapel ini)</p>
        <p class="text-2xl font-extrabold text-blue-600 mt-1">{{ $assignments->count() }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 p-4">
        <p class="text-xs text-gray-400 font-semibold">Jadwal (Mapel ini)</p>
        <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $schedules->count() }}</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    {{-- Students --}}
    <div class="xl:col-span-1 bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-100">
            <h2 class="text-base font-extrabold text-gray-900"><i class="fas fa-users text-[#A41E35] mr-2"></i>Daftar Siswa</h2>
            <p class="text-sm text-gray-500 mt-1">Kelas: {{ $class->name }} • Total: {{ $class->students->count() }} siswa</p>
        </div>
        <div class="p-5">
            @if($class->students->count() > 0)
                <div class="space-y-3">
                    @foreach($class->students as $student)
                        <div class="flex items-center justify-between gap-3 rounded-xl border border-gray-200 p-3">
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-gray-900 truncate">{{ $student->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $student->email }}</p>
                            </div>
                            <span class="text-xs font-semibold text-gray-500">Siswa</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">Belum ada siswa pada kelas ini.</p>
            @endif
        </div>
    </div>

    <div class="xl:col-span-2 space-y-6">
        {{-- Materials --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-100">
                <h2 class="text-base font-extrabold text-gray-900"><i class="fas fa-book text-[#A41E35] mr-2"></i>Materi</h2>
                <p class="text-sm text-gray-500 mt-1">Materi khusus untuk mapel: <span class="font-semibold">{{ $classSubject->subject->name }}</span>.</p>
            </div>
            <div class="p-5">
                @if($materials->count() > 0)
                    <div class="space-y-3">
                        @foreach($materials as $m)
                            <div class="rounded-xl border border-gray-200 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="text-sm font-extrabold text-gray-900">{{ $m->title }}</p>
                                        @if($m->description)
                                            <p class="text-xs text-gray-500 mt-1">{{ \Illuminate\Support\Str::limit($m->description, 140) }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400 mt-2">
                                            Upload: <span class="font-semibold">{{ optional($m->created_at)->format('d/m/Y H:i') }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">Belum ada materi untuk mapel ini.</p>
                @endif
            </div>
        </div>

        {{-- Assignments --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-100">
                <h2 class="text-base font-extrabold text-gray-900"><i class="fas fa-tasks text-blue-600 mr-2"></i>Tugas</h2>
                <p class="text-sm text-gray-500 mt-1">Tugas khusus untuk mapel: <span class="font-semibold">{{ $classSubject->subject->name }}</span>.</p>
            </div>
            <div class="p-5">
                @if($assignments->count() > 0)
                    <div class="space-y-3">
                        @foreach($assignments as $a)
                            <div class="rounded-xl border border-gray-200 p-4">
                                <p class="text-sm font-extrabold text-gray-900">{{ $a->title }}</p>
                                @if($a->description)
                                    <p class="text-xs text-gray-500 mt-1">{{ \Illuminate\Support\Str::limit($a->description, 140) }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-2">
                                    @if($a->deadline)
                                        Deadline: <span class="font-semibold">{{ $a->deadline->format('d/m/Y H:i') }}</span>
                                    @else
                                        Deadline: <span class="font-semibold">-</span>
                                    @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">Belum ada tugas untuk mapel ini.</p>
                @endif
            </div>
        </div>

        {{-- Schedules --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-100">
                <h2 class="text-base font-extrabold text-gray-900"><i class="fas fa-calendar-alt text-emerald-600 mr-2"></i>Jadwal</h2>
                <p class="text-sm text-gray-500 mt-1">Jadwal terkait mapel: <span class="font-semibold">{{ $classSubject->subject->name }}</span>.</p>
            </div>
            <div class="p-5">
                @if($schedules->count() > 0)
                    <div class="space-y-3">
                        @foreach($schedules as $s)
                            <div class="rounded-xl border border-gray-200 p-4">
                                <p class="text-sm font-extrabold text-gray-900">{{ $classSubject->subject->name }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $s->day_of_week }} • {{ substr($s->start_time, 0, 5) }} - {{ substr($s->end_time, 0, 5) }}
                                    @if($s->room)
                                        • Ruang: {{ $s->room }}
                                    @endif
                                </p>
                                @if($s->notes)
                                    <p class="text-xs text-gray-400 mt-2">{{ $s->notes }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">Belum ada jadwal untuk mapel ini.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
