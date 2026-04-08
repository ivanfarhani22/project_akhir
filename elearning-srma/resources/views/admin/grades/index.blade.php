@extends('layouts.admin')

@section('title', 'Kelola Nilai')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center space-x-2 mb-6">
        <i class="fas fa-star text-red-600"></i>
        <span class="text-gray-600">Admin</span>
        <span class="text-gray-400">/</span>
        <span class="font-semibold text-gray-800">Kelola Nilai</span>
    </div>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-black text-gray-800 mb-2">Kelola Nilai Siswa</h1>
            <p class="text-gray-600">Kelola dan pantau nilai siswa dari berbagai tugas</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2">
            <a href="{{ route('admin.grades.report') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                <i class="fas fa-chart-bar"></i> Laporan
            </a>
            <a href="{{ route('admin.grades.export') }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                <i class="fas fa-download"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
        <form action="{{ route('admin.grades.index') }}" method="GET" class="flex gap-4 items-end flex-wrap">
            <div class="w-48">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-door-open text-red-600 mr-2"></i>Kelas
                </label>
                <select name="class" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" @selected(request('class') == $class->id)>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-xs">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-search text-red-600 mr-2"></i>Nama Siswa
                </label>
                <input type="text" name="student" placeholder="Cari nama siswa..." 
                    value="{{ request('student') }}" 
                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-600 focus:outline-none">
            </div>

            <!-- Search Button -->
            <button type="submit" class="inline-flex items-center gap-2 bg-red-500 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:bg-red-600 transition">
                <i class="fas fa-search"></i> Cari
            </button>

            <!-- Reset Button -->
            <a href="{{ route('admin.grades.index') }}" class="inline-flex items-center gap-2 bg-gray-200 text-gray-900 px-6 py-2 rounded-lg font-semibold text-sm hover:bg-gray-300 transition">
                <i class="fas fa-redo"></i> Reset
            </a>
        </form>

        <!-- Info Text -->
        <p class="text-gray-500 text-sm mt-4">
            Menampilkan <strong>{{ $grades->count() }}</strong> dari <strong>{{ $grades->total() }}</strong> nilai
        </p>
    </div>

    <!-- Main Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        @if($grades->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b-2 border-gray-200">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 w-12">#</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Siswa</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Kelas</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tugas</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nilai</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Persentase</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700 w-20">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grades as $grade)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-600 font-semibold">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $grade->submission->student->name }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                                        {{ $grade->submission->assignment->classSubject->eClass->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ Str::limit($grade->submission->assignment->title, 20) }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="font-bold text-gray-800">{{ $grade->score }}</span> / {{ $grade->submission->assignment->max_score }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $percentage = ($grade->score / $grade->submission->assignment->max_score) * 100;
                                        $badgeClass = match(true) {
                                            $percentage >= 80 => 'bg-green-100 text-green-700',
                                            $percentage >= 70 => 'bg-blue-100 text-blue-700',
                                            $percentage >= 60 => 'bg-yellow-100 text-yellow-700',
                                            default => 'bg-red-100 text-red-700'
                                        };
                                    @endphp
                                    <span class="inline-block px-3 py-1 {{ $badgeClass }} rounded-full text-sm font-semibold">
                                        {{ number_format($percentage, 1) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.grades.edit', $grade) }}" class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 px-3 py-2 rounded-lg hover:bg-yellow-200 transition font-semibold text-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <i class="fas fa-inbox text-gray-400" style="font-size: 2rem;"></i>
                                    <p class="text-gray-500 mt-3">Tidak ada data nilai</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $grades->appends(request()->query())->links('pagination::tailwind') }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-inbox text-gray-400" style="font-size: 3rem;"></i>
                <p class="text-gray-600 mt-4">Belum ada data nilai</p>
            </div>
        @endif
    </div>

    <!-- Statistics Card -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-red-500 to-red-600 text-white rounded-lg shadow-md p-6">
            <div class="text-sm font-semibold text-red-100 mb-1">Nilai Rata-rata</div>
            <div class="text-4xl font-bold">{{ number_format($statistics['average'] ?? 0, 2) }}</div>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-md p-6">
            <div class="text-sm font-semibold text-green-100 mb-1">Nilai Tertinggi</div>
            <div class="text-4xl font-bold">{{ $statistics['highest'] ?? 0 }}</div>
        </div>
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-lg shadow-md p-6">
            <div class="text-sm font-semibold text-yellow-100 mb-1">Nilai Terendah</div>
            <div class="text-4xl font-bold">{{ $statistics['lowest'] ?? 0 }}</div>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow-md p-6">
            <div class="text-sm font-semibold text-blue-100 mb-1">Total Data Nilai</div>
            <div class="text-4xl font-bold">{{ $statistics['total'] ?? 0 }}</div>
        </div>
    </div>
</div>

@endsection
