@extends('layouts.admin')

@section('title', 'Tambah Jadwal Kelas')
@section('icon', 'fas fa-calendar-plus')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 mb-8 text-sm text-gray-600">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600 transition">Dashboard</a>
        <span class="text-gray-400">/</span>
        <a href="{{ route('admin.classes.index') }}" class="hover:text-red-600 transition">Kelas</a>
        <span class="text-gray-400">/</span>
        <a href="{{ route('admin.classes.show', $class) }}" class="hover:text-red-600 transition">{{ $class->name }}</a>
        <span class="text-gray-400">/</span>
        <span class="text-red-600 font-semibold">Tambah Jadwal</span>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            <span class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                <i class="fas fa-calendar-plus"></i>
            </span>
            Tambah Jadwal untuk {{ $class->name }}
        </h1>
        <p class="text-gray-600 mt-2">Tentukan mata pelajaran dan waktu pelaksanaannya</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
            <h2 class="text-white font-bold text-lg">Form Tambah Jadwal</h2>
        </div>

        <form action="{{ route('admin.schedules.store', $class) }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Mata Pelajaran -->
            <div>
                <label for="class_subject_id" class="block text-sm font-semibold text-gray-900 mb-2">
                    Mata Pelajaran <span class="text-red-500">*</span>
                </label>
                <select name="class_subject_id" id="class_subject_id" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('class_subject_id') border-red-500 @enderror">
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach($classSubjects as $cs)
                        <option value="{{ $cs->id }}">
                            {{ $cs->subject->name }} (Guru: {{ $cs->teacher->name }})
                        </option>
                    @endforeach
                </select>
                @error('class_subject_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Hari -->
            <div>
                <label for="day_of_week" class="block text-sm font-semibold text-gray-900 mb-2">
                    Hari <span class="text-red-500">*</span>
                </label>
                <select name="day_of_week" id="day_of_week" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('day_of_week') border-red-500 @enderror">
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
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Waktu Mulai dan Selesai -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="start_time" class="block text-sm font-semibold text-gray-900 mb-2">
                        Waktu Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="start_time" id="start_time" required 
                           value="{{ old('start_time') }}"
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('start_time') border-red-500 @enderror">
                    @error('start_time')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_time" class="block text-sm font-semibold text-gray-900 mb-2">
                        Waktu Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="end_time" id="end_time" required 
                           value="{{ old('end_time') }}"
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('end_time') border-red-500 @enderror">
                    @error('end_time')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Ruangan -->
            <div>
                <label for="room" class="block text-sm font-semibold text-gray-900 mb-2">
                    Ruangan <span class="text-gray-500 text-xs">(Opsional)</span>
                </label>
                <input type="text" name="room" id="room" 
                       value="{{ old('room') }}"
                       placeholder="Contoh: Ruang 101, Lab Komputer"
                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('room') border-red-500 @enderror">
                @error('room')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catatan -->
            <div>
                <label for="notes" class="block text-sm font-semibold text-gray-900 mb-2">
                    Catatan <span class="text-gray-500 text-xs">(Opsional)</span>
                </label>
                <textarea name="notes" id="notes" rows="4" 
                          placeholder="Catatan tambahan tentang jadwal ini..."
                          class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex flex-wrap gap-3 pt-6 border-t-2 border-gray-200 mt-6">
                <a href="{{ route('admin.classes.show', $class) }}" class="inline-flex items-center gap-2 bg-white border-2 border-gray-300 text-gray-900 px-6 py-2 rounded-lg font-semibold text-sm hover:bg-gray-50 transition">
                    <i class="fas fa-xmark"></i> Batal
                </a>
                <button type="submit" class="ml-auto inline-flex items-center gap-2 bg-red-500 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:bg-red-600 transition">
                    <i class="fas fa-save"></i> Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
