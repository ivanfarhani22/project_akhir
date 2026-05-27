<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('daily_reports');
    }

    public function down(): void
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->date('report_date');

            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('created_by_role', 32)->nullable();

            $table->text('notes')->nullable();
            $table->decimal('average_grade', 5, 2)->nullable();
            $table->unsignedSmallInteger('attendance_present')->nullable();
            $table->unsignedSmallInteger('attendance_total')->nullable();

            $table->timestamps();

            $table->unique(['student_id', 'report_date']);
            $table->index(['report_date']);
        });
    }
};
