<?php

namespace App\Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;

class DtrSummary extends Model
{
    // A DTR Summary has many daily logs
    public function attendanceLogs()
    {
        // Dynamically link logs based on the employee and the date range
        return $this->hasMany(AttendanceLog::class, 'employee_id', 'employee_id')
            ->whereBetween('date', [$this->period_start_date, $this->period_end_date]);
    }
}
