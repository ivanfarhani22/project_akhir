<nav x-data="{ open: false, profileOpen: false }" class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SRMA 25 Lamongan" class="w-12 h-12 object-contain">
                    <div class="hidden sm:block">
                        <span class="text-lg font-bold text-gray-800">SRMA 25</span>
                        <span class="text-sm text-gray-500 block -mt-1">Lamongan</span>
                    </div>
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-1">
                <a href="{{ route('home') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('home') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }} transition-colors">
                    Beranda
                </a>
                
                <!-- Profil Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.away="open = false" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('profil.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }} transition-colors inline-flex items-center">
                        Profil
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-cloak x-transition class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-1 z-50">
                        <!-- <a href="{{ route('profil.tentang') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Tentang Sekolah</a> -->
                        <a href="{{ route('profil.dasar-hukum') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Dasar Hukum</a>
                        <a href="{{ route('profil.visi-misi') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Visi & Misi</a>
                        <a href="{{ route('profil.sarana-prasarana') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Sarana Prasarana</a>
                        <a href="{{ route('profil.struktur') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Struktur Organisasi</a>
                        <a href="{{ route('profil.guru') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Guru</a>
                        <a href="{{ route('profil.tenaga-kependidikan') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Tenaga Kependidikan</a>
                        <a href="{{ route('profil.data-siswa') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Data Siswa</a>
                        <a href="{{ route('profil.persebaran-siswa') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Persebaran Siswa</a>
                    </div>
                </div>
                
                <a href="{{ route('berita.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('berita.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }} transition-colors">
                    Berita
                </a>
                
                <a href="{{ route('pengumuman.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('pengumuman.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }} transition-colors">
                    Pengumuman
                </a>
                
                <a href="{{ route('agenda.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('agenda.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }} transition-colors">
                    Agenda
                </a>
                
                <a href="{{ route('galeri.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('galeri.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }} transition-colors">
                    Galeri
                </a>
                
                <a href="{{ route('ppdb') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('ppdb') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }} transition-colors">
                    PPDB
                </a>
                
                <a href="{{ route('kontak') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('kontak') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }} transition-colors">
                    Kontak
                </a>
            </div>
            
            <!-- E-Learning Button -->
            <div class="hidden lg:flex items-center">
                @php
                    $elearningUrl = \App\Models\Setting::getValue('elearning_url', '#');
                @endphp
                <a href="{{ $elearningUrl }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    E-Learning
                </a>
            </div>
            
            <!-- Mobile menu button -->
            <div class="lg:hidden flex items-center">
                <button @click="open = !open" class="p-2 rounded-md text-gray-600 hover:text-primary-600 hover:bg-gray-50 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Navigation -->
    <div x-show="open" x-cloak x-transition class="lg:hidden bg-white border-t">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('home') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">Beranda</a>
            
            <div x-data="{ subOpen: false }">
                <button @click="subOpen = !subOpen" class="w-full flex justify-between items-center px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-50">
                    Profil
                    <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': subOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="subOpen" x-cloak class="pl-4 space-y-1">
                    <!-- <a href="{{ route('profil.tentang') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-primary-600">Tentang Sekolah</a> -->
                    <a href="{{ route('profil.dasar-hukum') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-primary-600">Dasar Hukum</a>
                    <a href="{{ route('profil.visi-misi') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-primary-600">Visi & Misi</a>
                    <a href="{{ route('profil.sarana-prasarana') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-primary-600">Sarana Prasarana</a>
                    <a href="{{ route('profil.struktur') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-primary-600">Struktur Organisasi</a>
                    <a href="{{ route('profil.guru') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-primary-600">Guru</a>
                    <a href="{{ route('profil.tenaga-kependidikan') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-primary-600">Tenaga Kependidikan</a>
                    <a href="{{ route('profil.data-siswa') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-primary-600">Data Siswa</a>
                    <a href="{{ route('profil.persebaran-siswa') }}" class="block px-3 py-2 text-sm text-gray-600 hover:text-primary-600">Persebaran Siswa</a>
                </div>
            </div>
            
            <a href="{{ route('berita.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('berita.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">Berita</a>
            <a href="{{ route('pengumuman.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('pengumuman.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">Pengumuman</a>
            <a href="{{ route('agenda.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('agenda.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">Agenda</a>
            <a href="{{ route('galeri.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('galeri.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">Galeri</a>
            <a href="{{ route('ppdb') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('ppdb') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">PPDB</a>
            <a href="{{ route('kontak') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('kontak') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-primary-600 hover:bg-gray-50' }}">Kontak</a>
            
            <div class="pt-3">
                <a href="{{ $elearningUrl }}" target="_blank" class="block w-full text-center px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700">
                    Masuk E-Learning
                </a>
            </div>
        </div>
    </div>
</nav>
