@extends('layouts.admin')

@section('title', 'Statistik Presensi')
@section('icon', 'fas fa-chart-bar')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 mb-8 text-sm text-gray-600">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600 transition">Dashboard</a>
        <span class="text-gray-400">/</span>
        <a href="{{ route('admin.attendance.index') }}" class="hover:text-red-600 transition">Presensi</a>
        <span class="text-gray-400">/</span>
        <span class="text-red-600 font-semibold">Statistik</span>
    </nav>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <span class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                    <i class="fas fa-chart-bar"></i>
                </span>
                Statistik Presensi
            </h1>
            <p class="text-gray-600 mt-2">Analisis lengkap data presensi siswa</p>
        </div>
        <a href="{{ route('admin.attendance.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-semibold">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="font-semibold text-lg text-gray-900 mb-4">Filter Statistik</h2>
        <form method="GET" action="{{ route('admin.attendance.statistics') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kelas</label>
                <select name="class_id" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition" onchange="this.form.submit()">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Dari Tanggal</label>
                <input type="date" name="from" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition" value="{{ request('from') }}" onchange="this.form.submit()">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sampai Tanggal</label>
                <input type="date" name="to" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition" value="{{ request('to') }}" onchange="this.form.submit()">
            </div>
        </form>
    </div>

    <!-- Main Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Sessions -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 text-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <p class="text-red-100 text-sm font-semibold mb-2">Total Sesi</p>
                <h3 class="text-4xl font-bold">{{ $statistics['total_sessions'] }}</h3>
                <p class="text-red-100 text-xs mt-2">Sesi presensi</p>
            </div>
        </div>

        <!-- Present -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <p class="text-green-100 text-sm font-semibold mb-2">Hadir</p>
                <h3 class="text-4xl font-bold">{{ $statistics['present'] }}</h3>
                <p class="text-green-100 text-xs mt-2">{{ $statistics['attendance_rate'] }}%</p>
            </div>
        </div>

        <!-- Absent -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <p class="text-orange-100 text-sm font-semibold mb-2">Absen</p>
                <h3 class="text-4xl font-bold">{{ $statistics['absent'] }}</h3>
                <p class="text-orange-100 text-xs mt-2">Siswa absen</p>
            </div>
        </div>

        <!-- Late / Sick -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <p class="text-yellow-100 text-sm font-semibold mb-2">Terlambat/Sakit</p>
                <h3 class="text-4xl font-bold">{{ $statistics['late'] + $statistics['sick'] }}</h3>
                <p class="text-yellow-100 text-xs mt-2">Kasus lainnya</p>
            </div>
        </div>
    </div>

    <!-- Statistics by Class Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
            <h2 class="text-white font-semibold text-lg flex items-center gap-2">
                <i class="fas fa-table"></i>
                Statistik per Kelas
            </h2>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="bg-gray-100">
                    <tr class="border-b-2 border-gray-300">
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Kelas</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-32">Jumlah Sesi</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-32">Total Siswa</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-24">Hadir</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-24">Absen</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-48">Rate Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($statistics['by_class'] as $className => $classStats)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $className }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                                    {{ $classStats['count'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                                    {{ $classStats['total_students'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                                    {{ $classStats['present'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                                    {{ $classStats['absent'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $rate = $classStats['total_students'] > 0 ? ($classStats['present'] / $classStats['total_students']) * 100 : 0;
                                @endphp
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-8 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-green-400 to-green-600 flex items-center justify-center text-white text-xs font-semibold" style="width: {{ $rate }}%">
                                            {{ number_format($rate, 1) }}%
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2 block opacity-50"></i>
                                <p class="mt-2">Tidak ada data presensi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
