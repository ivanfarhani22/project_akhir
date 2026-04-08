@extends('layouts.siswa')

@section('title', $class->subject->name)
@section('icon', 'fas fa-book')

@section('content')
    <!-- PAGE HEADER -->
    <div class="mb-8">
        <div class="flex justify-between items-center gap-4 mb-2">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <i class="fas fa-book text-blue-500"></i>
                {{ $class->subject->name }}
            </h1>
            <a href="{{ route('siswa.subjects.index') }}" class="text-blue-500 hover:text-blue-600 font-semibold text-sm transition inline-flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <p class="text-gray-600 text-sm">Kelas: {{ $class->name }} • Pengajar: {{ $class->teacher->name }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- INFO CARD -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-500"></i>
                    Informasi Kelas
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-gray-500 text-xs font-semibold mb-1 uppercase">Pengajar</p>
                    <p class="text-gray-900 font-medium">{{ $class->teacher->name }}</p>
                </div>
                
                <div>
                    <p class="text-gray-500 text-xs font-semibold mb-1 uppercase">Jadwal</p>
                    @if($class->schedules && $class->schedules->count() > 0)
                        @php $schedule = $class->schedules->first(); @endphp
                        <p class="text-gray-900 font-medium">
                            {{ ucfirst($schedule->day_of_week) }}
                            @if($schedule->start_time)
                                • {{ \Carbon\Carbon::createFromTimeString($schedule->start_time)->format('H:i') }}
                                @if($schedule->end_time)
                                    - {{ \Carbon\Carbon::createFromTimeString($schedule->end_time)->format('H:i') }}
                                @endif
                            @endif
                        </p>
                    @else
                        <p class="text-gray-900 font-medium">TBA</p>
                    @endif
                </div>

                @if($class->description)
                    <div class="pt-2">
                        <p class="text-gray-500 text-xs font-semibold mb-1 uppercase">Deskripsi</p>
                        <p class="text-gray-700 text-sm">{{ $class->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- STATISTICS CARD -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-green-500"></i>
                    Statistik
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="pb-4 border-b border-gray-200">
                    <p class="text-gray-600 text-xs font-semibold mb-2">MATERI PEMBELAJARAN</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $class->materials->count() }}</p>
                </div>
                
                <div class="pb-4 border-b border-gray-200">
                    <p class="text-gray-600 text-xs font-semibold mb-2">TUGAS</p>
                    <p class="text-3xl font-bold text-amber-600">{{ $class->assignments->count() }}</p>
                </div>

                <div>
                    <p class="text-gray-600 text-xs font-semibold mb-2">SISWA TERDAFTAR</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $class->students->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- MATERIALS SECTION -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-file-alt text-blue-500"></i>
                Materi Pembelajaran
            </h2>
        </div>
        <div class="p-6">
            @if($class->materials->count() > 0)
                <div class="space-y-3">
                    @foreach($class->materials as $material)
                        <div class="p-4 border border-gray-200 rounded-lg hover:shadow-md transition flex justify-between items-start gap-4">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 mb-2">{{ $material->title }}</h4>
                                @if($material->description)
                                    <p class="text-gray-700 text-sm mb-2">{{ Str::limit($material->description, 150) }}</p>
                                @endif
                                <p class="text-gray-600 text-xs">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $material->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg text-sm transition whitespace-nowrap inline-flex items-center gap-2">
                                <i class="fas fa-download"></i> Buka
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-file-alt text-gray-300 text-4xl mb-3 block"></i>
                    <p class="text-gray-600">Belum ada materi pembelajaran</p>
                </div>
            @endif
        </div>
    </div>

    <!-- ASSIGNMENTS SECTION -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-tasks text-amber-500"></i>
                Tugas dan Penilaian
            </h2>
        </div>
        <div class="p-6">
            @if($class->assignments->count() > 0)
                <div class="space-y-3">
                    @foreach($class->assignments as $assignment)
                        @php
                            $submission = \App\Models\Submission::where('student_id', auth()->id())
                                ->where('assignment_id', $assignment->id)
                                ->first();
                            $isLate = $submission && $submission->submitted_at && $submission->submitted_at > $assignment->deadline;
                        @endphp
                        <div class="p-4 border border-gray-200 rounded-lg hover:shadow-md transition">
                            <div class="flex justify-between items-start gap-3 mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 mb-1">{{ $assignment->title }}</h4>
                                    <p class="text-gray-700 text-sm">{{ Str::limit($assignment->description, 100) }}</p>
                                </div>
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap {{ $submission && $submission->submitted_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $submission && $submission->submitted_at ? '✓ Terkumpul' : '✗ Belum' }}
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4 p-3 bg-gray-50 rounded border-t border-b border-gray-200">
                                <div>
                                    <p class="text-gray-600 text-xs font-semibold mb-1">DEADLINE</p>
                                    <p class="text-gray-900 font-medium text-sm">{{ $assignment->deadline->format('d M H:i') }}</p>
                                </div>
                                @if($submission && $submission->grade)
                                    <div>
                                        <p class="text-gray-600 text-xs font-semibold mb-1">NILAI</p>
                                        <p class="text-blue-600 font-medium text-sm">{{ $submission->grade->score }}</p>
                                    </div>
                                @endif
                            </div>

                            <a href="{{ route('siswa.assignments.show', $assignment->id) }}" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg text-sm transition text-center inline-flex items-center justify-center gap-2">
                                <i class="fas fa-arrow-right"></i> Lihat Detail
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-tasks text-gray-300 text-4xl mb-3 block"></i>
                    <p class="text-gray-600">Belum ada tugas untuk mata pelajaran ini</p>
                </div>
            @endif
        </div>
    </div>

    <!-- ATTENDANCE SECTION -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-purple-500"></i>
                Presensi
            </h2>
        </div>
        <div class="p-6">
            @php
                $openSessions = [];
                foreach($class->classSubjects as $cs) {
                    $session = $cs->attendanceSessions()
                        ->where('status', 'open')
                        ->where('attendance_date', today())
                        ->first();
                    if ($session) {
                        $openSessions[$cs->id] = $session;
                    }
                }
            @endphp

            @if(count($openSessions) > 0)
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded mb-4">
                    <p class="text-green-800 font-semibold mb-3 flex items-center gap-2">
                        <i class="fas fa-circle-notch animate-spin"></i> Presensi Terbuka Hari Ini
                    </p>
                    <div class="space-y-2">
                        @foreach($openSessions as $classSubjectId => $session)
                            <a href="{{ route('siswa.attendance.show', $class->classSubjects->find($classSubjectId)) }}" class="block w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg text-sm transition text-center inline-flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle"></i> Lakukan Absensi - {{ $class->classSubjects->find($classSubjectId)->subject->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox text-gray-300 text-3xl mb-2 block"></i>
                    <p>Belum ada presensi hari ini</p>
                    <p class="text-xs text-gray-600 mt-1">Guru akan membuka presensi saat pelajaran dimulai</p>
                </div>
            @endif

            @if($class->classSubjects->count() > 0)
                <div class="mt-4 pt-4 border-t border-gray-200 space-y-2">
                    @foreach($class->classSubjects as $cs)
                        <a href="{{ route('siswa.attendance.show', $cs) }}" class="text-blue-500 hover:text-blue-600 font-semibold text-sm transition block">
                            <i class="fas fa-history mr-1"></i> Riwayat Presensi - {{ $cs->subject->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection