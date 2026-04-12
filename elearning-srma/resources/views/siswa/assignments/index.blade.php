@extends('layouts.siswa')

@section('title', 'Tugas')
@section('icon', 'fas fa-tasks')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            <i class="fas fa-tasks text-amber-500"></i>
            Tugas
        </h1>
        <p class="text-gray-600 text-sm mt-1">Kelola tugas, pengumpulan, dan nilai Anda</p>
    </div>

    @if($assignments->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @foreach($assignments as $assignment)
                @php
                    $deadline = $assignment->deadline;
                    $submission = $assignment->submissions()->where('student_id', auth()->id())->latest('submitted_at')->first();
                    $submittedAt = $submission?->submitted_at;
                    $isLate = $submittedAt && $deadline && $submittedAt->gt($deadline);
                    $isOverdue = $deadline && now()->gt($deadline) && !$submittedAt;
                @endphp
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-start gap-4">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">
                                {{ $assignment->title }}
                            </h3>
                            <p class="text-gray-600 text-xs mt-1">
                                {{ $assignment->classSubject?->subject?->name ?? '-' }}
                            </p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap {{ $submittedAt ? ($isLate ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800') : ($isOverdue ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                            @if($submittedAt)
                                ✓ {{ $isLate ? 'Terlambat' : 'Terkumpul' }}
                            @elseif($isOverdue)
                                ✗ Belum Dikumpul
                            @else
                                ⏳ Belum Submit
                            @endif
                        </span>
                    </div>
                    
                    <!-- Body -->
                    <div class="p-6 space-y-4">
                        <!-- Description -->
                        @if($assignment->description)
                            <div class="pb-4 border-b border-gray-200">
                                <p class="text-gray-700 text-sm">{{ Str::limit($assignment->description, 150) }}</p>
                            </div>
                        @endif

                        <!-- Info Grid -->
                        <div class="grid grid-cols-2 gap-3 pb-4 border-b border-gray-200">
                            <div>
                                <p class="text-gray-600 text-xs font-semibold mb-1 uppercase">Deadline</p>
                                @if($deadline)
                                    <p class="text-gray-900 font-medium text-sm">{{ $deadline->format('d M Y') }}</p>
                                    <p class="text-amber-600 text-xs mt-1">{{ $deadline->format('H:i') }}</p>
                                @else
                                    <p class="text-gray-500 text-sm">-</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-gray-600 text-xs font-semibold mb-1 uppercase">Status Pengumpulan</p>
                                @if($submittedAt)
                                    <p class="text-green-600 font-medium text-sm">✓ {{ $submittedAt->format('d M Y') }}</p>
                                    <p class="text-gray-700 text-xs mt-1">{{ $submittedAt->format('H:i') }}</p>
                                @else
                                    <p class="text-red-600 font-medium text-sm">Belum Dikumpul</p>
                                @endif
                            </div>
                        </div>

                        <!-- Action Button -->
                        <a href="{{ route('siswa.assignments.show', $assignment->id) }}" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg text-sm transition text-center inline-flex items-center justify-center gap-2">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-12 text-center">
                <i class="fas fa-check-circle text-gray-300 text-6xl mb-4 block"></i>
                <p class="text-gray-600 text-base">Anda belum memiliki tugas</p>
            </div>
        </div>
    @endif
@endsection
