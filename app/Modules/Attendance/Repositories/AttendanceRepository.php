<?php

namespace App\Modules\Attendance\Repositories;

use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\Attendance\Models\RawBiometricLog;

/**
 * Attendance Repository
 *
 * Purpose: Centralizes all complex queries involving Attendance Logs,
 * Raw Logs, and DTR Summaries.
 */
class AttendanceRepository
{
    public function __construct(
        protected AttendanceLog $attendanceLog,
        protected RawBiometricLog $rawLog
    ) {}

    public function getLogsForDateRange(string $employeeId, string $startDate, string $endDate)
    {
        return $this->attendanceLog
            ->where('employee_id', $employeeId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();
    }

    public function getRawLogsForDay(string $employeePin, string $date)
    {
        return $this->rawLog
            ->where('employee_pin', $employeePin)
            ->whereDate('punch_time', $date)
            ->orderBy('punch_time', 'asc')
            ->get();
    }
}
