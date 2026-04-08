@extends('layouts.siswa')

@section('title', 'Jadwal Pelajaran')
@section('icon', 'fas fa-calendar-alt')

@php
    $daysOrder = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
    $dayLabels = ['monday'=>'Senin','tuesday'=>'Selasa','wednesday'=>'Rabu','thursday'=>'Kamis','friday'=>'Jumat','saturday'=>'Sabtu','sunday'=>'Minggu'];
    $dayColors = ['monday'=>'bg-indigo-500','tuesday'=>'bg-sky-500','wednesday'=>'bg-emerald-500','thursday'=>'bg-amber-500','friday'=>'bg-red-500','saturday'=>'bg-purple-500','sunday'=>'bg-pink-500'];
    
    if (!isset($classes)) {
        $classes = collect([]);
    }
    
    $schedules = collect([]);
    foreach ($classes as $class) {
        if ($class->schedules && count($class->schedules) > 0) {
            foreach ($class->schedules as $schedule) {
                $schedule->class = $class;
                $schedules->push($schedule);
            }
        }
    }
@endphp

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
        <i class="fas fa-calendar-alt text-blue-500"></i>
        Jadwal Pelajaran
    </h1>
    <p class="text-gray-600 text-sm mt-1">Lihat jadwal mingguan kelas Anda</p>
</div>

@if($schedules->count())

    <!-- Weekly Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-table text-blue-500"></i>
                Jadwal Mingguan
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-200 bg-gray-50">
                        <th class="px-6 py-4 text-left text-gray-600 font-semibold text-sm">Hari</th>
                        <th class="px-6 py-4 text-left text-gray-600 font-semibold text-sm">Mata Pelajaran</th>
                        <th class="px-6 py-4 text-left text-gray-600 font-semibold text-sm">Kelas</th>
                        <th class="px-6 py-4 text-left text-gray-600 font-semibold text-sm">Pengajar</th>
                        <th class="px-6 py-4 text-left text-gray-600 font-semibold text-sm">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php $hasAny = false; @endphp
                    @foreach($daysOrder as $day)
                        @php $daySchedules = $schedules->filter(fn($s) => strtolower($s->day_of_week) === $day)->sortBy('start_time'); @endphp
                        @foreach($daySchedules as $schedule)
                            @php $hasAny = true; $colorClass = $dayColors[$day] ?? 'bg-indigo-500'; $class = $schedule->class; @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    @if($loop->first)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold text-white {{ $colorClass }}">
                                            {{ $dayLabels[$day] ?? ucfirst($day) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $class->classSubjects?->first()?->subject?->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $class->name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $class->classSubjects?->first()?->teacher?->name ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    @if($schedule->start_time)
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                            <i class="fas fa-clock"></i>
                                            {{ \Carbon\Carbon::createFromTimeString($schedule->start_time)->format('H:i') }}{{ $schedule->end_time ? ' – '.\Carbon\Carbon::createFromTimeString($schedule->end_time)->format('H:i') : '' }}
                                        </span>
                                    @else
                                        <span class="text-gray-500">TBA</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    @if(!$hasAny)
                        <tr><td colspan="5"><div class="py-8 text-center text-gray-500"><i class="fas fa-calendar text-gray-300 text-3xl mb-2 block"></i><p>Belum ada jadwal yang tersedia</p></div></td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Subject Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @foreach($schedules->sortBy(fn($s) => array_search(strtolower($s->day_of_week), $daysOrder)) as $schedule)
            @php $colorClass = $dayColors[strtolower($schedule->day_of_week)] ?? 'bg-indigo-500'; $class = $schedule->class; @endphp
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-start gap-4">
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">{{ $class->classSubjects?->first()?->subject?->name ?? 'Mata Pelajaran' }}</h3>
                        <p class="text-gray-600 text-xs mt-1">{{ $class->name }}</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold text-white {{ $colorClass }} whitespace-nowrap flex-shrink-0">
                        {{ $dayLabels[strtolower($schedule->day_of_week)] ?? $schedule->day_of_week }}
                    </span>
                </div>
                <div class="p-4 space-y-2">
                    <div class="flex items-center gap-2 text-gray-600 text-sm">
                        <i class="fas fa-chalkboard-teacher text-gray-400"></i> 
                        <span>{{ $class->classSubjects?->first()?->teacher?->name ?? '—' }}</span>
                    </div>
                    @if($schedule->start_time)
                        <div class="flex items-center gap-2 text-gray-600 text-sm">
                            <i class="fas fa-clock text-gray-400"></i>
                            <span>{{ \Carbon\Carbon::createFromTimeString($schedule->start_time)->format('H:i') }}{{ $schedule->end_time ? ' – '.\Carbon\Carbon::createFromTimeString($schedule->end_time)->format('H:i') : '' }}</span>
                        </div>
                    @endif
                    @if($schedule->room)
                        <div class="flex items-center gap-2 text-gray-600 text-sm">
                            <i class="fas fa-door-open text-gray-400"></i>
                            <span>{{ $schedule->room }}</span>
                        </div>
                    @endif
                    @if($class->description)
                        <div class="flex items-start gap-2 text-gray-600 text-xs pt-2 border-t border-gray-200">
                            <i class="fas fa-info-circle text-gray-400 mt-0.5 flex-shrink-0"></i>
                            <span>{{ Str::limit($class->description, 80) }}</span>
                        </div>
                    @endif
                    <a href="{{ route('siswa.subjects.show', $class->id) }}" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg text-sm transition text-center inline-flex items-center justify-center gap-2 mt-3">
                        <i class="fas fa-arrow-right"></i> Lihat Detail
                    </a>
                </div>
            </div>
        @endforeach
    </div>

@else
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-12 text-center">
            @if($classes->count() === 0)
                <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4 block"></i>
                <p class="text-gray-600 text-base font-semibold mb-1">Anda belum terdaftar di kelas apapun</p>
                <p class="text-gray-500 text-sm">Jadwal akan muncul setelah Anda terdaftar di kelas.</p>
            @else
                <i class="fas fa-clock text-gray-300 text-5xl mb-4 block"></i>
                <p class="text-gray-600 text-base font-semibold mb-1">Belum ada jadwal yang ditetapkan</p>
                <p class="text-gray-500 text-sm">Hubungi guru atau admin untuk mengatur jadwal kelas Anda.</p>
            @endif
        </div>
    </div>
@endif

@endsection
