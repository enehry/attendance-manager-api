<?php

use App\Modules\Attendance\Http\Controllers\ZkTecoAdmsController;
use Illuminate\Support\Facades\Route;

/**
 * Attendance Module Routes
 * Prefix: /api/v1/attendance
 */

// ZKTeco ADMS Routes
Route::prefix('iclock')->group(function () {
    Route::get('cdata', [ZkTecoAdmsController::class, 'handshake']);
    Route::post('cdata', [ZkTecoAdmsController::class, 'receiveCdata']);
});
