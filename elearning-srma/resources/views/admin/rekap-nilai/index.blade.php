@extends('layouts.admin')

@section('title', 'Rekap Nilai')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="flex items-center gap-2 text-gray-600 mb-2">
                <i class="fas fa-chart-bar text-red-600"></i>
                <span>Admin</span>
                <span class="text-gray-400">/</span>
                <span class="font-semibold text-gray-800">Rekap Nilai</span>
            </div>
            <h1 class="text-4xl font-black text-gray-800 mb-2">Rekap Nilai</h1>
            <p class="text-gray-600">Rekap nilai per kelas dan mata pelajaran (admin bisa semua).</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 sm:p-6 mb-6">
        <form method="GET" action="{{ route('admin.rekap-nilai.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kelas</label>
                <select name="class_id" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg text-sm focus:outline-none focus:border-red-500">
                    <option value="">Pilih kelas</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" @selected((string)$classId === (string)$c->id)>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Mata Pelajaran</label>
                <select name="class_subject_id" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg text-sm focus:outline-none focus:border-red-500" @disabled(!$classId)>
                    <option value="">Pilih mapel</option>
                    @foreach($classSubjects as $cs)
                        <option value="{{ $cs->id }}" @selected((string)$classSubjectId === (string)$cs->id)>
                            {{ $cs->subject?->name ?? '-' }} ({{ $cs->teacher?->name ?? '-' }})
                        </option>
                    @endforeach
                </select>
                @if(!$classId)
                    <p class="text-xs text-gray-500 mt-1">Pilih kelas dulu untuk menampilkan daftar mapel.</p>
                @endif
            </div>

            <div class="flex gap-2">
                <button class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition">
                    <i class="fas fa-filter"></i> Tampilkan
                </button>
                <a href="{{ route('admin.rekap-nilai.index') }}" class="inline-flex items-center justify-center px-4 py-2 border-2 border-gray-200 rounded-lg font-semibold hover:bg-gray-50 transition">
                    Reset
                </a>
            </div>
        </form>

        @if($classId && $classSubjectId)
            <div class="mt-4">
                <a href="{{ route('admin.rekap-nilai.export', ['class_id' => $classId, 'class_subject_id' => $classSubjectId]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
                    <i class="fas fa-download"></i> Export CSV
                </a>
            </div>
        @endif
    </div>

    @if(!$table)
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-12 text-center text-gray-600">
            <i class="fas fa-info-circle text-3xl text-gray-300 mb-3"></i>
            <p>Pilih kelas dan mapel untuk melihat rekap.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="font-semibold text-gray-900">
                    {{ $table['classSubject']->eClass?->name ?? '-' }} — {{ $table['classSubject']->subject?->name ?? '-' }}
                </div>
                <div class="text-sm text-gray-600">Guru: {{ $table['classSubject']->teacher?->name ?? '-' }}</div>
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
                                <td class="px-6 py-3 font-semibold text-red-700">{{ $row['average'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
