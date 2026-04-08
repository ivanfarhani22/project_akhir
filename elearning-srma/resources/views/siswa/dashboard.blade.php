@extends('layouts.siswa')

@section('title', 'Beranda')
@section('icon', 'fas fa-home')

@section('content')
    <!-- PAGE HEADER -->
    <div class="mb-8">
        <p class="text-gray-500 text-sm mb-2">Selamat datang,</p>
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3 mb-2">
            <i class="fas fa-home text-blue-500"></i>
            Beranda
        </h1>
        <p class="text-gray-600 text-sm">Lihat ringkasan tugas, jadwal, dan pengumuman Anda</p>
    </div>

    <!-- QUICK STATS -->
    @php
        $myClasses = auth()->user()->classes;
        $upcomingAssignments = \App\Models\Assignment::whereIn('e_class_id', $myClasses->pluck('id'))
            ->where('deadline', '>=', now())
            ->orderBy('deadline')
            ->count();
        $totalAssignments = \App\Models\Assignment::whereIn('e_class_id', $myClasses->pluck('id'))->count();
        $submittedAssignments = \App\Models\Submission::where('student_id', auth()->id())
            ->whereNotNull('submitted_at')
            ->count();
        $averageGrade = auth()->user()->grades()->avg('score');
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-8">
        <!-- Mata Pelajaran -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm font-medium mb-2">Mata Pelajaran</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $myClasses->count() }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-book text-blue-500 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Tugas Mendatang -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-amber-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm font-medium mb-2">Tugas Mendatang</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $upcomingAssignments }}</p>
                </div>
                <div class="bg-amber-100 p-3 rounded-lg">
                    <i class="fas fa-clock text-amber-500 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Tugas Terkumpul -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm font-medium mb-2">Tugas Terkumpul</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $submittedAssignments }}/{{ $totalAssignments }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-check-circle text-green-500 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Nilai Rata-rata -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm font-medium mb-2">Nilai Rata-rata</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $averageGrade ? number_format($averageGrade, 1) : '-' }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i class="fas fa-star text-purple-500 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- UPCOMING ASSIGNMENTS -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                    <i class="fas fa-hourglass-end text-amber-500"></i>
                    Tugas Mendatang
                </h2>
            </div>
            <div class="p-6">
                @php
                    $upcomingTasks = \App\Models\Assignment::whereIn('e_class_id', $myClasses->pluck('id'))
                        ->where('deadline', '>=', now())
                        ->orderBy('deadline')
                        ->limit(5)
                        ->get();
                @endphp
                
                @if($upcomingTasks->count() > 0)
                    <div class="space-y-4">
                        @foreach($upcomingTasks as $task)
                            <div class="pb-4 border-b border-gray-200 last:pb-0 last:border-b-0 flex justify-between items-start gap-4">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 mb-1 text-sm">{{ $task->title }}</p>
                                    <p class="text-gray-600 text-xs mb-2">{{ $task->eClass->subject->name }}</p>
                                    <p class="text-amber-600 text-xs font-semibold">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        {{ $task->deadline->format('d M, H:i') }}
                                    </p>
                                </div>
                                @php
                                    $submission = \App\Models\Submission::where('student_id', auth()->id())
                                        ->where('assignment_id', $task->id)
                                        ->first();
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap {{ $submission && $submission->submitted_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $submission && $submission->submitted_at ? 'Terkumpul' : 'Belum' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 text-center">
                        <a href="{{ route('siswa.assignments.index') }}" class="text-blue-500 hover:text-blue-600 font-semibold text-sm transition">
                            Lihat Semua Tugas <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-gray-300 text-4xl mb-3 block"></i>
                        <p class="text-gray-600 text-sm">Tidak ada tugas mendatang</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- TODAY'S SCHEDULE & QUICK ACTIONS -->
        <div class="space-y-6">
            <!-- JADWAL HARI INI -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                        <i class="fas fa-calendar-day text-blue-500"></i>
                        Jadwal Hari Ini
                    </h2>
                </div>
                <div class="p-6">
                    @php
                        $today = \Carbon\Carbon::now();
                        $dayOfWeek = strtolower($today->format('l'));
                        $todaySchedules = $myClasses->where('day_of_week', $dayOfWeek)->sortBy('start_time');
                    @endphp
                    
                    @if($todaySchedules->count() > 0)
                        <div class="space-y-3">
                            @foreach($todaySchedules as $schedule)
                                <div class="pb-3 border-b border-gray-200 last:pb-0 last:border-b-0">
                                    <div class="flex justify-between items-center mb-2">
                                        <p class="font-semibold text-gray-900 text-sm">{{ $schedule->subject->name }}</p>
                                        <span class="bg-blue-500 text-white px-3 py-1 rounded text-xs font-semibold">
                                            {{ $schedule->start_time ?? 'TBA' }}
                                        </span>
                                    </div>
                                    <p class="text-gray-600 text-xs mb-1">Pengajar: {{ $schedule->teacher->name }}</p>
                                    <p class="text-gray-600 text-xs">Kelas: {{ $schedule->name }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <i class="fas fa-calendar text-gray-300 text-3xl mb-2 block"></i>
                            <p class="text-gray-600 text-sm">Tidak ada jadwal hari ini</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- AKSES CEPAT -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                        <i class="fas fa-rocket text-green-500"></i>
                        Akses Cepat
                    </h2>
                </div>
                <div class="p-4 space-y-2">
                    <a href="{{ route('siswa.subjects.index') }}" class="block p-4 bg-gradient-to-r from-green-50 to-green-100 hover:shadow-md transition rounded-lg text-decoration-none group">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-book text-green-500 text-lg"></i>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Mata Pelajaran</p>
                                <p class="text-gray-600 text-xs">Lihat semua kelas Anda</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('siswa.assignments.index') }}" class="block p-4 bg-gradient-to-r from-amber-50 to-amber-100 hover:shadow-md transition rounded-lg text-decoration-none group">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-tasks text-amber-500 text-lg"></i>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Semua Tugas</p>
                                <p class="text-gray-600 text-xs">Kelola tugas Anda</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('siswa.schedule.index') }}" class="block p-4 bg-gradient-to-r from-blue-50 to-blue-100 hover:shadow-md transition rounded-lg text-decoration-none group">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-calendar-alt text-blue-500 text-lg"></i>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Jadwal Lengkap</p>
                                <p class="text-gray-600 text-xs">Lihat jadwal mingguan</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- TIPS SECTION -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6 sm:p-8">
        <div class="flex items-start gap-4">
            <i class="fas fa-lightbulb text-yellow-300 text-2xl flex-shrink-0 mt-1"></i>
            <div>
                <h3 class="font-bold text-lg mb-2">Tips Belajar</h3>
                <p class="text-blue-100">Jangan lupa untuk selalu mengecek jadwal pelajaran dan mengumpulkan tugas sebelum deadline. Semangat belajar! 💪</p>
            </div>
        </div>
    </div>
@endsection