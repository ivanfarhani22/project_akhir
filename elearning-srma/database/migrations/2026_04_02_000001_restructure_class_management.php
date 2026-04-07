<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan kolom schedule ke e_classes jika belum ada
        if (!Schema::hasColumn('e_classes', 'day_of_week')) {
            Schema::table('e_classes', function (Blueprint $table) {
                $table->string('day_of_week')->nullable()->after('description');
                $table->time('start_time')->nullable()->after('day_of_week');
                $table->time('end_time')->nullable()->after('start_time');
                $table->string('room')->nullable()->after('end_time');
            });
        }

        // Buat tabel class_subjects
        if (!Schema::hasTable('class_subjects')) {
            Schema::create('class_subjects', function (Blueprint $table) {
                $table->id();
                $table->foreignId('e_class_id')->constrained('e_classes')->onDelete('cascade');
                $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
                $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
                $table->text('description')->nullable();
                $table->timestamps();
                $table->unique(['e_class_id', 'subject_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('class_subjects');
        
        if (Schema::hasColumn('e_classes', 'day_of_week')) {
            Schema::table('e_classes', function (Blueprint $table) {
                $table->dropColumn(['day_of_week', 'start_time', 'end_time', 'room']);
            });
        }
    }
};
