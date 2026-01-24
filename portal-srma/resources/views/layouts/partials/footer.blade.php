@php
    $contact = \App\Models\Contact::getContact();
@endphp

<footer class="bg-gray-800 text-gray-300">
    <!-- Main Footer -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- About -->
            <div>
                <div class="flex items-center space-x-3 mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SRMA 25 Lamongan" class="w-12 h-12 object-contain bg-white rounded-lg p-1">
                    <div>
                        <span class="text-lg font-bold text-white">SRMA 25 Lamongan</span>
                    </div>
                </div>
                <p class="text-sm text-gray-400 mb-4">
                    Sekolah Rakyat di bawah naungan Kementerian Sosial Republik Indonesia, memberikan pendidikan berkualitas bagi anak-anak dari keluarga kurang mampu.
                </p>
                <div class="flex space-x-3">
                    @if($contact->facebook)
                    <a href="{{ $contact->facebook }}" target="_blank" class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center hover:bg-primary-600 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    @endif
                    @if($contact->instagram)
                    <a href="{{ $contact->instagram }}" target="_blank" class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center hover:bg-primary-600 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    @endif
                    @if($contact->youtube)
                    <a href="{{ $contact->youtube }}" target="_blank" class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center hover:bg-primary-600 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    @endif
                </div>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-semibold mb-4">Menu Utama</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-sm hover:text-primary-400 transition-colors">Beranda</a></li>
                    <!-- <li><a href="{{ route('profil.tentang') }}" class="text-sm hover:text-primary-400 transition-colors">Tentang Sekolah</a></li> -->
                    <li><a href="{{ route('berita.index') }}" class="text-sm hover:text-primary-400 transition-colors">Berita</a></li>
                    <li><a href="{{ route('pengumuman.index') }}" class="text-sm hover:text-primary-400 transition-colors">Pengumuman</a></li>
                    <li><a href="{{ route('agenda.index') }}" class="text-sm hover:text-primary-400 transition-colors">Agenda</a></li>
                    <li><a href="{{ route('galeri.index') }}" class="text-sm hover:text-primary-400 transition-colors">Galeri</a></li>
                </ul>
            </div>
            
            <!-- Profile Links -->
            <div>
                <h4 class="text-white font-semibold mb-4">Profil Sekolah</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('profil.tentang') }}" class="text-sm hover:text-primary-400 transition-colors">Tentang Kami</a></li>
                    <li><a href="{{ route('profil.visi-misi') }}" class="text-sm hover:text-primary-400 transition-colors">Visi & Misi</a></li>
                    <li><a href="{{ route('profil.struktur') }}" class="text-sm hover:text-primary-400 transition-colors">Struktur Organisasi</a></li>
                    <li><a href="{{ route('profil.guru') }}" class="text-sm hover:text-primary-400 transition-colors">Guru</a></li>
                    <li><a href="{{ route('profil.data-siswa') }}" class="text-sm hover:text-primary-400 transition-colors">Data Siswa</a></li>
                    <li><a href="{{ route('kontak') }}" class="text-sm hover:text-primary-400 transition-colors">Kontak</a></li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h4 class="text-white font-semibold mb-4">Hubungi Kami</h4>
                <ul class="space-y-3">
                    @if($contact->address)
                    <li class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-sm">{{ $contact->address }}</span>
                    </li>
                    @endif
                    @if($contact->phone)
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-primary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="text-sm">{{ $contact->phone }}</span>
                    </li>
                    @endif
                    @if($contact->email)
                    <li class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-primary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm">{{ $contact->email }}</span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Bottom Footer -->
    <div class="border-t border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-2 md:space-y-0">
                <p class="text-sm text-gray-400">
                    &copy; {{ date('Y') }} SRMA 25 Lamongan. Di bawah naungan Kementerian Sosial RI.
                </p>
                <p class="text-sm text-gray-400">
                    <a href="{{ route('admin.login') }}" class="hover:text-primary-400 transition-colors">Admin</a>
                </p>
            </div>
        </div>
    </div>
</footer>
