@extends('layouts.admin')

@section('title', 'Persebaran Siswa')
@section('page-title', 'Persebaran Siswa')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h2 class="text-xl font-semibold text-gray-800">Kelola Persebaran Siswa</h2>
        <a href="{{ route('admin.student-distribution.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Data
        </a>
    </div>
    
    <!-- Filter Tahun Ajaran -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" action="{{ route('admin.student-distribution.index') }}" class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Tahun Ajaran:</label>
            </div>
            <div class="relative w-full sm:w-auto">
                <select name="academic_year" onchange="this.form.submit()" 
                        class="w-full sm:w-56 appearance-none pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 font-medium focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all cursor-pointer hover:border-gray-300">
                    <option value="all" {{ $selectedYear === 'all' ? 'selected' : '' }}>📅 Semua Tahun</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year }}" {{ $selectedYear === $year ? 'selected' : '' }}>
                            {{ $year === $currentAcademicYear ? '🟢 ' : '📁 ' }}{{ $year }}{{ $year === $currentAcademicYear ? ' (Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            @if($selectedYear !== 'all' && $selectedYear !== $currentAcademicYear)
            @endif
        </form>
    </div>
    
    <!-- Statistik -->
    @if($stats)
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-blue-50 rounded-xl p-4">
            <p class="text-sm text-blue-600 font-medium">Total Wilayah{{ $selectedYear === 'all' ? ' (Keseluruhan)' : '' }}</p>
            <p class="text-2xl font-bold text-blue-700">{{ number_format($stats['total_districts']) }}</p>
        </div>
        <div class="bg-green-50 rounded-xl p-4">
            <p class="text-sm text-green-600 font-medium">Total Siswa{{ $selectedYear === 'all' ? ' (Keseluruhan)' : '' }}</p>
            <p class="text-2xl font-bold text-green-700">{{ number_format($stats['total_students']) }}</p>
        </div>
    </div>
    @endif
    
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        {{ session('success') }}
    </div>
    @endif
    
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Tahun</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Wilayah</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Jumlah</th>
                        <th class="px-4 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                @if($selectedYear === 'all')
                    {{-- Mode Semua Tahun: Grouped by District with Expandable Rows --}}
                    @forelse($distributions as $index => $distribution)
                    <tbody x-data="{ expanded: false }" class="bg-white divide-y divide-gray-200 border-b border-gray-200">
                        {{-- Parent Row --}}
                        <tr class="hover:bg-gray-50 cursor-pointer" @click="expanded = !expanded">
                            <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="inline-flex items-center gap-1 md:gap-2">
                                    <svg class="w-4 h-4 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-90': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                    <span class="hidden md:inline">Semua Tahun</span>
                                    <span class="md:hidden">Semua</span>
                                    <span class="px-1.5 py-0.5 text-xs bg-gray-100 text-gray-600 rounded-full">{{ $distribution->year_count }}</span>
                                </div>
                            </td>
                            <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="truncate max-w-[100px] md:max-w-none inline-block">{{ $distribution->district }}</span>
                            </td>
                            <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ number_format($distribution->total_students) }}</td>
                            <td class="px-4 md:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <span class="text-gray-400 text-xs hidden md:inline">Klik untuk detail</span>
                                <svg class="w-4 h-4 text-gray-400 md:hidden inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </td>
                        </tr>
                        {{-- Expanded Detail Rows --}}
                        @if(isset($districtDetails[$distribution->district]))
                            @foreach($districtDetails[$distribution->district] as $detail)
                            <tr x-show="expanded" x-cloak class="bg-gray-50/50">
                                <td class="px-4 md:px-6 py-3 whitespace-nowrap text-sm text-gray-700 pl-8 md:pl-12">
                                    <span class="truncate max-w-[80px] md:max-w-none inline-block">{{ $detail->academic_year }}</span>
                                    @if($detail->academic_year === $currentAcademicYear)
                                        <span class="hidden md:inline ml-1 px-2 py-0.5 text-xs bg-green-100 text-green-700 rounded-full">Aktif</span>
                                    @endif
                                </td>
                                <td class="px-4 md:px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $detail->district }}</td>
                                <td class="px-4 md:px-6 py-3 whitespace-nowrap text-sm text-gray-700">{{ number_format($detail->student_count) }}</td>
                                <td class="px-4 md:px-6 py-3 whitespace-nowrap text-right text-sm font-medium" @click.stop>
                                    <div class="flex justify-end space-x-1 md:space-x-2">
                                        <a href="{{ route('admin.student-distribution.edit', $detail->id) }}" class="inline-flex items-center px-2 md:px-3 py-1.5 bg-yellow-500 text-white text-xs md:text-sm rounded-lg hover:bg-yellow-600 transition-colors">
                                            <svg class="w-4 h-4 md:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            <span class="hidden md:inline">Edit</span>
                                        </a>
                                        <form action="{{ route('admin.student-distribution.destroy', $detail->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-2 md:px-3 py-1.5 bg-red-500 text-white text-xs md:text-sm rounded-lg hover:bg-red-600 transition-colors">
                                                <svg class="w-4 h-4 md:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                <span class="hidden md:inline">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                    @empty
                    <tbody class="bg-white">
                        <tr>
                            <td colspan="4" class="px-4 md:px-6 py-4 text-center text-gray-500">
                                Belum ada data persebaran siswa
                            </td>
                        </tr>
                    </tbody>
                    @endforelse
                @else
                    {{-- Mode Tahun Tertentu: Normal View --}}
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($distributions as $distribution)
                        <tr>
                            <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="truncate max-w-[80px] md:max-w-none inline-block">{{ $distribution->academic_year }}</span>
                                @if($distribution->academic_year === $currentAcademicYear)
                                    <span class="hidden md:inline ml-1 px-2 py-0.5 text-xs bg-green-100 text-green-700 rounded-full">Aktif</span>
                                @endif
                            </td>
                            <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $distribution->district }}</td>
                            <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($distribution->student_count) }}</td>
                            <td class="px-4 md:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-1 md:space-x-2">
                                    <a href="{{ route('admin.student-distribution.edit', $distribution->id) }}" class="inline-flex items-center px-2 md:px-3 py-1.5 bg-yellow-500 text-white text-xs md:text-sm rounded-lg hover:bg-yellow-600 transition-colors">
                                        <svg class="w-4 h-4 md:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span class="hidden md:inline">Edit</span>
                                    </a>
                                    <form action="{{ route('admin.student-distribution.destroy', $distribution->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-2 md:px-3 py-1.5 bg-red-500 text-white text-xs md:text-sm rounded-lg hover:bg-red-600 transition-colors">
                                            <svg class="w-4 h-4 md:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span class="hidden md:inline">Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 md:px-6 py-4 text-center text-gray-500">
                                Belum ada data persebaran siswa untuk tahun ajaran {{ $selectedYear }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                @endif
            </table>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $distributions->links() }}
    </div>
</div>
@endsection
