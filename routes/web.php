<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

// require __DIR__.'/auth.php';

Route::get('/check', function () {
    return response()->json([
        'message' => 'Attendance Manager API is running',
    ]);
});
