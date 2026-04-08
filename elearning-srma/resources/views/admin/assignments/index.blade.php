@extends('layouts.admin')

@section('title', 'Kelola Tugas')
@section('icon', 'fas fa-tasks')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 mb-8 text-sm text-gray-600">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600 transition">Dashboard</a>
        <span class="text-gray-400">/</span>
        <span class="text-red-600 font-semibold">Tugas</span>
    </nav>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <span class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                    <i class="fas fa-tasks"></i>
                </span>
                Kelola Tugas
            </h1>
            <p class="text-gray-600 mt-2">Kelola semua tugas untuk semua kelas dan mata pelajaran</p>
        </div>
        <a href="{{ route('admin.assignments.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition">
            <i class="fas fa-plus"></i> Tambah Tugas
        </a>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-2 border-green-500 text-green-700 rounded-lg flex items-center justify-between">
            <span class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </span>
            <button onclick="this.parentElement.style.display='none';" class="text-green-700 hover:text-green-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Search & Filter Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form action="{{ route('admin.assignments.index') }}" method="GET" class="flex gap-4 flex-col md:flex-row">
            <input type="text" name="search" placeholder="Cari tugas..." 
                class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition"
                value="{{ request('search') }}">
            <select name="class" class="px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition">
                <option value="">Semua Kelas</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" @selected(request('class') == $class->id)>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 transition">
                <i class="fas fa-search"></i> Cari
            </button>
        </form>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
            <h2 class="text-white font-semibold text-lg flex items-center gap-2">
                <i class="fas fa-list"></i>
                Daftar Tugas
            </h2>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            @if($assignments->count() > 0)
                <table class="w-full border-collapse">
                    <thead class="bg-gray-100">
                        <tr class="border-b-2 border-gray-300">
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-10">#</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Judul Tugas</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-32">Kelas</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-32">Guru</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-40">Deadline</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-40">Submission</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignments as $assignment)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $assignment->title }}</div>
                                    <div class="text-sm text-gray-600">{{ Str::limit($assignment->description, 50) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                        {{ $assignment->classSubject->eClass->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $assignment->classSubject->teacher->name }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if($assignment->deadline)
                                        <span class="@if(now() > $assignment->deadline) text-red-600 font-semibold @else text-gray-700 @endif">
                                            {{ $assignment->deadline->format('d M Y H:i') }}
                                        </span>
                                    @else
                                        <span class="text-gray-500">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <span class="inline-block px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">
                                            {{ $assignment->submissions_count ?? 0 }} kirim
                                        </span>
                                        <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-semibold">
                                            {{ $assignment->pending_count ?? 0 }} pending
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.assignments.show', $assignment) }}" 
                                            class="inline-flex items-center gap-2 px-3 py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition text-sm font-semibold"
                                            title="Lihat">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                        <a href="{{ route('admin.assignments.edit', $assignment) }}" 
                                            class="inline-flex items-center gap-2 px-3 py-2 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 transition text-sm font-semibold"
                                            title="Edit">
                                            <i class="fas fa-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.assignments.destroy', $assignment) }}" 
                                            method="POST" class="inline" 
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 bg-red-100 text-red-700 rounded hover:bg-red-200 transition text-sm font-semibold" 
                                                title="Hapus">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-2 block opacity-50"></i>
                                    <p class="mt-2">Tidak ada tugas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $assignments->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4 block"></i>
                    <p class="text-gray-500 mb-4">Belum ada tugas dibuat</p>
                    <a href="{{ route('admin.assignments.create') }}" class="inline-flex items-center gap-2 px-6 py-2 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition">
                        <i class="fas fa-plus"></i> Buat Tugas Baru
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-gray-500 to-gray-600 px-6 py-4">
            <h2 class="text-white font-semibold text-lg flex items-center gap-2">
                <i class="fas fa-chart-bar"></i>
                Statistik Tugas
            </h2>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 p-6">
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-red-100 rounded-lg mx-auto mb-4">
                    <span class="text-2xl font-bold text-red-600">{{ $statistics['total'] ?? 0 }}</span>
                </div>
                <p class="font-semibold text-gray-900 text-lg">Total Tugas</p>
                <p class="text-gray-600 text-sm mt-1">Semua tugas yang dibuat</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-lg mx-auto mb-4">
                    <span class="text-2xl font-bold text-green-600">{{ $statistics['this_month'] ?? 0 }}</span>
                </div>
                <p class="font-semibold text-gray-900 text-lg">Bulan Ini</p>
                <p class="text-gray-600 text-sm mt-1">Tugas dibuat bulan ini</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-lg mx-auto mb-4">
                    <span class="text-2xl font-bold text-blue-600">{{ $statistics['submission_rate'] ?? 0 }}%</span>
                </div>
                <p class="font-semibold text-gray-900 text-lg">Tingkat Pengumpulan</p>
                <p class="text-gray-600 text-sm mt-1">Rata-rata pengumpulan</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-lg mx-auto mb-4">
                    <span class="text-2xl font-bold text-yellow-600">{{ $statistics['pending_grading'] ?? 0 }}</span>
                </div>
                <p class="font-semibold text-gray-900 text-lg">Menunggu Nilai</p>
                <p class="text-gray-600 text-sm mt-1">Tugas belum dinilai</p>
            </div>
        </div>
    </div>
</div>
@endsection
