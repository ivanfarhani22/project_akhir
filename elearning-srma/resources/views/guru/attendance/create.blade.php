@extends('layouts.guru')

@section('title', 'Buka Presensi')
@section('icon', 'fas fa-clipboard-list')

@section('content')
    <!-- PAGE HEADER -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3 mb-2">
            <i class="fas fa-clipboard-list text-green-500"></i>
            Buka Presensi
        </h1>
        <p class="text-gray-600 text-sm">Pilih mata pelajaran dan atur waktu presensi</p>
    </div>

    <!-- ERROR ALERT -->
    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-900 p-4 rounded mb-6">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="font-bold text-gray-900 text-lg">Form Buka Presensi</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('guru.attendance.store') }}" method="POST">
                    @csrf

                    <!-- SUBJECT FIELD -->
                    <div class="mb-6">
                        <label for="class_subject_id" class="block font-semibold text-gray-900 mb-2">
                            Mata Pelajaran <span class="text-red-500">*</span>
                        </label>
                        <select id="class_subject_id" name="class_subject_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($classSubjects as $subject)
                                <option value="{{ $subject->id }}" @selected(old('class_subject_id') == $subject->id)>
                                    {{ $subject->eClass->name }} - {{ $subject->subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- DATE FIELD -->
                    <div class="mb-6">
                        <label for="attendance_date" class="block font-semibold text-gray-900 mb-2">
                            Tanggal Presensi <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="attendance_date" name="attendance_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition" value="{{ old('attendance_date', today()->format('Y-m-d')) }}" required>
                    </div>

                    <!-- TIME FIELD -->
                    <div class="mb-6">
                        <label for="opened_at" class="block font-semibold text-gray-900 mb-2">
                            Jam Buka Presensi <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="opened_at" name="opened_at" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition" value="{{ old('opened_at', now()->format('H:i')) }}" required>
                    </div>

                    <!-- NOTES FIELD -->
                    <div class="mb-8">
                        <label for="notes" class="block font-semibold text-gray-900 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea id="notes" name="notes" placeholder="Contoh: Presensi untuk topik Geometri Ruang" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition resize-none" rows="4">{{ old('notes') }}</textarea>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="flex flex-col sm:flex-row gap-3 mt-8">
                        <a href="{{ url()->previous() ?? route('guru.attendance.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-900 font-medium py-2 px-6 rounded-lg text-sm transition inline-flex justify-center items-center gap-2">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="flex-1 bg-[#A41E35] hover:bg-[#7D1627] text-white font-medium py-2 px-6 rounded-lg text-sm transition inline-flex justify-center items-center gap-2">
                            <i class="fas fa-save"></i> Simpan Presensi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
