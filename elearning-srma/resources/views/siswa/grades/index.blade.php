@extends('layouts.siswa')
@section('title', 'Nilai Saya')
@section('icon', 'fas fa-star')

@section('content')

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-star mr-1"></i> Siswa / Nilai</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-star text-amber-400 mr-2"></i>Nilai Saya</h1>
    <p class="text-sm text-gray-500 mt-1">Lihat nilai dan feedback dari guru</p>
</div>

@if($grades->count() > 0)
    @php $average = $grades->avg('score'); @endphp

    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm text-center py-5">
            <p class="text-3xl font-extrabold text-blue-600">{{ number_format($average, 1) }}</p>
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mt-1">Rata-rata</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm text-center py-5">
            <p class="text-3xl font-extrabold text-blue-600">{{ $grades->count() }}</p>
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mt-1">Total Penilaian</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-amber-400 to-orange-400"></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tugas</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Feedback</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($grades as $grade)
                        @php
                            $s = $grade->score;
                            $sc = $s >= 80 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($s >= 70 ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : 'bg-red-50 text-red-600 border-red-200');
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3.5 font-semibold text-gray-800">{{ $grade->assignment->eClass->name }}</td>
                            <td class="px-5 py-3.5 text-gray-600">{{ $grade->assignment->title }}</td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $sc }}">{{ $s }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-400">{{ $grade->graded_at?->format('d M Y') ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-xs text-gray-500">{{ $grade->feedback ? Str::limit($grade->feedback, 50) : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-star text-3xl text-gray-300"></i>
            </div>
            <p class="text-gray-500 text-sm">Anda belum memiliki nilai.</p>
        </div>
    </div>
@endif
@endsection