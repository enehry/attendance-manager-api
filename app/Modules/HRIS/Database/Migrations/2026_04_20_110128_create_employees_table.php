<?php

use App\Models\User;
use App\Modules\Schedule\Models\Schedule;
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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('employee_number')->unique();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('address');
            $table->string('employment_status');
            $table->foreignIdFor(User::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Schedule::class)->index()->constrained()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();

            // create a condition if middle name is empty remove it and if not make it middle initial
            $table->string('full_name')
                ->storedAs("first_name || ' ' || COALESCE(middle_name || ' ', '') || last_name")
                ->fulltext();

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
