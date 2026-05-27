<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'orang_tua' to enum users.role (MySQL)
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin_elearning','guru','siswa','orang_tua') NOT NULL DEFAULT 'siswa'");
    }

    public function down(): void
    {
        // Revert back (WARNING: will fail if any existing rows still have role='orang_tua')
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin_elearning','guru','siswa') NOT NULL DEFAULT 'siswa'");
    }
};
