@extends('layouts.siswa')
@section('title', 'Hasil Quiz')
@section('icon', 'fas fa-poll')

@section('content')
<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-poll mr-1"></i> Siswa / Quiz / Hasil</p>
    <h1 class="text-2xl font-extrabold text-gray-900">Hasil Quiz: {{ $assignment->title }}</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
        <div class="p-6">
            <p class="text-sm text-gray-500">Nilai Akhir</p>
            <p class="text-4xl font-extrabold text-gray-900 mt-1">{{ number_format($attempt->final_score, 2) }}</p>
            <p class="text-xs text-gray-500 mt-2">Poin: {{ $attempt->earned_points }} / {{ $attempt->total_points }}</p>
        </div>
    </div>

    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-bold text-gray-900">Review Jawaban</h2>
        </div>
        <div class="p-6 space-y-4">
            @foreach($attempt->quiz->questions->sortBy('order') as $idx => $q)
                @php
                    $answers = $attempt->answers ?? [];
                    $studentAnswer = $answers[$q->id] ?? null;
                    $isCorrect = $studentAnswer !== null && $q->correct_answer !== null && (string)$studentAnswer === (string)$q->correct_answer;
                @endphp
                <div class="border border-gray-200 rounded-xl p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-bold text-gray-900">Soal {{ $idx + 1 }}</p>
                            <p class="text-gray-700 mt-1 whitespace-pre-line">{{ $q->question }}</p>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $isCorrect ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $isCorrect ? 'Benar' : 'Salah' }}
                        </span>
                    </div>

                    <div class="mt-3 text-sm">
                        <p class="text-gray-600">Jawaban kamu: <span class="font-semibold text-gray-900">{{ $studentAnswer ?? '-' }}</span></p>
                        <p class="text-gray-600">Kunci: <span class="font-semibold text-gray-900">{{ $q->correct_answer ?? '-' }}</span></p>
                        <p class="text-gray-600 mt-1">Poin: <span class="font-semibold text-gray-900">{{ (int) $q->points }}</span></p>
                    </div>
                </div>
            @endforeach

            <div class="flex items-center justify-end">
                <a href="{{ route('siswa.grades.index') }}" class="px-5 py-2.5 rounded-xl bg-[#A41E35] hover:bg-[#7D1627] text-white text-sm font-bold">Lihat Nilai</a>
            </div>
        </div>
    </div>
</div>
@endsection
