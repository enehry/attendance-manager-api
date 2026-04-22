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
        Schema::create('dtr_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete()->index();

            // The cut-off or payroll period (e.g. 1st to 15th, or 16th to 30th)
            $table->date('period_start_date');
            $table->date('period_end_date');

            // Aggregated totals from attendance_logs
            $table->integer('total_days_present')->default(0);
            $table->integer('total_days_absent')->default(0);
            $table->integer('total_late_minutes')->default(0);
            $table->integer('total_undertime_minutes')->default(0); // Early clock-outs or over-breaks
            $table->decimal('total_regular_hours', 8, 2)->default(0); // Regular worked hours
            $table->decimal('total_overtime_hours', 8, 2)->default(0); // Excess of regular hours

            // Generation / Approval Status
            $table->string('status')->default('draft'); // draft, generated, approved

            $table->timestamps();

            // Ensures an employee only has one DTR summary per period
            $table->unique(['employee_id', 'period_start_date', 'period_end_date'], 'dtr_employee_period_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dtr_summaries');
    }
};
