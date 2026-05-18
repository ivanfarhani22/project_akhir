@extends('layouts.guru')
@section('title', 'Quiz')
@section('icon', 'fas fa-question-circle')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            <div class="text-[11px] text-slate-500">Guru / Quiz</div>
            <h1 class="text-[18px] font-extrabold tracking-tight text-slate-900">Quiz</h1>
            <p class="text-[12.5px] text-slate-500 mt-1">Buat dan kelola quiz untuk mapel yang Anda ampu.</p>
        </div>
        <div class="hidden sm:block text-[12px] text-slate-400 text-right">
            Nilai quiz otomatis masuk ke rekap nilai.
        </div>
    </div>

    @if($errors->any())
        <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4">
            <div class="font-semibold text-[13px]">Terjadi kesalahan</div>
            <ul class="mt-2 text-[12.5px] list-disc pl-5 space-y-1">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-slate-200">
            <div class="font-semibold text-slate-900">Buat Quiz Baru</div>
            <div class="text-[12px] text-slate-500 mt-0.5">Lengkapi informasi dasar, lalu kelola soal.</div>
        </div>
        <div class="p-5">
            <form method="POST" action="{{ route('guru.quizzes.create-assignment') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-[12.5px] font-semibold text-slate-700 mb-1">Mapel <span class="text-rose-600">*</span></label>
                    <select name="class_subject_id" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-white text-[13px] focus:outline-none focus:ring-2 focus:ring-[#C41E3A]/25 focus:border-[#C41E3A]">
                        <option value="" selected disabled>Pilih mapel...</option>
                        @foreach($classSubjects as $cs)
                            <option value="{{ $cs->id }}">
                                {{ $cs->eClass?->name }} — {{ $cs->subject?->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[12.5px] font-semibold text-slate-700 mb-1">Judul <span class="text-rose-600">*</span></label>
                    <input name="title" type="text" value="{{ old('title') }}" required
                           placeholder="Contoh: Quiz Bab 1"
                           class="w-full px-3 py-2 border border-slate-300 rounded-lg text-[13px] focus:outline-none focus:ring-2 focus:ring-[#C41E3A]/25 focus:border-[#C41E3A]" />
                </div>

                <div>
                    <label class="block text-[12.5px] font-semibold text-slate-700 mb-1">Deskripsi <span class="text-slate-400 font-normal">(opsional)</span></label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-[13px] focus:outline-none focus:ring-2 focus:ring-[#C41E3A]/25 focus:border-[#C41E3A] resize-none"
                              placeholder="Instruksi singkat...">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-[12.5px] font-semibold text-slate-700 mb-1">Deadline <span class="text-rose-600">*</span></label>
                    <input type="datetime-local" name="deadline" value="{{ old('deadline') }}" required
                           class="w-full px-3 py-2 border border-slate-300 rounded-lg text-[13px] focus:outline-none focus:ring-2 focus:ring-[#C41E3A]/25 focus:border-[#C41E3A]" />
                    <p class="text-[11.5px] text-slate-500 mt-1">Deadline mengikuti deadline penilaian.</p>
                </div>

                <button type="submit"
                        class="w-full inline-flex justify-center items-center px-4 py-2 rounded-lg bg-[#C41E3A] hover:bg-[#9B1630] text-white font-bold text-[13px]">
                    Buat & Kelola
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <div class="font-semibold text-slate-900">Daftar Quiz</div>
                <div class="text-[12px] text-slate-500 mt-0.5">Quiz yang sudah dibuat untuk mapel yang Anda ajar.</div>
            </div>
            <span class="text-[12px] font-bold px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 border border-slate-200">{{ $quizzes->total() }}</span>
        </div>

        @if($quizzes->count() === 0)
            <div class="p-8 text-center text-slate-600">
                <div class="text-[13px] font-semibold">Belum ada quiz</div>
                <div class="text-[12px] text-slate-500 mt-1">Buat quiz dari formulir di atas.</div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-[13px]">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="text-left font-semibold px-5 py-3">Judul</th>
                            <th class="text-left font-semibold px-5 py-3">Kelas</th>
                            <th class="text-left font-semibold px-5 py-3">Mapel</th>
                            <th class="text-left font-semibold px-5 py-3">Status</th>
                            <th class="text-left font-semibold px-5 py-3">Diupdate</th>
                            <th class="text-right font-semibold px-5 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($quizzes as $quiz)
                            <tr class="hover:bg-slate-50/60">
                                <td class="px-5 py-3">
                                    <div class="font-semibold text-slate-900">{{ $quiz->assignment?->title ?? '—' }}</div>
                                </td>
                                <td class="px-5 py-3 text-slate-700">{{ $quiz->assignment?->eClass?->name ?? '—' }}</td>
                                <td class="px-5 py-3 text-slate-700">{{ $quiz->assignment?->classSubject?->subject?->name ?? '—' }}</td>
                                <td class="px-5 py-3">
                                    @if($quiz->status === 'published')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[12px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[12px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-slate-600">{{ optional($quiz->updated_at)->format('d/m/Y H:i') }}</td>
                                <td class="px-5 py-3 text-right">
                                    @if($quiz->assignment)
                                        <a href="{{ route('guru.quizzes.manage', $quiz->assignment) }}"
                                           class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-[#C41E3A] text-white text-[12.5px] font-bold hover:bg-[#9B1630]">
                                            Kelola
                                        </a>
                                    @else
                                        <span class="text-[12px] text-slate-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $quizzes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
