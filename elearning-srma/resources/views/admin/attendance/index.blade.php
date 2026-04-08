@extends('layouts.admin')

@section('title', 'Kelola Presensi')
@section('icon', 'fas fa-clipboard-list')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-4 py-6 sm:py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 mb-8 text-xs sm:text-sm text-gray-600 flex-wrap">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600 transition">Dashboard</a>
        <span class="text-gray-400">/</span>
        <span class="text-red-600 font-semibold truncate">Presensi</span>
    </nav>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-8">
        <div class="flex-1">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center gap-3 flex-wrap">
                <span class="w-9 sm:w-10 h-9 sm:h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600 text-sm sm:text-base flex-shrink-0">
                    <i class="fas fa-clipboard-list"></i>
                </span>
                <span class="break-words">Kelola Presensi</span>
            </h1>
            <p class="text-gray-600 mt-2 text-xs sm:text-sm">Kelola data presensi siswa untuk semua kelas</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <a href="{{ route('admin.attendance.create') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-3 sm:px-6 py-2 sm:py-3 bg-red-500 text-white rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition whitespace-nowrap">
                <i class="fas fa-plus"></i> <span class="hidden sm:inline">Input Presensi</span><span class="sm:hidden">Input</span>
            </a>
            <a href="{{ route('admin.attendance.export') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-3 sm:px-6 py-2 sm:py-3 bg-green-500 text-white rounded-lg font-semibold text-xs sm:text-sm hover:bg-green-600 transition whitespace-nowrap">
                <i class="fas fa-download"></i> <span class="hidden sm:inline">Export CSV</span><span class="sm:hidden">Export</span>
            </a>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="mb-6 p-3 sm:p-4 bg-green-100 border-2 border-green-500 text-green-700 rounded-lg flex items-center justify-between text-xs sm:text-sm">
            <span class="flex items-center gap-2">
                <i class="fas fa-check-circle flex-shrink-0"></i>
                <span class="break-words">{{ session('success') }}</span>
            </span>
            <button onclick="this.parentElement.style.display='none';" class="text-green-700 hover:text-green-900 flex-shrink-0 ml-2">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Filter Card -->
    <div class="bg-white rounded-lg shadow-md p-3 sm:p-6 mb-8">
        <form action="{{ route('admin.attendance.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2 sm:gap-4">
            <select name="class" class="flex-1 px-3 sm:px-4 py-2 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition">
                <option value="">Semua Kelas</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" @selected(request('class') == $class->id)>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
            <input type="date" name="date" class="flex-1 px-3 sm:px-4 py-2 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition" 
                value="{{ request('date') }}">
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-3 sm:px-6 py-2 bg-blue-500 text-white rounded-lg font-semibold text-xs sm:text-sm hover:bg-blue-600 transition whitespace-nowrap w-full sm:w-auto">
                <i class="fas fa-search"></i> <span class="hidden sm:inline">Cari</span>
            </button>
        </form>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-3 sm:px-6 py-3 sm:py-4">
            <h2 class="text-white font-semibold text-base sm:text-lg flex items-center gap-2">
                <i class="fas fa-list"></i>
                <span class="hidden sm:inline">Daftar Presensi</span><span class="sm:hidden">Presensi</span>
            </h2>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            @if($sessions->count() > 0)
                <table class="w-full border-collapse text-xs sm:text-sm">
                    <thead class="bg-gray-100">
                        <tr class="border-b-2 border-gray-300">
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left font-semibold text-gray-700 w-8">No</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left font-semibold text-gray-700">Kelas</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left font-semibold text-gray-700 hidden sm:table-cell">Mata Pelajaran</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left font-semibold text-gray-700 hidden md:table-cell">Tanggal</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left font-semibold text-gray-700 hidden lg:table-cell">Total Siswa</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left font-semibold text-gray-700">Status</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-center font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-2 sm:px-6 py-2 sm:py-4 text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-2 sm:px-6 py-2 sm:py-4">
                                    <span class="inline-block px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold truncate">
                                        {{ $session->classSubject->eClass->name }}
                                    </span>
                                </td>
                                <td class="px-2 sm:px-6 py-2 sm:py-4 text-gray-700 hidden sm:table-cell truncate">{{ $session->classSubject->subject->name }}</td>
                                <td class="px-2 sm:px-6 py-2 sm:py-4 text-gray-700 hidden md:table-cell">{{ \Carbon\Carbon::parse($session->attendance_date)->format('d M Y') }}</td>
                                <td class="px-2 sm:px-6 py-2 sm:py-4 font-semibold text-gray-900 hidden lg:table-cell">{{ $session->records->count() }}</td>
                                <td class="px-2 sm:px-6 py-2 sm:py-4">
                                    <div class="flex gap-1 flex-wrap">
                                        @php
                                            $present = $session->records->where('status', 'present')->count();
                                            $absent = $session->records->where('status', 'absent')->count();
                                            $sick = $session->records->where('status', 'sick')->count();
                                        @endphp
                                        <span class="inline-block px-1 sm:px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">
                                            {{ $present }}
                                        </span>
                                        <span class="inline-block px-1 sm:px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">
                                            {{ $absent }}
                                        </span>
                                        <span class="inline-block px-1 sm:px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-semibold">
                                            {{ $sick }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-2 sm:px-6 py-2 sm:py-4">
                                    <div class="flex gap-1 justify-center">
                                        <a href="{{ route('admin.attendance.show', $session) }}" 
                                            class="inline-flex items-center justify-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition text-xs font-semibold whitespace-nowrap"
                                            title="Lihat">
                                            <i class="fas fa-eye"></i> <span class="hidden sm:inline">Lihat</span>
                                        </a>
                                        <form action="{{ route('admin.attendance.destroy', $session) }}" 
                                            method="POST" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(event, '{{ $session->class->name }}')" class="inline-flex items-center justify-center gap-1 px-2 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition text-xs font-semibold whitespace-nowrap" 
                                                title="Hapus">
                                                <i class="fas fa-trash"></i> <span class="hidden sm:inline">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-3 sm:px-6 py-6 sm:py-8 text-center text-gray-500 text-xs sm:text-sm">
                                    <i class="fas fa-inbox text-4xl sm:text-5xl mb-2 block opacity-50"></i>
                                    <p class="mt-2">Tidak ada data presensi</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-2 sm:px-6 py-3 sm:py-4 border-t border-gray-200 text-xs sm:text-sm">
                    {{ $sessions->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-8 sm:py-12">
                    <i class="fas fa-inbox text-5xl sm:text-6xl text-gray-300 mb-4 block"></i>
                    <p class="text-gray-500 mb-4 text-sm sm:text-base">Belum ada data presensi</p>
                    <a href="{{ route('admin.attendance.create') }}" class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2 bg-red-500 text-white rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition whitespace-nowrap">
                        <i class="fas fa-plus"></i> <span class="hidden sm:inline">Input Presensi</span>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-gray-500 to-gray-600 px-3 sm:px-6 py-3 sm:py-4">
            <h2 class="text-white font-semibold text-base sm:text-lg flex items-center gap-2">
                <i class="fas fa-chart-bar"></i>
                <span class="hidden sm:inline">Statistik Presensi</span><span class="sm:hidden">Statistik</span>
            </h2>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 p-3 sm:p-6">
            <div class="text-center">
                <div class="flex items-center justify-center w-12 sm:w-16 h-12 sm:h-16 bg-red-100 rounded-lg mx-auto mb-2 sm:mb-4">
                    <span class="text-lg sm:text-2xl font-bold text-red-600">{{ $statistics['total_sessions'] ?? 0 }}</span>
                </div>
                <p class="font-semibold text-gray-900 text-xs sm:text-lg">Total Sesi</p>
                <p class="text-gray-600 text-xs mt-1">Sesi presensi</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-12 sm:w-16 h-12 sm:h-16 bg-green-100 rounded-lg mx-auto mb-2 sm:mb-4">
                    <span class="text-lg sm:text-2xl font-bold text-green-600">{{ number_format($statistics['average_attendance'] ?? 0, 1) }}%</span>
                </div>
                <p class="font-semibold text-gray-900 text-xs sm:text-lg">Rata-rata Hadir</p>
                <p class="text-gray-600 text-xs mt-1">Kehadiran</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-12 sm:w-16 h-12 sm:h-16 bg-blue-100 rounded-lg mx-auto mb-2 sm:mb-4">
                    <span class="text-lg sm:text-2xl font-bold text-blue-600">{{ $statistics['this_month'] ?? 0 }}</span>
                </div>
                <p class="font-semibold text-gray-900 text-xs sm:text-lg">Bulan Ini</p>
                <p class="text-gray-600 text-xs mt-1">Sesi bulan ini</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center w-12 sm:w-16 h-12 sm:h-16 bg-yellow-100 rounded-lg mx-auto mb-2 sm:mb-4">
                    <span class="text-lg sm:text-2xl font-bold text-yellow-600">{{ $statistics['total_students'] ?? 0 }}</span>
                </div>
                <p class="font-semibold text-gray-900 text-xs sm:text-lg">Total Siswa</p>
                <p class="text-gray-600 text-xs mt-1">Siswa tercatat</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(event, name) {
    event.preventDefault();
    const form = event.target.closest('form');
    showConfirmation(`Yakin ingin menghapus presensi "${name}"?`, 'Konfirmasi Hapus', function() {
        form.submit();
    });
}
</script>
@endpush
