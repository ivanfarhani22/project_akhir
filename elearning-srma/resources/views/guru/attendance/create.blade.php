@extends('layouts.guru')
@section('title', 'Buka Presensi')
@section('icon', 'fas fa-clipboard-list')

@section('content')

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-clipboard-list mr-1"></i> Guru / Presensi / Buka</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-clipboard-list text-[#A41E35] mr-2"></i>Buka Presensi</h1>
    <p class="text-sm text-gray-500 mt-1">Pilih mata pelajaran dan atur waktu presensi</p>
</div>

@if($errors->any())
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6">
        <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
        <ul class="text-sm space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-bold text-gray-900">Form Buka Presensi</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('guru.attendance.store') }}" method="POST">
                @csrf

                <div class="mb-5">
                    <label for="class_subject_id" class="block text-sm font-semibold text-gray-700 mb-1.5">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select id="class_subject_id" name="class_subject_id" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition bg-white">
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($classSubjects as $subject)
                            <option value="{{ $subject->id }}" @selected(old('class_subject_id') == $subject->id)>
                                {{ $subject->eClass->name }} - {{ $subject->subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label for="attendance_date" class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Presensi <span class="text-red-500">*</span></label>
                        <input type="date" id="attendance_date" name="attendance_date" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition"
                            value="{{ old('attendance_date', today()->format('Y-m-d')) }}">
                    </div>
                    <div>
                        <label for="opened_at" class="block text-sm font-semibold text-gray-700 mb-1.5">Jam Buka <span class="text-red-500">*</span></label>
                        <input type="time" id="opened_at" name="opened_at" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition"
                            value="{{ old('opened_at', now()->format('H:i')) }}">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Contoh: Presensi untuk topik Geometri Ruang..."
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition resize-none">{{ old('notes') }}</textarea>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ url()->previous() ?? route('guru.attendance.index') }}"
                       class="flex-1 inline-flex justify-center items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-xl text-sm transition">
                        <i class="fas fa-arrow-left text-xs"></i> Kembali
                    </a>
                    <button type="submit"
                        class="flex-1 inline-flex justify-center items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold py-2.5 px-6 rounded-xl text-sm transition shadow-md hover:shadow-lg">
                        <i class="fas fa-save text-xs"></i> Simpan Presensi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection