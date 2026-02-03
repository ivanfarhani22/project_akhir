<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - SRMA 25 Lamongan</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Tailwind CSS with Config -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <style type="text/tailwindcss">
        @layer utilities {
            .text-primary-50 { color: #fef2f2; }
            .text-primary-100 { color: #fee2e2; }
            .text-primary-500 { color: #ef4444; }
            .text-primary-600 { color: #dc2626; }
            .text-primary-700 { color: #b91c1c; }
            .bg-primary-50 { background-color: #fef2f2; }
            .bg-primary-100 { background-color: #fee2e2; }
            .bg-primary-500 { background-color: #ef4444; }
            .bg-primary-600 { background-color: #dc2626; }
            .bg-primary-700 { background-color: #b91c1c; }
            .hover\:bg-primary-700:hover { background-color: #b91c1c; }
            .focus\:ring-primary-500:focus { --tw-ring-color: #ef4444; }
        }
    </style>
    
    <!-- Prevent sidebar flash - runs before Alpine.js -->
    <script>
        (function() {
            // Check localStorage immediately and set CSS variable
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        })();
    </script>
    <style>
        /* Apply collapsed state immediately via CSS when class is present */
        @media (min-width: 1024px) {
            html.sidebar-collapsed aside {
                width: 4rem !important; /* lg:w-16 = 64px = 4rem */
            }
            html.sidebar-collapsed aside .nav-link {
                justify-content: center;
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            html.sidebar-collapsed aside .nav-link span,
            html.sidebar-collapsed aside .nav-link svg.w-4,
            html.sidebar-collapsed aside p.text-xs {
                display: none !important;
            }
            html.sidebar-collapsed aside .nav-link svg.w-5 {
                margin-right: 0 !important;
            }
        }
    </style>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Hide sidebar elements until Alpine loads to prevent flash */
        [x-cloak-sidebar] { 
            display: none !important; 
        }
        
        /* Scrollbar sidebar */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 2px; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: #6b7280; }
        
        /* Scrollbar main content */
        .main-scroll::-webkit-scrollbar { width: 6px; }
        .main-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
        .main-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .main-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Independent scroll areas - prevent body scroll */
        html, body {
            height: 100%;
            overflow: hidden;
        }
        
        /* Touch scroll support for mobile */
        .sidebar-scroll, .main-scroll {
            -webkit-overflow-scrolling: touch;
            overscroll-behavior: contain;
        }
        
        /* Mobile sidebar - ensure all content visible */
        @media (max-width: 1023px) {
            aside {
                max-height: 100vh;
                max-height: 100dvh; /* Dynamic viewport height for mobile browsers */
            }
        }
        
        /* Tombol CRUD */
        .btn-primary { background-color: #dc2626 !important; color: white !important; }
        .btn-primary:hover { background-color: #b91c1c !important; }
        .bg-primary-600 { background-color: #dc2626 !important; }
        .bg-primary-600:hover, .hover\:bg-primary-700:hover { background-color: #b91c1c !important; }
        
        /* Animated dots for loading */
        .dots-loading::after {
            content: '';
            animation: dots 1.5s steps(4, end) infinite;
        }
        @keyframes dots {
            0%, 20% { content: ''; }
            40% { content: '.'; }
            60% { content: '..'; }
            80%, 100% { content: '...'; }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100 overflow-hidden" x-data="{ 
    sidebarOpen: false, 
    sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true', 
    profilOpen: localStorage.getItem('sidebarCollapsed') !== 'true' && {{ request()->routeIs('admin.teachers.*', 'admin.staff.*', 'admin.facilities.*', 'admin.student-data.*', 'admin.student-distribution.*', 'admin.settings.profile*') ? 'true' : 'false' }},
    profilDropdown: false,
    toggleCollapse() {
        this.sidebarCollapsed = !this.sidebarCollapsed;
        localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
        // Sync with CSS class
        if (this.sidebarCollapsed) {
            document.documentElement.classList.add('sidebar-collapsed');
            this.profilOpen = false;
        } else {
            document.documentElement.classList.remove('sidebar-collapsed');
        }
    }
}">
    <div class="h-screen flex overflow-hidden">
        <!-- Sidebar Overlay -->
        <div x-show="sidebarOpen" x-cloak
             class="fixed inset-0 z-40 bg-black/50 lg:hidden"
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>
        
        <!-- Sidebar -->
        <aside :class="[
                   sidebarOpen ? 'translate-x-0' : '-translate-x-full',
                   sidebarCollapsed ? 'lg:w-16' : 'lg:w-64'
               ]"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-800 transform transition-all duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col max-h-screen overflow-hidden lg:h-screen lg:flex-shrink-0">
            <!-- Logo -->
            <div class="flex items-center justify-between h-16 bg-gray-900 px-4 flex-shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3" :class="{ 'justify-center w-full space-x-0': sidebarCollapsed }">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-9 h-9 object-contain bg-white rounded-lg p-1 flex-shrink-0">
                    <span x-show="!sidebarCollapsed" class="text-white font-bold text-sm">Admin Panel</span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 min-h-0 overflow-y-auto sidebar-scroll py-4 px-3">
                <div class="space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="nav-link flex items-center px-3 py-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}" 
                       :class="{ 'justify-center px-2': sidebarCollapsed }"
                       title="Dashboard">
                        <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span x-show="!sidebarCollapsed">Dashboard</span>
                    </a>
                    
                    <p x-show="!sidebarCollapsed" class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Konten</p>
                    <div x-show="sidebarCollapsed" class="border-t border-gray-700 my-2 mx-2"></div>
                    
                    <!-- Berita -->
                    <a href="{{ route('admin.news.index') }}" 
                       class="nav-link flex items-center px-3 py-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.news.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
                       :class="{ 'justify-center px-2': sidebarCollapsed }"
                       title="Berita">
                        <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        <span x-show="!sidebarCollapsed">Berita</span>
                    </a>
                    
                    <!-- Pengumuman -->
                    <a href="{{ route('admin.announcements.index') }}" 
                       class="nav-link flex items-center px-3 py-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.announcements.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
                       :class="{ 'justify-center px-2': sidebarCollapsed }"
                       title="Pengumuman">
                        <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        <span x-show="!sidebarCollapsed">Pengumuman</span>
                    </a>
                    
                    <!-- Agenda -->
                    <a href="{{ route('admin.agendas.index') }}" 
                       class="nav-link flex items-center px-3 py-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.agendas.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
                       :class="{ 'justify-center px-2': sidebarCollapsed }"
                       title="Agenda">
                        <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span x-show="!sidebarCollapsed">Agenda</span>
                    </a>
                    
                    <!-- Galeri -->
                    <a href="{{ route('admin.galleries.index') }}" 
                       class="nav-link flex items-center px-3 py-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.galleries.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
                       :class="{ 'justify-center px-2': sidebarCollapsed }"
                       title="Galeri">
                        <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span x-show="!sidebarCollapsed">Galeri</span>
                    </a>
                    
                    <!-- Banner -->
                    <a href="{{ route('admin.banners.index') }}" 
                       class="nav-link flex items-center px-3 py-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.banners.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
                       :class="{ 'justify-center px-2': sidebarCollapsed }"
                       title="Banner">
                        <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span x-show="!sidebarCollapsed">Banner</span>
                    </a>
                    
                    <p x-show="!sidebarCollapsed" class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Profil Sekolah</p>
                    <div x-show="sidebarCollapsed" class="border-t border-gray-700 my-2 mx-2"></div>
                    
                    <!-- Profil Dropdown -->
                    <div class="relative" id="profil-dropdown-container">
                        <!-- Button untuk toggle dropdown -->
                        <button @click="profilOpen = !profilOpen; $nextTick(() => { if(sidebarCollapsed && profilOpen) { const rect = $el.getBoundingClientRect(); window.flyoutTop = rect.top } })" 
                                id="profil-btn"
                                class="nav-link w-full flex items-center px-3 py-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.teachers.*', 'admin.staff.*', 'admin.facilities.*', 'admin.student-data.*', 'admin.student-distribution.*', 'admin.settings.profile*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
                                :class="{ 'justify-center px-2': sidebarCollapsed }"
                                title="Data Profil">
                            <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span x-show="!sidebarCollapsed" class="flex-1 text-left">Data Profil</span>
                            <svg x-show="!sidebarCollapsed" class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': profilOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <!-- Submenu - Normal Accordion (saat sidebar expanded) -->
                        <div x-show="profilOpen && !sidebarCollapsed" 
                             x-collapse
                             class="mt-1 ml-4 pl-4 border-l border-gray-700 space-y-1">
                            <a href="{{ route('admin.settings.profile') }}" 
                               class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.settings.profile*') ? 'bg-primary-600 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                                Profil Sekolah
                            </a>
                            <a href="{{ route('admin.teachers.index') }}" 
                               class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.teachers.*') ? 'bg-primary-600 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                                Guru
                            </a>
                            <a href="{{ route('admin.staff.index') }}" 
                               class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.staff.*') ? 'bg-primary-600 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                                Tenaga Kependidikan
                            </a>
                            <a href="{{ route('admin.facilities.index') }}" 
                               class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.facilities.*') ? 'bg-primary-600 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                                Sarana Prasarana
                            </a>
                            <a href="{{ route('admin.student-data.index') }}" 
                               class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.student-data.*') ? 'bg-primary-600 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                                Data Siswa
                            </a>
                            <a href="{{ route('admin.student-distribution.index') }}" 
                               class="flex items-center px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.student-distribution.*') ? 'bg-primary-600 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }}">
                                Persebaran Siswa
                            </a>
                        </div>
                    </div>
                    
                    <p x-show="!sidebarCollapsed" class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sistem</p>
                    <div x-show="sidebarCollapsed" class="border-t border-gray-700 my-2 mx-2"></div>
                    
                    <!-- Pengaturan -->
                    <a href="{{ route('admin.settings') }}" 
                       class="nav-link flex items-center px-3 py-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.settings') && !request()->routeIs('admin.settings.profile*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
                       :class="{ 'justify-center px-2': sidebarCollapsed }"
                       title="Pengaturan">
                        <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span x-show="!sidebarCollapsed">Pengaturan</span>
                    </a>
                    
                    <!-- Log Aktivitas -->
                    <a href="{{ route('admin.activity-logs') }}" 
                       class="nav-link flex items-center px-3 py-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.activity-logs*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
                       :class="{ 'justify-center px-2': sidebarCollapsed }"
                       title="Log Aktivitas">
                        <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span x-show="!sidebarCollapsed">Log Aktivitas</span>
                    </a>
                </div>
            </nav>
            
            <!-- Toggle Collapse Button (Desktop only) -->
            <div class="hidden lg:block flex-shrink-0 border-t border-gray-700 p-2">
                <button @click="toggleCollapse()" 
                        class="w-full flex items-center justify-center p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors" 
                        :title="sidebarCollapsed ? 'Perbesar Sidebar' : 'Perkecil Sidebar'">
                    <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'rotate-180': sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                    </svg>
                </button>
            </div>
            
            <!-- User Info di Bottom Sidebar -->
            <div class="flex-shrink-0 border-t border-gray-700 p-3">
                <!-- Desktop View -->
                <div class="hidden lg:flex items-center" :class="{ 'justify-center': sidebarCollapsed }">
                    <div class="w-9 h-9 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-medium text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <template x-if="!sidebarCollapsed">
                        <div class="ml-3 flex-1 min-w-0 flex items-center">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-400 truncate">Administrator</p>
                            </div>
                            <form action="{{ route('admin.logout') }}" method="POST" class="ml-2" id="logoutFormDesktop">
                                @csrf
                                <button type="button" @click="$dispatch('open-logout-modal')" class="p-1.5 text-red-400 hover:text-white hover:bg-red-700 rounded-lg transition-colors" title="Logout">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </template>
                </div>
                
                <!-- Mobile View - Always show logout button -->
                <div class="lg:hidden flex items-center justify-between">
                    <div class="flex items-center min-w-0 flex-1">
                        <div class="w-9 h-9 bg-primary-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-medium text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-3 min-w-0 flex-1">
                            <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400 truncate">Administrator</p>
                        </div>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST" class="ml-3 flex-shrink-0" id="logoutFormMobile">
                        @csrf
                        <button type="button" @click="$dispatch('open-logout-modal')" class="flex items-center px-3 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-gray-700 rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
            <!-- Top Navigation (Sticky) -->
            <header class="sticky top-0 z-30 bg-white shadow-sm h-14 flex items-center justify-between px-4 lg:px-6 flex-shrink-0">
                <div class="flex items-center">
                    <!-- Mobile menu button -->
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 -ml-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <h1 class="text-base lg:text-lg font-semibold text-gray-800 ml-2 lg:ml-0 truncate">@yield('page-title', 'Dashboard')</h1>
                </div>
                
                <div class="flex items-center space-x-2">
                    <a href="{{ route('home') }}" target="_blank" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors" title="Lihat Situs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
            </header>
            
            <!-- Page Content (Scrollable) -->
            <main class="flex-1 p-4 lg:p-6 overflow-y-auto main-scroll">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center" role="alert">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm">{{ session('success') }}</span>
                </div>
                @endif
                
                @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center" role="alert">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm">{{ session('error') }}</span>
                </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Flyout Menu untuk Data Profil (DILUAR SIDEBAR - Fixed position) -->
    <div x-show="profilOpen && sidebarCollapsed" 
         x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.outside="profilOpen = false"
         class="hidden lg:block fixed z-[100] w-48 bg-gray-800 rounded-lg shadow-2xl border border-gray-600 py-1"
         :style="'left: 72px; top: ' + (window.flyoutTop || 350) + 'px;'">
        <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase border-b border-gray-700">Data Profil</div>
        <a href="{{ route('admin.settings.profile') }}" 
           class="block px-3 py-2 text-sm transition-colors {{ request()->routeIs('admin.settings.profile*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
            Profil Sekolah
        </a>
        <a href="{{ route('admin.teachers.index') }}" 
           class="block px-3 py-2 text-sm transition-colors {{ request()->routeIs('admin.teachers.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
            Guru
        </a>
        <a href="{{ route('admin.staff.index') }}" 
           class="block px-3 py-2 text-sm transition-colors {{ request()->routeIs('admin.staff.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
            Tenaga Kependidikan
        </a>
        <a href="{{ route('admin.facilities.index') }}" 
           class="block px-3 py-2 text-sm transition-colors {{ request()->routeIs('admin.facilities.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
            Sarana Prasarana
        </a>
        <a href="{{ route('admin.student-data.index') }}" 
           class="block px-3 py-2 text-sm transition-colors {{ request()->routeIs('admin.student-data.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
            Data Siswa
        </a>
        <a href="{{ route('admin.student-distribution.index') }}" 
           class="block px-3 py-2 text-sm transition-colors {{ request()->routeIs('admin.student-distribution.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
            Persebaran Siswa
        </a>
    </div>
    
    <!-- Logout Confirmation Modal -->
    <div x-data="{ open: false, loggingOut: false }" 
         @open-logout-modal.window="open = true"
         @keydown.escape.window="if(!loggingOut) open = false">
        
        <!-- Backdrop -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50"
             @click="if(!loggingOut) open = false"
             x-cloak>
        </div>
        
        <!-- Modal -->
        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-cloak>
            <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full p-6" @click.stop>
                <!-- Icon -->
                <div class="mx-auto w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
                
                <!-- Content -->
                <div class="text-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Logout</h3>
                    <p class="text-sm text-gray-500">Apakah Anda yakin ingin keluar dari sistem?</p>
                </div>
                
                <!-- Actions -->
                <div class="flex gap-3">
                    <button type="button" 
                            @click="open = false"
                            :disabled="loggingOut"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Batal
                    </button>
                    <form action="{{ route('admin.logout') }}" method="POST" class="flex-1" @submit="loggingOut = true">
                        @csrf
                        <button type="submit" 
                                :disabled="loggingOut"
                                class="w-full px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors disabled:opacity-75 disabled:cursor-wait">
                            <span x-show="!loggingOut">Ya, Logout</span>
                            <span x-show="loggingOut" class="flex items-center justify-center">
                                Logging out<span class="dots-loading"></span>
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>