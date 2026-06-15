@extends('layouts.admin')
@section('title', 'Bulk Scheduling')
@section('icon', 'calendar')

@section('content')
@php
    $classSubjectsJson = $classSubjects->map(fn($cs) => [
        'id'           => $cs->id,
        'subject_name' => optional($cs->subject)->name,
        'teacher_id'   => $cs->teacher_id,
        'teacher_name' => optional($cs->teacher)->name,
    ])->values();

    $existingSchedulesJson = collect($existingSchedules ?? [])->map(fn($s) => [
        'id'               => $s->id,
        'class_subject_id' => $s->class_subject_id,
        'day_of_week'      => $s->day_of_week,
        'start_time'       => is_string($s->start_time) ? substr($s->start_time,0,5) : optional($s->start_time)->format('H:i'),
        'end_time'         => is_string($s->end_time)   ? substr($s->end_time,0,5)   : optional($s->end_time)->format('H:i'),
        'room'             => $s->room,
        'notes'            => $s->notes,
    ])->values();

    $autoGenerateUrl = route('admin.schedules.bulk.autoGenerate', $class);
@endphp

{{-- ── PAGE HEADER ─────────────────────────────────────────────────────── --}}
<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">
        <i class="fas fa-calendar mr-1"></i> Admin / Jadwal / Bulk Scheduling
    </p>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Bulk Scheduling</h1>
            <p class="text-sm text-gray-500 mt-1">
                Kelas: <strong class="text-gray-800">{{ $class->name }}</strong>
                — Pilih mapel, atur hari & jam, lalu simpan sekaligus.
            </p>
        </div>
        <a href="{{ route('admin.classes.show', $class) }}"
           class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2.5 rounded-xl text-sm transition whitespace-nowrap">
            <i class="fas fa-arrow-left text-xs"></i> Kembali
        </a>
    </div>
</div>

{{-- ── ALERTS ──────────────────────────────────────────────────────────── --}}
@if($errors->any())
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6">
        <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
        <ul class="text-sm space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif
@if(session('success'))
    <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

{{-- ── MAIN ALPINE COMPONENT ───────────────────────────────────────────── --}}
<div x-data="bulkScheduler()" class="space-y-5">

    {{-- ═══ STEP 1: PILIH MAPEL ════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
            <div>
                <h2 class="font-bold text-gray-900">
                    <span class="inline-flex items-center justify-center w-6 h-6 bg-[#A41E35] text-white text-xs font-bold rounded-full mr-2">1</span>
                    Pilih Mata Pelajaran
                </h2>
                <p class="text-xs text-gray-400 mt-0.5 ml-8">Centang mapel yang ingin dijadwalkan.</p>
            </div>
            <span class="text-xs font-bold bg-gray-100 text-gray-600 px-3 py-1 rounded-full" x-text="selectedIds.length + ' dipilih'"></span>
        </div>
        <div class="p-5">
            <div class="mb-3">
                <input type="text" x-model="q" placeholder="Cari mata pelajaran..."
                    class="w-full sm:w-80 px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2">
                <template x-for="cs in filteredClassSubjects" :key="cs.id">
                    <label class="flex items-start gap-3 p-3 border-2 rounded-xl cursor-pointer transition-all"
                        :class="selectedIds.includes(String(cs.id)) ? 'border-[#A41E35] bg-red-50' : 'border-gray-100 hover:border-gray-300 bg-white'">
                        <input type="checkbox" class="mt-0.5 flex-shrink-0 accent-[#A41E35]" :value="cs.id" x-model="selectedIds" @change="syncRows()">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-900 truncate" x-text="cs.subject_name"></p>
                            <p class="text-xs text-gray-400 truncate mt-0.5" x-text="cs.teacher_name || 'Belum ada guru'"></p>
                        </div>
                    </label>
                </template>
            </div>
        </div>
    </div>

    {{-- ═══ STEP 2: GENERATOR TOOLS ════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-amber-400 to-orange-400"></div>
        <button type="button" @click="showGenerators = !showGenerators"
            class="w-full flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50 hover:bg-gray-100 transition text-left">
            <div>
                <h2 class="font-bold text-gray-900">
                    <span class="inline-flex items-center justify-center w-6 h-6 bg-amber-400 text-white text-xs font-bold rounded-full mr-2">2</span>
                    Generator Otomatis
                    <span class="text-xs font-normal text-gray-400 ml-2">— opsional, untuk isi cepat</span>
                </h2>
            </div>
            <i class="fas text-gray-400 transition-transform" :class="showGenerators ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
        </button>

        <div x-show="showGenerators" x-collapse class="p-5 space-y-4">

            {{-- Generator AI --}}
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <p class="font-bold text-emerald-800 text-sm"><i class="fas fa-magic mr-1.5"></i>Auto-Generate Jadwal (AI)</p>
                        <p class="text-xs text-emerald-600 mt-0.5">Distribusi mapel yang sudah dipilih ke slot waktu secara otomatis, anti bentrok.</p>
                    </div>
                    <button type="button" @click="autoGenerate()"
                        class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition disabled:opacity-50 whitespace-nowrap"
                        :disabled="selectedIds.length === 0 || aiLoading">
                        <i class="fas fa-magic text-xs" x-show="!aiLoading"></i>
                        <i class="fas fa-spinner fa-spin text-xs" x-show="aiLoading"></i>
                        <span x-text="aiLoading ? 'Generating...' : 'Generate Otomatis'"></span>
                    </button>
                </div>
            </div>

            {{-- Generator JP Sekolah --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <p class="font-bold text-blue-800 text-sm mb-1"><i class="fas fa-school mr-1.5"></i>Generator Grid JP Sekolah</p>
                <p class="text-xs text-blue-600 mb-3">Buat slot JP kosong (JP 1, JP 2, ...) berdasarkan durasi menit. Edit nama jadwal setelah dibuat.</p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Jam mulai JP 1</label>
                        <input type="time" x-model="gen.school.start" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-400 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">JP Senin–Kamis</label>
                        <input type="number" min="1" x-model.number="gen.school.totalMonThu" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-400 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">JP Jumat</label>
                        <input type="number" min="1" x-model.number="gen.school.totalFri" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-400 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Override Jumat</label>
                        <input type="text" x-model="gen.school.friOverrides" placeholder="1=60,7=120" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-400 transition">
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="button" @click="generateSchoolJpGrid(['monday','tuesday','wednesday','thursday'])"
                        class="inline-flex items-center gap-1.5 bg-white hover:bg-blue-100 border border-blue-300 text-blue-700 font-semibold text-xs px-3 py-2 rounded-lg transition">
                        <i class="fas fa-plus text-[10px]"></i> Generate Senin–Kamis
                    </button>
                    <button type="button" @click="generateSchoolJpGrid(['friday'])"
                        class="inline-flex items-center gap-1.5 bg-white hover:bg-blue-100 border border-blue-300 text-blue-700 font-semibold text-xs px-3 py-2 rounded-lg transition">
                        <i class="fas fa-plus text-[10px]"></i> Generate Jumat
                    </button>
                </div>
            </div>

            {{-- Generator Asrama --}}
            <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
                <p class="font-bold text-purple-800 text-sm mb-1"><i class="fas fa-building mr-1.5"></i>Generator Jadwal Asrama</p>
                <p class="text-xs text-purple-600 mb-3">Isi template kegiatan asrama harian. Slot lintas tengah malam otomatis di-split menjadi 2 baris.</p>
                <div class="flex flex-wrap gap-2">
                    <button type="button" @click="generateDormTemplate('weekday')"
                        class="inline-flex items-center gap-1.5 bg-white hover:bg-purple-100 border border-purple-300 text-purple-700 font-semibold text-xs px-3 py-2 rounded-lg transition">
                        <i class="fas fa-plus text-[10px]"></i> Asrama Senin–Jumat
                    </button>
                    <button type="button" @click="generateDormTemplate('weekend')"
                        class="inline-flex items-center gap-1.5 bg-white hover:bg-purple-100 border border-purple-300 text-purple-700 font-semibold text-xs px-3 py-2 rounded-lg transition">
                        <i class="fas fa-plus text-[10px]"></i> Asrama Sabtu–Minggu
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- ═══ STEP 3: TABEL JADWAL ════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b border-gray-100 bg-gray-50">
            <div>
                <h2 class="font-bold text-gray-900">
                    <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-500 text-white text-xs font-bold rounded-full mr-2">3</span>
                    Atur Jadwal
                </h2>
                <p class="text-xs text-gray-400 mt-0.5 ml-8">Edit hari, jam, guru, dan ruang untuk tiap baris jadwal.</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <button type="button" @click="addCustomRow()"
                    class="inline-flex items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs px-3 py-2 rounded-lg transition">
                    <i class="fas fa-plus text-[10px]"></i> Tambah Custom
                </button>
                <button type="button" @click="clearAll()"
                    class="inline-flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-semibold text-xs px-3 py-2 rounded-lg transition">
                    <i class="fas fa-trash text-[10px]"></i> Hapus Semua
                </button>
                <span class="text-xs font-bold bg-gray-900 text-white px-3 py-1 rounded-full" x-text="rows.length + ' baris'"></span>
            </div>
        </div>

        {{-- Quick Fill Bar --}}
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
            <div class="flex flex-wrap items-end gap-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider self-center">Isi Cepat:</span>
                <div class="flex items-end gap-2">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Hari</label>
                        <select x-model="quick.day" class="px-3 py-2 border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-[#A41E35] transition bg-white">
                            <template x-for="d in days" :key="d.value">
                                <option :value="d.value" x-text="d.label"></option>
                            </template>
                        </select>
                    </div>
                    <button type="button" @click="applyQuick('day')"
                        class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold text-xs rounded-lg transition">Apply</button>
                </div>
                <div class="flex items-end gap-2">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Jam Mulai – Selesai</label>
                        <div class="flex items-center gap-1">
                            <input type="time" x-model="quick.start" class="px-3 py-2 border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-[#A41E35] transition">
                            <span class="text-gray-400 text-xs">–</span>
                            <input type="time" x-model="quick.end" class="px-3 py-2 border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-[#A41E35] transition">
                        </div>
                    </div>
                    <button type="button" @click="applyQuick('time')"
                        class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold text-xs rounded-lg transition">Apply</button>
                </div>
                <div class="flex items-end gap-2">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Guru</label>
                        <select x-model="quick.teacher_id" class="px-3 py-2 border border-gray-200 rounded-lg text-xs focus:outline-none focus:border-[#A41E35] transition bg-white">
                            <option value="">— pilih guru —</option>
                            <template x-for="t in teachers" :key="t.id">
                                <option :value="t.id" x-text="t.name"></option>
                            </template>
                        </select>
                    </div>
                    <button type="button" @click="applyQuick('teacher')"
                        class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold text-xs rounded-lg transition">Apply</button>
                </div>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider min-w-[180px]">Mapel / Kegiatan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider min-w-[120px]">Hari</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider min-w-[100px]">Mulai</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider min-w-[100px]">Selesai</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider min-w-[160px]">Guru</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider min-w-[100px]">Ruang</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">Hapus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-if="rows.length === 0">
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center">
                                        <i class="fas fa-calendar-plus text-2xl text-gray-300"></i>
                                    </div>
                                    <p class="text-sm text-gray-400">Pilih mapel di Langkah 1 atau gunakan generator untuk menambah jadwal.</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-for="(r, idx) in rows" :key="r._key">
                        <tr class="hover:bg-gray-50 transition"
                            :class="r.entry_type === 'custom' ? 'bg-amber-50/30' : ''">
                            <td class="px-4 py-2.5">
                                <template x-if="r.entry_type === 'mapel'">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-[#A41E35] rounded-full flex-shrink-0"></span>
                                        <span class="font-semibold text-gray-900 text-sm" x-text="r.subject_name"></span>
                                    </div>
                                </template>
                                <template x-if="r.entry_type === 'custom'">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-amber-400 rounded-full flex-shrink-0"></span>
                                        <input type="text" x-model="r.custom_title" placeholder="Nama kegiatan..."
                                            class="flex-1 px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition">
                                    </div>
                                </template>
                            </td>
                            <td class="px-4 py-2.5">
                                <select x-model="r.day_of_week"
                                    class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition bg-white">
                                    <template x-for="d in days" :key="d.value">
                                        <option :value="d.value" x-text="d.label"></option>
                                    </template>
                                </select>
                            </td>
                            <td class="px-4 py-2.5">
                                <input type="time" x-model="r.start_time"
                                    class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition">
                            </td>
                            <td class="px-4 py-2.5">
                                <input type="time" x-model="r.end_time"
                                    class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition">
                            </td>
                            <td class="px-4 py-2.5">
                                <template x-if="r.entry_type === 'mapel'">
                                    <select x-model="r.teacher_id"
                                        class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition bg-white">
                                        <option value="">— pilih guru —</option>
                                        <template x-for="t in teachers" :key="t.id">
                                            <option :value="t.id" x-text="t.name"></option>
                                        </template>
                                    </select>
                                </template>
                                <template x-if="r.entry_type === 'custom'">
                                    <span class="text-xs text-gray-400 px-3">—</span>
                                </template>
                            </td>
                            <td class="px-4 py-2.5">
                                <input type="text" x-model="r.room" placeholder="—"
                                    class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition">
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <button type="button" @click="removeRow(r)"
                                    class="w-8 h-8 flex items-center justify-center mx-auto bg-red-50 hover:bg-red-500 text-red-400 hover:text-white border border-red-200 rounded-lg text-xs transition">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ═══ STEP 4: SIMPAN ══════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-emerald-500 to-teal-400"></div>
        <div class="p-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="font-bold text-gray-900">
                        <span class="inline-flex items-center justify-center w-6 h-6 bg-emerald-500 text-white text-xs font-bold rounded-full mr-2">4</span>
                        Mode Simpan & Konfirmasi
                    </h2>
                    <div class="flex flex-wrap items-center gap-4 mt-2 ml-8">
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="radio" name="mode_display" value="replace_day" x-model="mode" class="accent-[#A41E35]">
                            <div>
                                <span class="font-semibold text-gray-800">Replace per-hari</span>
                                <span class="text-xs text-gray-400 ml-1">— ganti semua jadwal hari yang terlibat</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="radio" name="mode_display" value="merge" x-model="mode" class="accent-[#A41E35]">
                            <div>
                                <span class="font-semibold text-gray-800">Merge</span>
                                <span class="text-xs text-gray-400 ml-1">— tambahkan tanpa menghapus yang ada</span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="ml-8 sm:ml-0">
                    <p class="text-xs text-gray-400 mb-2" x-show="rows.length > 0">
                        <i class="fas fa-info-circle mr-1"></i>
                        <span x-text="rows.length"></span> baris jadwal akan disimpan.
                    </p>
                    <button type="button" @click="submitForm()"
                        class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-3 rounded-xl text-sm transition shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="rows.length === 0">
                        <i class="fas fa-save text-xs"></i> Simpan Jadwal
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden form untuk submit --}}
    <form method="POST" :action="postUrl" x-ref="mainForm" class="hidden">
        @csrf
        <input type="hidden" name="mode" :value="mode">
        <template x-for="(r, idx) in rows" :key="'h-' + r._key">
            <div>
                <input type="hidden" :name="`items[${idx}][entry_type]`"       :value="r.entry_type">
                <input type="hidden" :name="`items[${idx}][class_subject_id]`" :value="r.entry_type === 'mapel' ? r.class_subject_id : ''">
                <input type="hidden" :name="`items[${idx}][custom_title]`"     :value="r.entry_type === 'custom' ? r.custom_title : ''">
                <input type="hidden" :name="`items[${idx}][day_of_week]`"      :value="r.day_of_week">
                <input type="hidden" :name="`items[${idx}][start_time]`"       :value="r.start_time">
                <input type="hidden" :name="`items[${idx}][end_time]`"         :value="r.end_time">
                <input type="hidden" :name="`items[${idx}][room]`"             :value="r.room">
                <input type="hidden" :name="`items[${idx}][notes]`"            :value="r.notes">
            </div>
        </template>
    </form>

</div>

<script>
function bulkScheduler() {
    const classSubjects      = @json($classSubjectsJson);
    const teachers           = @json($teachers);
    const existingSchedules  = @json($existingSchedulesJson);

    return {
        // ── state ──────────────────────────────────────────────────────
        q:               '',
        mode:            'replace_day',
        showGenerators:  false,
        selectedIds:     [],
        rows:            [],
        aiLoading:       false,
        quick: { day: 'monday', start: '07:00', end: '08:00', teacher_id: '' },
        gen:  { school: { start: '07:00', totalMonThu: 12, totalFri: 7, friOverrides: '1=60,7=120' } },
        days: [
            { value: 'monday',    label: 'Senin'  },
            { value: 'tuesday',   label: 'Selasa' },
            { value: 'wednesday', label: 'Rabu'   },
            { value: 'thursday',  label: 'Kamis'  },
            { value: 'friday',    label: 'Jumat'  },
            { value: 'saturday',  label: 'Sabtu'  },
            { value: 'sunday',    label: 'Minggu' },
        ],
        teachers,
        classSubjects,

        // ── computed ───────────────────────────────────────────────────
        get filteredClassSubjects() {
            const q = (this.q || '').toLowerCase();
            return this.classSubjects.filter(x => !q || (x.subject_name || '').toLowerCase().includes(q));
        },
        get postUrl() {
            return @json(route('admin.schedules.bulk.store', $class));
        },

        // ── init ───────────────────────────────────────────────────────
        init() {
            if (!Array.isArray(existingSchedules) || existingSchedules.length === 0) return;
            for (const s of existingSchedules) {
                const cs = this.classSubjects.find(x => Number(x.id) === Number(s.class_subject_id));
                if (!cs) {
                    this.rows.push({ ...this._customDefaults(), custom_title: s.notes || '', day_of_week: s.day_of_week || 'monday', start_time: s.start_time || '07:00', end_time: s.end_time || '08:00', room: s.room || '' });
                    continue;
                }
                if (this.rows.find(r => r.entry_type === 'mapel' && Number(r.class_subject_id) === Number(cs.id))) continue;
                this.selectedIds.push(String(cs.id));
                this.rows.push({ ...this._mapelRow(cs), day_of_week: s.day_of_week || 'monday', start_time: s.start_time || '07:00', end_time: s.end_time || '08:00', room: s.room || '' });
            }
            this.selectedIds = [...new Set(this.selectedIds)];
        },

        // ── helpers ────────────────────────────────────────────────────
        _mkKey(p) { return p + '-' + Date.now() + '-' + Math.random().toString(16).slice(2); },
        _mapelRow(cs) {
            return { _key: 'mapel-' + cs.id, entry_type: 'mapel', class_subject_id: cs.id, subject_name: cs.subject_name, teacher_id: cs.teacher_id || '', custom_title: '', day_of_week: this.quick.day, start_time: this.quick.start, end_time: this.quick.end, room: '', notes: '' };
        },
        _customDefaults() {
            return { _key: this._mkKey('custom'), entry_type: 'custom', class_subject_id: '', subject_name: '', teacher_id: '', custom_title: '', day_of_week: this.quick.day, start_time: this.quick.start, end_time: this.quick.end, room: '', notes: '' };
        },
        _addMinutes(hhmm, minutes) {
            const [h, m] = String(hhmm || '00:00').split(':').map(Number);
            if (isNaN(h) || isNaN(m)) return '00:00';
            const total = (h * 60 + m + minutes + 1440) % 1440;
            return String(Math.floor(total / 60)).padStart(2,'0') + ':' + String(total % 60).padStart(2,'0');
        },
        _parseOverrides(text) {
            const map = {};
            (text || '').split(',').map(s => s.trim()).filter(Boolean).forEach(pair => {
                const [k, v] = pair.split('=').map(x => (x||'').trim());
                const jpNo = parseInt(k,10), mins = parseInt(v,10);
                if (!isNaN(jpNo) && !isNaN(mins) && jpNo > 0 && mins > 0) map[jpNo] = mins;
            });
            return map;
        },
        _pushCustomRow({ day, start, end, title, notes = '' }) {
            this.rows.push({ ...this._customDefaults(), _key: this._mkKey('custom'), day_of_week: day, start_time: start, end_time: end, custom_title: title, notes });
        },
        _nextDay(day) {
            const order = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
            const idx   = order.indexOf(day);
            return order[(idx + 1) % 7];
        },

        // ── actions ────────────────────────────────────────────────────
        addCustomRow()   { this.rows.push(this._customDefaults()); },
        clearAll()       { this.selectedIds = []; this.rows = []; },

        syncRows() {
            // Add newly checked
            for (const id of this.selectedIds) {
                if (this.rows.find(r => r.entry_type === 'mapel' && String(r.class_subject_id) === String(id))) continue;
                const cs = this.classSubjects.find(x => String(x.id) === String(id));
                if (cs) this.rows.push(this._mapelRow(cs));
            }
            // Remove unchecked mapel rows
            this.rows = this.rows.filter(r => r.entry_type === 'custom' || this.selectedIds.includes(String(r.class_subject_id)));
        },

        removeRow(row) {
            if (row.entry_type === 'custom') {
                this.rows = this.rows.filter(r => r._key !== row._key);
            } else {
                this.rows = this.rows.filter(r => !(r.entry_type === 'mapel' && r.class_subject_id === row.class_subject_id));
                this.selectedIds = this.selectedIds.filter(x => String(x) !== String(row.class_subject_id));
            }
        },

        applyQuick(type) {
            if (type === 'day')     this.rows.forEach(r => { r.day_of_week = this.quick.day; });
            if (type === 'time')    this.rows.forEach(r => { r.start_time = this.quick.start; r.end_time = this.quick.end; });
            if (type === 'teacher') this.rows.forEach(r => { if (r.entry_type === 'mapel') r.teacher_id = this.quick.teacher_id; });
        },

        async autoGenerate() {
            if (!this.selectedIds.length) return;
            this.aiLoading = true;
            try {
                const res  = await fetch(@json($autoGenerateUrl), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': @json(csrf_token()), 'Accept': 'application/json' },
                    body: JSON.stringify({ class_subject_ids: this.selectedIds.map(Number), days: ['monday','tuesday','wednesday','thursday','friday'], day_start: '07:00', day_end: '15:00', slot_minutes: 60, break_minutes: 0 }),
                });
                const data = await res.json();
                if (!res.ok) { alert(data?.message || 'Gagal generate jadwal.'); return; }
                const ids  = new Set(this.selectedIds.map(Number));
                this.rows  = this.rows.filter(r => !ids.has(Number(r.class_subject_id)));
                for (const it of (data.items || [])) {
                    const cs = this.classSubjects.find(x => Number(x.id) === Number(it.class_subject_id));
                    if (cs) this.rows.push({ ...this._mapelRow(cs), day_of_week: it.day_of_week, start_time: it.start_time, end_time: it.end_time, room: it.room || '' });
                }
                if (data.unscheduled_count > 0) alert('Sebagian mapel belum terjadwal karena bentrok. Silakan atur manual.');
            } catch { alert('Gagal generate jadwal (network/server).'); }
            finally  { this.aiLoading = false; }
        },

        generateSchoolJpGrid(days) {
            for (const day of days) {
                const isFri    = day === 'friday';
                const total    = isFri ? (this.gen.school.totalFri || 7) : (this.gen.school.totalMonThu || 12);
                const defMins  = isFri ? 35 : 40;
                const over     = isFri ? this._parseOverrides(this.gen.school.friOverrides) : {};
                let t = this.gen.school.start || '07:00';
                for (let jp = 1; jp <= total; jp++) {
                    const dur = over[jp] || defMins;
                    const end = this._addMinutes(t, dur);
                    this._pushCustomRow({ day, start: t, end, title: `JP ${jp}`, notes: isFri ? 'Grid JP Jumat' : 'Grid JP Senin–Kamis' });
                    t = end;
                }
            }
        },

        generateDormTemplate(kind) {
            const weekday = [
                ['03:30','04:00','Bangun Pagi & Bersih Diri'],['04:00','04:30','Sholat Subuh'],['04:30','05:00','Hafalan Al-Qur\'an'],
                ['05:00','05:30','Olahraga Pagi'],['05:30','06:00','Mandi & Berpakaian'],['06:00','06:15','Apel Pagi'],
                ['06:15','06:30','Sholat Dhuha'],['06:30','06:45','Sarapan'],['06:45','07:00','Penyerahan ke Sekolah'],
                ['07:00','15:00','KBM di Sekolah'],['15:00','15:30','Sholat Ashar'],['15:30','17:30','Kegiatan Mandiri / Mencuci'],
                ['17:30','18:15','Sholat Maghrib'],['18:15','19:00','Mengaji'],['19:00','19:30','Sholat Isya'],
                ['19:30','20:00','Makan Malam'],['20:00','21:00','Bimbingan Wali Asuh'],['21:00','21:30','Apel Malam'],
                ['22:00','23:59','Jam Malam'],
            ];
            const weekend = [
                ['03:30','04:00','Bangun Pagi & Bersih Diri'],['04:00','04:30','Sholat Subuh'],['04:30','05:00','Hafalan Al-Qur\'an'],
                ['05:00','05:30','Olahraga Pagi'],['05:30','06:00','Mandi & Berpakaian'],['06:00','06:15','Apel Pagi'],
                ['06:15','06:30','Sholat Dhuha'],['06:30','06:45','Sarapan'],['07:00','08:00','Kerja Bakti'],
                ['08:00','12:00','Pengembangan Minat Bakat / Kunjungan Orang Tua'],['12:00','12:30','Sholat Dhuhur'],
                ['12:30','13:00','Makan Siang'],['13:00','15:00','Istirahat'],['15:00','15:30','Sholat Ashar'],
                ['15:30','17:30','Kegiatan Mandiri'],['17:30','18:15','Sholat Maghrib'],['18:15','19:00','Mengaji'],
                ['19:00','19:30','Sholat Isya'],['19:30','20:00','Makan Malam'],['20:00','21:00','Kegiatan Malam'],
                ['21:00','21:30','Apel Malam'],['22:00','23:59','Jam Malam'],
            ];
            const tpl  = kind === 'weekday' ? weekday : weekend;
            const days = kind === 'weekday' ? ['monday','tuesday','wednesday','thursday','friday'] : ['saturday','sunday'];
            const note = kind === 'weekday' ? 'Asrama (Senin–Jumat)' : 'Asrama (Sabtu–Minggu)';
            for (const day of days) {
                for (const [s, e, title] of tpl) this._pushCustomRow({ day, start: s, end: e, title, notes: note });
                this._pushCustomRow({ day: this._nextDay(day), start: '00:00', end: '03:30', title: 'Jam Malam (lanjutan)', notes: note });
            }
        },

        submitForm() {
            for (const r of this.rows) {
                if (!r.day_of_week || !r.start_time || !r.end_time) { alert('Lengkapi hari dan jam untuk semua baris.'); return; }
                if (r.end_time <= r.start_time) { alert(`Jam selesai harus lebih besar dari jam mulai (baris: ${r.subject_name || r.custom_title})`); return; }
                if (r.entry_type === 'custom' && !(r.custom_title || '').trim()) { alert('Nama kegiatan custom tidak boleh kosong.'); return; }
            }
            this.$refs.mainForm.submit();
        },
    };
}
</script>
@endsection