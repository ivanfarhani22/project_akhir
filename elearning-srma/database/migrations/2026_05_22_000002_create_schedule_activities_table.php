<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_activities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('schedule_id')->constrained('schedules')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->date('activity_date');

            // Optional fields for guru
            $table->unsignedSmallInteger('score')->nullable();
            $table->text('notes')->nullable();

            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            $table->timestamps();

            // One activity per student per schedule per date
            $table->unique(['schedule_id', 'student_id', 'activity_date']);
            $table->index(['activity_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_activities');
    }
};
