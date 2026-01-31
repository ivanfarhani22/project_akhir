<?php

namespace App\Console\Commands;

use App\Models\StudentData;
use App\Models\StudentDistribution;
use App\Models\ActivityLog;
use Illuminate\Console\Command;

class CleanupOldStudentData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'student:cleanup 
                            {--years=10 : Hapus data lebih dari N tahun}
                            {--dry-run : Tampilkan data yang akan dihapus tanpa menghapus}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus data siswa dan persebaran siswa yang lebih dari N tahun (default: 10 tahun)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $years = (int) $this->option('years');
        $dryRun = $this->option('dry-run');
        
        // Hitung cutoff tahun ajaran
        $currentYear = date('Y');
        $month = date('n');
        
        // Jika bulan >= 7, tahun ajaran aktif adalah currentYear/currentYear+1
        // Jika bulan < 7, tahun ajaran aktif adalah currentYear-1/currentYear
        $activeStartYear = $month >= 7 ? $currentYear : $currentYear - 1;
        
        // Cutoff: tahun ajaran yang dimulai $years tahun lalu
        $cutoffStartYear = $activeStartYear - $years;
        $cutoffAcademicYear = $cutoffStartYear . '/' . ($cutoffStartYear + 1);
        
        $this->info("=== Cleanup Data Siswa ===");
        $this->info("Tahun ajaran aktif: {$activeStartYear}/" . ($activeStartYear + 1));
        $this->info("Menghapus data sebelum: {$cutoffAcademicYear}");
        $this->newLine();
        
        // Ambil data yang akan dihapus
        $oldStudentData = StudentData::where('academic_year', '<', $cutoffAcademicYear)
            ->orderBy('academic_year')
            ->get();
        
        $oldDistributions = StudentDistribution::where('academic_year', '<', $cutoffAcademicYear)
            ->orderBy('academic_year')
            ->get();
        
        if ($oldStudentData->isEmpty() && $oldDistributions->isEmpty()) {
            $this->info("✓ Tidak ada data lama yang perlu dihapus.");
            return 0;
        }
        
        // Tampilkan data yang akan dihapus
        if ($oldStudentData->isNotEmpty()) {
            $this->warn("Data Siswa yang akan dihapus ({$oldStudentData->count()} records):");
            $headers = ['ID', 'Tahun Ajaran', 'Kelas', 'L', 'P', 'Total'];
            $rows = $oldStudentData->map(fn($d) => [
                $d->id, $d->academic_year, $d->class_name, 
                $d->male_count, $d->female_count, $d->total_students
            ])->toArray();
            $this->table($headers, $rows);
        }
        
        if ($oldDistributions->isNotEmpty()) {
            $this->warn("Data Persebaran Siswa yang akan dihapus ({$oldDistributions->count()} records):");
            $headers = ['ID', 'Tahun Ajaran', 'Kecamatan', 'Jumlah'];
            $rows = $oldDistributions->map(fn($d) => [
                $d->id, $d->academic_year, $d->district, $d->student_count
            ])->toArray();
            $this->table($headers, $rows);
        }
        
        if ($dryRun) {
            $this->newLine();
            $this->info("🔍 Mode dry-run: Tidak ada data yang dihapus.");
            $this->info("Jalankan tanpa --dry-run untuk menghapus data.");
            return 0;
        }
        
        // Konfirmasi penghapusan
        if (!$this->confirm('Yakin ingin menghapus data di atas?')) {
            $this->info("Dibatalkan.");
            return 0;
        }
        
        // Hapus data
        $deletedStudentData = StudentData::where('academic_year', '<', $cutoffAcademicYear)->delete();
        $deletedDistributions = StudentDistribution::where('academic_year', '<', $cutoffAcademicYear)->delete();
        
        // Log aktivitas
        ActivityLog::log(
            'delete', 
            "Auto-cleanup: Menghapus {$deletedStudentData} data siswa dan {$deletedDistributions} data persebaran sebelum tahun ajaran {$cutoffAcademicYear}",
            null, 
            null
        );
        
        $this->newLine();
        $this->info("✓ Berhasil menghapus:");
        $this->info("  - {$deletedStudentData} data siswa");
        $this->info("  - {$deletedDistributions} data persebaran siswa");
        
        return 0;
    }
}
