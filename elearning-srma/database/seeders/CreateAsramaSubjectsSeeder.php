<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class CreateAsramaSubjectsSeeder extends Seeder
{
    /**
     * Seed asrama (non-academic) activities as Subject entities with category='non_academic'
     */
    public function run(): void
    {
        $asramaSubjects = [
            [
                'name' => 'Upacara Bendera',
                'code' => 'UPC',
                'description' => 'Upacara bendera setiap hari Senin dan hari-hari khusus untuk mempertebal jiwa nasionalisme',
                'category' => 'non_academic',
            ],
            [
                'name' => 'Kegiatan Kelas dan Asrama (KKA)',
                'code' => 'KKA',
                'description' => 'Program kegiatan rutin di kelas dan asrama untuk mengembangkan karakter dan kebersamaan siswa',
                'category' => 'non_academic',
            ],
            [
                'name' => 'Ekstrakurikuler Olahraga',
                'code' => 'EKS-OLH',
                'description' => 'Program ekstrakurikuler bidang olahraga meliputi sepak bola, bola voli, tenis, badminton, dan lainnya',
                'category' => 'non_academic',
            ],
            [
                'name' => 'Ekstrakurikuler Seni dan Budaya',
                'code' => 'EKS-SB',
                'description' => 'Program ekstrakurikuler bidang seni dan budaya meliputi teater, tari, musik, dan seni tradisional',
                'category' => 'non_academic',
            ],
            [
                'name' => 'Ekstrakurikuler OSIS',
                'code' => 'EKS-OSIS',
                'description' => 'Program Organisasi Siswa Intra Sekolah untuk pengembangan kepemimpinan dan organisasi siswa',
                'category' => 'non_academic',
            ],
            [
                'name' => 'Program Jum\'at Beramal',
                'code' => 'JUM-BAM',
                'description' => 'Program khusus setiap hari Jumat untuk kegiatan sosial, amal, dan kepedulian masyarakat',
                'category' => 'non_academic',
            ],
            [
                'name' => 'Literasi Pagi',
                'code' => 'LIT-PAGI',
                'description' => 'Program literasi setiap pagi untuk meningkatkan minat baca dan wawasan siswa',
                'category' => 'non_academic',
            ],
            [
                'name' => 'Apel Pagi',
                'code' => 'APL-PAGI',
                'description' => 'Apel pagi sebelum pembelajaran dimulai untuk memastikan disiplin dan kesiapan siswa',
                'category' => 'non_academic',
            ],
            [
                'name' => 'Piket Kelas',
                'code' => 'PKT-KLS',
                'description' => 'Tugas piket harian untuk menjaga kebersihan dan kerapian ruang kelas',
                'category' => 'non_academic',
            ],
            [
                'name' => 'Pembinaan Asrama',
                'code' => 'PBN-ASR',
                'description' => 'Program pembinaan dan pengawasan kehidupan siswa di asrama termasuk disiplin dan kesejahteraan',
                'category' => 'non_academic',
            ],
        ];

        foreach ($asramaSubjects as $subject) {
            Subject::updateOrCreate(
                ['code' => $subject['code']],
                [
                    'name' => $subject['name'],
                    'description' => $subject['description'],
                    'category' => $subject['category'],
                ]
            );
        }

        $this->command->info('✅ Seeder asrama/kegiatan non-akademik berhasil dijalankan. Total asrama subjects: ' . 
            Subject::where('category', 'non_academic')->count());
    }
}
