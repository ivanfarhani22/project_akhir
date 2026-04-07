<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateTeachersAndStudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data 25 Guru (Teachers)
        $teachers = [
            ['name' => 'Budi Santoso', 'email' => 'budi.santoso@srma.sch.id'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti.nurhaliza@srma.sch.id'],
            ['name' => 'Ahmad Wijaya', 'email' => 'ahmad.wijaya@srma.sch.id'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi.lestari@srma.sch.id'],
            ['name' => 'Eka Putra', 'email' => 'eka.putra@srma.sch.id'],
            ['name' => 'Fatima Zahra', 'email' => 'fatima.zahra@srma.sch.id'],
            ['name' => 'Gunawan Hermawan', 'email' => 'gunawan.hermawan@srma.sch.id'],
            ['name' => 'Hendro Suryanto', 'email' => 'hendro.suryanto@srma.sch.id'],
            ['name' => 'Indri Rahmawati', 'email' => 'indri.rahmawati@srma.sch.id'],
            ['name' => 'Joko Setiawan', 'email' => 'joko.setiawan@srma.sch.id'],
            ['name' => 'Kharina Putri', 'email' => 'kharina.putri@srma.sch.id'],
            ['name' => 'Lukman Hakim', 'email' => 'lukman.hakim@srma.sch.id'],
            ['name' => 'Mira Kusuma', 'email' => 'mira.kusuma@srma.sch.id'],
            ['name' => 'Nandi Hermanto', 'email' => 'nandi.hermanto@srma.sch.id'],
            ['name' => 'Olivia Santoso', 'email' => 'olivia.santoso@srma.sch.id'],
            ['name' => 'Priyo Budiman', 'email' => 'priyo.budiman@srma.sch.id'],
            ['name' => 'Qori Ananda', 'email' => 'qori.ananda@srma.sch.id'],
            ['name' => 'Rina Wijaya', 'email' => 'rina.wijaya@srma.sch.id'],
            ['name' => 'Supandi Haryanto', 'email' => 'supandi.haryanto@srma.sch.id'],
            ['name' => 'Titi Sugiarto', 'email' => 'titi.sugiarto@srma.sch.id'],
            ['name' => 'Usman Hidayat', 'email' => 'usman.hidayat@srma.sch.id'],
            ['name' => 'Vina Rahmadani', 'email' => 'vina.rahmadani@srma.sch.id'],
            ['name' => 'Wawan Kurniawan', 'email' => 'wawan.kurniawan@srma.sch.id'],
            ['name' => 'Xenia Pratama', 'email' => 'xenia.pratama@srma.sch.id'],
            ['name' => 'Yandi Pratama', 'email' => 'yandi.pratama@srma.sch.id'],
        ];

        // Data 75 Siswa (Students)
        $students = [
            // Kelas X (25 siswa)
            ['name' => 'Adi Putra Wijaya', 'email' => 'adi.putra@student.srma.sch.id'],
            ['name' => 'Ahmad Rizki Pratama', 'email' => 'ahmad.rizki@student.srma.sch.id'],
            ['name' => 'Alfian Darmawan', 'email' => 'alfian.darmawan@student.srma.sch.id'],
            ['name' => 'Amelia Sari', 'email' => 'amelia.sari@student.srma.sch.id'],
            ['name' => 'Annisa Kusuma', 'email' => 'annisa.kusuma@student.srma.sch.id'],
            ['name' => 'Arief Pratama', 'email' => 'arief.pratama@student.srma.sch.id'],
            ['name' => 'Ayu Safitri', 'email' => 'ayu.safitri@student.srma.sch.id'],
            ['name' => 'Bagus Setiawan', 'email' => 'bagus.setiawan@student.srma.sch.id'],
            ['name' => 'Bella Handini', 'email' => 'bella.handini@student.srma.sch.id'],
            ['name' => 'Bimo Wicaksono', 'email' => 'bimo.wicaksono@student.srma.sch.id'],
            ['name' => 'Budi Hermawan', 'email' => 'budi.hermawan@student.srma.sch.id'],
            ['name' => 'Citra Dewi', 'email' => 'citra.dewi@student.srma.sch.id'],
            ['name' => 'Dani Hermanto', 'email' => 'dani.hermanto@student.srma.sch.id'],
            ['name' => 'Dina Putri', 'email' => 'dina.putri@student.srma.sch.id'],
            ['name' => 'Edo Kurniawan', 'email' => 'edo.kurniawan@student.srma.sch.id'],
            ['name' => 'Elisa Rahmawati', 'email' => 'elisa.rahmawati@student.srma.sch.id'],
            ['name' => 'Ema Kusuma', 'email' => 'ema.kusuma@student.srma.sch.id'],
            ['name' => 'Endra Setiawan', 'email' => 'endra.setiawan@student.srma.sch.id'],
            ['name' => 'Endang Kusuma', 'email' => 'endang.kusuma@student.srma.sch.id'],
            ['name' => 'Erni Kurniawati', 'email' => 'erni.kurniawati@student.srma.sch.id'],
            ['name' => 'Evan Pratama', 'email' => 'evan.pratama@student.srma.sch.id'],
            ['name' => 'Faisal Hermawan', 'email' => 'faisal.hermawan@student.srma.sch.id'],
            ['name' => 'Fara Putri', 'email' => 'fara.putri@student.srma.sch.id'],
            ['name' => 'Fasa Wijaya', 'email' => 'fasa.wijaya@student.srma.sch.id'],
            ['name' => 'Feri Kurniawan', 'email' => 'feri.kurniawan@student.srma.sch.id'],

            // Kelas XI (25 siswa)
            ['name' => 'Gandi Pratama', 'email' => 'gandi.pratama@student.srma.sch.id'],
            ['name' => 'Gara Kusuma', 'email' => 'gara.kusuma@student.srma.sch.id'],
            ['name' => 'Garni Setiawan', 'email' => 'garni.setiawan@student.srma.sch.id'],
            ['name' => 'Gina Hermawati', 'email' => 'gina.hermawati@student.srma.sch.id'],
            ['name' => 'Gista Rahmawati', 'email' => 'gista.rahmawati@student.srma.sch.id'],
            ['name' => 'Giyo Kurniawan', 'email' => 'giyo.kurniawan@student.srma.sch.id'],
            ['name' => 'Glori Putri', 'email' => 'glori.putri@student.srma.sch.id'],
            ['name' => 'Gogot Setiawan', 'email' => 'gogot.setiawan@student.srma.sch.id'],
            ['name' => 'Goni Hermanto', 'email' => 'goni.hermanto@student.srma.sch.id'],
            ['name' => 'Gora Kusuma', 'email' => 'gora.kusuma@student.srma.sch.id'],
            ['name' => 'Gora Pratama', 'email' => 'gora.pratama@student.srma.sch.id'],
            ['name' => 'Hadi Kurniawan', 'email' => 'hadi.kurniawan@student.srma.sch.id'],
            ['name' => 'Hana Putri', 'email' => 'hana.putri@student.srma.sch.id'],
            ['name' => 'Hani Setiawan', 'email' => 'hani.setiawan@student.srma.sch.id'],
            ['name' => 'Hardi Hermawan', 'email' => 'hardi.hermawan@student.srma.sch.id'],
            ['name' => 'Hedi Kurniawan', 'email' => 'hedi.kurniawan@student.srma.sch.id'],
            ['name' => 'Hendra Wijaya', 'email' => 'hendra.wijaya@student.srma.sch.id'],
            ['name' => 'Hera Kusuma', 'email' => 'hera.kusuma@student.srma.sch.id'],
            ['name' => 'Herdi Setiawan', 'email' => 'herdi.setiawan@student.srma.sch.id'],
            ['name' => 'Hesti Rahmawati', 'email' => 'hesti.rahmawati@student.srma.sch.id'],
            ['name' => 'Hilman Hermanto', 'email' => 'hilman.hermanto@student.srma.sch.id'],
            ['name' => 'Hisam Kurniawan', 'email' => 'hisam.kurniawan@student.srma.sch.id'],
            ['name' => 'Hisya Putri', 'email' => 'hisya.putri@student.srma.sch.id'],
            ['name' => 'Hitung Setiawan', 'email' => 'hitung.setiawan@student.srma.sch.id'],
            ['name' => 'Hoga Wijaya', 'email' => 'hoga.wijaya@student.srma.sch.id'],

            // Kelas XII (25 siswa)
            ['name' => 'Idam Hermawan', 'email' => 'idam.hermawan@student.srma.sch.id'],
            ['name' => 'Idha Kusuma', 'email' => 'idha.kusuma@student.srma.sch.id'],
            ['name' => 'Idris Setiawan', 'email' => 'idris.setiawan@student.srma.sch.id'],
            ['name' => 'Ifana Putri', 'email' => 'ifana.putri@student.srma.sch.id'],
            ['name' => 'Iga Rahmawati', 'email' => 'iga.rahmawati@student.srma.sch.id'],
            ['name' => 'Igov Kurniawan', 'email' => 'igov.kurniawan@student.srma.sch.id'],
            ['name' => 'Ihram Hermanto', 'email' => 'ihram.hermanto@student.srma.sch.id'],
            ['name' => 'Ijas Wijaya', 'email' => 'ijas.wijaya@student.srma.sch.id'],
            ['name' => 'Ijra Kusuma', 'email' => 'ijra.kusuma@student.srma.sch.id'],
            ['name' => 'Ilham Setiawan', 'email' => 'ilham.setiawan@student.srma.sch.id'],
            ['name' => 'Ilmi Putri', 'email' => 'ilmi.putri@student.srma.sch.id'],
            ['name' => 'Ilsa Rahmawati', 'email' => 'ilsa.rahmawati@student.srma.sch.id'],
            ['name' => 'Ilyas Kurniawan', 'email' => 'ilyas.kurniawan@student.srma.sch.id'],
            ['name' => 'Imam Hermawan', 'email' => 'imam.hermawan@student.srma.sch.id'],
            ['name' => 'Iman Wijaya', 'email' => 'iman.wijaya@student.srma.sch.id'],
            ['name' => 'Imama Kusuma', 'email' => 'imama.kusuma@student.srma.sch.id'],
            ['name' => 'Imari Setiawan', 'email' => 'imari.setiawan@student.srma.sch.id'],
            ['name' => 'Imbo Putri', 'email' => 'imbo.putri@student.srma.sch.id'],
            ['name' => 'Imma Rahmawati', 'email' => 'imma.rahmawati@student.srma.sch.id'],
            ['name' => 'Immah Kurniawan', 'email' => 'immah.kurniawan@student.srma.sch.id'],
            ['name' => 'Imo Hermanto', 'email' => 'imo.hermanto@student.srma.sch.id'],
            ['name' => 'Imog Wijaya', 'email' => 'imog.wijaya@student.srma.sch.id'],
            ['name' => 'Imom Kusuma', 'email' => 'imom.kusuma@student.srma.sch.id'],
            ['name' => 'Imon Setiawan', 'email' => 'imon.setiawan@student.srma.sch.id'],
            ['name' => 'Imot Putri', 'email' => 'imot.putri@student.srma.sch.id'],
        ];

        // Create Teachers
        foreach ($teachers as $teacher) {
            User::create([
                'name' => $teacher['name'],
                'email' => $teacher['email'],
                'password' => Hash::make('password123'),
                'role' => 'guru',
            ]);
        }

        // Create Students
        foreach ($students as $student) {
            User::create([
                'name' => $student['name'],
                'email' => $student['email'],
                'password' => Hash::make('password123'),
                'role' => 'siswa',
            ]);
        }

        $this->command->info('✅ 25 Guru dan 75 Siswa berhasil dibuat!');
        $this->command->info('📝 Password default: password123');
    }
}
