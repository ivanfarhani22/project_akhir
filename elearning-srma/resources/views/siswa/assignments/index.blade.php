@extends('layouts.siswa')
@section('title', 'Tugas')
@section('icon', 'fas fa-tasks')

@section('content')

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-tasks mr-1"></i> Siswa / Tugas</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-tasks text-amber-500 mr-2"></i>Tugas</h1>
    <p class="text-sm text-gray-500 mt-1">Kelola tugas, pengumpulan, dan nilai Anda</p>
</div>

@if($assignments->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($assignments as $assignment)
            @php
                $deadline = $assignment->deadline;
                $submission = $assignment->submissions()->where('student_id', auth()->id())->latest('submitted_at')->first();
                $submittedAt = $submission?->submitted_at;
                $isLate = $submittedAt && $deadline && $submittedAt->gt($deadline);
                $isOverdue = $deadline && now()->gt($deadline) && !$submittedAt;
                if ($submittedAt) { $badgeClass = $isLate ? 'bg-orange-50 text-orange-600 border-orange-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'; $badgeText = $isLate ? 'Terlambat' : 'Terkumpul'; }
                elseif ($isOverdue) { $badgeClass = 'bg-red-50 text-red-600 border-red-200'; $badgeText = 'Belum Dikumpul'; }
                else { $badgeClass = 'bg-blue-50 text-blue-600 border-blue-200'; $badgeText = 'Belum Submit'; }
            @endphp
            <div class="group bg-white rounded-2xl border-2 border-gray-100 hover:border-amber-400 hover:shadow-lg transition-all duration-200 overflow-hidden flex flex-col">
                <div class="h-1 bg-gradient-to-r from-amber-400 to-orange-400"></div>
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex justify-between items-start gap-3 mb-3">
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-gray-900 line-clamp-2">{{ $assignment->title }}</h3>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $assignment->classSubject?->subject?->name ?? '-' }}</p>
                        </div>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full border whitespace-nowrap flex-shrink-0 {{ $badgeClass }}">
                            {{ $badgeText }}
                        </span>
                    </div>

                    @if($assignment->description)
                        <p class="text-xs text-gray-500 line-clamp-2 mb-3">{{ $assignment->description }}</p>
                    @endif

                    <div class="grid grid-cols-2 gap-3 py-3 border-t border-b border-gray-100 my-3">
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase mb-1">Deadline</p>
                            @if($deadline)
                                <p class="text-xs font-bold text-gray-800">{{ $deadline->format('d M Y') }}</p>
                                <p class="text-xs text-amber-500">{{ $deadline->format('H:i') }}</p>
                            @else
                                <p class="text-xs text-gray-400">—</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase mb-1">Dikumpul</p>
                            @if($submittedAt)
                                <p class="text-xs font-bold text-emerald-600">{{ $submittedAt->format('d M Y') }}</p>
                                <p class="text-xs text-gray-400">{{ $submittedAt->format('H:i') }}</p>
                            @else
                                <p class="text-xs text-red-500 font-semibold">Belum</p>
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('siswa.assignments.show', $assignment->id) }}"
                       class="mt-auto w-full inline-flex justify-center items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold py-2.5 px-4 rounded-xl transition">
                        <i class="fas fa-eye text-[10px]"></i> Lihat Detail
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-tasks text-3xl text-gray-300"></i>
            </div>
            <p class="text-gray-500 text-sm">Anda belum memiliki tugas.</p>
        </div>
    </div>
@endif
@endsection