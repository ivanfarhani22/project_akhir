<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class CreateSubjectsSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            // Mata Pelajaran Wajib Kurikulum 2013
            [
                'name' => 'Pendidikan Agama Islam',
                'code' => 'PAI',
                'description' => 'Pelajaran agama Islam yang mencakup akidah, akhlak, syariah, dan sejarah Islam',
            ],
            [
                'name' => 'Pendidikan Pancasila dan Kewarganegaraan',
                'code' => 'PPKn',
                'description' => 'Pendidikan tentang nilai-nilai Pancasila dan kewarganegaraan Indonesia',
            ],
            [
                'name' => 'Bahasa Indonesia',
                'code' => 'BInd',
                'description' => 'Pembelajaran bahasa Indonesia yang mencakup membaca, menulis, mendengarkan, dan berbicara',
            ],
            [
                'name' => 'Bahasa Inggris',
                'code' => 'BIng',
                'description' => 'Pembelajaran bahasa Inggris untuk komunikasi internasional',
            ],
            [
                'name' => 'Matematika',
                'code' => 'MTK',
                'description' => 'Pelajaran matematika mencakup aljabar, geometri, trigonometri, dan kalkulus',
            ],
            [
                'name' => 'Sejarah Indonesia',
                'code' => 'SJH',
                'description' => 'Pembelajaran sejarah Indonesia dari masa praaksara hingga modern',
            ],
            [
                'name' => 'Geografi',
                'code' => 'GEO',
                'description' => 'Pelajaran tentang bumi, penduduk, dan interaksi manusia dengan lingkungan',
            ],
            [
                'name' => 'Biologi',
                'code' => 'BIO',
                'description' => 'Pelajaran tentang makhluk hidup, sel, genetika, ekologi, dan evolusi',
            ],
            [
                'name' => 'Kimia',
                'code' => 'KIM',
                'description' => 'Pelajaran tentang struktur atom, ikatan kimia, reaksi, dan stoikiometri',
            ],
            [
                'name' => 'Fisika',
                'code' => 'FIS',
                'description' => 'Pelajaran tentang mekanika, termodinamika, gelombang, dan listrik-magnet',
            ],
            [
                'name' => 'Ekonomi',
                'code' => 'EKO',
                'description' => 'Pelajaran tentang konsep ekonomi, perdagangan, dan sistem perekonomian',
            ],
            [
                'name' => 'Sosiologi',
                'code' => 'SOC',
                'description' => 'Pelajaran tentang masyarakat, interaksi sosial, dan struktur sosial',
            ],
            [
                'name' => 'Seni Rupa',
                'code' => 'SRP',
                'description' => 'Pelajaran tentang seni visual, melukis, dan apresiasi seni rupa',
            ],
            [
                'name' => 'Seni Musik',
                'code' => 'SMK',
                'description' => 'Pelajaran tentang musik, teori musik, dan apresiasi musik',
            ],
            [
                'name' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan',
                'code' => 'PJOK',
                'description' => 'Pelajaran tentang kesehatan, olahraga, dan kebugaran jasmani',
            ],
            [
                'name' => 'Teknologi Informasi dan Komunikasi',
                'code' => 'TIK',
                'description' => 'Pelajaran tentang komputer, internet, dan teknologi digital',
            ],
            
            // Mata Pelajaran Peminatan (Lintas Minat)
            [
                'name' => 'Matematika Peminatan',
                'code' => 'MTK-P',
                'description' => 'Matematika lanjutan untuk siswa yang tertarik dengan science dan teknik',
            ],
            [
                'name' => 'Biologi Peminatan',
                'code' => 'BIO-P',
                'description' => 'Biologi lanjutan untuk siswa yang tertarik dengan ilmu kesehatan dan bioteknologi',
            ],
            [
                'name' => 'Kimia Peminatan',
                'code' => 'KIM-P',
                'description' => 'Kimia lanjutan untuk siswa yang tertarik dengan science',
            ],
            [
                'name' => 'Fisika Peminatan',
                'code' => 'FIS-P',
                'description' => 'Fisika lanjutan untuk siswa yang tertarik dengan science dan teknik',
            ],
            [
                'name' => 'Sejarah Peminatan',
                'code' => 'SJH-P',
                'description' => 'Sejarah lanjutan untuk siswa yang tertarik dengan ilmu sosial',
            ],
            [
                'name' => 'Geografi Peminatan',
                'code' => 'GEO-P',
                'description' => 'Geografi lanjutan untuk siswa yang tertarik dengan ilmu sosial',
            ],
            [
                'name' => 'Ekonomi Peminatan',
                'code' => 'EKO-P',
                'description' => 'Ekonomi lanjutan untuk siswa yang tertarik dengan ilmu sosial dan bisnis',
            ],
            [
                'name' => 'Sosiologi Peminatan',
                'code' => 'SOC-P',
                'description' => 'Sosiologi lanjutan untuk siswa yang tertarik dengan ilmu sosial',
            ],
            
            // Mata Pelajaran Pilihan
            [
                'name' => 'Antropologi',
                'code' => 'ANT',
                'description' => 'Pelajaran tentang manusia, budaya, dan perkembangan masyarakat',
            ],
            [
                'name' => 'Akuntansi',
                'code' => 'AKT',
                'description' => 'Pelajaran tentang pencatatan keuangan dan laporan keuangan',
            ],
            [
                'name' => 'Administrasi Pemerintahan',
                'code' => 'AP',
                'description' => 'Pelajaran tentang sistem pemerintahan dan administrasi publik',
            ],
            [
                'name' => 'Bahasa Mandarin',
                'code' => 'BMNDRN',
                'description' => 'Pelajaran bahasa Mandarin untuk komunikasi dengan Tiongkok',
            ],
            [
                'name' => 'Bahasa Jepang',
                'code' => 'BJP',
                'description' => 'Pelajaran bahasa Jepang untuk komunikasi dengan Jepang',
            ],
            [
                'name' => 'Bahasa Arab',
                'code' => 'BAR',
                'description' => 'Pelajaran bahasa Arab untuk komunikasi dan pemahaman Al-Quran',
            ],
            [
                'name' => 'Desain Grafis',
                'code' => 'DG',
                'description' => 'Pelajaran tentang desain visual menggunakan software grafis modern',
            ],
            [
                'name' => 'Programming',
                'code' => 'PROG',
                'description' => 'Pelajaran tentang pemrograman komputer dan pengembangan aplikasi',
            ],
            [
                'name' => 'Web Development',
                'code' => 'WEB',
                'description' => 'Pelajaran tentang pengembangan website dan aplikasi web',
            ],
            [
                'name' => 'Literasi Digital',
                'code' => 'LitDig',
                'description' => 'Pelajaran tentang kemampuan digital dan literasi informasi',
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(
                ['code' => $subject['code']],
                [
                    'name' => $subject['name'],
                    'description' => $subject['description'],
                ]
            );
        }

        $totalSubjects = Subject::count();
        echo "✅ " . $totalSubjects . " Mata Pelajaran berhasil dibuat/diupdate!\n";
        echo "📚 Daftar Lengkap Mata Pelajaran:\n";
        
        $createdSubjects = Subject::orderBy('created_at', 'desc')->get();
        foreach ($createdSubjects as $index => $subject) {
            echo sprintf("   %2d. %-35s [%s]\n", $index + 1, $subject->name, $subject->code);
        }
    }
}
