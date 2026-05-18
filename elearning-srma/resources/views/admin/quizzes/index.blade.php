@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-[18px] font-extrabold tracking-tight text-slate-900">Daftar Quiz</h1>
            <p class="text-[12.5px] text-slate-500 mt-1">Kelola quiz yang tersedia di sistem.</p>
        </div>
    </div>

    @if($quizzes->count() === 0)
        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600">
                    <i class="fas fa-circle-info"></i>
                </div>
                <div>
                    <div class="font-semibold text-slate-800">Belum ada quiz</div>
                    <div class="text-[12.5px] text-slate-500 mt-0.5">Quiz akan tampil di sini setelah dibuat oleh guru.</div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <div class="font-semibold text-slate-900">Data Quiz</div>
                    <div class="text-[12px] text-slate-500">Total: {{ $quizzes->total() }}</div>
                </div>
            </div>

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
                                    <div class="font-semibold text-slate-900 leading-snug">
                                        {{ $quiz->assignment?->title ?? '—' }}
                                    </div>
                                    <div class="text-[12px] text-slate-500 mt-0.5">ID: {{ $quiz->id }}</div>
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
                                        <a href="{{ route('admin.quizzes.manage', $quiz->assignment) }}"
                                           class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-[#C41E3A] text-white text-[12.5px] font-semibold hover:bg-[#9B1630]">
                                            <i class="fas fa-sliders"></i>
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
        </div>

        <div class="mt-4">
            {{ $quizzes->links() }}
        </div>
    @endif
</div>
@endsection
