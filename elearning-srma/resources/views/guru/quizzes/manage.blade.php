@extends('layouts.guru')
@section('title', 'Kelola Quiz')
@section('icon', 'fas fa-question-circle')

@section('content')

@php $usedPoints = $quiz->questions->sum('points'); $remainingPoints = 100 - $usedPoints; @endphp

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div class="min-w-0">
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-question-circle mr-1"></i> Guru / Quiz / Kelola</p>
        <h1 class="text-2xl font-extrabold text-gray-900 truncate">{{ $assignment->title }}</h1>
        <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full mt-1">
            {{ $assignment->eClass?->name }} • {{ $assignment->classSubject?->subject?->name }}
        </span>
    </div>
    <div class="flex items-center gap-2 flex-shrink-0">
        <a href="{{ route('siswa.quizzes.show', $assignment) }}" target="_blank"
           class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl transition">
            <i class="fas fa-eye text-xs"></i> Preview
        </a>
        <a href="{{ route('guru.quizzes.index') }}"
           class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl transition">
            <i class="fas fa-arrow-left text-xs"></i> Kembali
        </a>
    </div>
</div>

@if(session('success'))
    <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

{{-- PROGRESS POIN --}}
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 mb-6">
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-gray-700">Total Poin Soal</span>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full border
                {{ $usedPoints == 100 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($usedPoints > 100 ? 'bg-red-50 text-red-600 border-red-200' : 'bg-blue-50 text-blue-600 border-blue-200') }}">
                {{ $usedPoints }}/100
            </span>
        </div>
        @if($remainingPoints > 0)
            <span class="text-xs text-gray-400">Sisa: <strong class="text-gray-700">{{ $remainingPoints }} poin</strong></span>
        @elseif($remainingPoints == 0)
            <span class="text-xs font-semibold text-emerald-600"><i class="fas fa-check-circle mr-1"></i>Poin sudah penuh</span>
        @else
            <span class="text-xs font-semibold text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>Melebihi 100!</span>
        @endif
    </div>
    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
        <div class="h-full rounded-full transition-all {{ $usedPoints > 100 ? 'bg-red-500' : ($usedPoints == 100 ? 'bg-emerald-500' : 'bg-blue-500') }}"
             style="width: {{ min($usedPoints, 100) }}%"></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- PENGATURAN QUIZ --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden sticky top-4">
            <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-bold text-gray-900 text-sm">Pengaturan Quiz</h2>
            </div>
            <div class="p-5">
                <form method="POST" action="{{ route('guru.assignments.quiz.upsert', $assignment) }}" class="space-y-4">
                    @csrf
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-semibold text-gray-700">Acak Soal</label>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="shuffle_questions" value="1" {{ $quiz->shuffle_questions ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-checked:bg-[#A41E35] rounded-full transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-4"></div>
                        </label>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-semibold text-gray-700">Acak Opsi</label>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="shuffle_options" value="1" {{ $quiz->shuffle_options ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-checked:bg-[#A41E35] rounded-full transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-4"></div>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Batas Waktu (menit)</label>
                        <input type="number" name="time_limit_minutes" value="{{ old('time_limit_minutes', $quiz->time_limit_minutes) }}" min="1" placeholder="Kosongkan = tanpa batas"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Maks. Percobaan</label>
                        <input type="number" name="attempts_allowed" value="{{ old('attempts_allowed', $quiz->attempts_allowed) }}" min="1" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status</label>
                        <select name="status" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition bg-white">
                            <option value="draft" {{ $quiz->status === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ $quiz->status === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                    <button type="submit"
                        class="w-full inline-flex justify-center items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold py-2.5 rounded-xl text-sm transition">
                        <i class="fas fa-save text-xs"></i> Simpan Pengaturan
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- SOAL --}}
    <div class="lg:col-span-2 space-y-4">

        @foreach($quiz->questions as $q)
            @php
                $options = is_array($q->options) ? $q->options : [];
                $correctAnswer = $q->correct_answer ?? '';
                $correctAnswers = array_filter(array_map('trim', explode(',', $correctAnswer)));
            @endphp

            <div class="bg-white rounded-2xl border-2 border-gray-100 shadow-sm overflow-hidden">
                <div class="h-1 bg-gradient-to-r from-gray-200 to-gray-100"></div>
                <div class="p-5">
                    {{-- Header soal --}}
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="w-7 h-7 bg-gray-100 rounded-lg flex items-center justify-center text-xs font-bold text-gray-500 flex-shrink-0">{{ $loop->iteration }}</span>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full border
                                {{ $q->type === 'multiple_choice' ? 'bg-blue-50 text-blue-600 border-blue-200' : ($q->type === 'checkbox' ? 'bg-purple-50 text-purple-600 border-purple-200' : 'bg-orange-50 text-orange-600 border-orange-200') }}">
                                @if($q->type === 'multiple_choice') <i class="fas fa-dot-circle mr-1"></i>Pilihan Ganda
                                @elseif($q->type === 'checkbox') <i class="fas fa-check-square mr-1"></i>Kotak Centang
                                @else <i class="fas fa-pen-alt mr-1"></i>Isian/Uraian @endif
                            </span>
                            <span class="text-xs font-semibold text-amber-600 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-full">
                                {{ $q->points }} poin
                            </span>
                        </div>
                        <form method="POST" action="{{ route('guru.quizzes.questions.destroy', [$assignment, $q]) }}"
                              onsubmit="return confirm('Hapus soal ini?')">
                            @csrf @method('DELETE')
                            <button class="w-8 h-8 flex items-center justify-center bg-red-50 hover:bg-red-500 text-red-400 hover:text-white border border-red-200 rounded-lg text-xs transition flex-shrink-0">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>

                    {{-- Teks soal --}}
                    <p class="text-sm font-semibold text-gray-900 mb-3 leading-relaxed">{{ $q->question }}</p>

                    {{-- Preview opsi --}}
                    @if(in_array($q->type, ['multiple_choice', 'checkbox']) && count($options))
                        <div class="space-y-1.5 mb-2">
                            @foreach($options as $opt)
                                @php $isCorrect = in_array(trim($opt), $correctAnswers) || trim($opt) === trim($correctAnswer); @endphp
                                <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl border text-sm {{ $isCorrect ? 'bg-emerald-50 border-emerald-300 text-emerald-800 font-semibold' : 'bg-gray-50 border-gray-200 text-gray-700' }}">
                                    @if($q->type === 'multiple_choice')
                                        <div class="w-4 h-4 rounded-full border-2 flex-shrink-0 {{ $isCorrect ? 'border-emerald-500 bg-emerald-500' : 'border-gray-300' }}"></div>
                                    @else
                                        <div class="w-4 h-4 rounded border-2 flex-shrink-0 flex items-center justify-center {{ $isCorrect ? 'border-emerald-500 bg-emerald-500' : 'border-gray-300' }}">
                                            @if($isCorrect)<i class="fas fa-check text-white text-[8px]"></i>@endif
                                        </div>
                                    @endif
                                    <span>{{ $opt }}</span>
                                    @if($isCorrect)<span class="ml-auto text-xs text-emerald-600 font-bold"><i class="fas fa-check mr-0.5"></i>Benar</span>@endif
                                </div>
                            @endforeach
                        </div>
                    @elseif($q->type === 'short_answer')
                        <div class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl mb-2">
                            <p class="text-xs text-gray-400 italic mb-1">Jawaban isian siswa...</p>
                            @if($correctAnswer)
                                <p class="text-xs font-semibold text-emerald-600"><i class="fas fa-check-circle mr-1"></i>Kunci: {{ $correctAnswer }}</p>
                            @endif
                        </div>
                    @endif

                    {{-- Edit accordion --}}
                    <details class="mt-3 group">
                        <summary class="cursor-pointer inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-800 transition select-none list-none">
                            <i class="fas fa-pen text-[10px]"></i> Edit Soal
                            <i class="fas fa-chevron-down text-[10px] group-open:rotate-180 transition-transform"></i>
                        </summary>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <form method="POST" action="{{ route('guru.quizzes.questions.update', [$assignment, $q]) }}"
                                  class="space-y-4" onsubmit="prepareSubmit(this)">
                                @csrf @method('PUT')

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pertanyaan</label>
                                    <textarea name="question" rows="3" required
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition resize-none">{{ $q->question }}</textarea>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipe Soal</label>
                                        <select name="type" required onchange="switchType(this)"
                                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition bg-white">
                                            <option value="multiple_choice" {{ $q->type==='multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
                                            <option value="checkbox" {{ $q->type==='checkbox' ? 'selected' : '' }}>Kotak Centang</option>
                                            <option value="short_answer" {{ $q->type==='short_answer' ? 'selected' : '' }}>Isian/Uraian</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                            Poin <span class="text-gray-400 font-normal text-xs">(maks. {{ $remainingPoints + $q->points }})</span>
                                        </label>
                                        <input type="number" name="points" min="1" max="{{ $remainingPoints + $q->points }}" value="{{ $q->points }}" required
                                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition">
                                    </div>
                                </div>

                                {{-- hidden field untuk correct_answer & options, diisi JS --}}
                                <input type="hidden" name="correct_answer" class="correct-answer-hidden">
                                <input type="hidden" name="options_text" class="options-hidden">

                                {{-- Area opsi dinamis --}}
                                <div class="options-area {{ $q->type === 'short_answer' ? 'hidden' : '' }}">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Opsi Jawaban
                                        <span class="text-gray-400 font-normal text-xs ml-1">— klik lingkaran/kotak untuk tandai jawaban benar</span>
                                    </label>
                                    <div class="options-list space-y-2">
                                        @foreach($options as $opt)
                                            @php $isCorrect = in_array(trim($opt), $correctAnswers) || trim($opt) === trim($correctAnswer); @endphp
                                            <div class="option-row flex items-center gap-2">
                                                @if($q->type === 'multiple_choice')
                                                    <button type="button" onclick="toggleCorrect(this, 'radio')"
                                                        class="correct-btn w-5 h-5 rounded-full border-2 flex-shrink-0 transition-all {{ $isCorrect ? 'border-emerald-500 bg-emerald-500' : 'border-gray-300' }}"
                                                        data-correct="{{ $isCorrect ? '1' : '0' }}">
                                                        @if($isCorrect)<span class="block w-2 h-2 bg-white rounded-full m-auto mt-0.5"></span>@endif
                                                    </button>
                                                @else
                                                    <button type="button" onclick="toggleCorrect(this, 'checkbox')"
                                                        class="correct-btn w-5 h-5 rounded border-2 flex-shrink-0 flex items-center justify-center transition-all {{ $isCorrect ? 'border-emerald-500 bg-emerald-500' : 'border-gray-300' }}"
                                                        data-correct="{{ $isCorrect ? '1' : '0' }}">
                                                        @if($isCorrect)<i class="fas fa-check text-white text-[9px]"></i>@endif
                                                    </button>
                                                @endif
                                                <input type="text" value="{{ $opt }}" placeholder="Teks opsi..."
                                                    class="option-input flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition">
                                                <button type="button" onclick="removeOption(this)"
                                                    class="w-7 h-7 flex items-center justify-center text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition flex-shrink-0">
                                                    <i class="fas fa-times text-xs"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" onclick="addOption(this, '{{ $q->type }}')"
                                        class="mt-2 inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-800 transition py-1">
                                        <i class="fas fa-plus-circle"></i> Tambah Opsi
                                    </button>
                                </div>

                                {{-- Area isian --}}
                                <div class="short-answer-area {{ $q->type !== 'short_answer' ? 'hidden' : '' }}">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kunci Jawaban <span class="text-gray-400 font-normal">(opsional)</span></label>
                                    <input type="text" class="short-answer-input w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition"
                                           value="{{ $q->type === 'short_answer' ? $correctAnswer : '' }}"
                                           placeholder="Tulis kunci jawaban untuk koreksi otomatis...">
                                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika dinilai manual.</p>
                                </div>

                                <button type="submit"
                                    class="inline-flex items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold py-2 px-5 rounded-xl text-sm transition">
                                    <i class="fas fa-save text-xs"></i> Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </details>
                </div>
            </div>
        @endforeach

        {{-- TAMBAH SOAL BARU --}}
        @if($remainingPoints > 0)
            <div class="bg-white rounded-2xl border-2 border-dashed border-gray-200 overflow-hidden" id="add-card">
                <div class="p-5">
                    <button type="button" onclick="openAddForm()"
                        class="w-full flex items-center justify-center gap-2 text-sm font-semibold text-gray-400 hover:text-[#A41E35] transition py-3" id="add-trigger">
                        <i class="fas fa-plus-circle text-xl"></i>
                        <span>Tambah Soal Baru</span>
                    </button>

                    <div id="add-form-area" class="hidden">
                        <form method="POST" action="{{ route('guru.quizzes.questions.store', $assignment) }}"
                              class="space-y-4" onsubmit="prepareSubmit(this)">
                            @csrf

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pertanyaan <span class="text-red-500">*</span></label>
                                <textarea name="question" rows="3" required placeholder="Tulis pertanyaan di sini..."
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition resize-none"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipe Soal</label>
                                    <select name="type" required id="new-type" onchange="switchType(this)"
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition bg-white">
                                        <option value="multiple_choice">Pilihan Ganda</option>
                                        <option value="checkbox">Kotak Centang</option>
                                        <option value="short_answer">Isian/Uraian</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Poin <span class="text-gray-400 font-normal text-xs">(maks. {{ $remainingPoints }})</span>
                                    </label>
                                    <input type="number" name="points" min="1" max="{{ $remainingPoints }}" value="{{ min(10, $remainingPoints) }}" required
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition">
                                </div>
                            </div>

                            <input type="hidden" name="correct_answer" class="correct-answer-hidden">
                            <input type="hidden" name="options_text" class="options-hidden">

                            {{-- Opsi dinamis --}}
                            <div class="options-area">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Opsi Jawaban
                                    <span class="text-gray-400 font-normal text-xs ml-1">— klik untuk tandai jawaban benar</span>
                                </label>
                                <div class="options-list space-y-2">
                                    <div class="option-row flex items-center gap-2">
                                        <button type="button" onclick="toggleCorrect(this, 'radio')"
                                            class="correct-btn w-5 h-5 rounded-full border-2 border-gray-300 flex-shrink-0 transition-all" data-correct="0"></button>
                                        <input type="text" placeholder="Opsi A" class="option-input flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition">
                                        <button type="button" onclick="removeOption(this)" class="w-7 h-7 flex items-center justify-center text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition flex-shrink-0">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                    <div class="option-row flex items-center gap-2">
                                        <button type="button" onclick="toggleCorrect(this, 'radio')"
                                            class="correct-btn w-5 h-5 rounded-full border-2 border-gray-300 flex-shrink-0 transition-all" data-correct="0"></button>
                                        <input type="text" placeholder="Opsi B" class="option-input flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition">
                                        <button type="button" onclick="removeOption(this)" class="w-7 h-7 flex items-center justify-center text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition flex-shrink-0">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" onclick="addOption(this, 'multiple_choice')"
                                    class="mt-2 inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-800 transition py-1">
                                    <i class="fas fa-plus-circle"></i> Tambah Opsi
                                </button>
                            </div>

                            {{-- Isian --}}
                            <div class="short-answer-area hidden">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kunci Jawaban <span class="text-gray-400 font-normal">(opsional)</span></label>
                                <input type="text" class="short-answer-input w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition"
                                       placeholder="Tulis kunci jawaban untuk koreksi otomatis...">
                                <p class="text-xs text-gray-400 mt-1">Kosongkan jika dinilai manual.</p>
                            </div>

                            <div class="flex gap-3">
                                <button type="submit"
                                    class="inline-flex items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold py-2.5 px-5 rounded-xl text-sm transition shadow-md">
                                    <i class="fas fa-plus text-xs"></i> Tambah Soal
                                </button>
                                <button type="button" onclick="closeAddForm()"
                                    class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-5 rounded-xl text-sm transition">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-emerald-50 border-2 border-emerald-200 rounded-2xl p-5 text-center">
                <i class="fas fa-check-circle text-emerald-500 text-2xl mb-2 block"></i>
                <p class="text-sm font-bold text-emerald-700">Semua 100 poin sudah terdistribusi</p>
                <p class="text-xs text-emerald-500 mt-1">Hapus atau edit soal yang ada untuk menyesuaikan poin.</p>
            </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
// ─── Open/close form tambah soal ───────────────────────────────────────────
function openAddForm() {
    document.getElementById('add-trigger').classList.add('hidden');
    document.getElementById('add-form-area').classList.remove('hidden');
    document.getElementById('add-card').classList.remove('border-dashed');
    document.getElementById('add-card').classList.add('border-[#A41E35]');
}
function closeAddForm() {
    document.getElementById('add-trigger').classList.remove('hidden');
    document.getElementById('add-form-area').classList.add('hidden');
    document.getElementById('add-card').classList.add('border-dashed');
    document.getElementById('add-card').classList.remove('border-[#A41E35]');
}

// ─── Switch tipe soal ───────────────────────────────────────────────────────
function switchType(select) {
    const form       = select.closest('form');
    const type       = select.value;
    const optArea    = form.querySelector('.options-area');
    const shortArea  = form.querySelector('.short-answer-area');
    const optList    = form.querySelector('.options-list');

    if (type === 'short_answer') {
        optArea.classList.add('hidden');
        shortArea.classList.remove('hidden');
    } else {
        optArea.classList.remove('hidden');
        shortArea.classList.add('hidden');
        // Update semua tombol correct ke bentuk radio/checkbox
        form.querySelectorAll('.correct-btn').forEach(btn => {
            const isCorrect = btn.dataset.correct === '1';
            if (type === 'multiple_choice') {
                btn.classList.remove('rounded');
                btn.classList.add('rounded-full');
                btn.innerHTML = isCorrect ? '<span class="block w-2 h-2 bg-white rounded-full m-auto"></span>' : '';
            } else {
                btn.classList.remove('rounded-full');
                btn.classList.add('rounded');
                btn.innerHTML = isCorrect ? '<i class="fas fa-check text-white text-[9px]"></i>' : '';
            }
            btn.setAttribute('onclick', `toggleCorrect(this, '${type === 'multiple_choice' ? 'radio' : 'checkbox'}')`);
        });
    }
}

// ─── Toggle jawaban benar ───────────────────────────────────────────────────
function toggleCorrect(btn, mode) {
    const form = btn.closest('form');
    const allBtns = form.querySelectorAll('.correct-btn');

    if (mode === 'radio') {
        // Reset semua
        allBtns.forEach(b => {
            b.dataset.correct = '0';
            b.classList.remove('border-emerald-500', 'bg-emerald-500');
            b.classList.add('border-gray-300');
            b.innerHTML = '';
        });
        // Set yang diklik
        btn.dataset.correct = '1';
        btn.classList.add('border-emerald-500', 'bg-emerald-500');
        btn.classList.remove('border-gray-300');
        btn.innerHTML = '<span class="block w-2 h-2 bg-white rounded-full m-auto"></span>';
    } else {
        // Toggle
        const isNowCorrect = btn.dataset.correct !== '1';
        btn.dataset.correct = isNowCorrect ? '1' : '0';
        btn.classList.toggle('border-emerald-500', isNowCorrect);
        btn.classList.toggle('bg-emerald-500', isNowCorrect);
        btn.classList.toggle('border-gray-300', !isNowCorrect);
        btn.innerHTML = isNowCorrect ? '<i class="fas fa-check text-white text-[9px]"></i>' : '';
    }
}

// ─── Tambah opsi ────────────────────────────────────────────────────────────
function addOption(btn, type) {
    const list      = btn.closest('.options-area').querySelector('.options-list');
    const count     = list.querySelectorAll('.option-row').length;
    const labels    = ['A','B','C','D','E','F'];
    const label     = labels[count] || (count + 1);
    const isRadio   = type === 'multiple_choice';
    const btnShape  = isRadio ? 'rounded-full' : 'rounded';
    const clickMode = isRadio ? 'radio' : 'checkbox';

    const row = document.createElement('div');
    row.className = 'option-row flex items-center gap-2';
    row.innerHTML = `
        <button type="button" onclick="toggleCorrect(this, '${clickMode}')"
            class="correct-btn w-5 h-5 ${btnShape} border-2 border-gray-300 flex-shrink-0 flex items-center justify-center transition-all" data-correct="0"></button>
        <input type="text" placeholder="Opsi ${label}"
            class="option-input flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition">
        <button type="button" onclick="removeOption(this)"
            class="w-7 h-7 flex items-center justify-center text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition flex-shrink-0">
            <i class="fas fa-times text-xs"></i>
        </button>`;
    list.appendChild(row);
}

// ─── Hapus opsi ─────────────────────────────────────────────────────────────
function removeOption(btn) {
    const list = btn.closest('.options-list');
    if (list.querySelectorAll('.option-row').length <= 1) return;
    btn.closest('.option-row').remove();
}

// ─── Siapkan hidden fields sebelum submit ───────────────────────────────────
function prepareSubmit(form) {
    const type = form.querySelector('[name="type"]').value;
    const correctHidden  = form.querySelector('.correct-answer-hidden');
    const optionsHidden  = form.querySelector('.options-hidden');

    if (type === 'short_answer') {
        const val = form.querySelector('.short-answer-input')?.value?.trim() ?? '';
        correctHidden.value = val;
        optionsHidden.value = '';
        return;
    }

    // Kumpulkan opsi
    const rows    = form.querySelectorAll('.option-row');
    const opts    = [];
    const correct = [];

    rows.forEach(row => {
        const text = row.querySelector('.option-input')?.value?.trim();
        const isOk = row.querySelector('.correct-btn')?.dataset?.correct === '1';
        if (text) {
            opts.push(text);
            if (isOk) correct.push(text);
        }
    });

    optionsHidden.value  = opts.join("\n");
    correctHidden.value  = correct.join(',');
}

// Pastikan semua form pakai prepareSubmit
document.querySelectorAll('form[onsubmit="prepareSubmit(this)"]').forEach(f => {
    f.addEventListener('submit', () => prepareSubmit(f));
});
</script>
@endpush
@endsection