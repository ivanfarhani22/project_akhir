@extends('layouts.orang-tua')

@section('title', 'Detail Laporan Harian')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('orang-tua.daily-reports.index', ['student_id' => $dailyReport->student_id]) }}" class="text-sm text-[#A41E35] hover:underline">&larr; Kembali</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Jurnal {{ $dailyReport->report_date->format('d/m/Y') }}</h1>
        <p class="text-gray-600">{{ $dailyReport->student?->name }}</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-5 space-y-4">
        <div>
            <div class="text-sm text-gray-500">Nilai rata-rata (snapshot)</div>
            <div class="font-semibold text-gray-900">{{ $dailyReport->average_grade !== null ? number_format($dailyReport->average_grade, 2) : '—' }}</div>
        </div>

        <div>
            <div class="text-sm text-gray-500">Rekap presensi (snapshot)</div>
            <div class="font-semibold text-gray-900">{{ $dailyReport->attendance_present ?? '—' }} / {{ $dailyReport->attendance_total ?? '—' }}</div>
        </div>

        <div>
            <div class="text-sm text-gray-500">Catatan</div>
            <div class="text-gray-900 whitespace-pre-line">{{ $dailyReport->notes ?: '—' }}</div>
        </div>

        <div class="pt-4 border-t border-gray-100 text-sm text-gray-600">
            Dibuat oleh: {{ $dailyReport->author?->name ?? '—' }} ({{ $dailyReport->created_by_role ?? '—' }})
        </div>
    </div>
</div>
@endsection
