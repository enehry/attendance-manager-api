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
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete()->index();
            $table->date('date')->index(); // Logical date of the shift
            $table->timestamp('time_in')->nullable();
            $table->timestamp('break_out')->nullable(); // Left for break
            $table->timestamp('break_in')->nullable();  // Returned from break
            $table->timestamp('time_out')->nullable();

            $table->string('status')->default('present'); // e.g., on_time, late, absent
            $table->integer('late_minutes')->default(0);
            $table->integer('overbreak_minutes')->default(0); // If they took too long on break

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
