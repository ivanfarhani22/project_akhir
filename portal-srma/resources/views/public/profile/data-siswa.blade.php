@extends('layouts.public')

@section('title', 'Data Siswa - SRMA 25 Lamongan')

@section('content')
<!-- Page Header -->
<section class="bg-gray-800 py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm mb-4">
            <ol class="flex flex-wrap items-center gap-2 text-gray-400">
                <li><a href="{{ route('home') }}" class="hover:text-white">Beranda</a></li>
                <li><span>/</span></li>
                <li><span class="text-white">Profil</span></li>
                <li><span>/</span></li>
                <li><span class="text-white">Data Siswa</span></li>
            </ol>
        </nav>
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white">Data Siswa</h1>
    </div>
</section>

<!-- Content -->
<section class="py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-4 gap-6 lg:gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                @include('partials.profile-sidebar')
            </div>
            
            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 md:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Data Siswa</h2>
                        <span class="px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-xs sm:text-sm font-medium w-fit">
                            Tahun Ajaran {{ $currentYear }}
                        </span>
                    </div>
                    
                    @if($studentData->count() > 0)
                        <!-- Summary Cards -->
                        <div class="grid grid-cols-2 gap-3 sm:gap-4 mb-6 sm:mb-8">
                            <div class="bg-blue-50 rounded-xl p-3 sm:p-4 text-center">
                                <div class="text-2xl sm:text-3xl font-bold text-blue-600">{{ $summary['male'] }}</div>
                                <div class="text-xs sm:text-sm text-blue-700">Laki-laki</div>
                            </div>
                            <div class="bg-pink-50 rounded-xl p-3 sm:p-4 text-center">
                                <div class="text-2xl sm:text-3xl font-bold text-pink-600">{{ $summary['female'] }}</div>
                                <div class="text-xs sm:text-sm text-pink-700">Perempuan</div>
                            </div>
                            <div class="bg-green-50 rounded-xl p-3 sm:p-4 text-center">
                                <div class="text-2xl sm:text-3xl font-bold text-green-600">{{ $summary['total'] }}</div>
                                <div class="text-xs sm:text-sm text-green-700">Total Siswa</div>
                            </div>
                            <div class="bg-purple-50 rounded-xl p-3 sm:p-4 text-center">
                                <div class="text-2xl sm:text-3xl font-bold text-purple-600">{{ $summary['groups'] }}</div>
                                <div class="text-xs sm:text-sm text-purple-700">Total Rombel</div>
                            </div>
                        </div>
                        
                        <!-- Detail Table -->
                        <div class="overflow-x-auto -mx-4 sm:mx-0">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden sm:rounded-lg border border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Kelas</th>
                                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm font-semibold text-gray-700">L</th>
                                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm font-semibold text-gray-700">P</th>
                                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm font-semibold text-gray-700">Total</th>
                                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm font-semibold text-gray-700">Rombel</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">
                                            @foreach($studentData as $data)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm font-medium text-gray-800 whitespace-nowrap">Kelas {{ $data->class_name }}</td>
                                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm text-blue-600">{{ $data->male_count }}</td>
                                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm text-pink-600">{{ $data->female_count }}</td>
                                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm font-semibold text-gray-800">{{ $data->total_students }}</td>
                                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm text-gray-600">{{ $data->study_groups }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="bg-gray-100 font-semibold">
                                                <td class="px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-800">Total</td>
                                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm text-blue-600">{{ $summary['male'] }}</td>
                                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm text-pink-600">{{ $summary['female'] }}</td>
                                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm text-gray-800">{{ $summary['total'] }}</td>
                                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm text-gray-600">{{ $summary['groups'] }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Legend for mobile -->
                        <div class="mt-3 sm:hidden text-xs text-gray-500">
                            <span class="text-blue-600">L</span> = Laki-laki, 
                            <span class="text-pink-600">P</span> = Perempuan
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-6 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <p class="text-gray-500">Data siswa untuk tahun ajaran ini belum tersedia.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
