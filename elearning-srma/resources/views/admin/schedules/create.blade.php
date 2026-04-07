@extends('layouts.admin')

@section('title', 'Tambah Jadwal Kelas')
@section('icon', 'fas fa-calendar-plus')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 mb-6 text-gray-600">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <span>/</span>
        <a href="{{ route('admin.classes.index') }}" class="hover:text-blue-600">Kelas</a>
        <span>/</span>
        <a href="{{ route('admin.classes.show', $class) }}" class="hover:text-blue-600">{{ $class->name }}</a>
        <span>/</span>
        <span class="text-blue-600 font-semibold">Tambah Jadwal</span>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Tambah Jadwal untuk {{ $class->name }}</h1>
        <p class="text-gray-600 mt-2">Tentukan mata pelajaran dan waktu pelaksanaannya</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-800">
            <h2 class="text-white font-bold text-lg">Form Tambah Jadwal</h2>
        </div>

        <form action="{{ route('admin.schedules.store', $class) }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Mata Pelajaran -->
            <div>
                <label for="class_subject_id" class="block text-gray-700 font-semibold mb-2">
                    Mata Pelajaran <span class="text-red-500">*</span>
                </label>
                <select name="class_subject_id" id="class_subject_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('class_subject_id') border-red-500 @enderror">
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach($classSubjects as $cs)
                        <option value="{{ $cs->id }}">
                            {{ $cs->subject->name }} (Guru: {{ $cs->teacher->name }})
                        </option>
                    @endforeach
                </select>
                @error('class_subject_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Hari -->
            <div>
                <label for="day_of_week" class="block text-gray-700 font-semibold mb-2">
                    Hari <span class="text-red-500">*</span>
                </label>
                <select name="day_of_week" id="day_of_week" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('day_of_week') border-red-500 @enderror">
                    <option value="">-- Pilih Hari --</option>
                    <option value="monday">Senin (Monday)</option>
                    <option value="tuesday">Selasa (Tuesday)</option>
                    <option value="wednesday">Rabu (Wednesday)</option>
                    <option value="thursday">Kamis (Thursday)</option>
                    <option value="friday">Jumat (Friday)</option>
                    <option value="saturday">Sabtu (Saturday)</option>
                    <option value="sunday">Minggu (Sunday)</option>
                </select>
                @error('day_of_week')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Waktu Mulai dan Selesai -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="start_time" class="block text-gray-700 font-semibold mb-2">
                        Waktu Mulai (HH:MM) <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="start_time" id="start_time" required 
                           value="{{ old('start_time') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('start_time') border-red-500 @enderror">
                    @error('start_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_time" class="block text-gray-700 font-semibold mb-2">
                        Waktu Selesai (HH:MM) <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="end_time" id="end_time" required 
                           value="{{ old('end_time') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('end_time') border-red-500 @enderror">
                    @error('end_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Ruangan -->
            <div>
                <label for="room" class="block text-gray-700 font-semibold mb-2">
                    Ruangan (Optional)
                </label>
                <input type="text" name="room" id="room" 
                       value="{{ old('room') }}"
                       placeholder="Contoh: Ruang 101, Lab Komputer"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('room') border-red-500 @enderror">
                @error('room')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catatan -->
            <div>
                <label for="notes" class="block text-gray-700 font-semibold mb-2">
                    Catatan (Optional)
                </label>
                <textarea name="notes" id="notes" rows="3" 
                          placeholder="Catatan tambahan tentang jadwal ini..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                <a href="{{ route('admin.classes.show', $class) }}" class="px-6 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 font-semibold">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
