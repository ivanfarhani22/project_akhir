@extends('layouts.admin')

@section('title', 'Detail Presensi')
@section('icon', 'fas fa-clipboard-list')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 mb-8 text-sm text-gray-600">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600 transition">Dashboard</a>
        <span class="text-gray-400">/</span>
        <a href="{{ route('admin.attendance.index') }}" class="hover:text-red-600 transition">Presensi</a>
        <span class="text-gray-400">/</span>
        <span class="text-red-600 font-semibold">Detail Presensi</span>
    </nav>

    <!-- Header with Actions -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <span class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                    <i class="fas fa-eye"></i>
                </span>
                Detail Presensi
            </h1>
            <p class="text-gray-600 mt-2">{{ \Carbon\Carbon::parse($session->attendance_date)->format('d M Y') }}</p>
        </div>
        <div class="flex gap-3">
            <form action="{{ route('admin.attendance.destroy', $session) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-semibold">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </form>
            <a href="{{ route('admin.attendance.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-semibold">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Session Info Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
            <h2 class="text-white font-semibold text-lg flex items-center gap-2">
                <i class="fas fa-info-circle"></i>
                Informasi Sesi Presensi
            </h2>
        </div>

        <div class="p-6">
            <div class="space-y-4">
                <div class="flex justify-between items-start pb-4 border-b border-gray-200">
                    <span class="text-gray-700 font-semibold">Kelas:</span>
                    <span class="inline-block px-4 py-2 bg-red-100 text-red-700 rounded-full font-semibold">
                        {{ $session->classSubject->eClass->name }}
                    </span>
                </div>

                <div class="flex justify-between items-start pb-4 border-b border-gray-200">
                    <span class="text-gray-700 font-semibold">Mata Pelajaran:</span>
                    <span class="text-gray-900">{{ $session->classSubject->subject->name }}</span>
                </div>

                <div class="flex justify-between items-start pb-4 border-b border-gray-200">
                    <span class="text-gray-700 font-semibold">Guru:</span>
                    <span class="text-gray-900">{{ $session->classSubject->teacher->name }}</span>
                </div>

                <div class="flex justify-between items-start pb-4 border-b border-gray-200">
                    <span class="text-gray-700 font-semibold">Tanggal Presensi:</span>
                    <span class="text-gray-900">{{ \Carbon\Carbon::parse($session->attendance_date)->format('d M Y') }}</span>
                </div>

                <div class="flex justify-between items-start">
                    <span class="text-gray-700 font-semibold">Dibuat:</span>
                    <span class="text-gray-900">{{ $session->created_at->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Records Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
            <h2 class="text-white font-semibold text-lg flex items-center gap-2">
                <i class="fas fa-list"></i>
                Daftar Presensi Siswa
            </h2>
        </div>

        <div class="overflow-x-auto">
            @if($attendances->count() > 0)
                <table class="w-full border-collapse">
                    <thead class="bg-gray-100">
                        <tr class="border-b-2 border-gray-300">
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-10">#</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama Siswa</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-32">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $attendance->student->name }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusBadgeColor = match($attendance->status) {
                                            'present' => 'bg-green-100 text-green-700',
                                            'absent' => 'bg-red-100 text-red-700',
                                            'late' => 'bg-yellow-100 text-yellow-700',
                                            'sick' => 'bg-blue-100 text-blue-700',
                                            default => 'bg-gray-100 text-gray-700'
                                        };
                                    @endphp
                                    <span class="inline-block px-3 py-1 {{ $statusBadgeColor }} rounded-full text-xs font-semibold">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $attendance->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-2 block opacity-50"></i>
                                    <p class="mt-2">Tidak ada data presensi</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4 block"></i>
                    <p>Tidak ada data presensi</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Summary Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mt-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
            <h2 class="text-white font-semibold text-lg flex items-center gap-2">
                <i class="fas fa-chart-pie"></i>
                Ringkasan Presensi
            </h2>
        </div>

        <div class="p-6">
            @php
                $present = $attendances->where('status', 'present')->count();
                $absent = $attendances->where('status', 'absent')->count();
                $late = $attendances->where('status', 'late')->count();
                $sick = $attendances->where('status', 'sick')->count();
                $total = $attendances->count();
            @endphp

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="text-center">
                    <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-lg mx-auto mb-3">
                        <span class="text-2xl font-bold text-green-600">{{ $present }}</span>
                    </div>
                    <p class="text-gray-600 text-sm">Hadir</p>
                </div>

                <div class="text-center">
                    <div class="flex items-center justify-center w-16 h-16 bg-red-100 rounded-lg mx-auto mb-3">
                        <span class="text-2xl font-bold text-red-600">{{ $absent }}</span>
                    </div>
                    <p class="text-gray-600 text-sm">Absen</p>
                </div>

                <div class="text-center">
                    <div class="flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-lg mx-auto mb-3">
                        <span class="text-2xl font-bold text-yellow-600">{{ $late }}</span>
                    </div>
                    <p class="text-gray-600 text-sm">Terlambat</p>
                </div>

                <div class="text-center">
                    <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-lg mx-auto mb-3">
                        <span class="text-2xl font-bold text-blue-600">{{ $sick }}</span>
                    </div>
                    <p class="text-gray-600 text-sm">Sakit</p>
                </div>
            </div>

            <!-- Progress Bar -->
            @if($total > 0)
                <div class="space-y-3">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-gray-700">Status Distribution</span>
                    </div>
                    
                    <div class="flex h-8 rounded-lg overflow-hidden shadow-md bg-gray-100">
                        @if($present > 0)
                            <div class="bg-green-500 flex items-center justify-center text-white text-xs font-semibold" style="width: {{ ($present/$total)*100 }}%">
                                {{ round(($present/$total)*100) }}%
                            </div>
                        @endif
                        
                        @if($absent > 0)
                            <div class="bg-red-500 flex items-center justify-center text-white text-xs font-semibold" style="width: {{ ($absent/$total)*100 }}%">
                                {{ round(($absent/$total)*100) }}%
                            </div>
                        @endif
                        
                        @if($late > 0)
                            <div class="bg-yellow-500 flex items-center justify-center text-white text-xs font-semibold" style="width: {{ ($late/$total)*100 }}%">
                                {{ round(($late/$total)*100) }}%
                            </div>
                        @endif
                        
                        @if($sick > 0)
                            <div class="bg-blue-500 flex items-center justify-center text-white text-xs font-semibold" style="width: {{ ($sick/$total)*100 }}%">
                                {{ round(($sick/$total)*100) }}%
                            </div>
                        @endif
                    </div>

                    <div class="text-xs text-gray-600 mt-2">
                        Hadir: {{ round(($present/$total)*100, 1) }}% | Absen: {{ round(($absent/$total)*100, 1) }}% | Terlambat: {{ round(($late/$total)*100, 1) }}% | Sakit: {{ round(($sick/$total)*100, 1) }}%
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
