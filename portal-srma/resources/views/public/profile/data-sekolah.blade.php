@extends('layouts.public')

@section('title', 'Data Sekolah - SRMA 25 Lamongan')

@section('content')
<!-- Page Header -->
<section class="bg-gray-800 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm mb-4">
            <ol class="flex items-center space-x-2 text-gray-400">
                <li><a href="{{ route('home') }}" class="hover:text-white">Beranda</a></li>
                <li><span>/</span></li>
                <li><span class="text-white">Profil</span></li>
                <li><span>/</span></li>
                <li><span class="text-white">Data Sekolah</span></li>
            </ol>
        </nav>
        <h1 class="text-3xl md:text-4xl font-bold text-white">Data Sekolah</h1>
    </div>
</section>

<!-- Content -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                @include('partials.profile-sidebar')
            </div>
            
            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-8">
                <!-- Data Peserta Didik -->
                <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-6 h-6 text-primary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Data Peserta Didik
                    </h2>
                    
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="bg-blue-50 rounded-lg p-6 text-center">
                            <div class="text-4xl font-bold text-blue-600 mb-2">{{ $data['siswa_laki'] }}</div>
                            <div class="text-sm text-blue-700">Siswa Laki-laki</div>
                        </div>
                        <div class="bg-pink-50 rounded-lg p-6 text-center">
                            <div class="text-4xl font-bold text-pink-600 mb-2">{{ $data['siswa_perempuan'] }}</div>
                            <div class="text-sm text-pink-700">Siswa Perempuan</div>
                        </div>
                        <div class="bg-primary-50 rounded-lg p-6 text-center">
                            <div class="text-4xl font-bold text-primary-600 mb-2">{{ $data['siswa_laki'] + $data['siswa_perempuan'] }}</div>
                            <div class="text-sm text-primary-700">Total Siswa</div>
                        </div>
                    </div>
                </div>
                
                <!-- Data Tenaga Pendidik & Kependidikan -->
                <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-6 h-6 text-primary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Data Tenaga Pendidik & Kependidikan
                    </h2>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">No</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Jabatan</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-600">1</td>
                                    <td class="px-4 py-3 text-sm text-gray-800">Kepala Sekolah</td>
                                    <td class="px-4 py-3 text-sm text-gray-800 text-center">{{ $data['kepala_sekolah'] }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-600">2</td>
                                    <td class="px-4 py-3 text-sm text-gray-800">Guru</td>
                                    <td class="px-4 py-3 text-sm text-gray-800 text-center">{{ $data['guru'] }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-600">3</td>
                                    <td class="px-4 py-3 text-sm text-gray-800">Wali Asrama</td>
                                    <td class="px-4 py-3 text-sm text-gray-800 text-center">{{ $data['wali_asrama'] }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-600">4</td>
                                    <td class="px-4 py-3 text-sm text-gray-800">Wali Asuh</td>
                                    <td class="px-4 py-3 text-sm text-gray-800 text-center">{{ $data['wali_asuh'] }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-600">5</td>
                                    <td class="px-4 py-3 text-sm text-gray-800">Keamanan</td>
                                    <td class="px-4 py-3 text-sm text-gray-800 text-center">{{ $data['keamanan'] }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-600">6</td>
                                    <td class="px-4 py-3 text-sm text-gray-800">Kebersihan</td>
                                    <td class="px-4 py-3 text-sm text-gray-800 text-center">{{ $data['kebersihan'] }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-600">7</td>
                                    <td class="px-4 py-3 text-sm text-gray-800">Juru Masak</td>
                                    <td class="px-4 py-3 text-sm text-gray-800 text-center">{{ $data['juru_masak'] }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-600">8</td>
                                    <td class="px-4 py-3 text-sm text-gray-800">Operator</td>
                                    <td class="px-4 py-3 text-sm text-gray-800 text-center">{{ $data['operator'] }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-600">9</td>
                                    <td class="px-4 py-3 text-sm text-gray-800">Bendahara</td>
                                    <td class="px-4 py-3 text-sm text-gray-800 text-center">{{ $data['bendahara'] }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-600">10</td>
                                    <td class="px-4 py-3 text-sm text-gray-800">Tata Usaha (TU)</td>
                                    <td class="px-4 py-3 text-sm text-gray-800 text-center">{{ $data['tu'] }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-50 font-semibold">
                                    <td class="px-4 py-3 text-sm text-gray-700" colspan="2">Total</td>
                                    <td class="px-4 py-3 text-sm text-gray-800 text-center">
                                        {{ $data['kepala_sekolah'] + $data['guru'] + $data['wali_asrama'] + $data['wali_asuh'] + $data['keamanan'] + $data['kebersihan'] + $data['juru_masak'] + $data['operator'] + $data['bendahara'] + $data['tu'] }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
