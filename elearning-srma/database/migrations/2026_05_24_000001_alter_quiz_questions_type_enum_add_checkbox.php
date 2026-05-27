<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update enum values to match current quiz builder types
        DB::statement("ALTER TABLE `quiz_questions` MODIFY `type` ENUM('multiple_choice','checkbox','short_answer') NOT NULL DEFAULT 'multiple_choice'");
    }

    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE `quiz_questions` MODIFY `type` ENUM('multiple_choice','true_false','short_answer') NOT NULL DEFAULT 'multiple_choice'");
    }
};
