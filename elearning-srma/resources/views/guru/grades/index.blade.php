@extends('layouts.guru')
@section('title', 'Penilaian')
@section('icon', 'fas fa-star')

@section('content')

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-star mr-1"></i> Guru / Penilaian</p>
        <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-star text-yellow-400 mr-2"></i>Penilaian</h1>
        <div class="flex flex-wrap items-center gap-2 mt-1">
            <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                <i class="fas fa-chalkboard text-[10px]"></i>
                {{ $assignment->eClass?->name }} — {{ $assignment->classSubject?->subject?->name }}
            </span>
            <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                <i class="fas fa-tasks text-[10px]"></i>
                {{ Str::limit($assignment->title, 35) }}
            </span>
        </div>
    </div>
    <a href="{{ route('guru.grades.index') }}"
       class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl transition whitespace-nowrap">
        <i class="fas fa-arrow-left text-xs"></i> Semua Penilaian
    </a>
</div>

{{-- Ringkasan --}}
@php
    $graded   = $submissions->filter(fn($s) => $s->grade)->count();
    $ungraded = $submissions->count() - $graded;
    $avgScore = $graded ? round($submissions->filter(fn($s) => $s->grade)->avg(fn($s) => $s->grade->score), 1) : null;
@endphp
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm text-center py-4">
        <p class="text-2xl font-extrabold text-gray-900">{{ $submissions->count() }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Total Siswa</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm text-center py-4">
        <p class="text-2xl font-extrabold text-emerald-600">{{ $graded }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Sudah Dinilai</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm text-center py-4">
        <p class="text-2xl font-extrabold text-amber-500">{{ $ungraded }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Belum Dinilai</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm text-center py-4">
        <p class="text-2xl font-extrabold text-blue-600">{{ $avgScore ?? '—' }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Rata-rata</p>
    </div>
</div>

{{-- TABEL DESKTOP --}}
<div class="hidden md:block bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-bold text-gray-900">Daftar Pengumpulan</h2>
        <span class="bg-gray-900 text-white text-xs font-bold px-3 py-1 rounded-full">{{ $submissions->count() }}</span>
    </div>

    @if($submissions->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-inbox text-3xl text-gray-300"></i>
            </div>
            <p class="text-gray-500 text-sm">Belum ada pengumpulan.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu Kirim</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Kirim</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($submissions as $submission)
                        @php
                            $grade = $submission->grade;
                            $isLate = $submission->submitted_at && $submission->submitted_at > $assignment->deadline;
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-gray-900">{{ $submission->student->name }}</p>
                                @if($submission->student->nis)
                                    <p class="text-xs text-gray-400 mt-0.5">NIS: {{ $submission->student->nis }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                @if($submission->submitted_at)
                                    <p class="text-xs font-semibold {{ $isLate ? 'text-orange-600' : 'text-gray-700' }}">
                                        {{ $submission->submitted_at->format('d M Y H:i') }}
                                    </p>
                                    @if($isLate)
                                        <p class="text-xs text-orange-500 mt-0.5"><i class="fas fa-exclamation-circle mr-0.5"></i>Terlambat</p>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($submission->submitted_at)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $isLate ? 'bg-orange-50 text-orange-600 border border-orange-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                        <i class="fas {{ $isLate ? 'fa-clock' : 'fa-check-circle' }} text-[10px]"></i>
                                        {{ $isLate ? 'Terlambat' : 'Tepat Waktu' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-200">
                                        <i class="fas fa-times-circle text-[10px]"></i> Belum Kirim
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($grade)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border
                                        {{ $grade->score >= 80 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($grade->score >= 70 ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : 'bg-red-50 text-red-600 border-red-200') }}">
                                        {{ $grade->score }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-400 border border-gray-200">
                                        Belum dinilai
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <a href="{{ route('guru.grades.edit', $submission) }}"
                                   class="inline-flex items-center gap-1.5 bg-yellow-50 hover:bg-yellow-500 text-yellow-600 hover:text-white border border-yellow-200 text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                                    <i class="fas fa-pen text-[10px]"></i> {{ $grade ? 'Ubah Nilai' : 'Nilai' }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- CARD MOBILE --}}
<div class="md:hidden space-y-3">
    @forelse($submissions as $submission)
        @php
            $grade = $submission->grade;
            $isLate = $submission->submitted_at && $submission->submitted_at > $assignment->deadline;
        @endphp
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="flex items-start justify-between gap-3 p-4 border-b border-gray-100">
                <div>
                    <p class="font-bold text-gray-900 text-sm">{{ $submission->student->name }}</p>
                    @if($submission->student->nis)
                        <p class="text-xs text-gray-400 mt-0.5">NIS: {{ $submission->student->nis }}</p>
                    @endif
                </div>
                @if($grade)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-extrabold border flex-shrink-0
                        {{ $grade->score >= 80 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($grade->score >= 70 ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : 'bg-red-50 text-red-600 border-red-200') }}">
                        {{ $grade->score }}
                    </span>
                @else
                    <span class="text-xs font-semibold text-gray-400 bg-gray-50 border border-gray-200 px-2.5 py-1 rounded-full flex-shrink-0">Belum</span>
                @endif
            </div>
            <div class="flex items-center justify-between px-4 py-3 gap-3">
                <div>
                    @if($submission->submitted_at)
                        <p class="text-xs font-semibold {{ $isLate ? 'text-orange-600' : 'text-emerald-600' }}">
                            <i class="fas {{ $isLate ? 'fa-clock' : 'fa-check-circle' }} mr-1"></i>
                            {{ $isLate ? 'Terlambat' : 'Tepat Waktu' }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $submission->submitted_at->format('d M Y H:i') }}</p>
                    @else
                        <p class="text-xs text-red-500 font-semibold"><i class="fas fa-times-circle mr-1"></i>Belum dikumpul</p>
                    @endif
                </div>
                <a href="{{ route('guru.grades.edit', $submission) }}"
                   class="inline-flex items-center gap-1.5 bg-yellow-50 hover:bg-yellow-500 text-yellow-600 hover:text-white border border-yellow-200 text-xs font-semibold px-3 py-2 rounded-lg transition flex-shrink-0">
                    <i class="fas fa-pen text-[10px]"></i> {{ $grade ? 'Ubah' : 'Nilai' }}
                </a>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <i class="fas fa-inbox text-gray-200 text-4xl mb-3"></i>
                <p class="text-gray-400 text-sm">Belum ada pengumpulan.</p>
            </div>
        </div>
    @endforelse
</div>

@endsection