@extends('layouts.siswa')
@section('title', 'Quiz')
@section('icon', 'fas fa-question-circle')

@section('content')
@php
    $hasTimeLimit = ($quiz->time_limit_minutes ?? null) && ($attempt->started_at ?? null);
    if ($hasTimeLimit) {
        $deadline = $attempt->started_at->copy()->addMinutes((int) $quiz->time_limit_minutes);
    }
@endphp

<div class="max-w-4xl mx-auto">
    {{-- Header: kecil, fokus, tidak ramai --}}
    <div class="flex items-start justify-between gap-4 mb-4">
        <div class="min-w-0">
            <div class="text-[11px] text-slate-500">Quiz</div>
            <h1 class="text-[20px] font-extrabold tracking-tight text-slate-900 truncate">{{ $assignment->title }}</h1>
            <div class="text-[12.5px] text-slate-500 mt-1">Jawab soal lalu submit.</div>
        </div>

        @if($hasTimeLimit)
            <div class="shrink-0 text-right">
                <div class="text-[11px] text-slate-500">Sisa waktu</div>
                <div class="text-[18px] font-extrabold text-slate-900 tabular-nums" id="quiz-timer">--:--</div>
            </div>
        @endif
    </div>

    {{-- Form: satu fokus (soal + jawaban) --}}
    <form id="quiz-form" method="POST" action="{{ route('siswa.quizzes.submit', $assignment) }}" class="space-y-4">
        @csrf
        <input type="hidden" name="attempt_id" value="{{ $attempt->id ?? '' }}">

        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200">
                <div class="text-[12px] text-slate-500">
                    Total soal: <span class="font-semibold text-slate-700">{{ $questions->count() }}</span>
                    @if($hasTimeLimit)
                        <span class="mx-2">•</span>
                        Batas waktu: <span class="font-semibold text-slate-700">{{ (int) $quiz->time_limit_minutes }} menit</span>
                    @endif
                </div>
            </div>

            <div class="p-5 space-y-5">
                @foreach($questions as $index => $q)
                    <div class="border border-slate-200 rounded-lg p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-[12px] font-bold text-slate-700">Soal {{ $index + 1 }}</div>
                                <div class="text-[14px] text-slate-900 mt-1 whitespace-pre-line">{{ $q->question }}</div>
                            </div>
                            <div class="shrink-0 text-[12px] text-slate-500">{{ (int) $q->points }} poin</div>
                        </div>

                        <div class="mt-3">
                            @if($q->type === 'multiple_choice')
                                @php $opts = $q->options ?? []; @endphp
                                <div class="space-y-2">
                                    @foreach($opts as $opt)
                                        <label class="flex items-start gap-2 text-[13px] text-slate-700 cursor-pointer">
                                            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}" class="mt-0.5 text-[#C41E3A]">
                                            <span class="leading-relaxed">{{ $opt }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @elseif($q->type === 'true_false')
                                <div class="flex gap-6 text-[13px] text-slate-700">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="answers[{{ $q->id }}]" value="true" class="text-[#C41E3A]">
                                        <span>Benar</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="answers[{{ $q->id }}]" value="false" class="text-[#C41E3A]">
                                        <span>Salah</span>
                                    </label>
                                </div>
                            @else
                                <input type="text" name="answers[{{ $q->id }}]"
                                       class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C41E3A]/25 focus:border-[#C41E3A]"
                                       placeholder="Jawaban...">
                            @endif
                        </div>
                    </div>
                @endforeach

                @error('answers')
                    <p class="text-rose-600 text-[13px]">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Action bar: jelas, sederhana --}}
        <div class="sticky bottom-0 py-3 bg-[var(--body-bg)]">
            <div class="flex items-center justify-between gap-3">
                <a href="{{ route('siswa.assignments.show', $assignment) }}"
                   class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-slate-300 bg-white text-[13px] font-semibold text-slate-700 hover:bg-slate-50">
                    Kembali
                </a>

                <button id="quiz-submit" type="submit"
                        class="inline-flex items-center justify-center px-5 py-2 rounded-lg bg-[#C41E3A] text-white text-[13px] font-bold hover:bg-[#9B1630]">
                    Submit
                </button>
            </div>
        </div>
    </form>
</div>

@if($hasTimeLimit)
<script>
(function () {
    const deadlineIso = @json($deadline->toIso8601String());
    const deadlineMs = Date.parse(deadlineIso);
    const timeUpUrl  = @json(route('siswa.quizzes.timeup', $assignment));

    const timerEl  = document.getElementById('quiz-timer');
    const formEl   = document.getElementById('quiz-form');
    const submitBtn = document.getElementById('quiz-submit');

    if (!timerEl || !formEl || !submitBtn || !deadlineMs) return;

    let submitted = false;

    function pad(n) { return String(n).padStart(2, '0'); }

    function lockForm() {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-60', 'cursor-not-allowed');

        const inputs = formEl.querySelectorAll('input, textarea, select, button');
        inputs.forEach((el) => {
            if (el === submitBtn) return;
            if (el.type === 'hidden') return;
            el.disabled = true;
        });
    }

    function redirectTimeUp() {
        window.location.href = timeUpUrl || window.location.href;
    }

    function tick() {
        const diff = deadlineMs - Date.now();

        if (diff <= 0) {
            timerEl.textContent = '00:00';
            if (!submitted) {
                submitted = true;
                lockForm();
                try {
                    formEl.submit();
                    setTimeout(function () {
                        if (!document.hidden) redirectTimeUp();
                    }, 2500);
                } catch (e) {
                    redirectTimeUp();
                }
            }
            return;
        }

        const totalSec = Math.floor(diff / 1000);
        const mm = Math.floor(totalSec / 60);
        const ss = totalSec % 60;
        timerEl.textContent = pad(mm) + ':' + pad(ss);

        // timer tetap tenang; hanya berubah warna ketika < 1 menit
        if (mm < 1) timerEl.classList.add('text-rose-700');
    }

    tick();
    setInterval(tick, 1000);
})();
</script>
@endif
@endsection
