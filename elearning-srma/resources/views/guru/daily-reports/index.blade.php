@extends('layouts.guru')
@section('title', 'Jurnal Harian')
@section('icon', 'fas fa-book')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
    <div>
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-book mr-1"></i> Guru / Jurnal Harian</p>
        <h1 class="text-2xl font-extrabold text-gray-900">Jurnal Harian</h1>
        <p class="text-sm text-gray-500 mt-1">Catatan + snapshot nilai & presensi untuk orang tua.</p>
    </div>
    <a href="{{ route('guru.daily-reports.create') }}" class="inline-flex items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition">
        <i class="fas fa-plus text-xs"></i> Buat Jurnal
    </a>
</div>

<form method="GET" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-6 grid grid-cols-1 sm:grid-cols-4 gap-3">
    <div class="sm:col-span-2">
        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Siswa</label>
        <select name="student_id" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm">
            <option value="">-- Semua --</option>
            @foreach($students as $s)
                <option value="{{ $s->id }}" @selected((string)request('student_id') === (string)$s->id)>{{ $s->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Dari</label>
        <input type="date" name="from" value="{{ request('from') }}" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm" />
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Sampai</label>
        <input type="date" name="to" value="{{ request('to') }}" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm" />
    </div>
    <div class="sm:col-span-4">
        <button class="inline-flex items-center gap-2 bg-gray-900 hover:bg-black text-white font-semibold px-4 py-2.5 rounded-xl text-sm">
            <i class="fas fa-filter text-xs"></i> Filter
        </button>
    </div>
</form>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 font-bold text-gray-900">Daftar Jurnal</div>

    @if($reports->count() === 0)
        <div class="p-6 text-gray-600">Belum ada jurnal.</div>
    @else
        <div class="divide-y divide-gray-100">
            @foreach($reports as $r)
                <a href="{{ route('guru.daily-reports.show', $r) }}" class="block px-6 py-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <div class="font-semibold text-gray-900">{{ $r->report_date->format('d/m/Y') }} — {{ $r->student?->name }}</div>
                            <div class="text-sm text-gray-600 line-clamp-1">{{ $r->notes ?: '—' }}</div>
                        </div>
                        <div class="text-sm text-gray-700 text-right">
                            <div>Avg: {{ $r->average_grade !== null ? number_format($r->average_grade, 2) : '—' }}</div>
                            <div>Presensi: {{ $r->attendance_present ?? '—' }}/{{ $r->attendance_total ?? '—' }}</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="p-6">
            {{ $reports->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
