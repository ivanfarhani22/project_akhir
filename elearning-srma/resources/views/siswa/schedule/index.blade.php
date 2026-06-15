@extends('layouts.siswa')
@section('title', 'Jadwal Pelajaran')
@section('icon', 'fas fa-calendar-alt')

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

    $grouped = $schedules
        ->sortBy([
            fn($a,$b) => ($dayOrder[strtolower($a->day_of_week)] ?? 9) <=> ($dayOrder[strtolower($b->day_of_week)] ?? 9),
            fn($a,$b) => ($a->start_time ?? '') <=> ($b->start_time ?? ''),
        ])
        ->groupBy(fn($s) => strtolower($s->day_of_week));

    $today = strtolower(now()->format('l'));
@endphp

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-calendar-alt mr-1"></i> Siswa / Jadwal</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-calendar-alt text-blue-500 mr-2"></i>Jadwal Pelajaran</h1>
    <p class="text-sm text-gray-500 mt-1">Lihat jadwal mingguan kelas Anda</p>
</div>

@if($schedules->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex flex-col items-center justify-center py-16 text-center px-6">
            <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-calendar-alt text-3xl text-gray-300"></i>
            </div>
            <p class="text-gray-500 text-sm">Belum ada jadwal yang tersedia.</p>
        </div>
    </div>
@else
    {{-- Navigasi hari cepat --}}
    <div class="flex flex-wrap gap-2 mb-5">
        @foreach($dayOrder as $day => $idx)
            @if($grouped->has($day))
                <a href="#hari-{{ $day }}"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full border transition
                       {{ $day === $today
                           ? 'bg-blue-500 text-white border-blue-500'
                           : $dayColors[$day] }}">
                    @if($day === $today)<i class="fas fa-circle text-[8px]"></i>@endif
                    {{ $dayLabels[$day] }}
                    <span class="opacity-60">({{ $grouped[$day]->count() }})</span>
                </a>
            @endif
        @endforeach
    </div>

    <div class="space-y-5">
        @foreach($dayOrder as $day => $idx)
            @if(!$grouped->has($day)) @continue @endif
            @php $daySchedules = $grouped[$day]; @endphp

            <div id="hari-{{ $day }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden
                {{ $day === $today ? 'ring-2 ring-blue-400' : '' }}">

                {{-- Header hari --}}
                <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100
                    {{ $day === $today ? 'bg-blue-50' : 'bg-gray-50' }}">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full border {{ $dayColors[$day] }}">
                            @if($day === $today)<i class="fas fa-circle text-[8px]"></i>@endif
                            {{ $dayLabels[$day] }}
                        </span>
                        @if($day === $today)
                            <span class="text-xs font-semibold text-blue-600">Hari ini</span>
                        @endif
                    </div>
                    <span class="text-xs text-gray-400">{{ $daySchedules->count() }} sesi</span>
                </div>

                {{-- TABEL DESKTOP --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50/50 border-b border-gray-100">
                            <tr>
                                <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Waktu</th>
                                <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Mata Pelajaran</th>
                                <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Guru</th>
                                <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Kelas</th>
                                <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Ruang</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($daySchedules as $schedule)
                                @php $isCustom = empty($schedule->class_subject_id); @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-5 py-3">
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-gray-600 bg-gray-100 px-2.5 py-1 rounded-lg whitespace-nowrap">
                                            <i class="fas fa-clock text-[10px]"></i>
                                            {{ $schedule->start_time ? \Carbon\Carbon::createFromTimeString($schedule->start_time)->format('H:i') : '—' }}
                                            {{ $schedule->end_time ? '–'.\Carbon\Carbon::createFromTimeString($schedule->end_time)->format('H:i') : '' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-2">
                                            @if($isCustom)
                                                <span class="w-2 h-2 bg-amber-400 rounded-full flex-shrink-0"></span>
                                            @else
                                                <span class="w-2 h-2 bg-blue-400 rounded-full flex-shrink-0"></span>
                                            @endif
                                            <span class="font-semibold text-gray-900">{{ $schedule->display_title }}</span>
                                            @if($isCustom)
                                                <span class="text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 rounded-full">Custom</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 text-xs text-gray-500">{{ $isCustom ? '—' : ($schedule->classSubject?->teacher?->name ?? '—') }}</td>
                                    <td class="px-5 py-3 text-xs text-gray-500">{{ $schedule->eClass?->name ?? '—' }}</td>
                                    <td class="px-5 py-3 text-xs text-gray-500">
                                        @if($schedule->room)
                                            <span class="inline-flex items-center gap-1"><i class="fas fa-door-open text-[10px]"></i>{{ $schedule->room }}</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- CARD MOBILE --}}
                <div class="md:hidden divide-y divide-gray-100">
                    @foreach($daySchedules as $schedule)
                        @php $isCustom = empty($schedule->class_subject_id); @endphp
                        <div class="flex items-start gap-3 px-5 py-3.5">
                            <div class="flex-shrink-0 text-center w-16">
                                <p class="text-xs font-bold text-gray-700">
                                    {{ $schedule->start_time ? \Carbon\Carbon::createFromTimeString($schedule->start_time)->format('H:i') : '—' }}
                                </p>
                                @if($schedule->end_time)
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ \Carbon\Carbon::createFromTimeString($schedule->end_time)->format('H:i') }}</p>
                                @endif
                            </div>
                            <div class="w-px self-stretch bg-gray-200 flex-shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <p class="font-bold text-gray-900 text-sm">{{ $schedule->display_title }}</p>
                                    @if($isCustom)
                                        <span class="text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 px-1.5 py-0.5 rounded-full">Custom</span>
                                    @endif
                                </div>
                                <div class="flex flex-wrap gap-x-3 gap-y-0.5 mt-1">
                                    @if(!$isCustom && $schedule->classSubject?->teacher?->name)
                                        <p class="text-xs text-gray-400"><i class="fas fa-chalkboard-teacher mr-1"></i>{{ $schedule->classSubject->teacher->name }}</p>
                                    @endif
                                    @if($schedule->eClass?->name)
                                        <p class="text-xs text-gray-400"><i class="fas fa-door-open mr-1"></i>{{ $schedule->eClass->name }}</p>
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

@endsection