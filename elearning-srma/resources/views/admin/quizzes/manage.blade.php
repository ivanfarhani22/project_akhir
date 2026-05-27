@extends('layouts.admin')

@section('title', 'Kelola Quiz')
@section('icon', 'fas fa-question-circle')

@section('content')

@php $usedPoints = $quiz->questions->sum('points'); $remainingPoints = 100 - $usedPoints; @endphp

<div class="max-w-6xl mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div class="min-w-0">
            <p class="text-xs text-slate-400 uppercase tracking-widest mb-1"><i class="fas fa-question-circle mr-1"></i> Admin / Quiz / Kelola</p>
            <h1 class="text-2xl font-extrabold text-slate-900 truncate">{{ $assignment->title }}</h1>
            <span class="inline-flex items-center gap-1 text-xs text-slate-600 bg-slate-100 px-3 py-1 rounded-full mt-1">
                {{ $assignment->eClass?->name }} • {{ $assignment->classSubject?->subject?->name }}
            </span>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('siswa.quizzes.show', $assignment) }}" target="_blank"
               class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-4 py-2.5 rounded-xl transition">
                <i class="fas fa-eye text-xs"></i> Preview
            </a>
            <a href="{{ route('admin.quizzes.index') }}"
               class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-4 py-2.5 rounded-xl transition">
                <i class="fas fa-arrow-left text-xs"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6">
            <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
            <ul class="text-sm space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- PROGRESS POIN --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-slate-700">Total Poin Soal</span>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full border
                    {{ $usedPoints == 100 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($usedPoints > 100 ? 'bg-red-50 text-red-600 border-red-200' : 'bg-blue-50 text-blue-600 border-blue-200') }}">
                    {{ $usedPoints }}/100
                </span>
            </div>
            @if($remainingPoints > 0)
                <span class="text-xs text-slate-500">Sisa: <strong class="text-slate-700">{{ $remainingPoints }} poin</strong></span>
            @elseif($remainingPoints == 0)
                <span class="text-xs font-semibold text-emerald-600"><i class="fas fa-check-circle mr-1"></i>Poin sudah penuh</span>
            @else
                <span class="text-xs font-semibold text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>Melebihi 100!</span>
            @endif
        </div>
        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-all {{ $usedPoints > 100 ? 'bg-red-500' : ($usedPoints == 100 ? 'bg-emerald-500' : 'bg-blue-500') }}"
                 style="width: {{ min($usedPoints, 100) }}%"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- PENGATURAN QUIZ --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden sticky top-4">
                <div class="h-1 bg-gradient-to-r from-[#C41E3A] to-rose-400"></div>
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                    <h2 class="font-bold text-slate-900 text-sm">Pengaturan Quiz</h2>
                </div>
                <div class="p-5">
                    <form method="POST" action="{{ route('admin.assignments.quiz.upsert', $assignment) }}" class="space-y-4">
                        @csrf
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-semibold text-slate-700">Acak Soal</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="shuffle_questions" value="1" {{ $quiz->shuffle_questions ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 peer-checked:bg-[#C41E3A] rounded-full transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-4"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-semibold text-slate-700">Acak Opsi</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="shuffle_options" value="1" {{ $quiz->shuffle_options ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 peer-checked:bg-[#C41E3A] rounded-full transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-4"></div>
                            </label>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Batas Waktu (menit)</label>
                            <input type="number" name="time_limit_minutes" value="{{ old('time_limit_minutes', $quiz->time_limit_minutes) }}" min="1" placeholder="Kosongkan = tanpa batas"
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#C41E3A] focus:ring-2 focus:ring-rose-100 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Maks. Percobaan</label>
                            <input type="number" name="attempts_allowed" value="{{ old('attempts_allowed', $quiz->attempts_allowed) }}" min="1" required
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#C41E3A] focus:ring-2 focus:ring-rose-100 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                            <select name="status" required
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#C41E3A] focus:ring-2 focus:ring-rose-100 transition bg-white">
                                <option value="draft" {{ $quiz->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ $quiz->status === 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>
                        <button type="submit"
                            class="w-full inline-flex justify-center items-center gap-2 bg-[#C41E3A] hover:bg-[#9B1630] text-white font-semibold py-2.5 rounded-xl text-sm transition">
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

                <div class="bg-white rounded-2xl border-2 border-slate-100 shadow-sm overflow-hidden">
                    <div class="h-1 bg-gradient-to-r from-slate-200 to-slate-100"></div>
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="w-7 h-7 bg-slate-100 rounded-lg flex items-center justify-center text-xs font-bold text-slate-500 flex-shrink-0">{{ $loop->iteration }}</span>
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
                            <form method="POST" action="{{ route('admin.quizzes.questions.destroy', [$assignment, $q]) }}"
                                  onsubmit="return confirm('Hapus soal ini?')">
                                @csrf @method('DELETE')
                                <button class="w-8 h-8 flex items-center justify-center bg-red-50 hover:bg-red-500 text-red-400 hover:text-white border border-red-200 rounded-lg text-xs transition flex-shrink-0">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>

                        <p class="text-sm font-semibold text-slate-900 mb-3 leading-relaxed">{{ $q->question }}</p>

                        @if(in_array($q->type, ['multiple_choice', 'checkbox']) && count($options))
                            <div class="space-y-1.5 mb-2">
                                @foreach($options as $opt)
                                    @php $isCorrect = in_array(trim($opt), $correctAnswers) || trim($opt) === trim($correctAnswer); @endphp
                                    <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl border text-sm {{ $isCorrect ? 'bg-emerald-50 border-emerald-300 text-emerald-800 font-semibold' : 'bg-slate-50 border-slate-200 text-slate-700' }}">
                                        @if($q->type === 'multiple_choice')
                                            <div class="w-4 h-4 rounded-full border-2 flex-shrink-0 {{ $isCorrect ? 'border-emerald-500 bg-emerald-500' : 'border-slate-300' }}"></div>
                                        @else
                                            <div class="w-4 h-4 rounded border-2 flex-shrink-0 flex items-center justify-center {{ $isCorrect ? 'border-emerald-500 bg-emerald-500' : 'border-slate-300' }}">
                                                @if($isCorrect)<i class="fas fa-check text-white text-[8px]"></i>@endif
                                            </div>
                                        @endif
                                        <span>{{ $opt }}</span>
                                        @if($isCorrect)<span class="ml-auto text-xs text-emerald-600 font-bold"><i class="fas fa-check mr-0.5"></i>Benar</span>@endif
                                    </div>
                                @endforeach
                            </div>
                        @elseif($q->type === 'short_answer')
                            <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl mb-2">
                                <p class="text-xs text-slate-500 italic mb-1">Jawaban isian siswa...</p>
                                @if($correctAnswer)
                                    <p class="text-xs font-semibold text-emerald-600"><i class="fas fa-check-circle mr-1"></i>Kunci: {{ $correctAnswer }}</p>
                                @endif
                            </div>
                        @endif

                        <details class="mt-3 group">
                            <summary class="cursor-pointer inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-800 transition select-none list-none">
                                <i class="fas fa-pen text-[10px]"></i> Edit Soal
                                <i class="fas fa-chevron-down text-[10px] group-open:rotate-180 transition-transform"></i>
                            </summary>
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <form method="POST" action="{{ route('admin.quizzes.questions.update', [$assignment, $q]) }}"
                                      class="space-y-4" onsubmit="prepareSubmit(this)">
                                    @csrf @method('PUT')

                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pertanyaan</label>
                                        <textarea name="question" rows="3" required
                                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#C41E3A] focus:ring-2 focus:ring-rose-100 transition resize-none">{{ $q->question }}</textarea>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tipe Soal</label>
                                            <select name="type" required onchange="switchType(this)"
                                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#C41E3A] focus:ring-2 focus:ring-rose-100 transition bg-white">
                                                <option value="multiple_choice" {{ $q->type==='multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
                                                <option value="checkbox" {{ $q->type==='checkbox' ? 'selected' : '' }}>Kotak Centang</option>
                                                <option value="short_answer" {{ $q->type==='short_answer' ? 'selected' : '' }}>Isian/Uraian</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                                Poin <span class="text-slate-400 font-normal text-xs">(maks. {{ $remainingPoints + $q->points }})</span>
                                            </label>
                                            <input type="number" name="points" min="1" max="{{ $remainingPoints + $q->points }}" value="{{ $q->points }}" required
                                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#C41E3A] focus:ring-2 focus:ring-rose-100 transition">
                                        </div>
                                    </div>

                                    <input type="hidden" name="correct_answer" class="correct-answer-hidden">
                                    <input type="hidden" name="options_text" class="options-hidden">

                                    <div class="options-area {{ $q->type === 'short_answer' ? 'hidden' : '' }}">
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                                            Opsi Jawaban
                                            <span class="text-slate-400 font-normal text-xs ml-1">— klik lingkaran/kotak untuk tandai jawaban benar</span>
                                        </label>
                                        <div class="options-list space-y-2">
                                            @foreach($options as $opt)
                                                @php $isCorrect = in_array(trim($opt), $correctAnswers) || trim($opt) === trim($correctAnswer); @endphp
                                                <div class="option-row flex items-center gap-2">
                                                    @if($q->type === 'multiple_choice')
                                                        <button type="button" onclick="toggleCorrect(this, 'radio')"
                                                            class="correct-btn w-5 h-5 rounded-full border-2 flex-shrink-0 transition-all {{ $isCorrect ? 'border-emerald-500 bg-emerald-500' : 'border-slate-300' }}"
                                                            data-correct="{{ $isCorrect ? '1' : '0' }}">
                                                            @if($isCorrect)<span class="block w-2 h-2 bg-white rounded-full m-auto mt-0.5"></span>@endif
                                                        </button>
                                                    @else
                                                        <button type="button" onclick="toggleCorrect(this, 'checkbox')"
                                                            class="correct-btn w-5 h-5 rounded border-2 flex-shrink-0 flex items-center justify-center transition-all {{ $isCorrect ? 'border-emerald-500 bg-emerald-500' : 'border-slate-300' }}"
                                                            data-correct="{{ $isCorrect ? '1' : '0' }}">
                                                            @if($isCorrect)<i class="fas fa-check text-white text-[9px]"></i>@endif
                                                        </button>
                                                    @endif
                                                    <input type="text" value="{{ $opt }}" placeholder="Teks opsi..."
                                                        class="option-input flex-1 px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#C41E3A] focus:ring-2 focus:ring-rose-100 transition">
                                                    <button type="button" onclick="removeOption(this)"
                                                        class="w-7 h-7 flex items-center justify-center text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition flex-shrink-0">
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

                                    <div class="short-answer-area {{ $q->type !== 'short_answer' ? 'hidden' : '' }}">
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kunci Jawaban <span class="text-slate-400 font-normal">(opsional)</span></label>
                                        <input type="text" class="short-answer-input w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#C41E3A] focus:ring-2 focus:ring-rose-100 transition"
                                               value="{{ $q->type === 'short_answer' ? $correctAnswer : '' }}"
                                               placeholder="Tulis kunci jawaban untuk koreksi otomatis...">
                                        <p class="text-xs text-slate-500 mt-1">Kosongkan jika dinilai manual.</p>
                                    </div>

                                    <button type="submit"
                                        class="inline-flex items-center gap-2 bg-[#C41E3A] hover:bg-[#9B1630] text-white font-semibold py-2 px-5 rounded-xl text-sm transition">
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
                <div class="bg-white rounded-2xl border-2 border-dashed border-slate-200 overflow-hidden" id="add-card">
                    <div class="p-5">
                        <button type="button" onclick="openAddForm()"
                            class="w-full flex items-center justify-center gap-2 text-sm font-semibold text-slate-400 hover:text-[#C41E3A] transition py-3" id="add-trigger">
                            <i class="fas fa-plus-circle text-xl"></i>
                            <span>Tambah Soal Baru</span>
                        </button>

                        <div id="add-form-area" class="hidden">
                            <form method="POST" action="{{ route('admin.quizzes.questions.store', $assignment) }}"
                                  class="space-y-4" onsubmit="prepareSubmit(this)">
                                @csrf

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pertanyaan <span class="text-red-500">*</span></label>
                                    <textarea name="question" rows="3" required placeholder="Tulis pertanyaan di sini..."
                                        class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#C41E3A] focus:ring-2 focus:ring-rose-100 transition resize-none"></textarea>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tipe Soal</label>
                                        <select name="type" required id="new-type" onchange="switchType(this)"
                                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#C41E3A] focus:ring-2 focus:ring-rose-100 transition bg-white">
                                            <option value="multiple_choice">Pilihan Ganda</option>
                                            <option value="checkbox">Kotak Centang</option>
                                            <option value="short_answer">Isian/Uraian</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                            Poin <span class="text-slate-400 font-normal text-xs">(maks. {{ $remainingPoints }})</span>
                                        </label>
                                        <input type="number" name="points" min="1" max="{{ $remainingPoints }}" value="{{ min(10, $remainingPoints) }}" required
                                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#C41E3A] focus:ring-2 focus:ring-rose-100 transition">
                                    </div>
                                </div>

                                <input type="hidden" name="correct_answer" class="correct-answer-hidden">
                                <input type="hidden" name="options_text" class="options-hidden">

                                <div class="options-area">
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                                        Opsi Jawaban
                                        <span class="text-slate-400 font-normal text-xs ml-1">— klik untuk tandai jawaban benar</span>
                                    </label>
                                    <div class="options-list space-y-2">
                                        <div class="option-row flex items-center gap-2">
                                            <button type="button" onclick="toggleCorrect(this, 'radio')"
                                                class="correct-btn w-5 h-5 rounded-full border-2 border-slate-300 flex-shrink-0 transition-all" data-correct="0"></button>
                                            <input type="text" placeholder="Opsi A" class="option-input flex-1 px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#C41E3A] focus:ring-2 focus:ring-rose-100 transition">
                                            <button type="button" onclick="removeOption(this)" class="w-7 h-7 flex items-center justify-center text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition flex-shrink-0">
                                                <i class="fas fa-times text-xs"></i>
                                            </button>
                                        </div>
                                        <div class="option-row flex items-center gap-2">
                                            <button type="button" onclick="toggleCorrect(this, 'radio')"
                                                class="correct-btn w-5 h-5 rounded-full border-2 border-slate-300 flex-shrink-0 transition-all" data-correct="0"></button>
                                            <input type="text" placeholder="Opsi B" class="option-input flex-1 px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#C41E3A] focus:ring-2 focus:ring-rose-100 transition">
                                            <button type="button" onclick="removeOption(this)" class="w-7 h-7 flex items-center justify-center text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition flex-shrink-0">
                                                <i class="fas fa-times text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button type="button" onclick="addOption(this, 'multiple_choice')"
                                        class="mt-2 inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-800 transition py-1">
                                        <i class="fas fa-plus-circle"></i> Tambah Opsi
                                    </button>
                                </div>

                                <div class="short-answer-area hidden">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kunci Jawaban <span class="text-slate-400 font-normal">(opsional)</span></label>
                                    <input type="text" class="short-answer-input w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#C41E3A] focus:ring-2 focus:ring-rose-100 transition"
                                           placeholder="Tulis kunci jawaban untuk koreksi otomatis...">
                                    <p class="text-xs text-slate-500 mt-1">Kosongkan jika dinilai manual.</p>
                                </div>

                                <div class="flex gap-3">
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 bg-[#C41E3A] hover:bg-[#9B1630] text-white font-semibold py-2.5 px-5 rounded-xl text-sm transition shadow-md">
                                        <i class="fas fa-plus text-xs"></i> Tambah Soal
                                    </button>
                                    <button type="button" onclick="closeAddForm()"
                                        class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 px-5 rounded-xl text-sm transition">
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
                    <p class="text-xs text-emerald-600 mt-1">Hapus atau edit soal yang ada untuk menyesuaikan poin.</p>
                </div>
            @endif

        </div>
    </div>
</div>

{{-- JS helpers (diambil dari versi guru) --}}
<script>
    function openAddForm() {
        document.getElementById('add-trigger')?.classList.add('hidden');
        document.getElementById('add-form-area')?.classList.remove('hidden');
    }

    function closeAddForm() {
        document.getElementById('add-trigger')?.classList.remove('hidden');
        document.getElementById('add-form-area')?.classList.add('hidden');
    }

    function switchType(selectEl) {
        const form = selectEl.closest('form');
        const type = selectEl.value;
        const optionsArea = form.querySelector('.options-area');
        const shortArea = form.querySelector('.short-answer-area');

        if (type === 'short_answer') {
            optionsArea?.classList.add('hidden');
            shortArea?.classList.remove('hidden');
        } else {
            optionsArea?.classList.remove('hidden');
            shortArea?.classList.add('hidden');
        }

        const list = form.querySelector('.options-list');
        if (list) {
            list.querySelectorAll('.correct-btn').forEach(btn => {
                btn.dataset.correct = '0';
                btn.innerHTML = '';
                btn.classList.remove('border-emerald-500', 'bg-emerald-500');
                btn.classList.add(type === 'multiple_choice' ? 'rounded-full' : 'rounded');
            });
        }
    }

    function toggleCorrect(btn, mode) {
        const row = btn.closest('form');
        if (!row) return;

        const typeSelect = row.querySelector('select[name="type"]');
        const type = typeSelect ? typeSelect.value : mode;

        if (type === 'multiple_choice') {
            row.querySelectorAll('.correct-btn').forEach(b => {
                b.dataset.correct = '0';
                b.innerHTML = '';
                b.classList.remove('border-emerald-500', 'bg-emerald-500');
                b.classList.add('border-slate-300');
            });
            btn.dataset.correct = '1';
            btn.classList.add('border-emerald-500', 'bg-emerald-500');
            btn.classList.remove('border-slate-300');
            btn.innerHTML = '<span class="block w-2 h-2 bg-white rounded-full m-auto mt-0.5"></span>';
        } else {
            const now = btn.dataset.correct === '1' ? '0' : '1';
            btn.dataset.correct = now;
            if (now === '1') {
                btn.classList.add('border-emerald-500', 'bg-emerald-500');
                btn.classList.remove('border-slate-300');
                btn.innerHTML = '<i class="fas fa-check text-white text-[9px]"></i>';
            } else {
                btn.classList.remove('border-emerald-500', 'bg-emerald-500');
                btn.classList.add('border-slate-300');
                btn.innerHTML = '';
            }
        }
    }

    function addOption(triggerBtn, type) {
        const form = triggerBtn.closest('form');
        const list = form.querySelector('.options-list');
        if (!list) return;

        const isMultiple = (form.querySelector('select[name="type"]')?.value || type) === 'multiple_choice';

        const div = document.createElement('div');
        div.className = 'option-row flex items-center gap-2';
        div.innerHTML = `
            <button type="button" onclick="toggleCorrect(this, '${isMultiple ? 'radio' : 'checkbox'}')"
                class="correct-btn w-5 h-5 ${isMultiple ? 'rounded-full' : 'rounded'} border-2 border-slate-300 flex-shrink-0 transition-all" data-correct="0"></button>
            <input type="text" placeholder="Teks opsi..." class="option-input flex-1 px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-[#C41E3A] focus:ring-2 focus:ring-rose-100 transition">
            <button type="button" onclick="removeOption(this)" class="w-7 h-7 flex items-center justify-center text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition flex-shrink-0">
                <i class="fas fa-times text-xs"></i>
            </button>
        `;
        list.appendChild(div);
    }

    function removeOption(btn) {
        const row = btn.closest('.option-row');
        row?.remove();
    }

    function prepareSubmit(form) {
        const type = form.querySelector('select[name="type"]')?.value;
        const correctHidden = form.querySelector('.correct-answer-hidden');
        const optionsHidden = form.querySelector('.options-hidden');

        if (type === 'short_answer') {
            const shortInput = form.querySelector('.short-answer-input');
            if (correctHidden) correctHidden.value = (shortInput?.value || '').trim();
            if (optionsHidden) optionsHidden.value = '';
            return true;
        }

        const opts = [];
        const corrects = [];

        form.querySelectorAll('.option-row').forEach(row => {
            const input = row.querySelector('.option-input');
            const btn = row.querySelector('.correct-btn');
            const val = (input?.value || '').trim();
            if (!val) return;
            opts.push(val);
            if (btn?.dataset.correct === '1') corrects.push(val);
        });

        if (optionsHidden) optionsHidden.value = opts.join('\n');
        if (correctHidden) correctHidden.value = (type === 'multiple_choice') ? (corrects[0] || '') : corrects.join(', ');
        return true;
    }
</script>
@endsection
