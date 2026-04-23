<?php

use App\Modules\HRIS\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::delete('employees/{id}/force-delete', [EmployeeController::class, 'forceDelete']);
    Route::post('employees/{id}/restore', [EmployeeController::class, 'restore']);

    Route::apiResource('employees', EmployeeController::class);

});
