<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PpdbController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\AgendaController as AdminAgendaController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\StudentDataController;
use App\Http\Controllers\Admin\StudentDistributionController;

/*
|--------------------------------------------------------------------------
| Public Routes - Portal Sekolah SRMA 25 Lamongan
|--------------------------------------------------------------------------
*/

// Beranda
Route::get('/', [HomeController::class, 'index'])->name('home');

// Profil Sekolah
Route::prefix('profil')->name('profil.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::get('/tentang', [ProfileController::class, 'tentang'])->name('tentang');
    Route::get('/dasar-hukum', [ProfileController::class, 'dasarHukum'])->name('dasar-hukum');
    Route::get('/visi-misi', [ProfileController::class, 'visiMisi'])->name('visi-misi');
    Route::get('/sarana-prasarana', [ProfileController::class, 'saranaPrasarana'])->name('sarana-prasarana');
    Route::get('/struktur-organisasi', [ProfileController::class, 'struktur'])->name('struktur');
    Route::get('/guru', [ProfileController::class, 'guru'])->name('guru');
    Route::get('/tenaga-kependidikan', [ProfileController::class, 'tenagaKependidikan'])->name('tenaga-kependidikan');
    Route::get('/data-siswa', [ProfileController::class, 'dataSiswa'])->name('data-siswa');
    Route::get('/persebaran-siswa', [ProfileController::class, 'persebaranSiswa'])->name('persebaran-siswa');
});

// Berita
Route::get('/berita', [NewsController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('berita.show');

// Pengumuman
Route::get('/pengumuman', [AnnouncementController::class, 'index'])->name('pengumuman.index');
Route::get('/pengumuman/{slug}', [AnnouncementController::class, 'show'])->name('pengumuman.show');

// Agenda
Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
Route::get('/agenda/{slug}', [AgendaController::class, 'show'])->name('agenda.show');

// Galeri
Route::get('/galeri', [GalleryController::class, 'index'])->name('galeri.index');

// PPDB (Banner Statis)
Route::get('/ppdb', [PpdbController::class, 'index'])->name('ppdb');

// Kontak
Route::get('/kontak', [ContactController::class, 'index'])->name('kontak');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Admin Auth
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin Protected Routes
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // News Management
    Route::resource('news', AdminNewsController::class);
    
    // Announcement Management
    Route::resource('announcements', AdminAnnouncementController::class);
    
    // Agenda Management
    Route::resource('agendas', AdminAgendaController::class);
    
    // Gallery Management
    Route::resource('galleries', AdminGalleryController::class);
    Route::get('gallery-categories', [AdminGalleryController::class, 'categories'])->name('galleries.categories');
    Route::post('gallery-categories', [AdminGalleryController::class, 'storeCategory'])->name('galleries.categories.store');
    Route::delete('gallery-categories/{category}', [AdminGalleryController::class, 'destroyCategory'])->name('galleries.categories.destroy');
    
    // Banner Management
    Route::resource('banners', BannerController::class);
    
    // Settings (Main)
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::put('/settings/profiles', [SettingController::class, 'updateProfiles'])->name('settings.profiles');
    Route::put('/settings/contact', [SettingController::class, 'updateContact'])->name('settings.contact');
    Route::put('/settings/site', [SettingController::class, 'updateSite'])->name('settings.site');
    Route::put('/settings/password', [SettingController::class, 'updatePassword'])->name('settings.password');
    
    // Profile Sekolah (Tentang, Visi Misi, Struktur Organisasi)
    Route::get('/settings/profile', [SettingController::class, 'profile'])->name('settings.profile');
    Route::put('/settings/profile', [SettingController::class, 'updateProfile'])->name('settings.profile.update');
    Route::delete('/settings/profile/struktur-image', [SettingController::class, 'deleteStrukturImage'])->name('settings.profile.delete-struktur-image');
    
    // Activity Logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs');
    Route::get('/activity-logs/export', [ActivityLogController::class, 'export'])->name('activity-logs.export');
    Route::get('/activity-logs/export-excel', [ActivityLogController::class, 'exportExcel'])->name('activity-logs.export-excel');
    Route::get('/activity-logs/{log}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
    
    // Profile Management - Teachers
    Route::resource('teachers', TeacherController::class);
    
    // Profile Management - Staff
    Route::resource('staff', StaffController::class);
    
    // Profile Management - Facilities
    Route::resource('facilities', FacilityController::class);
    
    // Profile Management - Student Data
    Route::resource('student-data', StudentDataController::class);
    
    // Profile Management - Student Distribution
    Route::resource('student-distribution', StudentDistributionController::class);
});
