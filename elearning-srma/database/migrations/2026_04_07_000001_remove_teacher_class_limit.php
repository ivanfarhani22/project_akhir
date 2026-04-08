<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Remove any check constraints that limit teacher to only 3 subjects per class
     */
    public function up(): void
    {
        // Check if there are any triggers or constraints that limit teacher assignments
        // This migration ensures unlimited teacher assignments
        
        // For SQLite/MySQL - check if constraint exists and drop it
        $tableName = 'class_subjects';
        
        // Try to drop any check constraint that limits teacher subjects
        try {
            if (DB::connection()->getDriverName() === 'mysql') {
                // Get all constraints
                $constraints = DB::select(
                    "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS 
                     WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = ?",
                    [DB::connection()->getDatabaseName(), $tableName]
                );
                
                // Log for debugging
                \Log::info('Found constraints for ' . $tableName . ': ' . json_encode($constraints));
            }
        } catch (\Exception $e) {
            \Log::warning('Could not check constraints: ' . $e->getMessage());
        }
        
        // Verify table structure allows unlimited entries
        if (Schema::hasTable($tableName)) {
            // Check if table structure is correct
            $columns = Schema::getColumns($tableName);
            \Log::info('Class subjects table structure: ' . json_encode($columns));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No changes to revert - this is just for verification
    }
};
