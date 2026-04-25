@extends('layouts.siswa')
@section('title', $assignment->title)
@section('icon', 'fas fa-tasks')

@section('content')

@php
    $submission = \App\Models\Submission::where('student_id', auth()->id())->where('assignment_id', $assignment->id)->first();
    $deadline = $assignment->deadline;
    $isLate = $submission && $submission->submitted_at && $deadline && $submission->submitted_at > $deadline;
    $isOverdue = $deadline && now() > $deadline && (!$submission || !$submission->submitted_at);
    $deadlineIso = $deadline?->toIso8601String();
@endphp

<div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-8">
    <div>
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-tasks mr-1"></i> Siswa / Tugas / Detail</p>
        <h1 class="text-2xl font-extrabold text-gray-900">{{ $assignment->title }}</h1>
        <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full mt-1">
            {{ $assignment->eClass->subject->name }} • {{ $assignment->eClass->name }}
        </span>
    </div>
    <a href="{{ route('siswa.assignments.index') }}"
       class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl transition whitespace-nowrap">
        <i class="fas fa-arrow-left text-xs"></i> Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-5">

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-amber-400 to-orange-400"></div>
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-bold text-gray-900"><i class="fas fa-file-alt mr-2 text-gray-400"></i>Deskripsi Tugas</h2>
            </div>
            <div class="p-6 text-gray-700 text-sm leading-relaxed">{!! $assignment->description !!}</div>
        </div>

        @if($assignment->file_path)
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="font-bold text-gray-900"><i class="fas fa-download mr-2 text-gray-400"></i>File dari Guru</h2>
                </div>
                <div class="p-5">
                    <div class="flex items-center justify-between gap-4 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-center">
                                <i class="fas fa-file text-emerald-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ basename($assignment->file_path) }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">Klik download untuk melihat soal</p>
                            </div>
                        </div>
                        <a href="{{ route('siswa.assignments.download', $assignment) }}"
                           class="inline-flex items-center gap-1.5 bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white border border-emerald-200 text-xs font-semibold px-3 py-2 rounded-lg transition whitespace-nowrap">
                            <i class="fas fa-download text-[10px]"></i> Download
                        </a>
                    </div>
                </div>
            </div>
        @endif

        @if($submission && $submission->grade)
            @php
                $pct = ($submission->grade->score / ($submission->grade->max_score ?? 100)) * 100;
                [$gl, $gc, $gb] = $pct >= 85 ? ['Sangat Baik','text-emerald-600','bg-emerald-500'] : ($pct >= 70 ? ['Baik','text-blue-600','bg-blue-500'] : ($pct >= 60 ? ['Cukup','text-yellow-600','bg-yellow-500'] : ['Kurang','text-red-600','bg-red-500']));
            @endphp
            <div class="bg-amber-50 rounded-2xl border border-amber-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-amber-100">
                    <h2 class="font-bold text-amber-800"><i class="fas fa-star mr-2 text-amber-400"></i>Penilaian Anda</h2>
                </div>
                <div class="p-6 text-center">
                    <p class="text-5xl font-extrabold text-amber-600 mb-2">{{ $submission->grade->score }}</p>
                    <p class="font-bold text-base mb-4 {{ $gc }}">{{ $gl }}</p>
                    <div class="w-full h-2 bg-amber-100 rounded-full overflow-hidden mb-2">
                        <div class="{{ $gb }} h-full rounded-full" style="width:{{ $pct }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500">{{ number_format($pct, 1) }}%</p>
                    @if($submission->grade->feedback)
                        <div class="mt-5 pt-5 border-t border-amber-200 text-left">
                            <p class="text-xs font-semibold text-amber-700 uppercase tracking-wider mb-2">Komentar Guru</p>
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $submission->grade->feedback }}</p>
                        </div>
                    @endif
                    @if($submission->grade->graded_at)
                        <p class="text-xs text-gray-400 mt-4 pt-4 border-t border-amber-200">
                            <i class="fas fa-check-circle mr-1"></i> Dinilai {{ $submission->grade->graded_at->format('d M Y H:i') }}
                        </p>
                    @endif
                </div>
            </div>
        @endif

        @if($submission && $submission->file_path)
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="font-bold text-gray-900"><i class="fas fa-paperclip mr-2 text-gray-400"></i>File Pengumpulan Anda</h2>
                </div>
                <div class="p-5">
                    <div class="flex items-center justify-between gap-4 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-50 border border-blue-200 rounded-xl flex items-center justify-center">
                                <i class="fas fa-file text-blue-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ basename($submission->file_path) }}</p>
                                @if($submission->submitted_at)
                                    <p class="text-xs text-gray-400 mt-0.5">Dikirim: {{ $submission->submitted_at->format('d M Y H:i') }}</p>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('siswa.submissions.download', $submission) }}"
                           class="inline-flex items-center gap-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-200 text-xs font-semibold px-3 py-2 rounded-lg transition whitespace-nowrap">
                            <i class="fas fa-download text-[10px]"></i> Download
                        </a>
                    </div>
                </div>
            </div>
        @endif

        @if(!$submission || !$submission->submitted_at || now()->lessThan($deadline))
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="h-1 bg-gradient-to-r from-emerald-500 to-teal-400"></div>
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="font-bold text-gray-900"><i class="fas fa-cloud-upload-alt mr-2 text-gray-400"></i>Pengumpulan Tugas</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('siswa.submissions.store', $assignment) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-file-upload mr-1"></i> Unggah File</label>
                            <input type="file" name="file" id="submission-file"
                                class="block w-full px-4 py-2.5 border-2 border-dashed border-gray-200 rounded-xl text-sm cursor-pointer hover:border-emerald-400 hover:bg-emerald-50 transition focus:outline-none" required>
                            <p class="text-xs text-gray-400 mt-1.5">PDF, DOC, DOCX, XLS, XLSX, ZIP — Maks. 20MB</p>
                        </div>
                        @if($submission && $submission->submitted_at && now()->lessThan($deadline))
                            <div class="flex items-start gap-2 bg-blue-50 border border-blue-100 px-4 py-3 rounded-xl">
                                <i class="fas fa-info-circle text-blue-500 mt-0.5 text-xs"></i>
                                <p class="text-xs text-blue-700">Anda dapat mengubah pengumpulan sebelum deadline.</p>
                            </div>
                        @endif
                        <button type="submit"
                            class="w-full inline-flex justify-center items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-6 rounded-xl transition">
                            <i class="fas fa-paper-plane text-sm"></i>
                            {{ $submission && $submission->submitted_at ? 'Perbarui Pengumpulan' : 'Kirim Pengumpulan' }}
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <div class="space-y-5">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden sticky top-4">
            <div class="h-1 bg-gradient-to-r from-amber-400 to-orange-400"></div>
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-bold text-gray-900"><i class="fas fa-info-circle mr-2 text-gray-400"></i>Status Tugas</h2>
            </div>
            <div class="p-5 space-y-4">
                <div class="pb-4 border-b border-gray-100">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-2">Status</p>
                    @if($submission && $submission->submitted_at)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $isLate ? 'bg-orange-50 text-orange-600 border-orange-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200' }}">
                            {{ $isLate ? '⚠️ Terlambat' : '✓ Terkumpul' }}
                        </span>
                    @elseif($isOverdue)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-200">✗ Belum Dikumpul</span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-200">⏳ Draft</span>
                    @endif
                </div>

                <div class="pb-4 border-b border-gray-100">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Deadline</p>
                    @if($deadline)
                        <p class="text-sm font-bold text-gray-900">{{ $deadline->format('d M Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $deadline->format('H:i') }} WIB</p>
                        @if(!now()->greaterThan($deadline))
                            <p class="text-xs font-bold text-amber-500 mt-1">⏳ <span id="countdown">-</span></p>
                        @else
                            <p class="text-xs font-bold text-red-500 mt-1">✗ Sudah terlewat</p>
                        @endif
                    @else
                        <p class="text-xs text-gray-400">—</p>
                    @endif
                </div>

                @if($submission && $submission->submitted_at)
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Waktu Pengumpulan</p>
                        <p class="text-sm font-bold text-gray-900">{{ $submission->submitted_at->format('d M Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $submission->submitted_at->format('H:i') }} WIB</p>
                        @if($isLate && $deadline)
                            @php $lateMin = $deadline->diffInMinutes($submission->submitted_at); $lateH = intdiv($lateMin,60); $lateM = $lateMin%60; @endphp
                            <p class="text-xs font-bold text-amber-600 mt-1">⚠️ Terlambat {{ $lateH > 0 ? $lateH.' jam ' : '' }}{{ $lateM > 0 ? $lateM.' menit' : '' }}</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-bold text-gray-900"><i class="fas fa-book mr-2 text-gray-400"></i>Informasi Kelas</h2>
            </div>
            <div class="divide-y divide-gray-100">
                <div class="px-5 py-3">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-0.5">Mata Pelajaran</p>
                    <p class="text-sm font-bold text-gray-900">{{ $assignment->eClass->subject->name }}</p>
                </div>
                <div class="px-5 py-3">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-0.5">Kelas</p>
                    <p class="text-sm font-bold text-gray-900">{{ $assignment->eClass->name }}</p>
                </div>
                <div class="px-5 py-3">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-0.5">Pengajar</p>
                    <p class="text-sm font-bold text-gray-900">{{ $assignment->eClass->teacher->name }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    const deadlineIso = @json($deadlineIso);
    const el = document.getElementById('countdown');
    if (!deadlineIso || !el) return;
    const deadline = new Date(deadlineIso).getTime();
    function pad2(n) { return String(n).padStart(2,'0'); }
    function tick() {
        const diff = deadline - Date.now();
        if (diff <= 0) { el.textContent = '00:00:00'; return; }
        const d = Math.floor(diff/86400000), h = Math.floor((diff%86400000)/3600000), m = Math.floor((diff%3600000)/60000), s = Math.floor((diff%60000)/1000);
        el.textContent = d > 0 ? `${d} hari ${pad2(h)}:${pad2(m)}:${pad2(s)}` : `${pad2(h)}:${pad2(m)}:${pad2(s)}`;
    }
    tick(); setInterval(tick, 1000);
    const input = document.getElementById('submission-file');
    if (input) input.addEventListener('change', function() { if (this.files[0]?.size > 20*1024*1024) { alert('Maksimal 20 MB.'); this.value=''; } });
})();
</script>
@endpush