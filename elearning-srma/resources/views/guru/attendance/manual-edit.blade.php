@extends('layouts.guru')
@section('title', 'Edit Presensi Manual')
@section('icon', 'fas fa-pen-to-square')

@section('content')
<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-clipboard-check mr-1"></i> Guru / Presensi / Manual</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-pen-to-square text-[#A41E35] mr-2"></i>Edit Presensi Manual</h1>
    <p class="text-sm text-gray-500 mt-1">{{ $session->classSubject->eClass->name }} — {{ $session->classSubject->subject->name }} ({{ optional($session->attendance_date)->format('d/m/Y') }})</p>
</div>

@if($errors->any())
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6">
        <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
        <ul class="text-sm space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="font-bold text-gray-900">Update Presensi</h2>
            <a class="text-sm text-[#A41E35] hover:underline" href="{{ route('guru.attendance.show', $session) }}">Lihat Detail</a>
        </div>

        <div class="p-6">
            <form action="{{ route('guru.attendance.manual.update', $session) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition resize-none">{{ old('notes', $session->notes) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Presensi Siswa <span class="text-red-500">*</span></label>

                    @php
                        $byStudent = $session->records->keyBy('student_id');
                        $statusOptions = [
                            'present' => 'Hadir',
                            'absent' => 'Absen',
                            'late' => 'Terlambat',
                            'sick' => 'Sakit',
                            'excused' => 'Izin',
                        ];
                    @endphp

                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-sm">
                            <thead class="bg-gray-50">
                                <tr class="border-b border-gray-200">
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Siswa</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($session->classSubject->eClass->students->sortBy('name') as $student)
                                    @php
                                        $current = old('attendance.' . $student->id, optional($byStudent->get($student->id))->status ?? 'absent');
                                    @endphp
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="px-4 py-2.5 text-gray-900">{{ $student->name }}</td>
                                        <td class="px-4 py-2.5">
                                            <select name="attendance[{{ $student->id }}]" class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition">
                                                @foreach($statusOptions as $val => $label)
                                                    <option value="{{ $val }}" @selected($current === $val)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <a href="{{ route('guru.attendance.index') }}" class="flex-1 inline-flex justify-center items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-xl text-sm transition">
                        <i class="fas fa-arrow-left text-xs"></i> Kembali
                    </a>
                    <button type="submit" class="flex-1 inline-flex justify-center items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold py-2.5 px-6 rounded-xl text-sm transition shadow-md hover:shadow-lg">
                        <i class="fas fa-save text-xs"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
