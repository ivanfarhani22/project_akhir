@extends('layouts.guru')

@section('title', 'Kelas Saya')
@section('icon', 'fas fa-chalkboard')

@section('content')

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-chalkboard mr-1"></i> Guru / Kelas</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-chalkboard text-[#A41E35] mr-2"></i>Kelas Saya</h1>
    <p class="text-sm text-gray-500 mt-1">Kelola kelas dan siswa Anda</p>
</div>

@if($classSubjects->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($classSubjects as $cs)
            @php
                $materialsCount = $cs->materials_count ?? ($cs->relationLoaded('materials') ? $cs->materials->count() : null);
                $assignmentsCount = $cs->assignments_count ?? ($cs->relationLoaded('assignments') ? $cs->assignments->count() : null);

                // If counts are not loaded, fall back to 0 to avoid triggering queries from the view.
                $materialsCount = is_null($materialsCount) ? 0 : $materialsCount;
                $assignmentsCount = is_null($assignmentsCount) ? 0 : $assignmentsCount;

                $detailUrl = route('guru.classes.show', $cs->id);
            @endphp

            <div class="group bg-white rounded-2xl border-2 border-gray-100 hover:border-[#A41E35] hover:shadow-lg transition-all duration-200 overflow-hidden flex flex-col cursor-pointer hover:-translate-y-0.5"
                 role="link"
                 tabindex="0"
                 onclick="window.location.href='{{ $detailUrl }}'"
                 onkeydown="if(event.key === 'Enter' || event.key === ' ') { event.preventDefault(); window.location.href='{{ $detailUrl }}'; }">

                <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>

                <div class="p-5 flex flex-col flex-1">
                    <div class="flex justify-between items-start gap-3 mb-3">
                        <div class="min-w-0">
                            <h3 class="text-base font-bold text-gray-900 truncate">{{ $cs->eClass->name }}</h3>
                            <span class="inline-block bg-amber-50 text-amber-700 border border-amber-200 text-xs font-semibold px-2.5 py-0.5 rounded-full mt-1">
                                {{ $cs->subject->name }}
                            </span>
                        </div>
                        <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 border border-blue-100 text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap flex-shrink-0">
                            <i class="fas fa-users text-[10px]"></i> {{ $cs->eClass->students->count() }}
                        </span>
                    </div>

                    @if($cs->eClass->description)
                        <p class="text-xs text-gray-500 leading-relaxed mb-4">{{ Str::limit($cs->eClass->description, 90) }}</p>
                    @endif

                    <div class="grid grid-cols-2 gap-3 mt-auto mb-4">
                        <div class="text-center bg-gray-50 border border-gray-100 rounded-xl py-3">
                            <p class="text-2xl font-extrabold text-[#A41E35]">{{ $materialsCount }}</p>
                            <p class="text-xs text-gray-400 font-medium mt-0.5"><i class="fas fa-book mr-1"></i>Materi</p>
                        </div>
                        <div class="text-center bg-gray-50 border border-gray-100 rounded-xl py-3">
                            <p class="text-2xl font-extrabold text-blue-600">{{ $assignmentsCount }}</p>
                            <p class="text-xs text-gray-400 font-medium mt-0.5"><i class="fas fa-tasks mr-1"></i>Tugas</p>
                        </div>
                    </div>

                    <div class="flex gap-2" onclick="event.stopPropagation()">
                        <a href="{{ route('guru.materials.index', ['class_id' => $cs->eClass->id, 'class_subject_id' => $cs->id]) }}"
                           onclick="event.stopPropagation()"
                           class="flex-1 inline-flex justify-center items-center gap-1.5 bg-[#A41E35] hover:bg-[#7D1627] text-white text-xs font-semibold py-2.5 px-3 rounded-xl transition-all shadow-sm hover:shadow-md">
                            <i class="fas fa-book text-[10px]"></i> Materi
                        </a>
                        <a href="{{ route('guru.assignments.index', ['class_id' => $cs->eClass->id, 'class_subject_id' => $cs->id]) }}"
                           onclick="event.stopPropagation()"
                           class="flex-1 inline-flex justify-center items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold py-2.5 px-3 rounded-xl transition-all">
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
            <p class="text-gray-500 text-sm">Anda belum mengajar kelas apapun.</p>
        </div>
    </div>
@endif

@endsection