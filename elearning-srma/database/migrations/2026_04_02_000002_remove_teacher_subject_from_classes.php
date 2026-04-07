<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus foreign keys terlebih dahulu jika ada
        Schema::table('e_classes', function (Blueprint $table) {
            // Drop foreign keys
            $foreignKeys = \Illuminate\Support\Facades\DB::select(
                "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_NAME = 'e_classes' AND COLUMN_NAME IN ('teacher_id', 'subject_id')"
            );
            
            foreach ($foreignKeys as $fk) {
                try {
                    $table->dropForeign($fk->CONSTRAINT_NAME);
                } catch (\Exception $e) {
                    // FK might not exist
                }
            }
        });

        // Hapus kolom teacher_id dan subject_id
        Schema::table('e_classes', function (Blueprint $table) {
            if (Schema::hasColumn('e_classes', 'teacher_id')) {
                $table->dropColumn('teacher_id');
            }
            if (Schema::hasColumn('e_classes', 'subject_id')) {
                $table->dropColumn('subject_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('e_classes', function (Blueprint $table) {
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade')->after('name');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade')->after('teacher_id');
        });
    }
};
