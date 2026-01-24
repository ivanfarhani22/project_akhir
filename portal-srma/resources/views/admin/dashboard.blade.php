@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Berita</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_news'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
            </div>
        </div>
        <a href="{{ route('admin.news.index') }}" class="text-sm text-blue-600 hover:underline mt-4 inline-block">Kelola Berita →</a>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Pengumuman Aktif</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['active_announcements'] }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
            </div>
        </div>
        <a href="{{ route('admin.announcements.index') }}" class="text-sm text-yellow-600 hover:underline mt-4 inline-block">Kelola Pengumuman →</a>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Agenda Mendatang</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['upcoming_agendas'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <a href="{{ route('admin.agendas.index') }}" class="text-sm text-green-600 hover:underline mt-4 inline-block">Kelola Agenda →</a>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Galeri</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_galleries'] }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <a href="{{ route('admin.galleries.index') }}" class="text-sm text-purple-600 hover:underline mt-4 inline-block">Kelola Galeri →</a>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <!-- Recent News -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">Berita Terbaru</h2>
                <a href="{{ route('admin.news.create') }}" class="text-sm text-primary-600 hover:underline">+ Tambah Baru</a>
            </div>
        </div>
        <div class="p-6">
            @if($recentNews->count() > 0)
            <div class="space-y-4">
                @foreach($recentNews as $news)
                <div class="flex items-start space-x-4">
                    @if($news->thumbnail)
                    <img src="{{ asset('storage/' . $news->thumbnail) }}" alt="{{ $news->title }}" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                    @else
                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-medium text-gray-800 line-clamp-2">{{ $news->title }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $news->created_at->diffForHumans() }}</p>
                    </div>
                    <a href="{{ route('admin.news.edit', $news) }}" class="text-gray-400 hover:text-gray-600 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-500 text-center py-8">Belum ada berita.</p>
            @endif
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h2>
                <a href="{{ route('admin.activity-logs') }}" class="text-sm text-primary-600 hover:underline">Lihat Semua</a>
            </div>
        </div>
        <div class="p-6">
            @if($recentActivities->count() > 0)
            <div class="space-y-4">
                @foreach($recentActivities as $activity)
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                        @switch($activity->action)
                            @case('create')
                                bg-green-100
                                @break
                            @case('update')
                                bg-blue-100
                                @break
                            @case('delete')
                                bg-red-100
                                @break
                            @case('login')
                                bg-purple-100
                                @break
                            @case('logout')
                                bg-gray-100
                                @break
                            @case('export')
                                bg-indigo-100
                                @break
                            @default
                                bg-gray-100
                        @endswitch">
                        @switch($activity->action)
                            @case('create')
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                @break
                            @case('update')
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                                @break
                            @case('delete')
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                @break
                            @case('login')
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                @break
                            @case('logout')
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                @break
                            @case('export')
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                @break
                            @default
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                        @endswitch
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-800">
                            <span class="font-medium">{{ $activity->user->name ?? 'System' }}</span>
                            @switch($activity->action)
                                @case('create')
                                    menambah
                                    @break
                                @case('update')
                                    mengubah
                                    @break
                                @case('delete')
                                    menghapus
                                    @break
                                @case('login')
                                    login ke sistem
                                    @break
                                @case('logout')
                                    logout dari sistem
                                    @break
                                @case('export')
                                    mengexport data
                                    @break
                                @default
                                    melakukan aktivitas
                            @endswitch
                        </p>
                        <p class="text-xs text-gray-500 mt-1">{{ $activity->description }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-500 text-center py-8">Belum ada aktivitas.</p>
            @endif
        </div>
    </div>
</div>

<!-- Upcoming Agendas -->
<div class="bg-white rounded-xl shadow-sm mt-6">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Agenda Mendatang</h2>
            <a href="{{ route('admin.agendas.create') }}" class="text-sm text-primary-600 hover:underline">+ Tambah Baru</a>
        </div>
    </div>
    <div class="p-6">
        @if($upcomingAgendas->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($upcomingAgendas as $agenda)
            <div class="border border-gray-200 rounded-lg p-4 hover:border-primary-300 transition-colors">
                <div class="flex items-start space-x-3">
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex flex-col items-center justify-center flex-shrink-0">
                        <span class="text-sm font-bold text-primary-600">{{ $agenda->start_date->format('d') }}</span>
                        <span class="text-xs text-primary-500 uppercase">{{ $agenda->start_date->translatedFormat('M') }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-medium text-gray-800 line-clamp-2">{{ $agenda->title }}</h3>
                        @if($agenda->location)
                        <p class="text-xs text-gray-500 mt-1 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            {{ $agenda->location }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-sm text-gray-500 text-center py-8">Tidak ada agenda mendatang.</p>
        @endif
    </div>
</div>
@endsection
