@extends('layouts.admin')

@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas')

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Log Aktivitas</h2>
                <p class="text-sm text-gray-500">Riwayat aktivitas admin di sistem</p>
            </div>
            <div class="flex items-center gap-4">
                <!-- Statistics Summary -->
                @if(isset($statistics))
                <div class="hidden lg:flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <span class="text-gray-600">Hari ini:</span>
                        <span class="font-semibold text-gray-800">{{ $statistics['today'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span class="text-gray-600">Bulan ini:</span>
                        <span class="font-semibold text-gray-800">{{ $statistics['this_month'] ?? 0 }}</span>
                    </div>
                </div>
                @endif
                <div class="text-sm text-gray-500">
                    Total: <span class="font-semibold text-gray-800">{{ $logs->total() }}</span> aktivitas
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filter & Search -->
    <div class="p-6 border-b border-gray-100 bg-gray-50">
        <form action="{{ route('admin.activity-logs') }}" method="GET">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Cari Deskripsi</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari aktivitas..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                
                <!-- Filter Action -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Aksi</label>
                    <select name="action" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Aksi</option>
                        <option value="create" {{ request('action') === 'create' ? 'selected' : '' }}>Create</option>
                        <option value="update" {{ request('action') === 'update' ? 'selected' : '' }}>Update</option>
                        <option value="delete" {{ request('action') === 'delete' ? 'selected' : '' }}>Delete</option>
                        <option value="login" {{ request('action') === 'login' ? 'selected' : '' }}>Login</option>
                        <option value="logout" {{ request('action') === 'logout' ? 'selected' : '' }}>Logout</option>
                        <option value="export" {{ request('action') === 'export' ? 'selected' : '' }}>Export</option>
                    </select>
                </div>
                
                <!-- Filter Type -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tipe</label>
                    <select name="model_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Tipe</option>
                        <option value="News" {{ request('model_type') === 'News' ? 'selected' : '' }}>Berita</option>
                        <option value="Announcement" {{ request('model_type') === 'Announcement' ? 'selected' : '' }}>Pengumuman</option>
                        <option value="Agenda" {{ request('model_type') === 'Agenda' ? 'selected' : '' }}>Agenda</option>
                        <option value="Gallery" {{ request('model_type') === 'Gallery' ? 'selected' : '' }}>Galeri</option>
                        <option value="Banner" {{ request('model_type') === 'Banner' ? 'selected' : '' }}>Banner</option>
                        <option value="Profile" {{ request('model_type') === 'Profile' ? 'selected' : '' }}>Profil</option>
                        <option value="Teacher" {{ request('model_type') === 'Teacher' ? 'selected' : '' }}>Guru</option>
                        <option value="Staff" {{ request('model_type') === 'Staff' ? 'selected' : '' }}>Tenaga Kependidikan</option>
                        <option value="Facility" {{ request('model_type') === 'Facility' ? 'selected' : '' }}>Fasilitas</option>
                        <option value="StudentData" {{ request('model_type') === 'StudentData' ? 'selected' : '' }}>Data Siswa</option>
                        <option value="StudentDistribution" {{ request('model_type') === 'StudentDistribution' ? 'selected' : '' }}>Persebaran Siswa</option>
                        <option value="Setting" {{ request('model_type') === 'Setting' ? 'selected' : '' }}>Setting</option>
                    </select>
                </div>
                
                <!-- Filter Date From -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                
                <!-- Filter Date To -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                
                <!-- Per Page -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tampilkan</label>
                    <select name="per_page" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        <option value="all" {{ request('per_page') === 'all' ? 'selected' : '' }}>Semua</option>
                    </select>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-2 mt-4">
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </button>
                @if(request()->hasAny(['search', 'action', 'model_type', 'date', 'date_from', 'date_to', 'per_page']))
                <a href="{{ route('admin.activity-logs') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reset
                </a>
                @endif
                
                <!-- Export Buttons -->
                <div class="ml-auto flex gap-2">
                    <a href="{{ route('admin.activity-logs.export', request()->query()) }}" 
                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export CSV
                    </a>
                    <a href="{{ route('admin.activity-logs.export-excel', request()->query()) }}" 
                       class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export Excel
                    </a>
                </div>
            </div>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($logs as $index => $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $logs->firstItem() + $index }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                        <div>{{ $log->created_at->format('d M Y') }}</div>
                        <div class="text-xs text-gray-400">{{ $log->created_at->format('H:i:s') }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-800">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center mr-2">
                                <span class="text-xs font-medium text-primary-600">
                                    {{ $log->user ? strtoupper(substr($log->user->name, 0, 2)) : 'SY' }}
                                </span>
                            </div>
                            <span>{{ $log->user->name ?? 'System' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @switch($log->action)
                                @case('create')
                                    bg-green-100 text-green-800
                                    @break
                                @case('update')
                                    bg-blue-100 text-blue-800
                                    @break
                                @case('delete')
                                    bg-red-100 text-red-800
                                    @break
                                @case('login')
                                    bg-purple-100 text-purple-800
                                    @break
                                @case('logout')
                                    bg-gray-100 text-gray-800
                                    @break
                                @case('export')
                                    bg-indigo-100 text-indigo-800
                                    @break
                                @default
                                    bg-gray-100 text-gray-800
                            @endswitch">
                            @switch($log->action)
                                @case('create')
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    @break
                                @case('update')
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    @break
                                @case('delete')
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    @break
                                @case('login')
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                    </svg>
                                    @break
                                @case('logout')
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    @break
                                @case('export')
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    @break
                            @endswitch
                            {{ ucfirst($log->action) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($log->model_type)
                            @php
                                $modelName = class_basename($log->model_type);
                                $typeLabels = [
                                    'News' => ['label' => 'Berita', 'color' => 'bg-yellow-100 text-yellow-800'],
                                    'Announcement' => ['label' => 'Pengumuman', 'color' => 'bg-orange-100 text-orange-800'],
                                    'Agenda' => ['label' => 'Agenda', 'color' => 'bg-cyan-100 text-cyan-800'],
                                    'Gallery' => ['label' => 'Galeri', 'color' => 'bg-pink-100 text-pink-800'],
                                    'GalleryCategory' => ['label' => 'Kategori Galeri', 'color' => 'bg-pink-100 text-pink-800'],
                                    'Banner' => ['label' => 'Banner', 'color' => 'bg-indigo-100 text-indigo-800'],
                                    'Profile' => ['label' => 'Profil', 'color' => 'bg-teal-100 text-teal-800'],
                                    'Contact' => ['label' => 'Kontak', 'color' => 'bg-teal-100 text-teal-800'],
                                    'Setting' => ['label' => 'Setting', 'color' => 'bg-gray-100 text-gray-800'],
                                    'User' => ['label' => 'User', 'color' => 'bg-violet-100 text-violet-800'],
                                    'Teacher' => ['label' => 'Guru', 'color' => 'bg-blue-100 text-blue-800'],
                                    'Staff' => ['label' => 'Tenaga Kependidikan', 'color' => 'bg-emerald-100 text-emerald-800'],
                                    'Facility' => ['label' => 'Fasilitas', 'color' => 'bg-amber-100 text-amber-800'],
                                    'StudentData' => ['label' => 'Data Siswa', 'color' => 'bg-purple-100 text-purple-800'],
                                    'StudentDistribution' => ['label' => 'Persebaran Siswa', 'color' => 'bg-rose-100 text-rose-800'],
                                ];
                                $type = $typeLabels[$modelName] ?? ['label' => $modelName, 'color' => 'bg-gray-100 text-gray-800'];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $type['color'] }}">
                                {{ $type['label'] }}
                            </span>
                        @else
                            <span class="text-sm text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                        <div class="truncate" title="{{ $log->description }}">
                            {{ $log->description ?? '-' }}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        @if(request()->hasAny(['search', 'action', 'model_type', 'date']))
                            <p>Tidak ada aktivitas ditemukan dengan filter tersebut.</p>
                            <a href="{{ route('admin.activity-logs') }}" class="text-primary-600 hover:underline mt-2 inline-block">Reset Filter</a>
                        @else
                            <p>Belum ada aktivitas tercatat.</p>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($logs->hasPages())
    <div class="p-6 border-t border-gray-100">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-600">
                Menampilkan {{ $logs->firstItem() }} - {{ $logs->lastItem() }} dari {{ $logs->total() }} aktivitas
            </div>
            <div>
                {{ $logs->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
