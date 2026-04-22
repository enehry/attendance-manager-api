<?php

namespace App\Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;

class RawBiometricLog extends Model
{
    protected $fillable = [
        'terminal_sn',
        'employee_pin',
        'punch_time',
        'punch_state',
        'verify_type',
        'is_processed',
    ];

    protected $casts = [
        'punch_time' => 'datetime',
        'is_processed' => 'boolean',
    ];
}
