@extends('layouts.admin')

@section('title', 'Buat Kelas Baru')
@section('icon', 'fas fa-plus-circle')

@section('content')
    <div style="margin-bottom: 30px;">
        <p style="color: #999; font-size: 14px; margin-bottom: 5px;">Manajemen Pembelajaran</p>
        <h1 class="page-title">
            <i class="fas fa-plus-circle"></i>
            Buat Kelas Baru
        </h1>
        <p class="page-description">Buat kelas baru dan atur informasi dasar</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Terjadi kesalahan:</strong>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="card" style="max-width: 700px;">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-chalkboard" style="color: var(--primary); margin-right: 10px;"></i>
                Informasi Kelas
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.classes.store') }}">
                @csrf

                <!-- Nama Kelas -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                        Nama Kelas <span style="color: var(--danger);">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        class="form-input"
                        value="{{ old('name') }}" 
                        placeholder="Misal: Kelas X-A, XI IPA-1"
                        style="width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px; transition: all 0.3s ease;"
                        required
                    >
                    <small style="color: #999; margin-top: 5px; display: block;">Format jelas seperti X-A, XI-IPA-1, XII-IPS-2, dll</small>
                </div>

                <!-- Deskripsi -->
                <div class="form-group" style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                        Deskripsi (Opsional)
                    </label>
                    <textarea 
                        name="description" 
                        id="description"
                        style="width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px; transition: all 0.3s ease; font-family: inherit; resize: vertical;"
                        rows="3"
                        placeholder="Deskripsi singkat tentang kelas ini..."
                    >{{ old('description') }}</textarea>
                </div>

                <!-- <hr style="margin: 30px 0; border: none; border-top: 1px solid var(--border);">
                <h3 style="margin-bottom: 20px; color: var(--secondary);">📅 JADWAL KELAS (Opsional)</h3>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                        Hari Pelajaran
                    </label>
                    <select 
                        name="day_of_week" 
                        id="day_of_week"
                        style="width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px; transition: all 0.3s ease;"
                    >
                        <option value="">-- Pilih Hari --</option>
                        <option value="monday" {{ old('day_of_week') == 'monday' ? 'selected' : '' }}>Senin</option>
                        <option value="tuesday" {{ old('day_of_week') == 'tuesday' ? 'selected' : '' }}>Selasa</option>
                        <option value="wednesday" {{ old('day_of_week') == 'wednesday' ? 'selected' : '' }}>Rabu</option>
                        <option value="thursday" {{ old('day_of_week') == 'thursday' ? 'selected' : '' }}>Kamis</option>
                        <option value="friday" {{ old('day_of_week') == 'friday' ? 'selected' : '' }}>Jumat</option>
                        <option value="saturday" {{ old('day_of_week') == 'saturday' ? 'selected' : '' }}>Sabtu</option>
                        <option value="sunday" {{ old('day_of_week') == 'sunday' ? 'selected' : '' }}>Minggu</option>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                            Jam Mulai
                        </label>
                        <input 
                            type="time" 
                            name="start_time" 
                            id="start_time" 
                            value="{{ old('start_time') }}"
                            style="width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px; transition: all 0.3s ease;"
                        >
                    </div>
                    <div class="form-group">
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                            Jam Selesai
                        </label>
                        <input 
                            type="time" 
                            name="end_time" 
                            id="end_time" 
                            value="{{ old('end_time') }}"
                            style="width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px; transition: all 0.3s ease;"
                        >
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                        Ruangan
                    </label>
                    <input 
                        type="text" 
                        name="room" 
                        id="room"
                        value="{{ old('room') }}"
                        placeholder="Misal: Ruang 101, Lab Komputer, dll"
                        style="width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px; transition: all 0.3s ease;"
                    >
                </div> -->

                <div style="display: flex; gap: 10px;">
                    <button 
                        type="submit" 
                        class="btn btn-primary"
                        style="flex: 1; justify-content: center;"
                    >
                        <i class="fas fa-save"></i> Buat Kelas
                    </button>
                    <a 
                        href="{{ route('admin.classes.index') }}" 
                        class="btn btn-secondary"
                        style="flex: 1; justify-content: center; text-decoration: none;"
                    >
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
    </style>
@endsection
