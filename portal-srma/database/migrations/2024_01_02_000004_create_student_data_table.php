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
        Schema::create('student_data', function (Blueprint $table) {
            $table->id();
            $table->string('academic_year'); // Tahun Ajaran, e.g., "2024/2025"
            $table->string('class_name'); // Nama Kelas, e.g., "X", "XI", "XII"
            $table->integer('male_count')->default(0); // Jumlah Laki-laki
            $table->integer('female_count')->default(0); // Jumlah Perempuan
            $table->integer('study_groups')->default(1); // Rombongan Belajar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_data');
    }
};
