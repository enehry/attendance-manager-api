<?php

namespace App\Modules\Attendance\Actions;

use App\Modules\Attendance\DTOs\ZkTecoPunchData;
use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\Attendance\Models\RawBiometricLog;
use App\Modules\HRIS\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Handles the single responsibility of processing an incoming real-time ADMS record
 */
class ProcessRealtimePunchAction
{
    public function execute(ZkTecoPunchData $dto): RawBiometricLog
    {
        // 1. Save the raw log immediately using strict DTO properties
        $rawLog = RawBiometricLog::create([
            'terminal_sn' => $dto->terminalSn,
            'employee_pin' => $dto->employeePin,
            'punch_time' => $dto->punchTime,
            'punch_state' => $dto->punchState,
            'verify_type' => $dto->verifyType,
            'is_processed' => false,
        ]);

        try {
            $employee = Employee::where('employee_id', $dto->employeePin)->first();

            // If employee doesn't exist yet, safe exit.
            if (! $employee) {
                return $rawLog;
            }

            $punchTime = $rawLog->punch_time;
            $dateString = $punchTime->toDateString();

            // 2. Cooldown Logic (10 minutes)
            $lastProcessedLog = RawBiometricLog::where('employee_pin', $dto->employeePin)
                ->where('id', '!=', $rawLog->id)
                ->where('is_processed', true)
                ->latest('punch_time')
                ->first();

            if ($lastProcessedLog && $punchTime->diffInMinutes($lastProcessedLog->punch_time) < 10) {
                Log::info('Punch ignored due to cooldown', ['employee' => $employee->employee_id]);
                $rawLog->update(['is_processed' => true]);

                return $rawLog;
            }

            // 3. Find today's AttendanceLog or create it
            $attendance = AttendanceLog::firstOrCreate(
                ['employee_id' => $employee->id, 'date' => $dateString]
            );

            // 4. Real-Time Smart Assignment (Best Guess)
            $this->assignPunch($attendance, $punchTime);

            $rawLog->update(['is_processed' => true]);

        } catch (\Exception $e) {
            Log::error('ProcessRealtimePunchAction Error: '.$e->getMessage());
        }

        return $rawLog;
    }

    private function assignPunch(AttendanceLog $attendance, Carbon $punchTime)
    {
        if (is_null($attendance->time_in)) {
            $attendance->time_in = $punchTime;
        } elseif (is_null($attendance->break_out)) {
            $attendance->break_out = $punchTime;
        } elseif (is_null($attendance->break_in)) {
            $attendance->break_in = $punchTime;
        } else {
            $attendance->time_out = $punchTime;
        }

        $attendance->save();
    }
}
