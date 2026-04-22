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
        Schema::create('raw_biometric_logs', function (Blueprint $table) {
            $table->id();
            $table->string('terminal_sn')->index();
            $table->string('employee_pin')->index();
            $table->datetime('punch_time')->index();
            $table->string('punch_state')->nullable(); // e.g. 0, 1, 2 from machine
            $table->string('verify_type')->nullable(); // e.g. 1=Fingerprint, 4=Card
            $table->boolean('is_processed')->default(false)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_biometric_logs');
    }
};
