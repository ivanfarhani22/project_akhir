@extends('layouts.admin')

@section('title', 'Kelola Kelas')
@section('icon', 'fas fa-chalkboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 mb-8">
        <div>
            <h1 class="text-xl sm:text-3xl font-bold text-gray-900 flex items-center gap-3">
                <span class="w-8 h-8 sm:w-10 sm:h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600 flex-shrink-0">
                    <i class="fas fa-chalkboard text-sm sm:text-base"></i>
                </span>
                Kelola Kelas
            </h1>
            <p class="text-gray-600 mt-2 text-xs sm:text-sm">Kelola kelas, mata pelajaran, guru, dan siswa</p>
        </div>
        <a href="{{ route('admin.classes.create') }}" class="inline-flex items-center gap-2 bg-red-500 text-white px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition whitespace-nowrap">
            <i class="fas fa-plus"></i> Tambah Kelas
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-6 mb-6">
        <form method="GET" action="{{ route('admin.classes.index') }}" class="flex flex-col sm:flex-row gap-3 sm:gap-4 items-stretch sm:items-end">
            <!-- Search Input -->
            <div class="flex-1">
                <label class="block text-xs sm:text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-search"></i> Cari Kelas
                </label>
                <input type="text" name="search" placeholder="Cari nama kelas..." 
                       value="{{ request('search') }}"
                       class="w-full px-3 sm:px-4 py-2 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition">
            </div>

            <!-- Search Button -->
            <button type="submit" class="inline-flex items-center gap-2 bg-red-500 text-white px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition justify-center whitespace-nowrap">
                <i class="fas fa-search"></i> Cari
            </button>

            <!-- Reset Button -->
            <a href="{{ route('admin.classes.index') }}" class="inline-flex items-center gap-2 bg-gray-200 text-gray-900 px-3 sm:px-6 py-2 rounded-lg font-semibold text-xs sm:text-sm hover:bg-gray-300 transition justify-center whitespace-nowrap">
                <i class="fas fa-redo"></i> Reset
            </a>
        </form>

        <!-- Info Text -->
        <p class="text-gray-500 text-xs sm:text-sm mt-4">
            Menampilkan <strong>{{ $classes->count() }}</strong> dari <strong>{{ $classes->total() }}</strong> kelas
        </p>
    </div>

    @if($classes->isEmpty())
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 sm:p-12 text-center">
            <i class="fas fa-inbox text-5xl sm:text-6xl text-gray-300 mb-4 inline-block"></i>
            <h3 class="text-gray-600 text-base sm:text-lg font-semibold mb-2">Tidak ada kelas ditemukan</h3>
            <p class="text-gray-500 mb-4 text-xs sm:text-sm">
                @if(request('search'))
                    Coba ubah pencarian Anda atau <a href="{{ route('admin.classes.index') }}" class="text-red-600 hover:text-red-700 font-semibold">reset filter</a>
                @else
                    Mulai dengan <a href="{{ route('admin.classes.create') }}" class="text-red-600 hover:text-red-700 font-semibold">membuat kelas baru</a>
                @endif
            </p>
        </div>
    @else
        <!-- Classes Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6 mb-8">
            @foreach($classes as $class)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-red-500 to-red-600 p-4 text-white">
                        <h3 class="text-lg font-bold mb-2">{{ $class->name }}</h3>
                        @if ($class->day_of_week)
                            <div class="text-sm opacity-90">
                                <i class="fas fa-calendar"></i> {{ ucfirst($class->day_of_week) }}
                                @if ($class->start_time && $class->end_time)
                                    • {{ \Carbon\Carbon::createFromFormat('H:i', $class->start_time)->format('H:i') }} - {{ \Carbon\Carbon::createFromFormat('H:i', $class->end_time)->format('H:i') }}
                                @endif
                                @if ($class->room)
                                    • {{ $class->room }}
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="p-4 flex flex-col">
                        <!-- Subjects Section -->
                        <div class="mb-4 pb-4 border-b border-gray-200">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-semibold text-gray-600 uppercase">📖 Mata Pelajaran</span>
                                <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-semibold">{{ $class->classSubjects->count() }}</span>
                            </div>
                            @if ($class->classSubjects->isNotEmpty())
                                <ul class="space-y-1">
                                    @foreach ($class->classSubjects->take(2) as $cs)
                                        <li class="text-sm text-gray-600">• {{ $cs->subject->name }}</li>
                                    @endforeach
                                    @if ($class->classSubjects->count() > 2)
                                        <li class="text-xs text-gray-500 italic">+{{ $class->classSubjects->count() - 2 }} lainnya</li>
                                    @endif
                                </ul>
                            @else
                                <p class="text-sm text-gray-500">⚠️ Belum ada mata pelajaran</p>
                            @endif
                        </div>

                        <!-- Students Section -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-semibold text-gray-600 uppercase">👥 Siswa</span>
                            <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs font-semibold">{{ $class->students->count() }}</span>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2 mt-auto">
                            <a href="{{ route('admin.classes.show', $class) }}" class="flex-1 inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-900 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-200 transition">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <a href="{{ route('admin.classes.edit', $class) }}" class="flex-1 inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-900 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-200 transition">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($classes->hasPages())
            <div class="flex justify-center items-center gap-3">
                <!-- Previous Button -->
                @if($classes->onFirstPage())
                    <button disabled class="inline-flex items-center gap-2 bg-gray-200 text-gray-600 px-4 py-2 rounded-lg font-semibold text-sm opacity-50 cursor-not-allowed">
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </button>
                @else
                    <a href="{{ $classes->previousPageUrl() }}" class="inline-flex items-center gap-2 bg-gray-200 text-gray-900 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-300 transition">
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </a>
                @endif

                <!-- Page Info -->
                <div class="px-4 py-2 bg-gray-100 rounded-lg text-sm text-gray-600 font-medium">
                    Halaman {{ $classes->currentPage() }} dari {{ $classes->lastPage() }}
                </div>

                <!-- Next Button -->
                @if($classes->hasMorePages())
                    <a href="{{ $classes->nextPageUrl() }}" class="inline-flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-red-600 transition">
                        Selanjutnya <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <button disabled class="inline-flex items-center gap-2 bg-gray-200 text-gray-600 px-4 py-2 rounded-lg font-semibold text-sm opacity-50 cursor-not-allowed">
                        Selanjutnya <i class="fas fa-chevron-right"></i>
                    </button>
                @endif
            </div>
        @endif
    @endif
</div>

@endsection

