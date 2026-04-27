@extends('layouts.admin')
@section('title', 'Detail Kelas')
@section('icon', 'fas fa-chalkboard')

@php
    $allStudents       = \App\Models\User::where('role', 'siswa')->get();
    $enrolledIds       = $class->students->pluck('id')->toArray();
    $availableStudents = $allStudents->whereNotIn('id', $enrolledIds)->values();
    $dayTranslate      = ['monday'=>'Senin','tuesday'=>'Selasa','wednesday'=>'Rabu',
                          'thursday'=>'Kamis','friday'=>'Jumat','saturday'=>'Sabtu','sunday'=>'Minggu'];
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-4 py-6 sm:py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 mb-8 text-xs sm:text-sm text-gray-600 flex-wrap">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600 transition">Dashboard</a>
        <span class="text-gray-400">/</span>
        <a href="{{ route('admin.classes.index') }}" class="hover:text-red-600 transition">Kelas</a>
        <span class="text-gray-400">/</span>
        <span class="text-red-600 font-semibold truncate">{{ $class->name }}</span>
    </nav>

    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center gap-3 flex-wrap">
                <span class="w-9 sm:w-10 h-9 sm:h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600 text-sm sm:text-base flex-shrink-0">
                    <i class="fas fa-chalkboard"></i>
                </span>
                <span class="break-words">{{ $class->name }}</span>
            </h1>
            <p class="text-gray-600 mt-2 text-xs sm:text-sm">Kelola mata pelajaran, guru, dan siswa dalam kelas</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <a href="{{ route('admin.classes.edit', $class) }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-gray-500 text-white px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-gray-600 transition whitespace-nowrap">
                <i class="fas fa-edit"></i> <span class="hidden sm:inline">Edit Kelas</span><span class="sm:hidden">Edit</span>
            </a>
            <form method="POST" action="{{ route('admin.classes.destroy', $class) }}" class="flex-1 sm:flex-none delete-form">
                @csrf @method('DELETE')
                <button type="button" onclick="confirmDelete(event, '{{ $class->name }}')" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-red-600 text-white px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-700 transition whitespace-nowrap">
                    <i class="fas fa-trash"></i> <span class="hidden sm:inline">Hapus Kelas</span><span class="sm:hidden">Hapus</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Info Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8">
        <!-- Deskripsi Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-6">
            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-7 sm:w-8 h-7 sm:h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 text-sm sm:text-base flex-shrink-0">
                    <i class="fas fa-info-circle"></i>
                </span>
                Deskripsi
            </h3>
            <p class="text-gray-600 text-xs sm:text-sm">{{ $class->description ?? '—' }}</p>
        </div>

        <!-- Kalender / Info Sekarang Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-6">
            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-7 sm:w-8 h-7 sm:h-8 bg-yellow-100 rounded-lg flex items-center justify-center text-yellow-600 text-sm sm:text-base flex-shrink-0">
                    <i class="fas fa-calendar"></i>
                </span>
                Kalender
            </h3>

            <div class="space-y-2 text-xs sm:text-sm">
                <p>
                    <span class="font-semibold text-gray-700">Hari ini:</span>
                    <span class="text-gray-600">
                        {{ $dayTranslate[strtolower(now()->locale('id')->englishDayOfWeek)] ?? now()->locale('id')->translatedFormat('l') }}
                        , {{ now()->locale('id')->translatedFormat('d F Y') }}
                    </span>
                </p>
                <p>
                    <span class="font-semibold text-gray-700">Waktu sekarang:</span>
                    <span class="text-gray-600" id="liveClock">{{ now()->format('H:i:s') }}</span>
                </p>
                <p>
                    <span class="font-semibold text-gray-700">Ruangan:</span>
                    <span class="text-gray-600">{{ $class->name ?: '—' }}</span>
                </p>
            </div>

            <script>
                (function () {
                    const el = document.getElementById('liveClock');
                    if (!el) return;

                    function pad(n) { return String(n).padStart(2, '0'); }
                    function tick() {
                        const d = new Date();
                        el.textContent = `${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
                    }

                    tick();
                    setInterval(tick, 1000);
                })();
            </script>
        </div>

        <!-- Statistik Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-6">
            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-7 sm:w-8 h-7 sm:h-8 bg-green-100 rounded-lg flex items-center justify-center text-green-600 text-sm sm:text-base flex-shrink-0">
                    <i class="fas fa-chart-bar"></i>
                </span>
                <span class="hidden sm:inline">Statistik</span><span class="sm:hidden">Stats</span>
            </h3>
            <div class="space-y-3 text-xs sm:text-sm">
                <div>
                    <p class="text-gray-600 font-semibold mb-1">📖 Mata Pelajaran</p>
                    <p class="text-xl sm:text-2xl font-bold text-red-600">{{ $class->classSubjects->count() }}</p>
                </div>
                <div>
                    <p class="text-gray-600 font-semibold mb-1">👥 Siswa</p>
                    <p class="text-xl sm:text-2xl font-bold text-red-600">{{ $class->students->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mata Pelajaran Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-3 sm:px-6 py-3 sm:py-4 text-white flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h3 class="text-base sm:text-lg font-bold flex items-center gap-2 m-0 flex-wrap">
                <i class="fas fa-book"></i>
                <span class="hidden sm:inline">Mata Pelajaran</span><span class="sm:hidden">Pelajaran</span>
                <span class="bg-red-700 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm">{{ $class->classSubjects->count() }}</span>
            </h3>
            <a href="{{ route('admin.class-subjects.create', $class) }}" class="inline-flex items-center justify-center gap-2 bg-white text-red-600 px-3 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-50 transition w-full sm:w-auto whitespace-nowrap">
                <i class="fas fa-plus"></i> <span class="hidden sm:inline">Tambah</span><span class="sm:hidden">+</span>
            </a>
        </div>

        <div class="p-3 sm:p-6">
            @if ($class->classSubjects->isEmpty())
                <div class="text-center py-8 sm:py-12">
                    <i class="fas fa-inbox text-5xl sm:text-6xl text-gray-300 mb-4 inline-block"></i>
                    <p class="text-gray-600 font-semibold mb-4 text-sm sm:text-base">Belum ada mata pelajaran di kelas ini</p>
                    <a href="{{ route('admin.class-subjects.create', $class) }}" class="inline-flex items-center justify-center gap-2 bg-red-500 text-white px-4 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition whitespace-nowrap">
                        <i class="fas fa-plus"></i> <span class="hidden sm:inline">Tambah Mata Pelajaran</span><span class="sm:hidden">Tambah</span>
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    @foreach ($class->classSubjects as $cs)
                        <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-3 sm:p-5 hover:shadow-md transition">
                            <div class="pb-3 sm:pb-4 border-b-2 border-gray-200 mb-3 sm:mb-4">
                                <h4 class="text-sm sm:text-base font-bold text-gray-900 m-0 mb-1">📖 {{ $cs->subject->name }}</h4>
                                <p class="text-xs text-gray-600 m-0">Kode: {{ $cs->subject->code }}</p>
                            </div>
                            <div class="mb-3 sm:mb-4">
                                <p class="text-xs text-gray-600 font-semibold mb-1">Guru Pengajar</p>
                                <p class="text-xs sm:text-sm font-semibold text-gray-900 m-0 truncate">👤 {{ $cs->teacher->name }}</p>
                            </div>
                            @if ($cs->description)
                                <p class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4 line-clamp-2">{{ Str::limit($cs->description, 60) }}</p>
                            @endif
                            <div class="flex flex-col sm:flex-row gap-2 pt-3 sm:pt-4 border-t-2 border-gray-200">
                                <a href="{{ route('admin.class-subjects.edit', [$class, $cs]) }}"
                                   class="flex-1 inline-flex items-center justify-center gap-2 bg-blue-500 text-white px-3 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-blue-600 transition whitespace-nowrap">
                                    <i class="fas fa-edit"></i> <span class="hidden sm:inline">Edit</span><span class="sm:hidden">Ed</span>
                                </a>
                                <form method="POST"
                                      action="{{ route('admin.class-subjects.destroy', [$class, $cs]) }}"
                                      class="flex-1 delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete(event, '{{ $cs->name }}')" class="w-full inline-flex items-center justify-center gap-2 bg-red-600 text-white px-3 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-700 transition whitespace-nowrap">
                                        <i class="fas fa-trash"></i> <span class="hidden sm:inline">Hapus</span><span class="sm:hidden">Del</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Jadwal Pelajaran Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="bg-gray-50 px-3 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h3 class="text-base sm:text-lg font-bold text-gray-900 m-0 flex items-center gap-2 flex-wrap">
                <i class="fas fa-calendar-alt"></i> <span class="hidden sm:inline">Jadwal Pelajaran</span><span class="sm:hidden">Jadwal</span>
                <span class="bg-yellow-100 text-yellow-800 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-semibold">{{ $class->schedules->count() }}</span>
            </h3>

            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <a href="{{ route('admin.schedules.bulk.edit', $class) }}" class="inline-flex items-center justify-center gap-2 bg-blue-600 text-white px-3 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-blue-700 transition w-full sm:w-auto whitespace-nowrap">
                    <i class="fas fa-wand-magic-sparkles"></i> <span class="hidden sm:inline">Bulk Scheduling</span><span class="sm:hidden">Bulk</span>
                </a>

                <a href="{{ route('admin.schedules.create', $class) }}" class="inline-flex items-center justify-center gap-2 bg-yellow-500 text-white px-3 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-yellow-600 transition w-full sm:w-auto whitespace-nowrap">
                    <i class="fas fa-plus"></i> <span class="hidden sm:inline">Tambah Jadwal</span><span class="sm:hidden">Tambah</span>
                </a>
            </div>
        </div>

        <div class="p-3 sm:p-6">
            @if ($class->schedules->isEmpty())
                <div class="text-center py-8 sm:py-12">
                    <i class="fas fa-calendar text-5xl sm:text-6xl text-gray-300 mb-4 inline-block"></i>
                    <p class="text-gray-600 font-semibold text-sm sm:text-base">Belum ada jadwal pelajaran</p>
                    <p class="text-gray-500 text-xs sm:text-sm">Jadwal akan ditampilkan ketika ada data</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b-2 border-gray-200">
                                <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold text-gray-900">Hari</th>
                                <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold text-gray-900 hidden sm:table-cell">Waktu</th>
                                <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold text-gray-900">Pelajaran</th>
                                <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold text-gray-900 hidden md:table-cell">Guru</th>
                                <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold text-gray-900 hidden lg:table-cell">Ruangan</th>
                                <th class="px-3 sm:px-6 py-3 sm:py-4 text-center font-semibold text-gray-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($class->schedules as $schedule)
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 font-semibold text-gray-900">{{ $dayTranslate[$schedule->day_of_week] ?? $schedule->day_of_week }}</td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 text-gray-700 hidden sm:table-cell"><strong>{{ $schedule->start_time }} – {{ $schedule->end_time }}</strong></td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 text-gray-700 truncate">{{ $schedule->classSubject->subject->name }}</td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 text-gray-700 hidden md:table-cell truncate">{{ $schedule->classSubject->teacher->name }}</td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 text-gray-700 hidden lg:table-cell">{{ $schedule->room ?? '—' }}</td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 text-center">
                                        <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 justify-center">
                                            <a href="{{ route('admin.schedules.edit', [$class, $schedule]) }}" class="inline-flex items-center justify-center gap-1 bg-blue-500 text-white px-2 sm:px-3 py-1 rounded text-xs font-semibold hover:bg-blue-600 transition whitespace-nowrap">
                                                <i class="fas fa-edit"></i> <span class="hidden sm:inline">Edit</span><span class="sm:hidden">Ed</span>
                                            </a>
                                            <form method="POST" action="{{ route('admin.schedules.destroy', [$class, $schedule]) }}" class="inline delete-form">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="confirmDelete(event, '{{ $schedule->day }}')" class="inline-flex items-center justify-center gap-1 bg-red-600 text-white px-2 sm:px-3 py-1 rounded text-xs font-semibold hover:bg-red-700 transition whitespace-nowrap w-full sm:w-auto">
                                                    <i class="fas fa-trash"></i> <span class="hidden sm:inline">Hapus</span><span class="sm:hidden">Del</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Daftar Siswa Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-3 sm:px-6 py-3 sm:py-4 text-white flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h3 class="text-base sm:text-lg font-bold flex items-center gap-2 m-0 flex-wrap">
                <i class="fas fa-users"></i>
                <span class="hidden sm:inline">Daftar Siswa</span><span class="sm:hidden">Siswa</span>
                <span class="bg-red-700 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm">{{ $class->students->count() }}</span>
            </h3>
            <button type="button" onclick="StudentModal.open()" class="inline-flex items-center justify-center gap-2 bg-white text-red-600 px-3 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-50 transition w-full sm:w-auto whitespace-nowrap">
                <i class="fas fa-user-plus"></i> <span class="hidden sm:inline">Tambah Siswa</span><span class="sm:hidden">Tambah</span>
            </button>
        </div>

        <div class="p-3 sm:p-6">
            @if ($class->students->isEmpty())
                <div class="text-center py-8 sm:py-12">
                    <i class="fas fa-users text-5xl sm:text-6xl text-gray-300 mb-4 inline-block"></i>
                    <p class="text-gray-600 font-semibold mb-4 text-sm sm:text-base">Belum ada siswa di kelas ini</p>
                    <button type="button" onclick="StudentModal.open()" class="inline-flex items-center justify-center gap-2 bg-red-500 text-white px-4 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition whitespace-nowrap">
                        <i class="fas fa-user-plus"></i> <span class="hidden sm:inline">Tambah Siswa</span><span class="sm:hidden">Tambah</span>
                    </button>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b-2 border-gray-200">
                                <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold text-gray-900 w-8">No</th>
                                <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold text-gray-900">Nama Siswa</th>
                                <th class="px-3 sm:px-6 py-3 sm:py-4 text-left font-semibold text-gray-900 hidden sm:table-cell">Email</th>
                                <th class="px-3 sm:px-6 py-3 sm:py-4 text-center font-semibold text-gray-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($class->students as $i => $student)
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 text-gray-600">{{ $i + 1 }}</td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 font-semibold text-gray-900 truncate">{{ $student->name }}</td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 text-gray-600 hidden sm:table-cell truncate text-xs">{{ $student->email }}</td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 text-center">
                                        <form method="POST"
                                              action="{{ route('admin.classes.students.destroy', [$class, $student]) }}"
                                              style="display: inline;" class="delete-form">
                                            @csrf @method('DELETE')
                                            <button type="button" onclick="confirmDelete(event, '{{ $student->name }}')" class="inline-flex items-center justify-center gap-2 bg-red-600 text-white px-2 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-700 transition whitespace-nowrap">
                                                <i class="fas fa-trash"></i> <span class="hidden sm:inline">Hapus</span><span class="sm:hidden">Del</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Tambah Siswa -->
<div id="addStudentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-96 flex flex-col overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gray-50 px-3 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2 m-0 truncate">
                <i class="fas fa-user-plus"></i> <span class="hidden sm:inline">Tambah Siswa ke {{ $class->name }}</span><span class="sm:hidden">Tambah Siswa</span>
            </h2>
            <button type="button" onclick="StudentModal.close()" class="text-gray-500 hover:text-gray-700 text-2xl leading-none w-8 h-8 flex items-center justify-center flex-shrink-0">
                ✕
            </button>
        </div>

        <!-- Modal Body -->
        <form method="POST" action="{{ route('admin.classes.students.store', $class) }}" id="addStudentForm" class="flex-1 overflow-y-auto p-3 sm:p-6">
            @csrf
            @if ($availableStudents->isEmpty())
                <div class="text-center py-8 sm:py-12">
                    <i class="fas fa-check-circle text-3xl sm:text-4xl text-green-500 mb-3 inline-block"></i>
                    <p class="text-gray-600 text-sm sm:text-base">Semua siswa sudah terdaftar di kelas ini.</p>
                </div>
            @else
                <div class="space-y-3 sm:space-y-4">
                    <!-- Search Input -->
                    <input type="text" id="studentSearch" placeholder="🔍 Cari nama atau email..." 
                           class="w-full px-3 sm:px-4 py-2 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition"
                           autocomplete="off">

                    <!-- Student List -->
                    <div class="border-2 border-gray-200 rounded-lg bg-gray-50 max-h-40 sm:max-h-48 overflow-y-auto">
                        <div id="studentList" class="space-y-2 p-2 sm:p-3 text-xs sm:text-sm"></div>
                        <div id="noResults" class="text-center py-6 sm:py-8 text-gray-500 hidden text-xs sm:text-sm">
                            <i class="fas fa-search text-2xl sm:text-3xl mb-2 inline-block"></i><br>
                            Tidak ada siswa yang cocok
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div id="pagination" class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 text-xs sm:text-sm bg-gray-50 p-2 sm:p-3 rounded-lg hidden">
                        <span id="pageInfo" class="text-gray-600 text-center sm:text-left">Halaman 1 dari 1</span>
                        <div class="flex gap-2 justify-center sm:justify-end">
                            <button type="button" id="prevBtn" onclick="StudentModal.prev()" class="px-2 sm:px-3 py-1 border border-gray-300 rounded text-gray-700 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed text-xs sm:text-sm">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button type="button" id="nextBtn" onclick="StudentModal.next()" class="px-2 sm:px-3 py-1 border border-gray-300 rounded text-gray-700 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed text-xs sm:text-sm">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Selected Count -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-2 sm:p-3 rounded text-xs sm:text-sm text-blue-800">
                        <i class="fas fa-info-circle"></i>
                        Dipilih: <strong id="selectedCount">0</strong> dari
                        <strong>{{ $availableStudents->count() }}</strong> siswa
                    </div>
                </div>
            @endif

            <!-- Modal Footer -->
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 pt-4 sm:pt-6 border-t border-gray-200 mt-4 sm:mt-6">
                <button type="button" onclick="StudentModal.close()" class="flex-1 inline-flex items-center justify-center gap-2 bg-gray-300 text-gray-900 px-3 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-gray-400 transition order-2 sm:order-1">
                    <i class="fas fa-times"></i> <span class="hidden sm:inline">Batal</span><span class="sm:hidden">Tutup</span>
                </button>
                @if ($availableStudents->isNotEmpty())
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 bg-red-500 text-white px-3 sm:px-4 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition order-1 sm:order-2">
                        <i class="fas fa-save"></i> <span class="hidden sm:inline">Tambahkan Siswa</span><span class="sm:hidden">Tambah</span>
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>

<script>
const StudentModal = (() => {
    const STUDENTS = @json($availableStudents->map(fn($s) => ['id'=>$s->id,'name'=>$s->name,'email'=>$s->email])->values()->toArray());
    const PER_PAGE = 8;
    
    let filteredStudents = [...STUDENTS];
    let currentPage = 1;
    let selectedIds = new Set();

    const getElement = (id) => document.getElementById(id);

    function renderStudents() {
        const listEl = getElement('studentList');
        if (!listEl) return;

        const totalPages = Math.max(1, Math.ceil(filteredStudents.length / PER_PAGE));
        currentPage = Math.min(currentPage, totalPages);

        if (filteredStudents.length === 0) {
            listEl.innerHTML = '';
            const noResults = getElement('noResults');
            if (noResults) noResults.classList.remove('hidden');
            const pagination = getElement('pagination');
            if (pagination) pagination.classList.add('hidden');
            return;
        }

        const noResults = getElement('noResults');
        if (noResults) noResults.classList.add('hidden');
        
        const pagination = getElement('pagination');
        if (pagination) pagination.classList.toggle('hidden', filteredStudents.length <= PER_PAGE);

        const start = (currentPage - 1) * PER_PAGE;
        const end = start + PER_PAGE;
        const pageStudents = filteredStudents.slice(start, end);

        listEl.innerHTML = pageStudents.map(student => `
            <label class="flex items-center gap-3 p-2 rounded cursor-pointer hover:bg-gray-100 ${selectedIds.has(student.id) ? 'bg-blue-100' : ''}">
                <input type="checkbox" name="student_ids[]" value="${student.id}"
                       ${selectedIds.has(student.id) ? 'checked' : ''}
                       onchange="StudentModal.toggleStudent(${student.id}, this.checked)"
                       class="w-4 h-4 accent-red-600">
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-900 truncate">${student.name}</p>
                    <p class="text-xs text-gray-600 truncate">${student.email}</p>
                </div>
            </label>
        `).join('');

        const pageInfo = getElement('pageInfo');
        if (pageInfo) pageInfo.textContent = `Halaman ${currentPage} dari ${totalPages}`;

        const prevBtn = getElement('prevBtn');
        if (prevBtn) prevBtn.disabled = currentPage <= 1;

        const nextBtn = getElement('nextBtn');
        if (nextBtn) nextBtn.disabled = currentPage >= totalPages;

        updateSelectedCount();
    }

    function updateSelectedCount() {
        const counter = getElement('selectedCount');
        if (counter) counter.textContent = selectedIds.size;
    }

    function openModal() {
        const modal = getElement('addStudentModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        const search = getElement('studentSearch');
        if (search) search.value = '';
        filteredStudents = [...STUDENTS];
        currentPage = 1;
        renderStudents();
        if (search) search.focus();
    }

    function closeModal() {
        const modal = getElement('addStudentModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    }

    function toggleStudent(id, checked) {
        if (checked) {
            selectedIds.add(id);
        } else {
            selectedIds.delete(id);
        }
        renderStudents();
        updateSelectedCount();
    }

    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            renderStudents();
        }
    }

    function nextPage() {
        const totalPages = Math.ceil(filteredStudents.length / PER_PAGE);
        if (currentPage < totalPages) {
            currentPage++;
            renderStudents();
        }
    }

    let searchTimeout;
    function onSearch(query) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const q = query.toLowerCase().trim();
            if (q) {
                filteredStudents = STUDENTS.filter(s =>
                    s.name.toLowerCase().includes(q) ||
                    s.email.toLowerCase().includes(q)
                );
            } else {
                filteredStudents = [...STUDENTS];
            }
            currentPage = 1;
            renderStudents();
        }, 200);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const search = getElement('studentSearch');
        if (search) {
            search.addEventListener('input', (e) => onSearch(e.target.value));
        }

        const modal = getElement('addStudentModal');
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal();
                }
            });
        }

        document.addEventListener('keydown', (e) => {
            const modal = getElement('addStudentModal');
            if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    });

    return {
        open: openModal,
        close: closeModal,
        toggleStudent,
        prev: prevPage,
        next: nextPage
    };
})();

function confirmDelete(event, name) {
    event.preventDefault();
    const form = event.target.closest('form');
    showConfirmation(`Yakin ingin menghapus "${name}"?`, 'Konfirmasi Hapus', function() {
        form.submit();
    });
}

</script>

@endsection