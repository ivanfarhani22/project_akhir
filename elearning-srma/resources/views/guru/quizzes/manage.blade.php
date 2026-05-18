@extends('layouts.guru')
@section('title', 'Kelola Quiz')
@section('icon', 'fas fa-question-circle')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex items-start justify-between gap-4 mb-6">
        <div class="min-w-0">
            <div class="text-[11px] text-slate-500">Guru / Quiz</div>
            <h1 class="text-[18px] font-extrabold tracking-tight text-slate-900 truncate">Kelola Quiz</h1>
            <div class="text-[12.5px] text-slate-500 mt-1 truncate">{{ $assignment->title }}</div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('guru.quizzes.index') }}"
               class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-300 bg-white text-[12.5px] font-semibold text-slate-700 hover:bg-slate-50">
                <i class="fas fa-arrow-left text-[11px]"></i>
                Kembali
            </a>
            <a href="{{ route('siswa.quizzes.show', $assignment) }}" target="_blank"
               class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-300 bg-white text-[12.5px] font-semibold text-slate-700 hover:bg-slate-50">
                <i class="fas fa-up-right-from-square text-[11px]"></i>
                Preview
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-[13px] font-semibold rounded-xl p-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="lg:col-span-1">
            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200">
                    <div class="font-semibold text-slate-900">Pengaturan</div>
                    <div class="text-[12px] text-slate-500 mt-0.5">Atur timer, percobaan, dan status quiz.</div>
                </div>
                <div class="p-5">
                    <form method="POST" action="{{ route('guru.assignments.quiz.upsert', $assignment) }}" class="space-y-4">
                        @csrf

                        <label class="flex items-center justify-between gap-3 text-[13px] text-slate-700">
                            <span class="font-semibold">Acak Soal</span>
                            <input type="checkbox" name="shuffle_questions" value="1" {{ $quiz->shuffle_questions ? 'checked' : '' }}>
                        </label>

                        <label class="flex items-center justify-between gap-3 text-[13px] text-slate-700">
                            <span class="font-semibold">Acak Opsi</span>
                            <input type="checkbox" name="shuffle_options" value="1" {{ $quiz->shuffle_options ? 'checked' : '' }}>
                        </label>

                        <div>
                            <label class="block text-[12.5px] font-semibold text-slate-700 mb-1">Batas waktu (menit)</label>
                            <input type="number" name="time_limit_minutes" value="{{ old('time_limit_minutes', $quiz->time_limit_minutes) }}" min="1"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C41E3A]/25 focus:border-[#C41E3A]">
                            <p class="text-[11.5px] text-slate-500 mt-1">Kosongkan jika tanpa batas waktu.</p>
                        </div>

                        <div>
                            <label class="block text-[12.5px] font-semibold text-slate-700 mb-1">Maks percobaan</label>
                            <input type="number" name="attempts_allowed" value="{{ old('attempts_allowed', $quiz->attempts_allowed) }}" min="1" required
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C41E3A]/25 focus:border-[#C41E3A]">
                        </div>

                        <div>
                            <label class="block text-[12.5px] font-semibold text-slate-700 mb-1">Status</label>
                            <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C41E3A]/25 focus:border-[#C41E3A]" required>
                                <option value="draft" {{ $quiz->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ $quiz->status === 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg bg-[#C41E3A] hover:bg-[#9B1630] text-white text-[13px] font-bold">
                            Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200">
                    <div class="font-semibold text-slate-900">Tambah Soal</div>
                    <div class="text-[12px] text-slate-500 mt-0.5">Gunakan bahasa yang singkat dan jelas.</div>
                </div>
                <div class="p-5">
                    <form method="POST" action="{{ route('guru.quizzes.questions.store', $assignment) }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-[12.5px] font-semibold text-slate-700 mb-1">Pertanyaan</label>
                            <textarea name="question" rows="3" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C41E3A]/25 focus:border-[#C41E3A]"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[12.5px] font-semibold text-slate-700 mb-1">Tipe</label>
                                <select name="type" class="w-full px-3 py-2 border border-slate-300 rounded-lg" required>
                                    <option value="multiple_choice">Pilihan Ganda</option>
                                    <option value="true_false">Benar/Salah</option>
                                    <option value="short_answer">Isian Singkat</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[12.5px] font-semibold text-slate-700 mb-1">Poin</label>
                                <input type="number" name="points" min="0" value="1" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-[12.5px] font-semibold text-slate-700 mb-1">Kunci jawaban</label>
                                <input type="text" name="correct_answer" class="w-full px-3 py-2 border border-slate-300 rounded-lg" placeholder="mis: A / true / teks">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[12.5px] font-semibold text-slate-700 mb-1">Opsi (khusus PG, 1 opsi per baris)</label>
                            <textarea name="options_text" rows="4" class="w-full px-3 py-2 border border-slate-300 rounded-lg" placeholder="A\nB\nC\nD"></textarea>
                            <p class="text-[11.5px] text-slate-500 mt-1">Kosongkan jika tipe bukan Pilihan Ganda.</p>
                        </div>

                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-slate-900 hover:bg-black text-white text-[13px] font-bold">
                            Tambah Soal
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <div class="font-semibold text-slate-900">Daftar Soal</div>
                        <div class="text-[12px] text-slate-500 mt-0.5">Klik "Edit" untuk ubah soal.</div>
                    </div>
                    <span class="text-[12px] font-bold px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 border border-slate-200">{{ $quiz->questions->count() }}</span>
                </div>

                <div class="p-5 space-y-3">
                    @if($quiz->questions->isEmpty())
                        <div class="text-[13px] text-slate-500">Belum ada soal.</div>
                    @else
                        @foreach($quiz->questions as $q)
                            @php
                                $optionsText = is_array($q->options) ? implode("\n", $q->options) : '';
                            @endphp
                            <div class="border border-slate-200 rounded-lg p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <div class="text-[12px] font-bold text-slate-700">#{{ $q->order }} • {{ strtoupper(str_replace('_',' ', $q->type)) }}</div>
                                        <div class="text-[13.5px] text-slate-900 mt-1 whitespace-pre-line">{{ $q->question }}</div>
                                        <div class="text-[12px] text-slate-500 mt-2">Poin: <span class="font-semibold text-slate-700">{{ $q->points }}</span> • Kunci: <span class="font-semibold text-slate-700">{{ $q->correct_answer ?? '-' }}</span></div>
                                    </div>
                                    <form method="POST" action="{{ route('guru.quizzes.questions.destroy', [$assignment, $q]) }}" onsubmit="return confirm('Hapus soal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-[12.5px] font-bold text-rose-700 hover:text-rose-900">Hapus</button>
                                    </form>
                                </div>

                                <details class="mt-3">
                                    <summary class="cursor-pointer text-[13px] font-semibold text-slate-700">Edit</summary>
                                    <form method="POST" action="{{ route('guru.quizzes.questions.update', [$assignment, $q]) }}" class="mt-3 space-y-3">
                                        @csrf
                                        @method('PUT')

                                        <div>
                                            <label class="block text-[12.5px] font-semibold text-slate-700 mb-1">Pertanyaan</label>
                                            <textarea name="question" rows="3" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">{{ $q->question }}</textarea>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                            <div>
                                                <label class="block text-[12.5px] font-semibold text-slate-700 mb-1">Tipe</label>
                                                <select name="type" class="w-full px-3 py-2 border border-slate-300 rounded-lg" required>
                                                    <option value="multiple_choice" {{ $q->type==='multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
                                                    <option value="true_false" {{ $q->type==='true_false' ? 'selected' : '' }}>Benar/Salah</option>
                                                    <option value="short_answer" {{ $q->type==='short_answer' ? 'selected' : '' }}>Isian Singkat</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-[12.5px] font-semibold text-slate-700 mb-1">Poin</label>
                                                <input type="number" name="points" min="0" value="{{ $q->points }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                                            </div>
                                            <div>
                                                <label class="block text-[12.5px] font-semibold text-slate-700 mb-1">Kunci jawaban</label>
                                                <input type="text" name="correct_answer" value="{{ $q->correct_answer }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-[12.5px] font-semibold text-slate-700 mb-1">Opsi</label>
                                            <textarea name="options_text" rows="4" class="w-full px-3 py-2 border border-slate-300 rounded-lg" placeholder="1 opsi per baris">{{ $optionsText }}</textarea>
                                        </div>

                                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-[#C41E3A] hover:bg-[#9B1630] text-white text-[13px] font-bold">
                                            Simpan Perubahan
                                        </button>
                                    </form>
                                </details>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
