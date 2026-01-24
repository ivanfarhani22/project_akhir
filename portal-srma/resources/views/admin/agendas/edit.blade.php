@extends('layouts.admin')

@section('title', 'Edit Agenda')
@section('page-title', 'Edit Agenda')

@section('content')
<form action="{{ route('admin.agendas.update', $agenda) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Agenda <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title', $agenda->title) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('title') border-red-500 @enderror">
                    @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" id="description" rows="6"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $agenda->description) }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Waktu & Lokasi</h3>
                
                <div class="mb-4">
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $agenda->start_date->format('Y-m-d')) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                
                <div class="mb-4">
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $agenda->end_date?->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Waktu Mulai</label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $agenda->start_time) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">Waktu Selesai</label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $agenda->end_time) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $agenda->location) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="upcoming" {{ old('status', $agenda->status) === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="ongoing" {{ old('status', $agenda->status) === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ old('status', $agenda->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $agenda->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                
                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        Update
                    </button>
                    <a href="{{ route('admin.agendas.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
