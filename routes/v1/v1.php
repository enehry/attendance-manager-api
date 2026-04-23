<?php

use App\Modules\HRIS\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

/**
 * Version 1 Modular Monolith API
 */
Route::prefix('v1')->group(function () {
    // Auth Routes
    Route::prefix('auth')->group(base_path('routes/v1/modules/auth.php'));

    // Load HRIS Module Routes
    Route::prefix('hris')->group(base_path('routes/v1/modules/hris.api.php'));

    // Load Attendance & ZkTeco Module Routes
    Route::prefix('attendance')->group(base_path('routes/v1/modules/attendance.api.php'));

});

Route::get('employee-photo/{path}', [EmployeeController::class, 'photo'])
    ->name('employee-photo')->where('path', '.*');
