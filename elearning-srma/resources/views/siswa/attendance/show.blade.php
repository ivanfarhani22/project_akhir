@extends('layouts.siswa')

@section('title', 'Presensi - ' . $classSubject->subject->name)
@section('icon', 'fas fa-clipboard-list')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
        <i class="fas fa-clipboard-list text-purple-500"></i>
        Presensi
    </h1>
    <p class="text-gray-600 text-sm mt-1">{{ $classSubject->subject->name }} • {{ $classSubject->eClass->name }}</p>
</div>

@if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded mb-6">
        <p class="text-green-800 font-semibold">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </p>
    </div>
@endif

@if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-6">
        <p class="text-red-800 font-semibold">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        </p>
    </div>
@endif

@if($session)
    <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg shadow-md p-8 mb-8">
        <h2 class="text-2xl font-bold mb-2">Presensi Hari Ini</h2>
        <p class="text-purple-100 mb-4">{{ $session->attendance_date->format('l, d F Y') }}</p>
        
        <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-full text-sm font-semibold mb-6">
            <i class="fas fa-circle"></i> Presensi Terbuka
        </span>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6 mb-6">
            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg border border-white/20">
                <p class="text-purple-100 text-sm font-semibold mb-1 uppercase">Waktu Dibuka</p>
                <p class="text-white font-bold text-lg">{{ $session->opened_at }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg border border-white/20">
                <p class="text-purple-100 text-sm font-semibold mb-1 uppercase">Status Anda</p>
                <p class="text-white font-bold text-lg">
                    @if($hasAttended)
                        <span class="inline-flex items-center gap-1 text-green-300"><i class="fas fa-check-circle"></i> Sudah Hadir</span>
                    @else
                        <span class="inline-flex items-center gap-1 text-amber-300"><i class="fas fa-clock"></i> Belum Hadir</span>
                    @endif
                </p>
            </div>
        </div>

        @if(!$hasAttended)
            <form action="{{ route('siswa.attendance.store', $session) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition">
                    <i class="fas fa-check"></i> Lakukan Absensi Sekarang
                </button>
            </form>
        @else
            <div class="bg-green-500/20 p-4 rounded-lg text-center border border-green-500/30">
                <i class="fas fa-check-circle text-green-400 text-3xl mb-2 block"></i>
                <p class="text-green-700 font-bold">Absensi Anda Tercatat</p>
            </div>
        @endif
    </div>
@else
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-12 text-center">
            <i class="fas fa-inbox text-gray-300 text-5xl mb-4 block"></i>
            <p class="text-gray-600 text-base font-semibold mb-2">Belum Ada Presensi Hari Ini</p>
            <p class="text-gray-500 text-sm">Guru akan membuka presensi saat pelajaran dimulai</p>
        </div>
    </div>
@endif

<!-- ATTENDANCE HISTORY -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h2 class="font-bold text-gray-900 flex items-center gap-2">
            <i class="fas fa-history text-blue-500"></i>
            Riwayat Presensi
        </h2>
    </div>
    <div class="p-6">
        @php
            $allSessions = $classSubject->attendanceSessions()
                ->where('status', '!=', 'cancelled')
                ->with('records')
                ->orderBy('attendance_date', 'desc')
                ->take(10)
                ->get();
        @endphp

        @if($allSessions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="px-4 py-3 text-left text-gray-600 font-semibold text-xs uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-gray-600 font-semibold text-xs uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-gray-600 font-semibold text-xs uppercase">Waktu Hadir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($allSessions as $sess)
                            @php
                                $record = $sess->records->where('student_id', auth()->id())->first();
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-gray-900 font-medium">
                                    {{ $sess->attendance_date->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($record)
                                        @php
                                            $statusConfig = [
                                                'present' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Hadir'],
                                                'absent' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Tidak Hadir'],
                                                'late' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Terlambat'],
                                                'excused' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Izin'],
                                            ];
                                            $config = $statusConfig[$record->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Unknown'];
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
                                            {{ $config['label'] }}
                                        </span>
                                    @else
                                        <span class="text-gray-500 text-sm">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600 text-sm">
                                    @if($record && $record->checked_in_at)
                                        {{ $record->checked_in_at->format('H:i') }}
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-gray-500 py-8">Belum ada riwayat presensi</p>
        @endif
    </div>
</div>

@endsection
