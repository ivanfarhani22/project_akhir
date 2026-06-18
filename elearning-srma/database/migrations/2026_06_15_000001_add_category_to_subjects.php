<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Tambah kolom category dengan enum: 'academic' (akademik/sekolah) atau 'non_academic' (non-akademik/asrama)
            $table->enum('category', ['academic', 'non_academic'])->default('academic')->after('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
