<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClearDataSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear all tables except users and migrations
        DB::table('activity_logs')->truncate();
        DB::table('submissions')->truncate();
        DB::table('grades')->truncate();
        DB::table('assignments')->truncate();
        DB::table('materials')->truncate();
        DB::table('class_student')->truncate();
        DB::table('e_classes')->truncate();
        DB::table('subjects')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ Data berhasil dikosongkan! (Users tetap tersimpan)');
    }
}
