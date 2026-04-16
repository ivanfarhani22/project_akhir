@extends('layouts.guru')

@section('title', 'Rekap Nilai')
@section('icon', 'fas fa-table')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8">
        <p class="text-gray-600 text-sm mb-2">Rekap</p>
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            <i class="fas fa-table text-blue-600"></i>
            Rekap Nilai
        </h1>
        <p class="text-gray-600 text-sm mt-2">Rekap nilai per kelas dan mata pelajaran yang Anda ajarkan.</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-8">
        <form method="GET" action="{{ route('guru.rekap-nilai.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kelas + Mata Pelajaran</label>
                <select name="class_subject_id" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                    <option value="">Pilih kelas & mapel</option>
                    @foreach($classSubjects as $cs)
                        <option value="{{ $cs->id }}" @selected((string)$classSubjectId === (string)$cs->id)>
                            {{ $cs->eClass?->name ?? '-' }} — {{ $cs->subject?->name ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                    <i class="fas fa-filter"></i> Tampilkan
                </button>
                <a href="{{ route('guru.rekap-nilai.index') }}" class="inline-flex items-center justify-center px-4 py-2 border-2 border-gray-200 rounded-lg font-semibold hover:bg-gray-50 transition">
                    Reset
                </a>
            </div>
        </form>

        @if($classSubjectId)
            <div class="mt-4">
                <a href="{{ route('guru.rekap-nilai.export', ['class_subject_id' => $classSubjectId]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
                    <i class="fas fa-download"></i> Export CSV
                </a>
            </div>
        @endif
    </div>

    @if(!$table)
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-12 text-center text-gray-600">
            <i class="fas fa-info-circle text-3xl text-gray-300 mb-3"></i>
            <p>Pilih kelas & mapel untuk melihat rekap.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="font-semibold text-gray-900">
                    {{ $table['classSubject']->eClass?->name ?? '-' }} — {{ $table['classSubject']->subject?->name ?? '-' }}
                </div>
                <div class="text-sm text-gray-600">Kolom menampilkan nilai per tugas dan rata-rata.</div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-800">Siswa</th>
                            @foreach($table['assignments'] as $as)
                                <th class="px-6 py-3 text-left font-semibold text-gray-800">{{ $as->title }}</th>
                            @endforeach
                            <th class="px-6 py-3 text-left font-semibold text-gray-800">Rata-rata</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($table['rows'] as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 font-semibold text-gray-900">{{ $row['student']->name }}</td>
                                @foreach($table['assignments'] as $as)
                                    <td class="px-6 py-3 text-gray-700">{{ $row['scores'][$as->id] ?? 0 }}</td>
                                @endforeach
                                <td class="px-6 py-3 font-semibold text-blue-700">{{ $row['average'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
