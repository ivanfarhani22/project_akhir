@extends('layouts.admin')

@section('title', 'Input Presensi')
@section('icon', 'fas fa-clipboard-list')

@section('content')
<div class="max-w-4xl mx-auto px-3 sm:px-4 py-6 sm:py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 mb-8 text-xs sm:text-sm text-gray-600 flex-wrap">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600 transition">Dashboard</a>
        <span class="text-gray-400">/</span>
        <a href="{{ route('admin.attendance.index') }}" class="hover:text-red-600 transition">Presensi</a>
        <span class="text-gray-400">/</span>
        <span class="text-red-600 font-semibold truncate">Input Presensi</span>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center gap-3 flex-wrap">
            <span class="w-9 sm:w-10 h-9 sm:h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600 text-sm sm:text-base flex-shrink-0">
                <i class="fas fa-plus-circle"></i>
            </span>
            <span class="break-words">Input Presensi Baru</span>
        </h1>
        <p class="text-gray-600 mt-2 text-xs sm:text-sm">Catat presensi siswa untuk kelas dan mata pelajaran tertentu</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-3 sm:px-6 py-3 sm:py-4">
            <h2 class="text-white font-semibold text-base sm:text-lg flex items-center gap-2">
                <i class="fas fa-pen-to-square"></i>
                <span class="hidden sm:inline">Form Input Presensi</span><span class="sm:hidden">Form</span>
            </h2>
        </div>

        <!-- Card Body -->
        <div class="p-3 sm:p-6">
            <form action="{{ route('admin.attendance.store') }}" method="POST" class="space-y-4 sm:space-y-6">
                @csrf

                <!-- Kelas & Mata Pelajaran Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-6">
                    <div>
                        <label for="e_class_id" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                            Kelas <span class="text-red-600">*</span>
                        </label>
                        <select name="e_class_id" id="e_class_id" required class="w-full px-3 sm:px-4 py-2 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition @error('e_class_id') border-red-500 @enderror">
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
                        <label for="class_subject_id" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                            Mata Pelajaran <span class="text-red-600">*</span>
                        </label>
                        <select name="class_subject_id" id="class_subject_id" required class="w-full px-3 sm:px-4 py-2 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition @error('class_subject_id') border-red-500 @enderror">
                            <option value="">-- Pilih Mata Pelajaran --</option>
                        </select>
                        @error('class_subject_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tanggal Presensi -->
                <div>
                    <label for="attendance_date" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                        Tanggal Presensi <span class="text-red-600">*</span>
                    </label>
                    <input type="date" name="attendance_date" id="attendance_date" required class="w-full px-3 sm:px-4 py-2 border-2 border-gray-300 rounded-lg text-xs sm:text-sm focus:outline-none focus:border-red-500 transition @error('attendance_date') border-red-500 @enderror" 
                        value="{{ old('attendance_date', date('Y-m-d')) }}">
                    @error('attendance_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Presensi Siswa -->
                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                        Presensi Siswa <span class="text-red-600">*</span>
                    </label>
                    <div id="students-list" class="p-3 sm:p-4 bg-blue-50 border-2 border-blue-200 rounded-lg text-blue-700 text-xs sm:text-sm">
                        Pilih kelas dan mata pelajaran terlebih dahulu
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.attendance.index') }}" class="inline-flex items-center justify-center gap-2 px-3 sm:px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold text-xs sm:text-sm hover:bg-gray-50 transition order-2 sm:order-1">
                        <i class="fas fa-arrow-left"></i> <span class="hidden sm:inline">Batal</span><span class="sm:hidden">Kembali</span>
                    </a>
                    <button type="submit" class="sm:ml-auto inline-flex items-center justify-center gap-2 px-3 sm:px-6 py-2 bg-red-500 text-white rounded-lg font-semibold text-xs sm:text-sm hover:bg-red-600 transition disabled:opacity-50 disabled:cursor-not-allowed order-1 sm:order-2" id="submit-btn" disabled>
                        <i class="fas fa-save"></i> <span class="hidden sm:inline">Simpan Presensi</span><span class="sm:hidden">Simpan</span>
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
                    let html = '<div class="overflow-x-auto"><table class="w-full border-collapse text-xs sm:text-sm"><thead class="bg-gray-100"><tr class="border-b-2 border-gray-300"><th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Siswa</th><th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Status</th></tr></thead><tbody>';
                    data.forEach(student => {
                        html += `<tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-2 sm:px-4 py-2 sm:py-3 text-gray-900">${student.name}</td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3">
                                <select name="attendance[${student.id}]" class="px-2 sm:px-3 py-1 border-2 border-gray-300 rounded text-xs sm:text-sm focus:outline-none focus:border-red-500 transition w-full sm:w-auto">
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
                    studentsList.innerHTML = '<div class="p-3 sm:p-4 bg-yellow-50 border-2 border-yellow-200 rounded-lg text-yellow-700 text-xs sm:text-sm">Tidak ada siswa di kelas ini</div>';
                    submitBtn.disabled = true;
                }
            });
    } else {
        studentsList.innerHTML = '<div class="p-3 sm:p-4 bg-blue-50 border-2 border-blue-200 rounded-lg text-blue-700 text-xs sm:text-sm">Pilih mata pelajaran terlebih dahulu</div>';
        submitBtn.disabled = true;
    }
});
</script>
@endsection
