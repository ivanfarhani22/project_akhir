@extends('layouts.guru')
@section('title', 'Kelas Saya')
@section('icon', 'fas fa-chalkboard')

@section('content')

@php
    $dayOrder  = ['monday'=>0,'tuesday'=>1,'wednesday'=>2,'thursday'=>3,'friday'=>4,'saturday'=>5,'sunday'=>6];
    $dayLabels = ['monday'=>'Senin','tuesday'=>'Selasa','wednesday'=>'Rabu','thursday'=>'Kamis','friday'=>'Jumat','saturday'=>'Sabtu','sunday'=>'Minggu'];
    $dayColors = [
        'monday'    => 'bg-blue-50 text-blue-700 border-blue-200',
        'tuesday'   => 'bg-indigo-50 text-indigo-700 border-indigo-200',
        'wednesday' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'thursday'  => 'bg-amber-50 text-amber-700 border-amber-200',
        'friday'    => 'bg-rose-50 text-rose-700 border-rose-200',
        'saturday'  => 'bg-purple-50 text-purple-700 border-purple-200',
        'sunday'    => 'bg-orange-50 text-orange-700 border-orange-200',
    ];
    $grouped = ($schedules ?? collect())
        ->sortBy([
            fn($a,$b) => ($dayOrder[strtolower($a->day_of_week)] ?? 9) <=> ($dayOrder[strtolower($b->day_of_week)] ?? 9),
            fn($a,$b) => ($a->start_time ?? '') <=> ($b->start_time ?? ''),
        ])
        ->groupBy(fn($s) => strtolower($s->day_of_week));
    $today = strtolower(now()->format('l'));
@endphp

{{-- PAGE HEADER --}}
<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-chalkboard mr-1"></i> Guru / Kelas</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-chalkboard text-[#A41E35] mr-2"></i>Kelas Saya</h1>
    <p class="text-sm text-gray-500 mt-1">Kelola kelas, jadwal, dan materi Anda.</p>
</div>

{{-- ═══ JADWAL MENGAJAR ══════════════════════════════════════════════════ --}}
<div class="mb-10">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-extrabold text-gray-900">
            <i class="fas fa-calendar-alt text-emerald-500 mr-2"></i>Jadwal Mengajar
            @if(($schedules ?? collect())->isNotEmpty())
                <span class="text-gray-400 font-normal text-sm">({{ $schedules->count() }} sesi)</span>
            @endif
        </h2>
    </div>

    @if(($schedules ?? collect())->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex flex-col items-center justify-center py-12 text-center px-6">
                <div class="w-16 h-16 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-3">
                    <i class="fas fa-calendar-alt text-2xl text-gray-300"></i>
                </div>
                <p class="text-gray-500 text-sm">Belum ada jadwal mengajar.</p>
            </div>
        </div>
    @else
        {{-- Navigasi hari --}}
        <div class="flex flex-wrap gap-2 mb-4">
            @foreach($dayOrder as $day => $idx)
                @if($grouped->has($day))
                    <a href="#guru-hari-{{ $day }}"
                       class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full border transition
                           {{ $day === $today ? 'bg-[#A41E35] text-white border-[#A41E35]' : $dayColors[$day] }}">
                        @if($day === $today)<i class="fas fa-circle text-[8px]"></i>@endif
                        {{ $dayLabels[$day] }}
                        <span class="opacity-60">({{ $grouped[$day]->count() }})</span>
                    </a>
                @endif
            @endforeach
        </div>

        <div class="space-y-4">
            @foreach($dayOrder as $day => $idx)
                @if(!$grouped->has($day)) @continue @endif
                @php $daySchedules = $grouped[$day]; @endphp

                <div id="guru-hari-{{ $day }}"
                     class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden
                         {{ $day === $today ? 'ring-2 ring-[#A41E35]/30' : '' }}">

                    <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100
                        {{ $day === $today ? 'bg-rose-50' : 'bg-gray-50' }}">
                        <div class="flex items-center gap-2.5">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full border {{ $dayColors[$day] }}">
                                @if($day === $today)<i class="fas fa-circle text-[8px]"></i>@endif
                                {{ $dayLabels[$day] }}
                            </span>
                            @if($day === $today)
                                <span class="text-xs font-semibold text-[#A41E35]">Hari ini</span>
                            @endif
                        </div>
                        <span class="text-xs text-gray-400">{{ $daySchedules->count() }} sesi</span>
                    </div>

                    {{-- Desktop --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50/50 border-b border-gray-100">
                                <tr>
                                    <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Waktu</th>
                                    <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Mata Pelajaran</th>
                                    <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Kelas</th>
                                    <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Ruang</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($daySchedules as $schedule)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-5 py-3">
                                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-gray-600 bg-gray-100 px-2.5 py-1 rounded-lg whitespace-nowrap">
                                                <i class="fas fa-clock text-[10px]"></i>
                                                {{ $schedule->start_time ? \Carbon\Carbon::createFromTimeString($schedule->start_time)->format('H:i') : '—' }}
                                                {{ $schedule->end_time ? '–'.\Carbon\Carbon::createFromTimeString($schedule->end_time)->format('H:i') : '' }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3 font-semibold text-gray-900">{{ $schedule->display_title }}</td>
                                        <td class="px-5 py-3 text-xs text-gray-500">{{ $schedule->eClass?->name ?? '—' }}</td>
                                        <td class="px-5 py-3 text-xs text-gray-500">
                                            @if($schedule->room)
                                                <span class="inline-flex items-center gap-1"><i class="fas fa-door-open text-[10px]"></i>{{ $schedule->room }}</span>
                                            @else —
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile --}}
                    <div class="md:hidden divide-y divide-gray-100">
                        @foreach($daySchedules as $schedule)
                            <div class="flex items-start gap-3 px-5 py-3.5">
                                <div class="flex-shrink-0 text-center w-14">
                                    <p class="text-xs font-bold text-gray-700">
                                        {{ $schedule->start_time ? \Carbon\Carbon::createFromTimeString($schedule->start_time)->format('H:i') : '—' }}
                                    </p>
                                    @if($schedule->end_time)
                                        <p class="text-[10px] text-gray-400 mt-0.5">{{ \Carbon\Carbon::createFromTimeString($schedule->end_time)->format('H:i') }}</p>
                                    @endif
                                </div>
                                <div class="w-px self-stretch bg-gray-200 flex-shrink-0"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-gray-900 text-sm">{{ $schedule->display_title }}</p>
                                    <div class="flex flex-wrap gap-x-3 mt-1">
                                        @if($schedule->eClass?->name)
                                            <p class="text-xs text-gray-400"><i class="fas fa-chalkboard mr-1"></i>{{ $schedule->eClass->name }}</p>
                                        @endif
                                        @if($schedule->room)
                                            <p class="text-xs text-gray-400"><i class="fas fa-map-marker-alt mr-1"></i>{{ $schedule->room }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- ═══ DAFTAR KELAS ══════════════════════════════════════════════════════ --}}
<div class="flex items-center justify-between mb-4">
    <h2 class="text-base font-extrabold text-gray-900">
        <i class="fas fa-chalkboard text-[#A41E35] mr-2"></i>Daftar Kelas
        @if($classSubjects->count() > 0)
            <span class="text-gray-400 font-normal text-sm">({{ $classSubjects->count() }} kelas)</span>
        @endif
    </h2>
</div>

@if($classSubjects->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($classSubjects as $cs)
            @php
                $materialsCount   = $cs->materials_count   ?? ($cs->relationLoaded('materials')   ? $cs->materials->count()   : 0);
                $assignmentsCount = $cs->assignments_count ?? ($cs->relationLoaded('assignments') ? $cs->assignments->count() : 0);
                $detailUrl        = route('guru.classes.show', $cs->id);
            @endphp

            <div class="group bg-white rounded-2xl border-2 border-gray-100 hover:border-[#A41E35] hover:shadow-lg transition-all duration-200 overflow-hidden flex flex-col cursor-pointer"
                 onclick="window.location.href='{{ $detailUrl }}'"
                 onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();window.location.href='{{ $detailUrl }}'}"
                 role="link" tabindex="0">

                <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>

                <div class="p-5 flex flex-col flex-1">
                    {{-- Nama kelas & mapel --}}
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
                        <p class="text-xs text-gray-500 leading-relaxed mb-4 line-clamp-2">{{ $cs->eClass->description }}</p>
                    @endif

                    {{-- Stats --}}
                    <div class="grid grid-cols-2 gap-3 mt-auto mb-4">
                        <div class="text-center bg-gray-50 border border-gray-100 rounded-xl py-3">
                            <p class="text-2xl font-extrabold text-[#A41E35]">{{ $materialsCount }}</p>
                            <p class="text-xs text-gray-400 mt-0.5"><i class="fas fa-book mr-1"></i>Materi</p>
                        </div>
                        <div class="text-center bg-gray-50 border border-gray-100 rounded-xl py-3">
                            <p class="text-2xl font-extrabold text-blue-600">{{ $assignmentsCount }}</p>
                            <p class="text-xs text-gray-400 mt-0.5"><i class="fas fa-tasks mr-1"></i>Tugas</p>
                        </div>
                    </div>

                    {{-- Aksi --}}
                    <div class="flex gap-2" onclick="event.stopPropagation()">
                        <a href="{{ route('guru.materials.index', ['class_id' => $cs->eClass->id, 'class_subject_id' => $cs->id]) }}"
                           onclick="event.stopPropagation()"
                           class="flex-1 inline-flex justify-center items-center gap-1.5 bg-[#A41E35] hover:bg-[#7D1627] text-white text-xs font-semibold py-2.5 px-3 rounded-xl transition shadow-sm hover:shadow-md">
                            <i class="fas fa-book text-[10px]"></i> Materi
                        </a>
                        <a href="{{ route('guru.assignments.index', ['class_id' => $cs->eClass->id, 'class_subject_id' => $cs->id]) }}"
                           onclick="event.stopPropagation()"
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
            <p class="text-gray-500 text-sm">Anda belum mengajar kelas apapun.</p>
        </div>
    </div>
@endif

@endsection