<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            if (!Schema::hasColumn('materials', 'class_subject_id')) {
                $table->foreignId('class_subject_id')
                    ->nullable()
                    ->after('e_class_id')
                    ->constrained('class_subjects')
                    ->nullOnDelete();

                $table->index(['e_class_id', 'class_subject_id']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            if (Schema::hasColumn('materials', 'class_subject_id')) {
                $table->dropForeign(['class_subject_id']);
                $table->dropIndex(['e_class_id', 'class_subject_id']);
                $table->dropColumn('class_subject_id');
            }
        });
    }
};
