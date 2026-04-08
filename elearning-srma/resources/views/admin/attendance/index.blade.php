@extends('layouts.admin')

@section('title', 'Kelola Presensi')
@section('icon', 'fas fa-clipboard-list')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 mb-8 text-sm text-gray-600">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600 transition">Dashboard</a>
        <span class="text-gray-400">/</span>
        <span class="text-red-600 font-semibold">Presensi</span>
    </nav>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <span class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                    <i class="fas fa-clipboard-list"></i>
                </span>
                Kelola Presensi
            </h1>
            <p class="text-gray-600 mt-2">Kelola data presensi siswa untuk semua kelas</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.attendance.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition">
                <i class="fas fa-plus"></i> Input Presensi
            </a>
            <a href="{{ route('admin.attendance.export') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition">
                <i class="fas fa-download"></i> Export CSV
            </a>
        </div>
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

    <!-- Filter Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form action="{{ route('admin.attendance.index') }}" method="GET" class="flex gap-4 flex-col md:flex-row">
            <select name="class" class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition">
                <option value="">Semua Kelas</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" @selected(request('class') == $class->id)>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
            <input type="date" name="date" class="px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition" 
                value="{{ request('date') }}">
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
                Daftar Presensi
            </h2>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            @if($sessions->count() > 0)
                <table class="w-full border-collapse">
                    <thead class="bg-gray-100">
                        <tr class="border-b-2 border-gray-300">
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-10">#</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Kelas</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Mata Pelajaran</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-32">Tanggal</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-24">Total Siswa</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-40">Hadir/Absen/Izin</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                        {{ $session->classSubject->eClass->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $session->classSubject->subject->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($session->attendance_date)->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $session->records->count() }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        @php
                                            $present = $session->records->where('status', 'present')->count();
                                            $absent = $session->records->where('status', 'absent')->count();
                                            $sick = $session->records->where('status', 'sick')->count();
                                        @endphp
                                        <span class="inline-block px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">
                                            {{ $present }}
                                        </span>
                                        <span class="inline-block px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">
                                            {{ $absent }}
                                        </span>
                                        <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-semibold">
                                            {{ $sick }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.attendance.show', $session) }}" 
                                            class="inline-flex items-center gap-2 px-3 py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition text-sm font-semibold"
                                            title="Lihat">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                        <form action="{{ route('admin.attendance.destroy', $session) }}" 
                                            method="POST" class="inline" 
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus presensi ini?')">
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
                                    <p class="mt-2">Tidak ada data presensi</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $sessions->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4 block"></i>
                    <p class="text-gray-500 mb-4">Belum ada data presensi</p>
                    <a href="{{ route('admin.attendance.create') }}" class="inline-flex items-center gap-2 px-6 py-2 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition">
                        <i class="fas fa-plus"></i> Input Presensi
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
                Statistik Presensi
            </h2>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 p-6">
            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-red-100 rounded-lg mx-auto mb-4">
                    <span class="text-2xl font-bold text-red-600">{{ $statistics['total_sessions'] ?? 0 }}</span>
                </div>
                <p class="font-semibold text-gray-900 text-lg">Total Sesi</p>
                <p class="text-gray-600 text-sm mt-1">Sesi presensi yang dicatat</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-lg mx-auto mb-4">
                    <span class="text-2xl font-bold text-green-600">{{ number_format($statistics['average_attendance'] ?? 0, 1) }}%</span>
                </div>
                <p class="font-semibold text-gray-900 text-lg">Rata-rata Hadir</p>
                <p class="text-gray-600 text-sm mt-1">Persentase kehadiran</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-lg mx-auto mb-4">
                    <span class="text-2xl font-bold text-blue-600">{{ $statistics['this_month'] ?? 0 }}</span>
                </div>
                <p class="font-semibold text-gray-900 text-lg">Bulan Ini</p>
                <p class="text-gray-600 text-sm mt-1">Sesi bulan ini</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-lg mx-auto mb-4">
                    <span class="text-2xl font-bold text-yellow-600">{{ $statistics['total_students'] ?? 0 }}</span>
                </div>
                <p class="font-semibold text-gray-900 text-lg">Total Siswa</p>
                <p class="text-gray-600 text-sm mt-1">Siswa tercatat</p>
            </div>
        </div>
    </div>
</div>
@endsection
