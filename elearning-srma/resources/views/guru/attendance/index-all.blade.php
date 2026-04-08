@extends('layouts.guru')

@section('title', 'Presensi - Semua Mata Pelajaran')
@section('icon', 'fas fa-clipboard-list')

@section('content')
<!-- PAGE HEADER -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3 mb-2">
        <i class="fas fa-clipboard-list text-green-500"></i>
        Presensi - Semua Mata Pelajaran
    </h1>
    <p class="text-gray-600 text-sm">Kelola presensi untuk semua mata pelajaran Anda</p>
</div>

<!-- SUCCESS MESSAGE -->
@if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded mb-8 inline-flex items-center gap-2">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if($classSubjects->count() > 0)
    <div class="space-y-6">
        @foreach($classSubjects as $classSubject)
            <!-- SUBJECT SECTION -->
            <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
                <!-- SUBJECT HEADER with Gradient -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-lg">{{ $classSubject->subject->name }}</h2>
                        <p class="text-green-100 text-sm mt-1"><i class="fas fa-door-open mr-1"></i> {{ $classSubject->eClass->name }}</p>
                    </div>
                    <a href="{{ route('guru.attendance.create') }}" data-subject="{{ $classSubject->id }}" class="bg-green-400 hover:bg-green-300 text-green-900 font-medium py-1.5 px-3 rounded text-sm transition inline-flex items-center gap-2 btn-create-attendance">
                        <i class="fas fa-plus"></i> Buka Presensi
                    </a>
                </div>

                <!-- SESSION LIST -->
                <div class="p-6">
                    @php
                        $subjectSessions = $sessions->filter(fn($s) => $s->class_subject_id === $classSubject->id)->take(5);
                    @endphp

                    @if($subjectSessions->count() > 0)
                        <div class="space-y-3">
                            @foreach($subjectSessions as $session)
                                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 hover:bg-gray-100 transition flex justify-between items-start sm:items-center gap-4">
                                    <!-- SESSION INFO -->
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-gray-900">{{ $session->attendance_date->format('l, d F Y') }}</div>
                                        <div class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-clock mr-1"></i> {{ $session->opened_at }}
                                            @if($session->closed_at)
                                                - {{ $session->closed_at }}
                                            @endif
                                        </div>
                                        <div class="flex flex-wrap gap-4 mt-3 text-xs sm:text-sm">
                                            <div class="flex items-center gap-1 text-gray-600">
                                                <i class="fas fa-users"></i>
                                                <span>Total: <span class="font-semibold text-gray-900">{{ $session->records->count() }}</span></span>
                                            </div>
                                            <div class="flex items-center gap-1 text-green-600">
                                                <i class="fas fa-check"></i>
                                                <span>Hadir: <span class="font-semibold">{{ $session->records->where('status', 'present')->count() }}</span></span>
                                            </div>
                                            <div class="flex items-center gap-1 text-red-600">
                                                <i class="fas fa-times"></i>
                                                <span>Tidak Hadir: <span class="font-semibold">{{ $session->records->where('status', 'absent')->count() }}</span></span>
                                            </div>
                                            <div class="flex items-center gap-1 text-blue-600">
                                                <i class="fas fa-percent"></i>
                                                <span>Kehadiran: <span class="font-semibold">{{ $session->getAttendancePercentage() }}%</span></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- STATUS & ACTIONS -->
                                    <div class="flex items-center gap-3 flex-shrink-0">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold {{ $session->isOpen() ? 'bg-green-100 text-green-800' : ($session->isClosed() ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }} whitespace-nowrap">
                                            @if($session->isOpen())
                                                <i class="fas fa-circle"></i> Terbuka
                                            @elseif($session->isClosed())
                                                <i class="fas fa-check-circle"></i> Ditutup
                                            @else
                                                <i class="fas fa-ban"></i> Dibatalkan
                                            @endif
                                        </span>

                                        <div class="flex gap-2">
                                            <a href="{{ route('guru.attendance.show', $session) }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-1 px-2 rounded text-xs transition" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($session->isOpen())
                                                <form action="{{ route('guru.attendance.close', $session) }}" method="POST" class="inline attendance-form">
                                                    @csrf
                                                    <button type="button" class="bg-orange-500 hover:bg-orange-600 text-white font-medium py-1 px-2 rounded text-xs transition" onclick="confirmAttendanceClose(event)" title="Tutup">
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-gray-300 text-3xl mb-2 block"></i>
                            <p class="text-sm">Belum ada presensi untuk mata pelajaran ini</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-16 px-4">
        <i class="fas fa-inbox text-gray-300 text-6xl mb-4 block"></i>
        <p class="text-gray-600 text-lg">Anda tidak memiliki mata pelajaran apapun</p>
    </div>
@endif

@endsection

@push('scripts')
<script>
function confirmAttendanceClose(event) {
    event.preventDefault();
    const form = event.target.closest('form');
    showConfirmation('Tutup presensi?', 'Konfirmasi Tutup Presensi', function() {
        form.submit();
    });
}
</script>
@endpush
