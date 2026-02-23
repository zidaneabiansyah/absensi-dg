<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add missing indexes to attendances table for faster filtering
        Schema::table('attendances', function (Blueprint $table) {
            // Index for status filtering (commonly used in stats queries)
            if (!$this->indexExists('attendances', 'attendances_status_index')) {
                $table->index('status');
            }
            
            // Index for date and status combined (used in daily stat queries)
            if (!$this->indexExists('attendances', 'attendances_date_status_index')) {
                $table->index(['date', 'status']);
            }
            
            // Index for student and status (used in reports)
            if (!$this->indexExists('attendances', 'attendances_student_id_status_index')) {
                $table->index(['student_id', 'status']);
            }
        });

        // Add indexes to students table for faster lookups
        Schema::table('students', function (Blueprint $table) {
            // Index for RFID lookups (auto-attendance scanning)
            if (Schema::hasColumn('students', 'rfid_uid') && !$this->indexExists('students', 'students_rfid_uid_index')) {
                $table->index('rfid_uid');
            }
        });

        // Add index to classes table for academic year lookups
        Schema::table('classes', function (Blueprint $table) {
            if (!$this->indexExists('classes', 'classes_academic_year_index')) {
                $table->index('academic_year');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if ($this->indexExists('attendances', 'attendances_status_index')) {
                $table->dropIndex(['status']);
            }
            if ($this->indexExists('attendances', 'attendances_date_status_index')) {
                $table->dropIndex(['date', 'status']);
            }
            if ($this->indexExists('attendances', 'attendances_student_id_status_index')) {
                $table->dropIndex(['student_id', 'status']);
            }
        });

        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'rfid_uid') && $this->indexExists('students', 'students_rfid_uid_index')) {
                $table->dropIndex(['rfid_uid']);
            }
        });

        Schema::table('classes', function (Blueprint $table) {
            if ($this->indexExists('classes', 'classes_academic_year_index')) {
                $table->dropIndex(['academic_year']);
            }
        });
    }

    /**
     * Check if index exists
     */
    private function indexExists(string $table, string $indexName): bool
    {
        try {
            $indexes = DB::select("
                SELECT indexname FROM pg_indexes 
                WHERE tablename = ? AND indexname = ?
            ", [$table, $indexName]);
            
            return !empty($indexes);
        } catch (\Exception $e) {
            // Fallback for non-PostgreSQL databases
            return false;
        }
    }
};
