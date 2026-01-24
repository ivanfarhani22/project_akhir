<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use App\Models\Setting;
use App\Models\Contact;
use App\Models\Banner;
use App\Models\GalleryCategory;
use App\Models\StudentDistribution;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin Portal',
            'email' => 'admin@srma25lamongan.sch.id',
            'password' => Hash::make('password123'),
        ]);

        // Seed Profile Data (Placeholders)
        $profiles = [
            // ['key' => 'tentang_sekolah', 'title' => 'Tentang Sekolah', 'content' => '<p>Konten akan disesuaikan dengan PPT resmi sekolah.</p>', 'type' => 'html'],
            ['key' => 'sejarah_sekolah', 'title' => 'Sejarah Sekolah', 'content' => '<p>Konten akan disesuaikan dengan PPT resmi sekolah.</p>', 'type' => 'html'],
            ['key' => 'visi', 'title' => 'Visi', 'content' => '<p>Konten akan disesuaikan dengan PPT resmi sekolah.</p>', 'type' => 'html'],
            ['key' => 'misi', 'title' => 'Misi', 'content' => '<p>Konten akan disesuaikan dengan PPT resmi sekolah.</p>', 'type' => 'html'],
            ['key' => 'tujuan', 'title' => 'Tujuan', 'content' => '<p>Konten akan disesuaikan dengan PPT resmi sekolah.</p>', 'type' => 'html'],
            ['key' => 'struktur_organisasi', 'title' => 'Struktur Organisasi', 'content' => '<p>Konten akan disesuaikan dengan PPT resmi sekolah.</p>', 'type' => 'html'],
        ];

        foreach ($profiles as $profile) {
            Profile::create($profile);
        }

        // Seed School Data Settings
        $settings = [
            ['key' => 'total_siswa_laki', 'value' => '40', 'type' => 'text'],
            ['key' => 'total_siswa_perempuan', 'value' => '35', 'type' => 'text'],
            ['key' => 'total_guru', 'value' => '16', 'type' => 'text'],
            ['key' => 'total_kepala_sekolah', 'value' => '1', 'type' => 'text'],
            ['key' => 'total_wali_asrama', 'value' => '3', 'type' => 'text'],
            ['key' => 'total_wali_asuh', 'value' => '8', 'type' => 'text'],
            ['key' => 'total_keamanan', 'value' => '3', 'type' => 'text'],
            ['key' => 'total_kebersihan', 'value' => '4', 'type' => 'text'],
            ['key' => 'total_juru_masak', 'value' => '5', 'type' => 'text'],
            ['key' => 'total_operator', 'value' => '1', 'type' => 'text'],
            ['key' => 'total_bendahara', 'value' => '1', 'type' => 'text'],
            ['key' => 'total_tu', 'value' => '1', 'type' => 'text'],
            ['key' => 'elearning_url', 'value' => 'https://elearning.srma25lamongan.sch.id', 'type' => 'url'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }

        // Seed Contact
        Contact::create([
            'address' => 'Jl. Contoh No. 123, Lamongan, Jawa Timur',
            'phone' => '(0322) 123456',
            'email' => 'info@srma25lamongan.sch.id',
            'whatsapp' => '081234567890',
            'google_maps_embed' => '',
            'facebook' => '',
            'instagram' => '',
            'youtube' => '',
            'twitter' => '',
        ]);

        // Seed Default Banner
        Banner::create([
            'title' => 'Selamat Datang di Portal SRMA 25 Lamongan',
            'subtitle' => 'Sekolah Rakyat di bawah naungan Kementerian Sosial Republik Indonesia',
            'image' => null,
            'link' => null,
            'button_text' => 'Pelajari Lebih Lanjut',
            'order' => 1,
            'is_active' => true,
        ]);

        // Seed Gallery Categories
        $categories = [
            ['name' => 'Kegiatan Pembelajaran', 'slug' => 'kegiatan-pembelajaran', 'description' => 'Dokumentasi kegiatan belajar mengajar'],
            ['name' => 'Upacara & Peringatan', 'slug' => 'upacara-peringatan', 'description' => 'Dokumentasi upacara dan peringatan hari besar'],
            ['name' => 'Kegiatan Ekstrakurikuler', 'slug' => 'ekstrakurikuler', 'description' => 'Dokumentasi kegiatan ekstrakurikuler'],
            ['name' => 'Kegiatan Sosial', 'slug' => 'kegiatan-sosial', 'description' => 'Dokumentasi kegiatan sosial kemasyarakatan'],
            ['name' => 'Fasilitas Sekolah', 'slug' => 'fasilitas-sekolah', 'description' => 'Dokumentasi fasilitas dan sarana prasarana'],
        ];

        foreach ($categories as $category) {
            GalleryCategory::create($category);
        }

        // Seed Student Distribution
        $currentYear = date('Y') . '/' . (date('Y') + 1);
        $distributions = [
            ['district' => 'Lamongan', 'student_count' => 15],
            ['district' => 'Paciran', 'student_count' => 8],
            ['district' => 'Brondong', 'student_count' => 7],
            ['district' => 'Solokuro', 'student_count' => 6],
            ['district' => 'Mantup', 'student_count' => 5],
            ['district' => 'Babat', 'student_count' => 5],
            ['district' => 'Sukodadi', 'student_count' => 4],
            ['district' => 'Pucuk', 'student_count' => 4],
            ['district' => 'Tikung', 'student_count' => 3],
            ['district' => 'Glagah', 'student_count' => 3],
            ['district' => 'Karangbinangun', 'student_count' => 3],
            ['district' => 'Turi', 'student_count' => 2],
            ['district' => 'Deket', 'student_count' => 2],
            ['district' => 'Sekaran', 'student_count' => 2],
            ['district' => 'Lainnya', 'student_count' => 6],
        ];

        foreach ($distributions as $dist) {
            StudentDistribution::create([
                'academic_year' => $currentYear,
                'district' => $dist['district'],
                'student_count' => $dist['student_count'],
            ]);
        }
    }
}
