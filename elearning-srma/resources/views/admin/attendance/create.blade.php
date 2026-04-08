@extends('layouts.admin')

@section('title', 'Input Presensi')
@section('icon', 'fas fa-clipboard-list')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 mb-8 text-sm text-gray-600">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600 transition">Dashboard</a>
        <span class="text-gray-400">/</span>
        <a href="{{ route('admin.attendance.index') }}" class="hover:text-red-600 transition">Presensi</a>
        <span class="text-gray-400">/</span>
        <span class="text-red-600 font-semibold">Input Presensi</span>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            <span class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                <i class="fas fa-plus-circle"></i>
            </span>
            Input Presensi Baru
        </h1>
        <p class="text-gray-600 mt-2">Catat presensi siswa untuk kelas dan mata pelajaran tertentu</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
            <h2 class="text-white font-semibold text-lg flex items-center gap-2">
                <i class="fas fa-pen-to-square"></i>
                Form Input Presensi
            </h2>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <form action="{{ route('admin.attendance.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Kelas & Mata Pelajaran Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="e_class_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kelas <span class="text-red-600">*</span>
                        </label>
                        <select name="e_class_id" id="e_class_id" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('e_class_id') border-red-500 @enderror">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" @selected(old('e_class_id') == $class->id)>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('e_class_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="class_subject_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Mata Pelajaran <span class="text-red-600">*</span>
                        </label>
                        <select name="class_subject_id" id="class_subject_id" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('class_subject_id') border-red-500 @enderror">
                            <option value="">-- Pilih Mata Pelajaran --</option>
                        </select>
                        @error('class_subject_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tanggal Presensi -->
                <div>
                    <label for="attendance_date" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal Presensi <span class="text-red-600">*</span>
                    </label>
                    <input type="date" name="attendance_date" id="attendance_date" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition @error('attendance_date') border-red-500 @enderror" 
                        value="{{ old('attendance_date', date('Y-m-d')) }}">
                    @error('attendance_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Presensi Siswa -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Presensi Siswa <span class="text-red-600">*</span>
                    </label>
                    <div id="students-list" class="p-4 bg-blue-50 border-2 border-blue-200 rounded-lg text-blue-700 text-sm">
                        Pilih kelas dan mata pelajaran terlebih dahulu
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.attendance.index') }}" class="inline-flex items-center gap-2 px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-50 transition">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="ml-auto inline-flex items-center gap-2 px-6 py-2 bg-red-500 text-white rounded-lg font-semibold text-sm hover:bg-red-600 transition disabled:opacity-50 disabled:cursor-not-allowed" id="submit-btn" disabled>
                        <i class="fas fa-save"></i> Simpan Presensi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('e_class_id').addEventListener('change', function() {
    const classId = this.value;
    const subjectSelect = document.getElementById('class_subject_id');
    subjectSelect.innerHTML = '<option value="">-- Pilih Mata Pelajaran --</option>';
    
    if (classId) {
        fetch(`/admin/classes/${classId}/subjects`)
            .then(response => response.json())
            .then(data => {
                data.forEach(cs => {
                    const option = document.createElement('option');
                    option.value = cs.id;
                    option.text = cs.subject.name + ' (' + cs.teacher.name + ')';
                    subjectSelect.appendChild(option);
                });
            });
    }
});

document.getElementById('class_subject_id').addEventListener('change', function() {
    const classSubjectId = this.value;
    const studentsList = document.getElementById('students-list');
    const submitBtn = document.getElementById('submit-btn');
    
    if (classSubjectId) {
        fetch(`/admin/class-subjects/${classSubjectId}/students`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    let html = '<div class="overflow-x-auto"><table class="w-full border-collapse"><thead class="bg-gray-100"><tr class="border-b-2 border-gray-300"><th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Siswa</th><th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Status</th></tr></thead><tbody>';
                    data.forEach(student => {
                        html += `<tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">${student.name}</td>
                            <td class="px-4 py-3">
                                <select name="attendance[${student.id}]" class="px-3 py-2 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-500 transition">
                                    <option value="present">Hadir</option>
                                    <option value="absent">Absen</option>
                                    <option value="late">Terlambat</option>
                                    <option value="sick">Sakit</option>
                                </select>
                            </td>
                        </tr>`;
                    });
                    html += '</tbody></table></div>';
                    studentsList.innerHTML = html;
                    submitBtn.disabled = false;
                } else {
                    studentsList.innerHTML = '<div class="p-4 bg-yellow-50 border-2 border-yellow-200 rounded-lg text-yellow-700 text-sm">Tidak ada siswa di kelas ini</div>';
                    submitBtn.disabled = true;
                }
            });
    } else {
        studentsList.innerHTML = '<div class="p-4 bg-blue-50 border-2 border-blue-200 rounded-lg text-blue-700 text-sm">Pilih mata pelajaran terlebih dahulu</div>';
        submitBtn.disabled = true;
    }
});
</script>
@endsection
