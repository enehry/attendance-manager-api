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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Morning Shift'
            $table->time('start_time');
            $table->time('end_time');

            // Break properties
            $table->time('break_start_time')->nullable();
            $table->time('break_end_time')->nullable();
            $table->integer('break_duration_minutes')->default(60); // Total allocated break time

            $table->integer('grace_period_minutes')->default(15);
            $table->json('work_days'); // e.g., ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
