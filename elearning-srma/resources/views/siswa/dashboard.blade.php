@extends('layouts.siswa')
@section('title', 'Beranda')
@section('icon', 'fas fa-home')

@section('content')

@php
    $myClasses = auth()->user()->classes;
    $upcomingAssignments = \App\Models\Assignment::whereIn('e_class_id', $myClasses->pluck('id'))->where('deadline','>=',now())->orderBy('deadline')->count();
    $totalAssignments = \App\Models\Assignment::whereIn('e_class_id', $myClasses->pluck('id'))->count();
    $submittedAssignments = \App\Models\Submission::where('student_id', auth()->id())->whereNotNull('submitted_at')->count();
    $averageGrade = auth()->user()->grades()->avg('score');
@endphp

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Selamat datang,</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-home text-blue-500 mr-2"></i>Beranda</h1>
    <p class="text-sm text-gray-500 mt-1">Lihat ringkasan tugas, jadwal, dan pengumuman Anda</p>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @php
        $stats = [
            ['label'=>'Mata Pelajaran','value'=>$myClasses->count(),'icon'=>'fa-book','bg'=>'bg-blue-50','color'=>'text-blue-500','border'=>'bg-blue-400'],
            ['label'=>'Tugas Mendatang','value'=>$upcomingAssignments,'icon'=>'fa-clock','bg'=>'bg-amber-50','color'=>'text-amber-500','border'=>'bg-amber-400'],
            ['label'=>'Terkumpul','value'=>$submittedAssignments.'/'.$totalAssignments,'icon'=>'fa-check-circle','bg'=>'bg-emerald-50','color'=>'text-emerald-500','border'=>'bg-emerald-400'],
            ['label'=>'Nilai Rata-rata','value'=>$averageGrade ? number_format($averageGrade,1) : '—','icon'=>'fa-star','bg'=>'bg-purple-50','color'=>'text-purple-500','border'=>'bg-purple-400'],
        ];
    @endphp
    @foreach($stats as $s)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="h-1 {{ $s['border'] }}"></div>
            <div class="p-5 flex justify-between items-start">
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">{{ $s['label'] }}</p>
                    <p class="text-3xl font-extrabold text-gray-900">{{ $s['value'] }}</p>
                </div>
                <div class="w-10 h-10 {{ $s['bg'] }} rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas {{ $s['icon'] }} {{ $s['color'] }}"></i>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-amber-400 to-orange-400"></div>
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-bold text-gray-900"><i class="fas fa-hourglass-end mr-2 text-amber-400"></i>Tugas Mendatang</h2>
        </div>
        <div class="p-5">
            @php
                $upcomingTasks = \App\Models\Assignment::whereIn('e_class_id', $myClasses->pluck('id'))->where('deadline','>=',now())->orderBy('deadline')->limit(5)->get();
            @endphp
            @if($upcomingTasks->count() > 0)
                <div class="space-y-3">
                    @foreach($upcomingTasks as $task)
                        @php $sub = \App\Models\Submission::where('student_id',auth()->id())->where('assignment_id',$task->id)->first(); @endphp
                        <div class="flex justify-between items-start gap-4 py-3 border-b border-gray-100 last:border-b-0">
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 text-sm truncate">{{ $task->title }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $task->eClass->subject->name }}</p>
                                <p class="text-xs text-amber-500 font-semibold mt-1"><i class="fas fa-calendar-alt mr-1"></i>{{ $task->deadline->format('d M, H:i') }}</p>
                            </div>
                            <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full border whitespace-nowrap flex-shrink-0 {{ $sub && $sub->submitted_at ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-600 border-red-200' }}">
                                {{ $sub && $sub->submitted_at ? 'Terkumpul' : 'Belum' }}
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('siswa.assignments.index') }}" class="text-xs font-semibold text-blue-500 hover:text-blue-700 transition">
                        Lihat Semua Tugas <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <i class="fas fa-check-circle text-gray-200 text-4xl mb-3"></i>
                    <p class="text-xs text-gray-400">Tidak ada tugas mendatang.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="space-y-5">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-blue-400 to-indigo-400"></div>
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-bold text-gray-900"><i class="fas fa-calendar-day mr-2 text-blue-400"></i>Jadwal Hari Ini</h2>
            </div>
            <div class="p-5">
                @php
                    $dayOfWeek = strtolower(\Carbon\Carbon::now()->format('l'));
                    $todaySchedules = $myClasses->where('day_of_week', $dayOfWeek)->sortBy('start_time');
                @endphp
                @if($todaySchedules->count() > 0)
                    <div class="space-y-3">
                        @foreach($todaySchedules as $schedule)
                            <div class="flex justify-between items-start gap-3 py-2.5 border-b border-gray-100 last:border-b-0">
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $schedule->subject->name }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $schedule->teacher->name }}</p>
                                </div>
                                <span class="inline-flex items-center text-xs font-bold bg-blue-50 text-blue-600 border border-blue-200 px-2.5 py-1 rounded-lg whitespace-nowrap">
                                    {{ $schedule->start_time ?? 'TBA' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <i class="fas fa-calendar text-gray-200 text-3xl mb-2"></i>
                        <p class="text-xs text-gray-400">Tidak ada jadwal hari ini.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-bold text-gray-900"><i class="fas fa-rocket mr-2 text-emerald-500"></i>Akses Cepat</h2>
            </div>
            <div class="p-3 space-y-2">
                @php
                    $quickLinks = [
                        ['href'=>route('siswa.subjects.index'),'icon'=>'fa-book','bg'=>'bg-emerald-50','color'=>'text-emerald-500','label'=>'Mata Pelajaran','desc'=>'Lihat semua kelas Anda'],
                        ['href'=>route('siswa.assignments.index'),'icon'=>'fa-tasks','bg'=>'bg-amber-50','color'=>'text-amber-500','label'=>'Semua Tugas','desc'=>'Kelola tugas Anda'],
                        ['href'=>route('siswa.schedule.index'),'icon'=>'fa-calendar-alt','bg'=>'bg-blue-50','color'=>'text-blue-500','label'=>'Jadwal Lengkap','desc'=>'Lihat jadwal mingguan'],
                    ];
                @endphp
                @foreach($quickLinks as $l)
                    <a href="{{ $l['href'] }}"
                       class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition group">
                        <div class="w-9 h-9 {{ $l['bg'] }} rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas {{ $l['icon'] }} {{ $l['color'] }} text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $l['label'] }}</p>
                            <p class="text-xs text-gray-400">{{ $l['desc'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl p-5 sm:p-6 flex items-start gap-4">
    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
        <i class="fas fa-lightbulb text-yellow-300"></i>
    </div>
    <div>
        <h3 class="font-bold text-white text-sm mb-1">Tips Belajar</h3>
        <p class="text-blue-100 text-xs leading-relaxed">Jangan lupa untuk selalu mengecek jadwal pelajaran dan mengumpulkan tugas sebelum deadline. Semangat belajar! 💪</p>
    </div>
</div>

@endsection