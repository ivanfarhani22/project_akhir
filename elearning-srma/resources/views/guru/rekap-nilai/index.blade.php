@extends('layouts.guru')
@section('title', 'Rekap Nilai')
@section('icon', 'fas fa-table')

@section('content')

<div class="mb-8">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-table mr-1"></i> Guru / Rekap Nilai</p>
            <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-table text-blue-600 mr-2"></i>Rekap Nilai</h1>
            <p class="text-sm text-gray-500 mt-1">Rekap nilai per kelas dan mata pelajaran yang Anda ajarkan.</p>
        </div>

        <a href="{{ url('/guru/grades') }}"
           class="inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2.5 rounded-xl text-sm transition">
            <i class="fas fa-arrow-left text-xs"></i> Kembali
        </a>
    </div>
</div>

{{-- FILTER --}}
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
    <div class="h-1 bg-gradient-to-r from-blue-500 to-indigo-400"></div>
    <div class="p-5">
        <form method="GET" action="{{ route('guru.rekap-nilai.index') }}" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-end">
            <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kelas + Mata Pelajaran</label>
                <select name="class_subject_id"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition bg-white">
                    <option value="">Pilih kelas & mapel...</option>
                    @foreach($classSubjects as $cs)
                        <option value="{{ $cs->id }}" @selected((string)$classSubjectId === (string)$cs->id)>
                            {{ $cs->eClass?->name ?? '-' }} — {{ $cs->subject?->name ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition shadow-sm">
                    <i class="fas fa-filter text-xs"></i> Tampilkan
                </button>
                <a href="{{ route('guru.rekap-nilai.index') }}"
                   class="inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2.5 rounded-xl text-sm transition">
                    <i class="fas fa-times text-xs"></i> Reset
                </a>
                @if($classSubjectId)
                    <a href="{{ route('guru.rekap-nilai.export', ['class_subject_id' => $classSubjectId]) }}"
                       class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition shadow-sm">
                        <i class="fas fa-download text-xs"></i>
                        <span class="hidden sm:inline">Export CSV</span>
                        <span class="sm:hidden">CSV</span>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- KONTEN --}}
@if(!$table)
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex flex-col items-center justify-center py-16 text-center px-6">
            <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-table text-3xl text-gray-300"></i>
            </div>
            <p class="text-gray-500 text-sm">Pilih kelas & mapel untuk melihat rekap nilai.</p>
        </div>
    </div>
@else
    @php
        $assignments = $table['assignments'];
        $rows = $table['rows'];
        $cs = $table['classSubject'];
    @endphp

    {{-- INFO HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div>
            <h2 class="font-extrabold text-gray-900">{{ $cs->eClass?->name ?? '-' }} — {{ $cs->subject?->name ?? '-' }}</h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ $rows->count() }} siswa • {{ $assignments->count() }} tugas</p>
        </div>
        {{-- Statistik ringkas --}}
        @php
            $classAvg = $rows->count() ? round($rows->avg('average'), 1) : 0;
            $highest  = $rows->count() ? $rows->max('average') : 0;
            $lowest   = $rows->count() ? $rows->min('average') : 0;
        @endphp
        <div class="flex gap-3">
            <div class="text-center bg-blue-50 border border-blue-100 rounded-xl px-4 py-2">
                <p class="text-lg font-extrabold text-blue-600">{{ $classAvg }}</p>
                <p class="text-xs text-gray-400">Rata-rata kelas</p>
            </div>
            <div class="text-center bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-2">
                <p class="text-lg font-extrabold text-emerald-600">{{ $highest }}</p>
                <p class="text-xs text-gray-400">Tertinggi</p>
            </div>
            <div class="text-center bg-red-50 border border-red-100 rounded-xl px-4 py-2">
                <p class="text-lg font-extrabold text-red-500">{{ $lowest }}</p>
                <p class="text-xs text-gray-400">Terendah</p>
            </div>
        </div>
    </div>

    {{-- TABEL DESKTOP --}}
    <div class="hidden md:block bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10 min-w-[160px]">Siswa</th>
                        @foreach($assignments as $as)
                            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider min-w-[120px]">
                                <div class="truncate max-w-[120px]" title="{{ $as->title }}">{{ Str::limit($as->title, 18) }}</div>
                            </th>
                        @endforeach
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider min-w-[100px]">Rata-rata</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($rows as $row)
                        @php
                            $avg = (float) $row['average'];
                            $avgColor = $avg >= 80 ? 'text-emerald-600' : ($avg >= 70 ? 'text-blue-600' : ($avg >= 60 ? 'text-yellow-600' : 'text-red-500'));
                            $avgBg = $avg >= 80 ? 'bg-emerald-50 border-emerald-200' : ($avg >= 70 ? 'bg-blue-50 border-blue-200' : ($avg >= 60 ? 'bg-yellow-50 border-yellow-200' : 'bg-red-50 border-red-200'));
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3.5 font-semibold text-gray-900 sticky left-0 bg-white hover:bg-gray-50 z-10">
                                {{ $row['student']->name }}
                            </td>
                            @foreach($assignments as $as)
                                @php $score = $row['scores'][$as->id] ?? null; @endphp
                                <td class="px-5 py-3.5 text-center">
                                    @if($score !== null)
                                        <span class="inline-flex items-center justify-center text-xs font-bold px-2.5 py-1 rounded-full border
                                            {{ (int)$score >= 80 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ((int)$score >= 70 ? 'bg-blue-50 text-blue-600 border-blue-200' : ((int)$score >= 60 ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : 'bg-red-50 text-red-600 border-red-200')) }}">
                                            {{ $score }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center justify-center text-sm font-extrabold px-3 py-1 rounded-full border {{ $avgBg }} {{ $avgColor }}">
                                    {{ $row['average'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- CARD MOBILE --}}
    <div class="md:hidden space-y-4">
        @foreach($rows as $row)
            @php
                $avg = (float) $row['average'];
                $avgColor = $avg >= 80 ? 'text-emerald-600' : ($avg >= 70 ? 'text-blue-600' : ($avg >= 60 ? 'text-yellow-600' : 'text-red-500'));
                $avgBg = $avg >= 80 ? 'bg-emerald-50 border-emerald-200' : ($avg >= 70 ? 'bg-blue-50 border-blue-200' : ($avg >= 60 ? 'bg-yellow-50 border-yellow-200' : 'bg-red-50 border-red-200'));
            @endphp
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
                    <p class="font-bold text-gray-900 text-sm">{{ $row['student']->name }}</p>
                    <span class="inline-flex items-center justify-center text-sm font-extrabold px-3 py-1 rounded-full border {{ $avgBg }} {{ $avgColor }}">
                        Rata: {{ $row['average'] }}
                    </span>
                </div>
                <div class="p-4 grid grid-cols-2 gap-2">
                    @foreach($assignments as $as)
                        @php $score = $row['scores'][$as->id] ?? null; @endphp
                        <div class="flex items-center justify-between gap-2 bg-gray-50 rounded-lg px-3 py-2">
                            <span class="text-xs text-gray-500 truncate flex-1" title="{{ $as->title }}">{{ Str::limit($as->title, 20) }}</span>
                            @if($score !== null)
                                <span class="text-xs font-bold px-2 py-0.5 rounded-full border flex-shrink-0
                                    {{ (int)$score >= 80 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ((int)$score >= 70 ? 'bg-blue-50 text-blue-600 border-blue-200' : ((int)$score >= 60 ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : 'bg-red-50 text-red-600 border-red-200')) }}">
                                    {{ $score }}
                                </span>
                            @else
                                <span class="text-gray-300 text-xs flex-shrink-0">—</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection