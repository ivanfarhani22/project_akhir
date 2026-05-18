<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('assignments', 'type')) {
                $table->string('type')->default('task')->after('title');
                $table->index('type');
            }
        });

        // Backfill existing quiz-internal assignments that were previously marked by description = 'Quiz'
        if (Schema::hasColumn('assignments', 'type') && Schema::hasColumn('assignments', 'description')) {
            \DB::table('assignments')
                ->where('description', 'Quiz')
                ->update(['type' => 'quiz']);
        }
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (Schema::hasColumn('assignments', 'type')) {
                $table->dropIndex(['type']);
                $table->dropColumn('type');
            }
        });
    }
};
