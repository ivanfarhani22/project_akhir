@extends('layouts.siswa')

@section('title', 'Nilai Saya')
@section('icon', 'fas fa-star')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            <i class="fas fa-star text-amber-500"></i>
            Nilai Saya
        </h1>
        <p class="text-gray-600 text-sm mt-1">Lihat nilai dan feedback dari guru</p>
    </div>

    @if($grades->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gray-50">
                            <th class="px-6 py-4 text-left text-gray-600 font-semibold text-sm">Kelas</th>
                            <th class="px-6 py-4 text-left text-gray-600 font-semibold text-sm">Tugas/Ujian</th>
                            <th class="px-6 py-4 text-left text-gray-600 font-semibold text-sm">Nilai</th>
                            <th class="px-6 py-4 text-left text-gray-600 font-semibold text-sm">Tanggal Penilaian</th>
                            <th class="px-6 py-4 text-left text-gray-600 font-semibold text-sm">Feedback</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($grades as $grade)
                            @php
                                $assignment = $grade->assignment;
                                $score = $grade->score;
                                $scoreColor = $score >= 80 ? 'bg-green-100 text-green-800' : ($score >= 70 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-gray-900 font-semibold">{{ $assignment->eClass->name }}</td>
                                <td class="px-6 py-4 text-gray-900">{{ $assignment->title }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $scoreColor }}">
                                        {{ $score }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600 text-sm">{{ $grade->graded_at ? $grade->graded_at->format('d M Y') : '-' }}</td>
                                <td class="px-6 py-4 text-gray-700 text-sm">{{ $grade->feedback ? substr($grade->feedback, 0, 50) . '...' : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 text-center">
                <p class="text-gray-600 text-sm font-semibold mb-2 uppercase">Nilai Rata-rata</p>
                @php
                    $average = $grades->avg('score');
                @endphp
                <p class="text-4xl font-bold text-blue-600">
                    {{ number_format($average, 1) }}
                </p>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 text-center">
                <p class="text-gray-600 text-sm font-semibold mb-2 uppercase">Total Penilaian</p>
                <p class="text-4xl font-bold text-blue-600">
                    {{ $grades->count() }}
                </p>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-12 text-center">
                <i class="fas fa-inbox text-gray-300 text-6xl mb-4 block"></i>
                <p class="text-gray-600 text-base">Anda belum memiliki nilai</p>
            </div>
        </div>
    @endif
@endsection
