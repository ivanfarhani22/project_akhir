<!-- Profile Sidebar Navigation -->
<div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
    <h3 class="font-semibold text-gray-800 mb-4">Profil Sekolah</h3>
    <nav class="space-y-2">
        <!-- <a href="{{ route('profil.tentang') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('profil.tentang') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            Tentang Sekolah
        </a> -->
        <a href="{{ route('profil.dasar-hukum') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('profil.dasar-hukum') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            Dasar Hukum
        </a>
        <a href="{{ route('profil.visi-misi') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('profil.visi-misi') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            Visi & Misi
        </a>
        <a href="{{ route('profil.sarana-prasarana') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('profil.sarana-prasarana') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            Sarana Prasarana
        </a>
        <a href="{{ route('profil.struktur') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('profil.struktur') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            Struktur Organisasi
        </a>
        <a href="{{ route('profil.guru') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('profil.guru') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            Guru
        </a>
        <a href="{{ route('profil.tenaga-kependidikan') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('profil.tenaga-kependidikan') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            Tenaga Kependidikan
        </a>
        <a href="{{ route('profil.data-siswa') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('profil.data-siswa') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            Data Siswa
        </a>
        <a href="{{ route('profil.persebaran-siswa') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('profil.persebaran-siswa') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            Persebaran Siswa
        </a>
    </nav>
</div>
