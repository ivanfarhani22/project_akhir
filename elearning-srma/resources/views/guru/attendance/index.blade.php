@extends('layouts.guru')

@section('title', 'Presensi - ' . $class->name)
@section('icon', 'fas fa-clipboard-list')

@section('content')
    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3 mb-2">
                <i class="fas fa-clipboard-list text-green-500"></i>
                Presensi
            </h1>
            <p class="text-gray-600 text-sm">{{ $class->name }}</p>
        </div>
        <a href="{{ route('guru.attendance.create', ['class_id' => $class->id]) }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-6 rounded-lg text-sm transition whitespace-nowrap inline-flex items-center gap-2">
            <i class="fas fa-plus"></i> Buka Presensi
        </a>
    </div>

    <!-- SUCCESS ALERT -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-900 p-4 rounded mb-6">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    @if($sessions->count() > 0)
        <div class="space-y-4 mb-8">
            @foreach($sessions as $session)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition p-6">
                    <!-- HEADER -->
                    <div class="flex justify-between items-start mb-4 pb-4 border-b border-gray-200">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $session->attendance_date->format('l, d F Y') }}</p>
                            <p class="text-xs text-gray-600 mt-1">Jam: {{ $session->opened_at }} @if($session->closed_at)- {{ $session->closed_at }}@endif</p>
                        </div>
                        <span class="inline-block rounded-full text-xs font-semibold px-3 py-1
                            @if($session->isOpen()) bg-green-100 text-green-800
                            @elseif($session->isClosed()) bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            @if($session->isOpen())
                                <i class="fas fa-circle mr-1"></i> Terbuka
                            @elseif($session->isClosed())
                                <i class="fas fa-check-circle mr-1"></i> Ditutup
                            @else
                                <i class="fas fa-ban mr-1"></i> Dibatalkan
                            @endif
                        </span>
                    </div>

                    <!-- STATISTICS -->
                    <div class="grid grid-cols-4 gap-3 mb-4 pb-4 border-b border-gray-200">
                        <div class="text-center">
                            <div class="text-xl font-bold text-gray-900">{{ $session->records->count() }}</div>
                            <p class="text-xs text-gray-600 font-medium mt-1">Total</p>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-green-600">{{ $session->records->where('status', 'present')->count() }}</div>
                            <p class="text-xs text-gray-600 font-medium mt-1">Hadir</p>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-red-600">{{ $session->records->where('status', 'absent')->count() }}</div>
                            <p class="text-xs text-gray-600 font-medium mt-1">Tidak Hadir</p>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-blue-600">{{ $session->getAttendancePercentage() }}%</div>
                            <p class="text-xs text-gray-600 font-medium mt-1">Kehadiran</p>
                        </div>
                    </div>

                    <!-- ACTIONS -->
                    <div class="flex gap-2">
                        <a href="{{ route('guru.attendance.show', $session) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-1.5 px-4 rounded text-sm transition inline-flex items-center gap-2">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                        @if($session->isOpen())
                            <form action="{{ route('guru.attendance.close', $session) }}" method="POST" class="inline attendance-form">
                                @csrf
                                <button type="button" class="bg-orange-500 hover:bg-orange-600 text-white font-medium py-1.5 px-4 rounded text-sm transition inline-flex items-center gap-2" onclick="confirmAttendanceClose(event)">
                                    <i class="fas fa-times-circle"></i> Tutup
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($sessions->hasPages())
            <div class="flex justify-center mt-8">
                {{ $sessions->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-12 text-center">
            <i class="fas fa-inbox text-gray-300 text-5xl mb-4 block"></i>
            <p class="text-gray-600 text-base mb-4">Belum ada presensi untuk kelas ini</p>
            <a href="{{ route('guru.attendance.create', ['class_id' => $class->id]) }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-6 rounded-lg text-sm transition inline-flex items-center gap-2">
                <i class="fas fa-plus"></i> Buka Presensi Sekarang
            </a>
        </div>
    @endif
@endsection

@push('scripts')
<script>
function confirmAttendanceClose(event) {
    event.preventDefault();
    const form = event.target.closest('form');
    showConfirmation('Tutup presensi?', 'Konfirmasi Tutup Presensi', function() {
        form.submit();
    });
}
</script>
@endpush
