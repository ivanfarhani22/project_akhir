<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CreateSubjectsFromKbmSeeder extends Seeder
{
    public function run(): void
    {
        // Source: Jadwal Pelajaran SRMA 25 Lamongan (Semester Genap 2025/2026)
        // Note: duplicates in source (e.g. PAI repeated) are merged by code.
        $subjects = [
            ['code' => 'UPACARA', 'name' => 'Upacara', 'description' => 'Kegiatan upacara bendera/seremonial sekolah.'],
            ['code' => 'KKA', 'name' => 'KKA', 'description' => 'Kegiatan KKA sesuai program sekolah.'],
            ['code' => 'SEJ', 'name' => 'Sejarah', 'description' => 'Mata pelajaran Sejarah.'],
            ['code' => 'PP', 'name' => 'PP', 'description' => 'Mata pelajaran PP sesuai kurikulum sekolah.'],
            ['code' => 'SENBUD', 'name' => 'Seni Budaya', 'description' => 'Mata pelajaran Seni Budaya.'],
            ['code' => 'INF', 'name' => 'Informatika', 'description' => 'Mata pelajaran Informatika.'],
            ['code' => 'MAT', 'name' => 'Matematika', 'description' => 'Mata pelajaran Matematika.'],
            ['code' => 'PJOK', 'name' => 'PJOK', 'description' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan.'],
            ['code' => 'EKO', 'name' => 'Ekonomi', 'description' => 'Mata pelajaran Ekonomi.'],
            ['code' => 'SOS', 'name' => 'Sosiologi', 'description' => 'Mata pelajaran Sosiologi.'],
            ['code' => 'GEO', 'name' => 'Geografi', 'description' => 'Mata pelajaran Geografi.'],
            ['code' => 'BIO', 'name' => 'Biologi', 'description' => 'Mata pelajaran Biologi.'],
            ['code' => 'FIS', 'name' => 'Fisika', 'description' => 'Mata pelajaran Fisika.'],
            ['code' => 'KIM', 'name' => 'Kimia', 'description' => 'Mata pelajaran Kimia.'],
            ['code' => 'BIN', 'name' => 'Bahasa Indonesia', 'description' => 'Mata pelajaran Bahasa Indonesia.'],
            ['code' => 'BIG', 'name' => 'Bahasa Inggris', 'description' => 'Mata pelajaran Bahasa Inggris.'],
            ['code' => 'KWU', 'name' => 'Kewirausahaan', 'description' => 'Mata pelajaran Kewirausahaan.'],
            ['code' => 'PAI', 'name' => 'Pendidikan Agama Islam', 'description' => 'Mata pelajaran Pendidikan Agama Islam.'],
            ['code' => 'BK', 'name' => 'Bimbingan Konseling', 'description' => 'Layanan Bimbingan Konseling.'],
            ['code' => 'KK_KO', 'name' => 'KK/KO', 'description' => 'Kegiatan KK/KO sesuai program sekolah.'],
            ['code' => 'EKSKUL', 'name' => 'Ekstrakurikuler', 'description' => 'Kegiatan ekstrakurikuler.'],
            ['code' => 'PROJUMAT', 'name' => "Program Jum'at", 'description' => "Kegiatan Program Jum'at sekolah."],
            ['code' => 'BIMAKAD', 'name' => 'Bimbingan Akademik (Guru Wali)', 'description' => 'Bimbingan akademik oleh guru wali.'],
        ];

        foreach ($subjects as $s) {
            Subject::updateOrCreate(
                ['code' => $s['code']],
                [
                    'name' => $s['name'],
                    'description' => $s['description'],
                ]
            );
        }

        $this->command->info('✅ Seeder mapel KBM berhasil dijalankan. Total subjects saat ini: ' . Subject::count());
    }
}
