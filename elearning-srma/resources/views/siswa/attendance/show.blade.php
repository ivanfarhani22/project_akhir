@extends('layouts.siswa')
@section('title', 'Presensi - ' . $classSubject->subject->name)
@section('icon', 'fas fa-clipboard-list')

@section('content')

<div class="mb-8">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1"><i class="fas fa-clipboard-list mr-1"></i> Siswa / Presensi</p>
    <h1 class="text-2xl font-extrabold text-gray-900"><i class="fas fa-clipboard-list text-purple-500 mr-2"></i>Presensi</h1>
    <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full mt-1">
        {{ $classSubject->subject->name }} • {{ $classSubject->eClass->name }}
    </span>
</div>

@if(session('success'))
    <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
        <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
    </div>
@endif

@if($session)
    <div class="bg-white rounded-2xl border-2 border-purple-200 overflow-hidden shadow-sm mb-6">
        <div class="h-1 bg-gradient-to-r from-purple-500 to-blue-500"></div>
        <div class="p-6">
            <h2 class="text-lg font-extrabold text-gray-900 mb-1">Presensi Hari Ini</h2>
            <p class="text-sm text-gray-500 mb-4">{{ $session->attendance_date->format('l, d F Y') }}</p>

            <div class="grid grid-cols-2 gap-3 mb-5">
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Dibuka</p>
                    <p class="text-sm font-bold text-gray-900">{{ $session->opened_at }}</p>
                </div>
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Status Anda</p>
                    @if($hasAttended)
                        <span class="inline-flex items-center gap-1.5 text-sm font-bold text-emerald-600"><i class="fas fa-check-circle"></i> Sudah Hadir</span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-sm font-bold text-amber-500"><i class="fas fa-clock"></i> Belum Hadir</span>
                    @endif
                </div>
            </div>

            @if(!$hasAttended)
                <form action="{{ route('siswa.attendance.store', $session) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full inline-flex justify-center items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-6 rounded-xl transition shadow-md hover:shadow-lg">
                        <i class="fas fa-check"></i> Lakukan Absensi Sekarang
                    </button>
                </form>
            @else
                <div class="flex flex-col items-center justify-center bg-emerald-50 border border-emerald-200 rounded-xl py-5">
                    <i class="fas fa-check-circle text-emerald-500 text-3xl mb-2"></i>
                    <p class="text-emerald-700 font-bold text-sm">Absensi Anda Tercatat</p>
                </div>
            @endif
        </div>
    </div>
@else
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm mb-6">
        <div class="flex flex-col items-center justify-center py-12 text-center px-6">
            <div class="w-16 h-16 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-inbox text-2xl text-gray-300"></i>
            </div>
            <p class="text-gray-700 font-semibold text-sm mb-1">Belum Ada Presensi Hari Ini</p>
            <p class="text-xs text-gray-400">Guru akan membuka presensi saat pelajaran dimulai</p>
        </div>
    </div>
@endif

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-bold text-gray-900"><i class="fas fa-history mr-2 text-gray-400"></i>Riwayat Presensi</h2>
    </div>
    <div class="p-5">
        @php
            $allSessions = $classSubject->attendanceSessions()->where('status','!=','cancelled')->with('records')->orderBy('attendance_date','desc')->take(10)->get();
            $statusMap = ['present'=>['bg-emerald-50 text-emerald-700 border-emerald-200','Hadir'],'absent'=>['bg-red-50 text-red-600 border-red-200','Tidak Hadir'],'late'=>['bg-yellow-50 text-yellow-700 border-yellow-200','Terlambat'],'excused'=>['bg-blue-50 text-blue-600 border-blue-200','Izin']];
        @endphp
        @if($allSessions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($allSessions as $sess)
                            @php $record = $sess->records->where('student_id', auth()->id())->first(); @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $sess->attendance_date->format('d M Y') }}</td>
                                <td class="px-4 py-3">
                                    @if($record)
                                        @php [$sc, $sl] = $statusMap[$record->status] ?? ['bg-gray-100 text-gray-500 border-gray-200','—']; @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $sc }}">{{ $sl }}</span>
                                    @else
                                        <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-400">{{ $record?->checked_in_at?->format('H:i') ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-10 text-center">
                <i class="fas fa-inbox text-gray-200 text-3xl mb-2"></i>
                <p class="text-xs text-gray-400">Belum ada riwayat presensi.</p>
            </div>
        @endif
    </div>
</div>
@endsection