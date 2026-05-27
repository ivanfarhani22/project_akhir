@extends('layouts.guru')
@section('title', 'Detail Jurnal Harian')
@section('icon', 'fas fa-book-open')

@section('content')
<div class="mb-6">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-book mr-1"></i> Guru / Jurnal Harian / Detail</p>
    <h1 class="text-2xl font-extrabold text-gray-900">Jurnal {{ $dailyReport->report_date->format('d/m/Y') }}</h1>
    <p class="text-sm text-gray-500 mt-1">{{ $dailyReport->student?->name }}</p>
</div>

<div class="max-w-4xl">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <div class="font-bold text-gray-900">Ringkasan</div>
            @if((int)$dailyReport->created_by === (int)auth()->id())
                <a href="{{ route('guru.daily-reports.edit', $dailyReport) }}" class="text-sm text-[#A41E35] hover:underline">Edit</a>
            @endif
        </div>

        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 border border-gray-100 rounded-xl">
                    <div class="text-xs text-gray-500 uppercase font-semibold">Nilai rata-rata (snapshot)</div>
                    <div class="text-lg font-extrabold text-gray-900">{{ $dailyReport->average_grade !== null ? number_format($dailyReport->average_grade, 2) : '—' }}</div>
                </div>
                <div class="p-4 border border-gray-100 rounded-xl">
                    <div class="text-xs text-gray-500 uppercase font-semibold">Rekap presensi (snapshot)</div>
                    <div class="text-lg font-extrabold text-gray-900">{{ $dailyReport->attendance_present ?? '—' }} / {{ $dailyReport->attendance_total ?? '—' }}</div>
                </div>
            </div>

            <div class="p-4 border border-gray-100 rounded-xl">
                <div class="text-xs text-gray-500 uppercase font-semibold">Catatan</div>
                <div class="mt-2 text-gray-900 whitespace-pre-line">{{ $dailyReport->notes ?: '—' }}</div>
            </div>

            <div class="text-sm text-gray-600 pt-3 border-t border-gray-100">
                Dibuat oleh: {{ $dailyReport->author?->name ?? '—' }} ({{ $dailyReport->created_by_role ?? '—' }})
            </div>

            <div class="flex gap-3">
                <a href="{{ route('guru.daily-reports.index') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm px-5 py-2.5 rounded-xl transition">
                    <i class="fas fa-arrow-left text-xs"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
