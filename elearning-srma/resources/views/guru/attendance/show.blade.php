@extends('layouts.guru')

@section('title', 'Detail Presensi - ' . $session->classSubject->eClass->name . ' - ' . $session->classSubject->subject->name)
@section('icon', 'fas fa-clipboard-list')

@section('content')
<!-- SESSION HEADER with Gradient -->
<div class="bg-gradient-to-r from-green-500 to-green-700 text-white rounded-lg p-6 sm:p-8 mb-8 shadow-lg">
    <h1 class="text-2xl sm:text-3xl font-bold mb-3">{{ $session->classSubject->eClass->name }} - {{ $session->classSubject->subject->name }}</h1>
    <div class="space-y-1 text-green-100 text-sm sm:text-base">
        <p><i class="fas fa-calendar mr-2"></i> {{ $session->attendance_date->format('l, d F Y') }}</p>
        <p><i class="fas fa-clock mr-2"></i> Dibuka: {{ $session->opened_at }}</p>
    </div>
    
    <!-- STATUS BADGE -->
    <div class="mt-4 pt-4 border-t border-green-400">
        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full font-semibold text-sm {{ $session->isOpen() ? 'bg-green-400 text-green-900' : ($session->isClosed() ? 'bg-red-400 text-red-900' : 'bg-gray-400 text-gray-900') }}">
            @if($session->isOpen())
                <i class="fas fa-circle"></i> Terbuka
            @elseif($session->isClosed())
                <i class="fas fa-check-circle"></i> Ditutup
            @else
                <i class="fas fa-ban"></i> Dibatalkan
            @endif
        </span>
    </div>

    <!-- STATS GRID -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6">
        <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
            <div class="text-2xl sm:text-3xl font-bold">{{ $session->records->count() }}</div>
            <div class="text-xs sm:text-sm mt-2 opacity-90">Total Siswa</div>
        </div>
        <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
            <div class="text-2xl sm:text-3xl font-bold text-green-200">{{ $session->records->where('status', 'present')->count() }}</div>
            <div class="text-xs sm:text-sm mt-2 opacity-90">Hadir</div>
        </div>
        <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
            <div class="text-2xl sm:text-3xl font-bold text-red-200">{{ $session->records->where('status', 'absent')->count() }}</div>
            <div class="text-xs sm:text-sm mt-2 opacity-90">Tidak Hadir</div>
        </div>
        <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
            <div class="text-2xl sm:text-3xl font-bold">{{ $session->getAttendancePercentage() }}%</div>
            <div class="text-xs sm:text-sm mt-2 opacity-90">Kehadiran</div>
        </div>
    </div>
</div>

<!-- ACTION BUTTONS -->
@if($session->isOpen())
    <div class="flex flex-col sm:flex-row gap-3 mb-8">
        <form action="{{ route('guru.attendance.close', $session) }}" method="POST" class="attendance-form flex-1">
            @csrf
            <button type="button" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-lg transition inline-flex items-center justify-center gap-2" onclick="confirmAttendanceAction(event, 'close')">
                <i class="fas fa-times-circle"></i> Tutup Presensi
            </button>
        </form>
        <form action="{{ route('guru.attendance.cancel', $session) }}" method="POST" class="attendance-form flex-1">
            @csrf
            <button type="button" class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition inline-flex items-center justify-center gap-2" onclick="confirmAttendanceAction(event, 'cancel')">
                <i class="fas fa-ban"></i> Batalkan Presensi
            </button>
        </form>
    </div>
@endif

<!-- ATTENDANCE TABLE -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h2 class="font-bold text-gray-900 text-lg flex items-center gap-2">
            <i class="fas fa-users text-green-500"></i>
            Daftar Absensi Siswa
        </h2>
    </div>

    @if($session->records->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">No.</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Nama Siswa</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Waktu Hadir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($session->records->sortBy('student.name') as $record)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 font-medium text-gray-700">{{ $record->student->name }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusConfig = [
                                        'present' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Hadir'],
                                        'absent' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Tidak Hadir'],
                                        'late' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Terlambat'],
                                        'excused' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Izin'],
                                    ];
                                    $config = $statusConfig[strtolower($record->status)] ?? $statusConfig['absent'];
                                @endphp
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
                                    @if(strtolower($record->status) === 'present')
                                        <i class="fas fa-check-circle"></i>
                                    @elseif(strtolower($record->status) === 'absent')
                                        <i class="fas fa-times-circle"></i>
                                    @elseif(strtolower($record->status) === 'late')
                                        <i class="fas fa-hourglass-end"></i>
                                    @else
                                        <i class="fas fa-clipboard"></i>
                                    @endif
                                    {{ $config['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 text-sm">
                                @if($record->checked_in_at)
                                    <span class="font-medium">{{ $record->checked_in_at->format('H:i') }}</span>
                                @else
                                    <span class="text-gray-400 italic">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12 px-6">
            <i class="fas fa-inbox text-gray-300 text-5xl mb-4 block"></i>
            <p class="text-gray-600 text-base">Belum ada data presensi</p>
        </div>
    @endif
</div>

<!-- BACK BUTTON -->
<div class="mt-8">
    <a href="{{ route('guru.attendance.index') }}" class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-900 font-medium py-2 px-6 rounded-lg transition">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

@endsection

@push('scripts')
<script>
function confirmAttendanceAction(event, action) {
    event.preventDefault();
    const form = event.target.closest('form');
    
    let message, title;
    if (action === 'close') {
        message = 'Tutup presensi? Siswa tidak bisa absensi lagi.';
        title = 'Konfirmasi Tutup Presensi';
    } else if (action === 'cancel') {
        message = 'Batalkan presensi? Data akan dihapus dan hanya admin bisa restore.';
        title = 'Konfirmasi Batalkan Presensi';
    }
    
    showConfirmation(message, title, function() {
        form.submit();
    });
}
</script>
@endpush
