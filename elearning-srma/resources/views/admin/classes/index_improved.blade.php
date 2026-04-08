@extends('layouts.admin')

@section('title', 'Kelola Kelas')
@section('icon', 'fas fa-chalkboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <span class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                    <i class="fas fa-chalkboard"></i>
                </span>
                Kelola Kelas
            </h1>
            <p class="text-gray-600 mt-2">Atur kelas, mata pelajaran, guru, dan siswa dengan mudah</p>
        </div>
        <a href="{{ route('admin.classes.create') }}" class="inline-flex items-center gap-2 bg-red-500 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:bg-red-600 transition">
            <i class="fas fa-plus"></i> Tambah Kelas
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <form method="GET" action="{{ route('admin.classes.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
            <!-- Search Input -->
            <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-900 mb-2">
                    <i class="fas fa-search"></i> Cari Kelas
                </label>
                <input type="text" name="search" placeholder="Cari nama kelas..." 
                       value="{{ request('search') }}"
                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition">
            </div>

            <!-- Search Button -->
            <button type="submit" class="inline-flex items-center gap-2 bg-red-500 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:bg-red-600 transition">
                <i class="fas fa-search"></i> Cari
            </button>

            <!-- Reset Button -->
            <a href="{{ route('admin.classes.index') }}" class="inline-flex items-center gap-2 bg-gray-400 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:bg-gray-500 transition">
                <i class="fas fa-redo"></i> Reset
            </a>
        </form>

        <!-- Info Text -->
        <p class="text-gray-600 text-xs mt-4">
            Menampilkan {{ $classes->count() }} dari {{ $classes->total() }} kelas
        </p>
    </div>

    <!-- No Results Message -->
    @if($classes->isEmpty())
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-16 text-center">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4 inline-block"></i>
            <h3 class="text-gray-600 mb-2 text-lg font-semibold">Tidak ada kelas ditemukan</h3>
            <p class="text-gray-500 mb-6">
                @if(request('search'))
                    Coba ubah pencarian Anda atau <a href="{{ route('admin.classes.index') }}" class="text-red-600 font-semibold hover:underline">reset filter</a>
                @else
                    Mulai dengan <a href="{{ route('admin.classes.create') }}" class="text-red-600 font-semibold hover:underline">membuat kelas baru</a>
                @endif
            </p>
        </div>
    @else
        <!-- Classes Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($classes as $class)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition flex flex-col">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-red-500 to-red-600 p-6 text-white">
                        <h3 class="text-lg font-bold m-0">
                            <i class="fas fa-chalkboard"></i> {{ $class->name }}
                        </h3>
                        <p class="text-red-100 mt-2 mb-0 text-sm">
                            {{ $class->day_of_week }} • {{ date('H:i', strtotime($class->start_time)) }} - {{ date('H:i', strtotime($class->end_time)) }}
                        </p>
                    </div>

                    <!-- Body -->
                    <div class="flex-1 p-6">
                        <!-- Stats -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <div class="text-2xl font-bold text-red-600">
                                    {{ $class->subjects()->count() }}
                                </div>
                                <p class="text-gray-600 text-xs mt-2 m-0">
                                    <i class="fas fa-book"></i> Mata Pelajaran
                                </p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <div class="text-2xl font-bold text-red-600">
                                    {{ $class->students()->count() }}
                                </div>
                                <p class="text-gray-600 text-xs mt-2 m-0">
                                    <i class="fas fa-users"></i> Siswa
                                </p>
                            </div>
                        </div>

                        <!-- Description -->
                        <p class="text-gray-600 text-sm leading-relaxed m-0">
                            {{ Str::limit($class->description, 60) ?? 'Tidak ada deskripsi' }}
                        </p>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-gray-200 flex gap-3">
                        <a href="{{ route('admin.classes.show', $class) }}" class="flex-1 inline-flex items-center justify-center gap-2 bg-red-500 text-white px-4 py-2 rounded-lg font-semibold text-xs hover:bg-red-600 transition">
                            <i class="fas fa-eye"></i> Kelola
                        </a>
                        <a href="{{ route('admin.classes.edit', $class) }}" class="flex-1 inline-flex items-center justify-center gap-2 bg-gray-300 text-gray-900 px-4 py-2 rounded-lg font-semibold text-xs hover:bg-gray-400 transition">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($classes->hasPages())
            <div class="flex justify-center items-center gap-3 mt-8">
                <!-- Previous Button -->
                @if($classes->onFirstPage())
                    <button disabled class="inline-flex items-center gap-2 bg-gray-300 text-gray-500 px-4 py-2 rounded-lg font-semibold text-sm cursor-not-allowed opacity-50">
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </button>
                @else
                    <a href="{{ $classes->previousPageUrl() }}" class="inline-flex items-center gap-2 bg-gray-400 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-500 transition">
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </a>
                @endif

                <!-- Page Info -->
                <div class="px-4 py-2 bg-gray-50 rounded-lg text-sm text-gray-700 font-semibold">
                    Halaman {{ $classes->currentPage() }} dari {{ $classes->lastPage() }}
                </div>

                <!-- Next Button -->
                @if($classes->hasMorePages())
                    <a href="{{ $classes->nextPageUrl() }}" class="inline-flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-red-600 transition">
                        Selanjutnya <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <button disabled class="inline-flex items-center gap-2 bg-gray-300 text-gray-500 px-4 py-2 rounded-lg font-semibold text-sm cursor-not-allowed opacity-50">
                        Selanjutnya <i class="fas fa-chevron-right"></i>
                    </button>
                @endif
            </div>
        @endif
    @endif
</div>

@endsection
