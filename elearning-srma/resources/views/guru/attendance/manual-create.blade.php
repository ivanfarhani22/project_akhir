@extends('layouts.guru')
@section('title', 'Input Presensi Manual')
@section('icon', 'fas fa-clipboard-check')

@section('content')
<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-clipboard-check mr-1"></i> Guru / Presensi / Manual</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-clipboard-check text-[#A41E35] mr-2"></i>Input Presensi Manual</h1>
    <p class="text-sm text-gray-500 mt-1">Guru dapat mengisi presensi manual untuk mapel yang diajar (mirip admin).</p>
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
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-bold text-gray-900">Form Input Presensi Manual</h2>
        </div>

        <div class="p-6">
            <form action="{{ route('guru.attendance.manual.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="class_subject_id" class="block text-sm font-semibold text-gray-700 mb-1.5">Mata Pelajaran <span class="text-red-500">*</span></label>
                        <select id="class_subject_id" name="class_subject_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition bg-white">
                            <option value="">-- Pilih Kelas & Mapel --</option>
                            @foreach($classSubjects as $cs)
                                <option value="{{ $cs->id }}" @selected(old('class_subject_id') == $cs->id)>
                                    {{ $cs->eClass->name }} - {{ $cs->subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="attendance_date" class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Presensi <span class="text-red-500">*</span></label>
                        <input type="date" id="attendance_date" name="attendance_date" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition"
                            value="{{ old('attendance_date', today()->format('Y-m-d')) }}">
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition resize-none">{{ old('notes') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Presensi Siswa <span class="text-red-500">*</span></label>
                    <div id="students-list" class="p-4 bg-blue-50 border border-blue-200 rounded-xl text-blue-800 text-sm">
                        Pilih mapel terlebih dahulu.
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <a href="{{ route('guru.attendance.index') }}" class="flex-1 inline-flex justify-center items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-xl text-sm transition">
                        <i class="fas fa-arrow-left text-xs"></i> Kembali
                    </a>
                    <button type="submit" id="submit-btn" disabled
                        class="flex-1 inline-flex justify-center items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold py-2.5 px-6 rounded-xl text-sm transition shadow-md hover:shadow-lg">
                        <i class="fas fa-save text-xs"></i> Simpan Presensi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const dataById = {
@foreach($classSubjects as $cs)
    {{ $cs->id }}: @json($cs->eClass->students->map(fn($s) => ['id' => $s->id, 'name' => $s->name])->values()),
@endforeach
};

function renderStudents(classSubjectId) {
    const studentsList = document.getElementById('students-list');
    const submitBtn = document.getElementById('submit-btn');

    const students = dataById[classSubjectId] || [];

    if (!classSubjectId) {
        studentsList.innerHTML = 'Pilih mapel terlebih dahulu.';
        submitBtn.disabled = true;
        return;
    }

    if (students.length === 0) {
        studentsList.innerHTML = '<div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl text-yellow-800 text-sm">Tidak ada siswa di kelas ini.</div>';
        submitBtn.disabled = true;
        return;
    }

    let html = '<div class="overflow-x-auto"><table class="w-full border-collapse text-sm"><thead class="bg-gray-50"><tr class="border-b border-gray-200"><th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Siswa</th><th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Status</th></tr></thead><tbody>';

    for (const s of students) {
        html += `<tr class="border-b border-gray-100 hover:bg-gray-50">
            <td class="px-4 py-2.5 text-gray-900">${s.name}</td>
            <td class="px-4 py-2.5">
                <select name="attendance[${s.id}]" class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition">
                    <option value="present">Hadir</option>
                    <option value="absent">Absen</option>
                    <option value="late">Terlambat</option>
                    <option value="sick">Sakit</option>
                    <option value="excused">Izin</option>
                </select>
            </td>
        </tr>`;
    }

    html += '</tbody></table></div>';
    studentsList.innerHTML = html;
    submitBtn.disabled = false;
}

document.getElementById('class_subject_id').addEventListener('change', function() {
    renderStudents(this.value);
});

// initial
renderStudents(document.getElementById('class_subject_id').value);
</script>
@endsection
