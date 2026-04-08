@extends('layouts.guru')

@section('title', 'Dashboard Guru')
@section('icon', 'fas fa-graduation-cap')

@section('content')
    <!-- PAGE HEADER -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3 mb-2">
            <i class="fas fa-graduation-cap text-amber-500"></i>
            Dashboard Guru
        </h1>
        <p class="text-gray-600 text-sm">Kelola kelas, materi, dan penilaian Anda</p>
    </div>

    <!-- STATISTICS CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-8">
        <!-- Mata Pelajaran -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-amber-500">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm font-medium mb-2">Mata Pelajaran</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $totalClassSubjects }}</p>
                </div>
                <div class="bg-amber-100 p-3 rounded-lg flex-shrink-0">
                    <i class="fas fa-book text-amber-500 text-lg sm:text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Kelas -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm font-medium mb-2">Kelas</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $totalClasses }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg flex-shrink-0">
                    <i class="fas fa-chalkboard text-blue-500 text-lg sm:text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Siswa Total -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm font-medium mb-2">Siswa Total</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $totalStudents }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg flex-shrink-0">
                    <i class="fas fa-users text-green-500 text-lg sm:text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Materi Diunggah -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-red-500">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm font-medium mb-2">Materi Diunggah</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $totalMaterials }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-lg flex-shrink-0">
                    <i class="fas fa-file-alt text-red-500 text-lg sm:text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
            <i class="fas fa-lightning-bolt text-amber-500"></i>
            Aksi Cepat
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
            <!-- Buat Materi -->
            <a href="{{ route('guru.materials.create') }}" class="bg-white rounded-lg shadow-sm hover:shadow-md transition p-4 sm:p-6 text-center group border border-gray-100">
                <div class="text-4xl text-amber-500 mb-4 group-hover:scale-110 transition">
                    <i class="fas fa-plus-square"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2 text-sm sm:text-base">Buat Materi</h3>
                <p class="text-xs sm:text-sm text-gray-600">Tambahkan materi pembelajaran baru</p>
            </a>

            <!-- Buat Tugas -->
            <a href="{{ route('guru.assignments.create') }}" class="bg-white rounded-lg shadow-sm hover:shadow-md transition p-4 sm:p-6 text-center group border border-gray-100">
                <div class="text-4xl text-blue-500 mb-4 group-hover:scale-110 transition">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2 text-sm sm:text-base">Buat Tugas</h3>
                <p class="text-xs sm:text-sm text-gray-600">Buat tugas atau kuis untuk siswa</p>
            </a>

            <!-- Kelola Presensi -->
            <a href="{{ route('guru.attendance.index') }}" class="bg-white rounded-lg shadow-sm hover:shadow-md transition p-4 sm:p-6 text-center group border border-gray-100">
                <div class="text-4xl text-green-500 mb-4 group-hover:scale-110 transition">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2 text-sm sm:text-base">Kelola Presensi</h3>
                <p class="text-xs sm:text-sm text-gray-600">Catat kehadiran siswa di kelas</p>
            </a>
        </div>
    </div>

    <!-- MY CLASSES SECTION -->
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
            <i class="fas fa-chalkboard text-amber-500"></i>
            Mata Pelajaran Saya ({{ $totalClassSubjects }})
        </h2>

        @if($classSubjects->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <i class="fas fa-inbox text-gray-300 text-5xl mb-4 block"></i>
                <p class="text-gray-600 text-base">Belum ada mata pelajaran yang ditugaskan</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($classSubjects as $cs)
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition border border-gray-100 overflow-hidden">
                        <div class="p-6">
                            <div class="mb-4">
                                <h3 class="text-lg font-bold text-gray-900 mb-1">
                                    {{ $cs->eClass->name }}
                                </h3>
                                <p class="text-sm text-gray-600">{{ $cs->subject->name }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-6 py-4 border-y border-gray-100">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ $cs->eClass->students->count() }}</div>
                                    <p class="text-xs text-gray-600 font-medium mt-1">Siswa</p>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ $cs->eClass->materials->count() }}</div>
                                    <p class="text-xs text-gray-600 font-medium mt-1">Materi</p>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('guru.materials.index', ['class' => $cs->eClass->id]) }}" class="flex-1 bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg text-sm transition text-center">
                                    <i class="fas fa-book mr-2"></i> Materi
                                </a>
                                <a href="{{ route('guru.assignments.index', ['class' => $cs->eClass->id]) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg text-sm transition text-center">
                                    <i class="fas fa-tasks mr-2"></i> Tugas
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- RECENT ACTIVITY SECTION -->
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
            <i class="fas fa-history text-amber-500"></i>
            Aktivitas Terbaru
        </h2>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-6 py-4 text-left font-semibold text-gray-900">Aksi</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-900">Deskripsi</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-900">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse(\App\Models\ActivityLog::where('user_id', auth()->id())->orderBy('timestamp', 'desc')->take(10)->get() as $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <span class="inline-block bg-amber-100 text-amber-800 text-xs font-semibold px-3 py-1 rounded-full">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ Str::limit($log->description, 60) }}</td>
                                <td class="px-6 py-4 text-gray-600 text-xs">
                                    {{ \Carbon\Carbon::parse($log->timestamp)->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-gray-600">
                                    <i class="fas fa-inbox text-gray-300 text-4xl mb-4 block"></i>
                                    <p class="text-base">Belum ada aktivitas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
