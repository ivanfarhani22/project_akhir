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
        // Attendance sessions - opened by teacher for specific subject in a class, managed by admin
        if (!Schema::hasTable('attendance_sessions')) {
            Schema::create('attendance_sessions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('class_subject_id')->constrained('class_subjects')->onDelete('cascade'); // Subject in class
                $table->foreignId('opened_by')->constrained('users')->onDelete('cascade'); // Teacher who opened
                $table->date('attendance_date');
                $table->time('opened_at');
                $table->time('closed_at')->nullable();
                $table->enum('status', ['open', 'closed', 'cancelled'])->default('open'); // open, closed, cancelled
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->unique(['class_subject_id', 'attendance_date']); // Only one session per subject per day
                $table->index(['status', 'attendance_date']);
            });
        }

        // Student attendance records
        if (!Schema::hasTable('attendance_records')) {
            Schema::create('attendance_records', function (Blueprint $table) {
                $table->id();
                $table->foreignId('attendance_session_id')->constrained('attendance_sessions')->onDelete('cascade');
                $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
                $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('absent');
                $table->time('checked_in_at')->nullable(); // When student checked in
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->unique(['attendance_session_id', 'student_id']); // Only one record per student per session
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
        Schema::dropIfExists('attendance_sessions');
    }
};
