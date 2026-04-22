<?php

namespace App\Modules\Attendance\Console;

use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\Attendance\Models\RawBiometricLog;
use App\Modules\Employee\Models\Employee;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('attendance:realign {--date= : The date to realign (Y-m-d). Defaults to today.}')]
#[Description('EOD Batch Processor to mathematically self-heal and realign messy raw biometric punches')]
class RealignDailyAttendance extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateString = $this->option('date') ?: today()->toDateString();
        $this->info("Realigning attendance for date: {$dateString}");

        $employees = Employee::has('rawBiometricLogs')->get();

        foreach ($employees as $employee) {
            $rawLogs = RawBiometricLog::where('employee_pin', $employee->employee_id)
                ->whereDate('punch_time', $dateString)
                ->orderBy('punch_time', 'asc')
                ->get();

            if ($rawLogs->isEmpty()) {
                continue;
            }

            // Filter out rapid double punches (cooldown 10 mins)
            $filteredPunches = [];
            $lastTime = null;
            foreach ($rawLogs as $log) {
                if ($lastTime === null || $log->punch_time->diffInMinutes($lastTime) >= 10) {
                    $filteredPunches[] = $log->punch_time;
                    $lastTime = $log->punch_time;
                }
            }

            $punchCount = count($filteredPunches);
            $timeIn = $punchCount > 0 ? $filteredPunches[0] : null;
            $timeOut = $punchCount > 1 ? end($filteredPunches) : null;

            $breakOut = null;
            $breakIn = null;

            if ($punchCount >= 4) {
                $breakOut = $filteredPunches[1];
                $breakIn = $filteredPunches[2];
            } elseif ($punchCount === 3) {
                // Simplistic fallback for 3 punches
                $breakOut = $filteredPunches[1];
            }

            // Overwrite the Attendance Log safely
            AttendanceLog::updateOrCreate(
                ['employee_id' => $employee->id, 'date' => $dateString],
                [
                    'time_in' => $timeIn,
                    'break_out' => $breakOut,
                    'break_in' => $breakIn,
                    'time_out' => $timeOut,
                ]
            );
        }

        $this->info('Realignment complete.');
    }
}
