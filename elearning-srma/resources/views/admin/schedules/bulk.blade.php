@extends('layouts.admin')

@section('title', 'Bulk Scheduling')
@section('icon', 'calendar')

@section('content')
@php
    $classSubjectsJson = $classSubjects->map(function ($cs) {
        return [
            'id' => $cs->id,
            'subject_name' => optional($cs->subject)->name,
            'teacher_id' => $cs->teacher_id,
            'teacher_name' => optional($cs->teacher)->name,
        ];
    })->values();

    $existingSchedulesJson = collect($existingSchedules ?? [])->map(function ($s) {
        return [
            'id' => $s->id,
            'class_subject_id' => $s->class_subject_id,
            'day_of_week' => $s->day_of_week,
            'start_time' => is_string($s->start_time) ? substr($s->start_time, 0, 5) : optional($s->start_time)->format('H:i'),
            'end_time' => is_string($s->end_time) ? substr($s->end_time, 0, 5) : optional($s->end_time)->format('H:i'),
            'room' => $s->room,
            'notes' => $s->notes,
        ];
    })->values();
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex items-start justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Bulk Scheduling</h1>
            <p class="text-sm text-gray-600 mt-1">Kelas: <span class="font-semibold">{{ $class->name }}</span> — pilih beberapa mapel, atur hari & jam, lalu simpan sekaligus.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.classes.show', $class) }}" class="inline-flex items-center gap-2 bg-white border border-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-50">
                Kembali
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
            <div class="font-semibold mb-1">Terjadi error:</div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div x-data="bulkScheduler()" class="grid grid-cols-1 lg:grid-cols-12 gap-4">
        <!-- Left: picker -->
        <div class="lg:col-span-4 bg-white border border-gray-200 rounded-xl p-4">
            <div class="flex items-center justify-between gap-3 mb-3">
                <div class="font-bold text-gray-900">Pilih Mata Pelajaran</div>
                <div class="text-xs text-gray-500" x-text="selectedIds.length + ' dipilih'"></div>
            </div>

            <input type="text" x-model="q" placeholder="Cari mapel..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />

            <div class="mt-3 space-y-2 max-h-[520px] overflow-auto pr-1">
                <template x-for="cs in filteredClassSubjects" :key="cs.id">
                    <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" class="mt-1" :value="cs.id" x-model="selectedIds" @change="syncRows()">
                        <div class="min-w-0">
                            <div class="text-sm font-semibold text-gray-900 truncate" x-text="cs.subject_name"></div>
                            <div class="text-xs text-gray-500 truncate" x-text="cs.teacher_name ? ('Guru default: ' + cs.teacher_name) : 'Guru default: -'"></div>
                        </div>
                    </label>
                </template>
            </div>
        </div>

        <!-- Right: editor -->
        <div class="lg:col-span-8 bg-white border border-gray-200 rounded-xl p-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <div class="font-bold text-gray-900">Pengaturan Jadwal</div>
                    <div class="text-xs text-gray-500 mt-1">Default mode: <span class="font-semibold">Replace per-hari</span> (jadwal kelas pada hari yang terlibat akan diganti).</div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-xs text-gray-600 font-semibold">Mode Simpan:</div>
                    <label class="text-xs flex items-center gap-2">
                        <input type="radio" name="mode" value="replace_day" x-model="mode"> Replace per-hari
                    </label>
                    <label class="text-xs flex items-center gap-2">
                        <input type="radio" name="mode" value="merge" x-model="mode"> Merge
                    </label>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-12 gap-3 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                <div class="md:col-span-3">
                    <label class="text-xs font-semibold text-gray-700">Quick fill hari</label>
                    <select x-model="quick.day" class="mt-1 w-full border border-gray-300 rounded-lg px-2 py-2 text-sm">
                        <template x-for="d in days" :key="d.value">
                            <option :value="d.value" x-text="d.label"></option>
                        </template>
                    </select>
                    <button type="button" @click="applyQuick('day')" class="mt-2 w-full text-xs font-semibold bg-white border border-gray-300 rounded-lg px-2 py-2 hover:bg-gray-100">Apply ke semua</button>
                </div>
                <div class="md:col-span-3">
                    <label class="text-xs font-semibold text-gray-700">Quick fill jam</label>
                    <div class="mt-1 grid grid-cols-2 gap-2">
                        <input type="time" x-model="quick.start" class="border border-gray-300 rounded-lg px-2 py-2 text-sm" />
                        <input type="time" x-model="quick.end" class="border border-gray-300 rounded-lg px-2 py-2 text-sm" />
                    </div>
                    <button type="button" @click="applyQuick('time')" class="mt-2 w-full text-xs font-semibold bg-white border border-gray-300 rounded-lg px-2 py-2 hover:bg-gray-100">Apply ke semua</button>
                </div>
                <div class="md:col-span-4">
                    <label class="text-xs font-semibold text-gray-700">Quick fill guru</label>
                    <select x-model="quick.teacher_id" class="mt-1 w-full border border-gray-300 rounded-lg px-2 py-2 text-sm">
                        <option value="">- pilih guru -</option>
                        <template x-for="t in teachers" :key="t.id">
                            <option :value="t.id" x-text="t.name"></option>
                        </template>
                    </select>
                    <button type="button" @click="applyQuick('teacher')" class="mt-2 w-full text-xs font-semibold bg-white border border-gray-300 rounded-lg px-2 py-2 hover:bg-gray-100">Apply ke semua</button>
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-700">&nbsp;</label>
                    <button type="button" @click="clearAll()" class="mt-1 w-full text-xs font-semibold bg-red-50 border border-red-200 text-red-700 rounded-lg px-2 py-2 hover:bg-red-100">Reset semua</button>
                </div>
            </div>

            <div class="mt-4 overflow-auto border border-gray-200 rounded-lg">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-3 py-2 text-xs font-bold text-gray-700">Mapel</th>
                            <th class="text-left px-3 py-2 text-xs font-bold text-gray-700">Hari</th>
                            <th class="text-left px-3 py-2 text-xs font-bold text-gray-700">Mulai</th>
                            <th class="text-left px-3 py-2 text-xs font-bold text-gray-700">Selesai</th>
                            <th class="text-left px-3 py-2 text-xs font-bold text-gray-700">Guru</th>
                            <th class="text-left px-3 py-2 text-xs font-bold text-gray-700">Ruang</th>
                            <th class="text-left px-3 py-2 text-xs font-bold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-if="rows.length === 0">
                            <tr>
                                <td colspan="7" class="px-3 py-6 text-center text-gray-500 text-sm">Pilih mata pelajaran di panel kiri untuk mulai mengisi jadwal.</td>
                            </tr>
                        </template>

                        <template x-for="(r, idx) in rows" :key="r.class_subject_id">
                            <tr class="border-t border-gray-100">
                                <td class="px-3 py-2">
                                    <div class="font-semibold text-gray-900" x-text="r.subject_name"></div>
                                </td>
                                <td class="px-3 py-2">
                                    <select x-model="r.day_of_week" class="border border-gray-300 rounded-lg px-2 py-1.5 text-sm">
                                        <template x-for="d in days" :key="d.value">
                                            <option :value="d.value" x-text="d.label"></option>
                                        </template>
                                    </select>
                                </td>
                                <td class="px-3 py-2"><input type="time" x-model="r.start_time" class="border border-gray-300 rounded-lg px-2 py-1.5 text-sm" /></td>
                                <td class="px-3 py-2"><input type="time" x-model="r.end_time" class="border border-gray-300 rounded-lg px-2 py-1.5 text-sm" /></td>
                                <td class="px-3 py-2">
                                    <select x-model="r.teacher_id" class="border border-gray-300 rounded-lg px-2 py-1.5 text-sm">
                                        <option value="">- pilih guru -</option>
                                        <template x-for="t in teachers" :key="t.id">
                                            <option :value="t.id" x-text="t.name"></option>
                                        </template>
                                    </select>
                                </td>
                                <td class="px-3 py-2"><input type="text" x-model="r.room" placeholder="-" class="border border-gray-300 rounded-lg px-2 py-1.5 text-sm w-28" /></td>
                                <td class="px-3 py-2">
                                    <button type="button" @click="removeRow(r.class_subject_id)" class="text-xs font-semibold text-red-600 hover:text-red-800">Hapus</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <form method="POST" :action="postUrl" @submit.prevent="submitForm" class="mt-4">
                @csrf
                <input type="hidden" name="mode" :value="mode">
                <template x-for="(r, idx) in rows" :key="'hidden-' + r.class_subject_id">
                    <div>
                        <input type="hidden" :name="`items[${idx}][class_subject_id]`" :value="r.class_subject_id">
                        <input type="hidden" :name="`items[${idx}][day_of_week]`" :value="r.day_of_week">
                        <input type="hidden" :name="`items[${idx}][start_time]`" :value="r.start_time">
                        <input type="hidden" :name="`items[${idx}][end_time]`" :value="r.end_time">
                        <input type="hidden" :name="`items[${idx}][room]`" :value="r.room">
                        <input type="hidden" :name="`items[${idx}][notes]`" :value="r.notes">
                    </div>
                </template>

                <div class="flex items-center justify-end gap-3">
                    <div class="text-xs text-gray-500" x-show="rows.length > 0">Pastikan hari & jam untuk semua baris sudah terisi.</div>
                    <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-blue-700 disabled:opacity-50" :disabled="rows.length === 0">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function bulkScheduler() {
    const classSubjects = @json($classSubjectsJson);
    const teachers = @json($teachers);
    const existingSchedules = @json($existingSchedulesJson);

    return {
        q: '',
        mode: 'replace_day',
        days: [
            { value: 'monday', label: 'Senin' },
            { value: 'tuesday', label: 'Selasa' },
            { value: 'wednesday', label: 'Rabu' },
            { value: 'thursday', label: 'Kamis' },
            { value: 'friday', label: 'Jumat' },
            { value: 'saturday', label: 'Sabtu' },
            { value: 'sunday', label: 'Minggu' },
        ],
        teachers,
        classSubjects,
        existingSchedules,
        selectedIds: [],
        rows: [],
        quick: {
            day: 'monday',
            start: '07:00',
            end: '08:00',
            teacher_id: '',
        },
        init() {
            // Prefill dari jadwal yang sudah ada (jika ada)
            if (!Array.isArray(this.existingSchedules) || this.existingSchedules.length === 0) return;

            for (const s of this.existingSchedules) {
                const cs = this.classSubjects.find(x => Number(x.id) === Number(s.class_subject_id));
                if (!cs) continue;

                // Jika ada beberapa schedule untuk mapel yang sama, ambil yang pertama saja
                const already = this.rows.find(r => Number(r.class_subject_id) === Number(s.class_subject_id));
                if (already) continue;

                this.selectedIds.push(String(cs.id));
                this.rows.push({
                    class_subject_id: cs.id,
                    subject_name: cs.subject_name,
                    teacher_id: cs.teacher_id || '',
                    day_of_week: s.day_of_week || this.quick.day,
                    start_time: s.start_time || this.quick.start,
                    end_time: s.end_time || this.quick.end,
                    room: s.room || '',
                    notes: s.notes || '',
                });
            }

            // Pastikan selectedIds unik
            this.selectedIds = Array.from(new Set(this.selectedIds));
        },
        get filteredClassSubjects() {
            const q = (this.q || '').toLowerCase();
            return this.classSubjects.filter(x => !q || (x.subject_name || '').toLowerCase().includes(q));
        },
        get postUrl() {
            return @json(route('admin.schedules.bulk.store', $class));
        },
        syncRows() {
            // Add rows for selected
            for (const id of this.selectedIds) {
                const existing = this.rows.find(r => r.class_subject_id === Number(id));
                if (existing) continue;

                const cs = this.classSubjects.find(x => x.id === Number(id));
                if (!cs) continue;

                this.rows.push({
                    class_subject_id: cs.id,
                    subject_name: cs.subject_name,
                    teacher_id: cs.teacher_id || '',
                    day_of_week: this.quick.day,
                    start_time: this.quick.start,
                    end_time: this.quick.end,
                    room: '',
                    notes: '',
                });
            }

            // Remove rows for unselected
            this.rows = this.rows.filter(r => this.selectedIds.includes(String(r.class_subject_id)) || this.selectedIds.includes(r.class_subject_id));
        },
        removeRow(classSubjectId) {
            this.rows = this.rows.filter(r => r.class_subject_id !== classSubjectId);
            this.selectedIds = this.selectedIds.filter(x => Number(x) !== Number(classSubjectId));
        },
        applyQuick(type) {
            if (type === 'day') {
                for (const r of this.rows) r.day_of_week = this.quick.day;
            }
            if (type === 'time') {
                for (const r of this.rows) {
                    r.start_time = this.quick.start;
                    r.end_time = this.quick.end;
                }
            }
            if (type === 'teacher') {
                for (const r of this.rows) r.teacher_id = this.quick.teacher_id;
            }
        },
        clearAll() {
            this.selectedIds = [];
            this.rows = [];
        },
        submitForm(e) {
            // Minimal client-side validation
            for (const r of this.rows) {
                if (!r.day_of_week || !r.start_time || !r.end_time) {
                    alert('Lengkapi hari dan jam untuk semua baris.');
                    return;
                }
                if (r.end_time <= r.start_time) {
                    alert('Jam selesai harus lebih besar dari jam mulai.');
                    return;
                }
            }

            e.target.submit();
        },
    };
}
</script>
@endsection
