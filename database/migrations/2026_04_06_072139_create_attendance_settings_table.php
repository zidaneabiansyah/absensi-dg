<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        Schema::create('attendance_settings', function (Blueprint $table) {
            $table->id();
            $table->time('check_in_start');
            $table->time('check_in_end');
            $table->time('check_out_start');
            $table->time('check_out_end');
            $table->time('late_threshold');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default settings
        DB::table('attendance_settings')->insert([
            'check_in_start' => '07:00:00',
            'check_in_end' => '07:30:00',
            'check_out_start' => '14:00:00',
            'check_out_end' => '15:00:00',
            'late_threshold' => '07:30:00',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_settings');
    }
};
