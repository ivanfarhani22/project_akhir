@extends('layouts.guru')
@section('title', 'Tugas')
@section('icon', 'fas fa-tasks')

@section('content')

@php
    $cs = $classSubject ?? null;

    // Optional: for better UX on class-level view, build quick links to per-mapel view
    $classSubjectsForClass = null;
    if (!$cs && isset($class) && $class) {
        $classSubjectsForClass = $class->classSubjects
            ->where('teacher_id', auth()->id())
            ->loadMissing('subject');
    }
@endphp

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-tasks mr-1"></i> Guru / Tugas</p>
        <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-tasks text-[#A41E35] mr-2"></i>Tugas</h1>
        <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full mt-1">
            <i class="fas fa-door-open"></i> Kelas: <strong class="text-gray-700">{{ $class->name }}</strong>
            @if($cs)
                <span class="mx-1 text-gray-300">•</span> {{ $cs->subject?->name }}
            @endif
        </span>
    </div>
    <a href="{{ $cs ? route('guru.class-subjects.assignments.create', $cs) : route('guru.assignments.create', ['class_id' => $class->id]) }}"
       class="inline-flex items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white text-sm font-bold px-5 py-2.5 rounded-xl shadow-md hover:shadow-lg transition whitespace-nowrap">
        <i class="fas fa-plus text-xs"></i> Buat Tugas
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-bold text-gray-900">Daftar Tugas</h2>
        <span class="bg-gray-900 text-white text-xs font-bold px-3 py-1 rounded-full">{{ $assignments->count() }} Tugas</span>
    </div>

    <div class="p-6">
        @if($assignments->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-tasks text-3xl text-gray-300"></i>
                </div>
                <p class="text-gray-500 text-sm mb-4">Belum ada tugas untuk kelas ini.</p>
                <a href="{{ $cs ? route('guru.class-subjects.assignments.create', $cs) : route('guru.assignments.create', ['class_id' => $class->id]) }}"
                   class="inline-flex items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-md">
                    <i class="fas fa-plus text-xs"></i> Buat Tugas Pertama
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($assignments as $assignment)
                    @php
                        $submitted = $assignment->submissions()->whereNotNull('submitted_at')->count();
                        $total = $class->students->count();
                        $pct = $total > 0 ? round(($submitted/$total)*100) : 0;

                        // UX: kalau halaman ini tidak scoped ke class_subject, tampilkan label mapel per card
                        $subjectLabel = $assignment->classSubject?->subject?->name
                            ?? $assignment->eClass?->subject?->name
                            ?? null;
                    @endphp

                    <div class="group flex flex-col rounded-2xl border-2 border-gray-100 hover:border-[#A41E35] hover:shadow-lg transition-all duration-200 overflow-hidden bg-white">
                        <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>

                        <a href="{{ route('guru.assignments.show', $assignment) }}" class="p-4 flex-1">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="font-bold text-gray-900 text-sm truncate">{{ Str::limit($assignment->title, 44) }}</h3>

                                    @if(!$cs && $subjectLabel)
                                        <p class="text-[11px] text-gray-500 mt-1 inline-flex items-center gap-1">
                                            <i class="fas fa-book text-gray-400"></i>
                                            <span class="font-semibold">{{ $subjectLabel }}</span>
                                        </p>
                                    @endif

                                    @if($assignment->file_path)
                                        <p class="text-xs text-gray-400 mt-0.5"><i class="fas fa-paperclip mr-1"></i>File tersedia</p>
                                    @endif
                                </div>
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2 py-0.5 rounded-full border
                                    {{ $assignment->deadline->isPast() ? 'bg-red-50 text-red-600 border-red-200' : ($assignment->deadline->diffInDays() <= 2 ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : 'bg-blue-50 text-blue-600 border-blue-200') }}">
                                    <i class="fas fa-clock"></i>
                                    {{ $assignment->deadline->isPast() ? 'Lewat' : ($assignment->deadline->diffInDays() <= 2 ? 'Segera' : 'Aktif') }}
                                </span>
                            </div>

                            <div class="mt-3 grid grid-cols-2 gap-3">
                                <div class="rounded-xl border border-gray-100 bg-gray-50 px-3 py-2">
                                    <div class="text-[11px] text-gray-500">Deadline</div>
                                    <div class="text-xs font-bold text-gray-900 mt-0.5">{{ $assignment->deadline->format('d M Y') }}</div>
                                </div>
                                <div class="rounded-xl border border-gray-100 bg-gray-50 px-3 py-2">
                                    <div class="text-[11px] text-gray-500">Submit</div>
                                    <div class="text-xs font-bold text-gray-900 mt-0.5">{{ $submitted }} <span class="text-gray-400 font-semibold">/ {{ $total }}</span></div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="flex items-center justify-between text-[11px] text-gray-500">
                                    <span>Progress</span>
                                    <span class="font-semibold">{{ $pct }}%</span>
                                </div>
                                <div class="mt-2 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-full" style="width:{{ $pct }}%"></div>
                                </div>
                            </div>
                        </a>

                        <div class="flex gap-2 px-4 pb-4" onclick="event.stopPropagation();">
                            <a href="{{ route('guru.assignments.edit', $assignment) }}"
                               class="flex-1 flex items-center justify-center gap-1.5 text-xs font-semibold text-gray-700 bg-gray-50 hover:bg-gray-700 hover:text-white border border-gray-200 py-2 rounded-lg transition-all">
                                <i class="fas fa-pen"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('guru.assignments.destroy', $assignment) }}" class="flex-1 delete-form">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmDelete(event, '{{ $assignment->title }}')"
                                        class="w-full flex items-center justify-center gap-1.5 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-600 hover:text-white border border-red-200 py-2 rounded-lg transition-all">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="mt-6">
    <a href="{{ $cs ? route('guru.classes.show', $cs) : route('guru.classes.index') }}"
       class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm px-5 py-2.5 rounded-xl transition-all">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

@endsection

@push('scripts')
<script>
function confirmDelete(event, name) {
    event.preventDefault();
    event.stopPropagation();
    const form = event.target.closest('form');
    showConfirmation(`Apakah Anda yakin ingin menghapus tugas \"${name}\"?`, 'Konfirmasi Penghapusan', () => form.submit());
}
</script>
@endpush