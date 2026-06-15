<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClearDataSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks (MySQL only)
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // Clear all tables except users and migrations
        $tables = [
            'attendance_sessions',
            'attendances',
            'schedules',
            'schedule_activities',
            'class_subjects',
            'materials',
            'assignments',
            'submissions',
            'grades',
            'activity_logs',
            'class_student',
            'e_classes',
            'subjects',
            'settings',
        ];

        foreach ($tables as $table) {
            try {
                DB::table($table)->truncate();
            } catch (\Throwable $e) {
                // ignore if table doesn't exist in current schema
            }
        }

        // Re-enable foreign key checks
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $this->command->info('✅ Data e-learning berhasil dikosongkan! (Users tetap tersimpan)');
    }
}
