@extends('layouts.siswa')
@section('title', 'Jadwal Pelajaran')
@section('icon', 'fas fa-calendar-alt')

{{-- NOTE: $schedules is provided by ScheduleController@index --}}

@section('content')

<div class="mb-6">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Siswa / Jadwal</p>
    <h1 class="text-xl font-bold text-gray-900">Jadwal Pelajaran</h1>
    <p class="text-sm text-gray-500 mt-1">Lihat jadwal mingguan kelas Anda</p>
</div>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-semibold text-gray-900">Jadwal</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Hari</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Guru</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ruang</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($schedules as $schedule)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3.5">
                            <span class="text-xs font-semibold text-gray-700">
                                {{ ucfirst($schedule->day_of_week) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-gray-600 bg-gray-100 px-2.5 py-1 rounded-lg">
                                <i class="fas fa-clock text-[10px]"></i>
                                {{ $schedule->start_time ? \Carbon\Carbon::createFromTimeString($schedule->start_time)->format('H:i') : '—' }}
                                {{ $schedule->end_time ? '–'.\Carbon\Carbon::createFromTimeString($schedule->end_time)->format('H:i') : '' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 font-semibold text-gray-800">{{ $schedule->classSubject?->subject?->name ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-gray-500 text-xs">{{ $schedule->classSubject?->teacher?->name ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-gray-500 text-xs">{{ $schedule->eClass?->name ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-gray-500 text-xs">{{ $schedule->room ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-xs text-gray-400">Belum ada jadwal.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection