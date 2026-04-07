@extends('layouts.admin')

@section('title', 'Edit Kelas')
@section('icon', 'fas fa-edit')

@section('content')
    <!-- Breadcrumb -->
    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; font-size: 14px;">
        <a href="{{ route('admin.classes.index') }}" style="color: var(--primary); text-decoration: none;">
            <i class="fas fa-chalkboard"></i> Kelola Kelas
        </a>
        <span style="color: #999;">/</span>
        <span style="color: var(--secondary); font-weight: 600;">Edit Kelas</span>
    </div>

    <!-- Header -->
    <div style="margin-bottom: 30px;">
        <p style="color: #999; font-size: 14px; margin-bottom: 5px;">Manajemen Pembelajaran</p>
        <h1 class="page-title">
            <i class="fas fa-edit"></i>
            Edit Kelas - {{ $class->name }}
        </h1>
        <p class="page-description">Perbarui informasi kelas, jadwal, dan siswa</p>
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
                Form Edit Kelas
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.classes.update', $class) }}">
                @csrf
                @method('PUT')

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
                        value="{{ old('name', $class->name) }}" 
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
                    >{{ old('description', $class->description) }}</textarea>
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
                        <option value="monday" {{ old('day_of_week', $class->day_of_week) == 'monday' ? 'selected' : '' }}>Senin</option>
                        <option value="tuesday" {{ old('day_of_week', $class->day_of_week) == 'tuesday' ? 'selected' : '' }}>Selasa</option>
                        <option value="wednesday" {{ old('day_of_week', $class->day_of_week) == 'wednesday' ? 'selected' : '' }}>Rabu</option>
                        <option value="thursday" {{ old('day_of_week', $class->day_of_week) == 'thursday' ? 'selected' : '' }}>Kamis</option>
                        <option value="friday" {{ old('day_of_week', $class->day_of_week) == 'friday' ? 'selected' : '' }}>Jumat</option>
                        <option value="saturday" {{ old('day_of_week', $class->day_of_week) == 'saturday' ? 'selected' : '' }}>Sabtu</option>
                        <option value="sunday" {{ old('day_of_week', $class->day_of_week) == 'sunday' ? 'selected' : '' }}>Minggu</option>
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
                            value="{{ old('start_time', $class->start_time) }}"
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
                            value="{{ old('end_time', $class->end_time) }}"
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
                        value="{{ old('room', $class->room) }}"
                        placeholder="Misal: Ruang 101, Lab Komputer, dll"
                        style="width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 14px; transition: all 0.3s ease;"
                    >
                </div>

                <hr style="margin: 30px 0; border: none; border-top: 1px solid var(--border);">
                <h3 style="margin-bottom: 20px; color: var(--secondary);">👥 DAFTAR SISWA</h3>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--secondary);">
                        Pilih Siswa (Opsional)
                    </label>
                    <div style="border: 2px solid var(--border); border-radius: 8px; padding: 12px; max-height: 300px; overflow-y: auto; background: #f8f9fa;">
                        @if($students->isEmpty())
                            <p style="color: #999; text-align: center; padding: 20px;">
                                ⚠️ Tidak ada siswa.
                            </p>
                        @else
                            @foreach($students as $student)
                                <label style="display: flex; align-items: center; gap: 10px; padding: 10px; cursor: pointer; border-radius: 6px; transition: all 0.2s ease;" onmouseover="this.style.background='#f0f0f0'" onmouseout="this.style.background='transparent'">
                                    <input 
                                        type="checkbox" 
                                        name="students[]" 
                                        value="{{ $student->id }}"
                                        {{ in_array($student->id, old('students', $enrolledStudents)) ? 'checked' : '' }}
                                        style="width: 18px; height: 18px; cursor: pointer;"
                                    >
                                    <span style="color: var(--secondary); flex: 1;">{{ $student->name }}</span>
                                    <span style="color: #999; font-size: 12px;">{{ $student->email }}</span>
                                </label>
                            @endforeach
                        @endif
                    </div>
                    <small style="color: #999; margin-top: 8px; display: block;">
                        ℹ️ Total siswa yang dipilih: <span id="studentCount" style="font-weight: 600;">{{ count($enrolledStudents) }}</span>
                    </small>
                </div> -->

                <div style="display: flex; gap: 10px;">
                    <button 
                        type="submit" 
                        class="btn btn-primary"
                        style="flex: 1; justify-content: center;"
                    >
                        <i class="fas fa-save"></i> Perbarui Kelas
                    </button>
                    <a 
                        href="{{ route('admin.classes.index') }}" 
                        class="btn btn-secondary"
                        style="flex: 1; justify-content: center; text-decoration: none;"
                    >
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const checkboxes = document.querySelectorAll('input[name="students[]"]');
                        const studentCountSpan = document.getElementById('studentCount');

                        function updateCount() {
                            const checked = document.querySelectorAll('input[name="students[]"]:checked').length;
                            studentCountSpan.textContent = checked;
                        }

                        checkboxes.forEach(checkbox => {
                            checkbox.addEventListener('change', updateCount);
                        });
                    });
                </script>
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
