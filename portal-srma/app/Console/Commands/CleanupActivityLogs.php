<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Command untuk membersihkan log activity yang sudah lama
 * Implementasi retensi data sesuai standar audit trail
 * 
 * Usage: php artisan logs:cleanup
 * Dengan option: php artisan logs:cleanup --months=12
 */
class CleanupActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:cleanup 
                            {--months=6 : Jumlah bulan data yang dipertahankan}
                            {--dry-run : Preview tanpa menghapus data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membersihkan activity logs yang lebih lama dari periode retensi (default: 6 bulan)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $months = (int) $this->option('months');
        $dryRun = $this->option('dry-run');
        
        $cutoffDate = now()->subMonths($months);
        
        // Hitung jumlah record yang akan dihapus
        $count = ActivityLog::where('created_at', '<', $cutoffDate)->count();
        
        if ($count === 0) {
            $this->info('✅ Tidak ada log yang perlu dihapus.');
            return Command::SUCCESS;
        }

        $this->info("📊 Ditemukan {$count} log yang lebih lama dari {$months} bulan.");
        $this->info("📅 Cutoff date: {$cutoffDate->format('d M Y H:i:s')}");
        
        if ($dryRun) {
            $this->warn('🔍 [DRY RUN] Tidak ada data yang dihapus.');
            return Command::SUCCESS;
        }

        // Konfirmasi untuk interaktif (bukan scheduler)
        if ($this->input->isInteractive()) {
            if (!$this->confirm("Apakah Anda yakin ingin menghapus {$count} log?")) {
                $this->info('❌ Operasi dibatalkan.');
                return Command::SUCCESS;
            }
        }

        // Hapus dalam batch untuk performa
        $deletedCount = 0;
        $batchSize = 1000;

        $this->output->progressStart($count);

        while (true) {
            $deleted = ActivityLog::where('created_at', '<', $cutoffDate)
                ->limit($batchSize)
                ->delete();
            
            if ($deleted === 0) {
                break;
            }
            
            $deletedCount += $deleted;
            $this->output->progressAdvance($deleted);
        }

        $this->output->progressFinish();

        // Log hasil ke file log system
        Log::info("Activity logs cleanup completed", [
            'deleted_count' => $deletedCount,
            'retention_months' => $months,
            'cutoff_date' => $cutoffDate->toDateTimeString(),
        ]);

        $this->info("✅ Berhasil menghapus {$deletedCount} log activity.");
        
        return Command::SUCCESS;
    }
}
