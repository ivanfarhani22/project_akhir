@extends('layouts.guru')
@section('title', 'Aktivitas Harian')
@section('icon', 'fas fa-clipboard-check')

@section('content')

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-clipboard-check mr-1"></i> Guru / Aktivitas Harian</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-clipboard-check text-[#A41E35] mr-2"></i>Aktivitas Harian</h1>
    <p class="text-sm text-gray-500 mt-1">Input nilai dan catatan per jadwal. Presensi diambil otomatis dari sesi presensi mapel.</p>
</div>

@if(session('success'))
    <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="flex items-start gap-3 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
        <i class="fas fa-circle-exclamation mt-0.5 flex-shrink-0"></i>
        <div>
            <p class="font-semibold">Tidak ditemukan</p>
            <p class="text-sm font-normal">{{ session('error') }}</p>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6">
        <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
        <ul class="text-sm space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

{{-- FILTER --}}
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
    <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>
    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-bold text-gray-900 text-sm">Pilih Kelas & Tanggal</h2>
    </div>
    <div class="p-5">
        <form method="GET" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-end">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Kelas</label>
                <select name="e_class_id"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition bg-white">
                    <option value="">— Pilih kelas —</option>
                    @foreach(($classes ?? collect()) as $c)
                        <option value="{{ $c->id }}" @selected((int)$classId === (int)$c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Tanggal</label>
                <input type="date" name="date" value="{{ $date }}"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition">
            </div>
            <button type="submit"
                class="inline-flex justify-center items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition shadow-md whitespace-nowrap">
                <i class="fas fa-search text-xs"></i> Tampilkan
            </button>
        </form>
    </div>
</div>

@if(!$classId)
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex flex-col items-center justify-center py-16 text-center px-6">
            <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-clipboard-check text-3xl text-gray-300"></i>
            </div>
            <p class="text-gray-700 font-semibold text-sm mb-1">Belum ada kelas dipilih</p>
            <p class="text-xs text-gray-400">Pilih kelas dan tanggal di atas untuk mulai input aktivitas harian.</p>
        </div>
    </div>
@else
    @php $rowIndex = 0; @endphp
    <form method="POST" action="{{ route('guru.daily-activities.store') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="date" value="{{ $date }}">

        @forelse($schedules as $sch)
            @php
                $session = $attendanceSessions->get($sch->class_subject_id);
                $recordByStudent = $session ? $session->records->keyBy('student_id') : collect();
                $subjectName = optional($sch->classSubject->subject)->name ?? 'Kegiatan';
                $timeRange = substr((string)$sch->start_time, 0, 5) . ' – ' . substr((string)$sch->end_time, 0, 5);
            @endphp

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="h-1 bg-gradient-to-r from-[#A41E35] to-rose-400"></div>

                {{-- Header jadwal --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 px-5 py-4 border-b border-gray-100 bg-gray-50">
                    <div>
                        <h3 class="font-bold text-gray-900">{{ $subjectName }}</h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            <i class="fas fa-clock mr-1"></i>{{ $timeRange }}
                            <span class="mx-1.5 text-gray-300">•</span>
                            <i class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full border self-start sm:self-auto
                        {{ $session ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-gray-100 text-gray-500 border-gray-200' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $session ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                        {{ $session ? 'Ada Sesi Presensi' : 'Belum Ada Sesi' }}
                    </span>
                </div>

                {{-- TABEL DESKTOP --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-1/3">Siswa</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-1/6">Presensi</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-1/6">Nilai (0–100)</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($students as $stu)
                                @php
                                    $key = $sch->id . ':' . $stu->id;
                                    $a = $existing->get($key);
                                    $pres = $recordByStudent->get($stu->id)?->status;
                                    $presLabel = match($pres) {
                                        'present' => ['Hadir', 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                        'late'    => ['Terlambat', 'bg-orange-50 text-orange-600 border-orange-200'],
                                        'excused' => ['Izin', 'bg-blue-50 text-blue-600 border-blue-200'],
                                        'absent'  => ['Tidak Hadir', 'bg-red-50 text-red-600 border-red-200'],
                                        default   => ['—', 'bg-gray-100 text-gray-400 border-gray-200'],
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-5 py-3 font-semibold text-gray-900">{{ $stu->name }}</td>
                                    <td class="px-5 py-3">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $presLabel[1] }}">
                                            {{ $presLabel[0] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3">
                                        <input type="hidden" name="entries[{{ $rowIndex }}][schedule_id]" value="{{ $sch->id }}">
                                        <input type="hidden" name="entries[{{ $rowIndex }}][student_id]" value="{{ $stu->id }}">
                                        <input type="number" min="0" max="100"
                                            name="entries[{{ $rowIndex }}][score]"
                                            value="{{ old('entries.'.$rowIndex.'.score', $a?->score) }}"
                                            placeholder="—"
                                            class="w-24 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition text-center">
                                    </td>
                                    <td class="px-5 py-3">
                                        <input type="text"
                                            name="entries[{{ $rowIndex }}][notes]"
                                            value="{{ old('entries.'.$rowIndex.'.notes', $a?->notes) }}"
                                            placeholder="Opsional..."
                                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition">
                                    </td>
                                </tr>
                                @php $rowIndex++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- CARD MOBILE --}}
                <div class="md:hidden divide-y divide-gray-100">
                    @foreach($students as $stu)
                        @php
                            $key = $sch->id . ':' . $stu->id;
                            $a = $existing->get($key);
                            $pres = $recordByStudent->get($stu->id)?->status;
                            $presLabel = match($pres) {
                                'present' => ['Hadir', 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                'late'    => ['Terlambat', 'bg-orange-50 text-orange-600 border-orange-200'],
                                'excused' => ['Izin', 'bg-blue-50 text-blue-600 border-blue-200'],
                                'absent'  => ['Tidak Hadir', 'bg-red-50 text-red-600 border-red-200'],
                                default   => ['—', 'bg-gray-100 text-gray-400 border-gray-200'],
                            };
                        @endphp
                        <div class="p-4 space-y-3">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-bold text-gray-900 text-sm">{{ $stu->name }}</p>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $presLabel[1] }} flex-shrink-0">
                                    {{ $presLabel[0] }}
                                </span>
                            </div>
                            <input type="hidden" name="entries[{{ $rowIndex }}][schedule_id]" value="{{ $sch->id }}">
                            <input type="hidden" name="entries[{{ $rowIndex }}][student_id]" value="{{ $stu->id }}">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Nilai (0–100)</label>
                                    <input type="number" min="0" max="100"
                                        name="entries[{{ $rowIndex }}][score]"
                                        value="{{ old('entries.'.$rowIndex.'.score', $a?->score) }}"
                                        placeholder="—"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition text-center">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Catatan</label>
                                    <input type="text"
                                        name="entries[{{ $rowIndex }}][notes]"
                                        value="{{ old('entries.'.$rowIndex.'.notes', $a?->notes) }}"
                                        placeholder="Opsional..."
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#A41E35] focus:ring-2 focus:ring-red-100 transition">
                                </div>
                            </div>
                        </div>
                        @php $rowIndex++; @endphp
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
                <div class="flex flex-col items-center justify-center py-12 text-center px-6">
                    <div class="w-16 h-16 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-3">
                        <i class="fas fa-calendar-times text-2xl text-gray-300"></i>
                    </div>
                    <p class="text-gray-600 font-semibold text-sm mb-1">Tidak ada jadwal ditemukan</p>
                    <p class="text-xs text-gray-400">Tidak ada jadwal pada tanggal {{ \Carbon\Carbon::parse($date)->format('d M Y') }} untuk kelas ini.</p>
                </div>
            </div>
        @endforelse

        @if($schedules->count() > 0)
            <div class="flex justify-end pt-2">
                <button type="submit"
                    class="inline-flex justify-center items-center gap-2 bg-[#A41E35] hover:bg-[#7D1627] text-white font-semibold px-6 py-3 rounded-xl text-sm transition shadow-md hover:shadow-lg">
                    <i class="fas fa-save text-xs"></i> Simpan Aktivitas
                </button>
            </div>
        @endif
    </form>
@endif

@endsection