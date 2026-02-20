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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['H', 'I', 'S', 'A', 'T'])->comment('H=Hadir, I=Izin, S=Sakit, A=Alpha, T=Terlambat');
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->enum('source', ['manual', 'rfid'])->default('manual');
            $table->timestamps();
            
            $table->index(['student_id', 'date']);
            $table->index('date');
            $table->unique(['student_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
