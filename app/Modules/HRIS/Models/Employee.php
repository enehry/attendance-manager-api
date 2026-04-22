<?php

namespace App\Modules\HRIS\Models;

use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\Attendance\Models\RawBiometricLog;
use App\Modules\Schedule\Models\Schedule;
use App\Shared\Enums\EmploymentStatus;
use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Attributes\WithCast;

#[Fillable([
    'employee_number',
    'last_name',
    'first_name',
    'middle_name',
    'phone',
    'address',
    'employment_status',
    'user_id',
    'schedule_id',
])]
#[WithCast([
    'employment_status' => EmploymentStatus::class,
])]
class Employee extends Model
{
    use HasFactory;
    use HasUuids;

    // The schedule an employee must follow
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    // Processed time records
    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    // The raw data sent by ZKTeco
    public function rawBiometricLogs()
    {
        return $this->hasMany(RawBiometricLog::class, 'employee_pin', 'employee_number');
    }

    // is active filter scope
    public function scopeActive($query)
    {
        return $query->whereIn('employment_status', [
            EmploymentStatus::Permanent,
            EmploymentStatus::Casual,
            EmploymentStatus::Contractual,
            EmploymentStatus::Coterminous,
        ]);
    }

    // use UUID in selecting/updating/deleting employee instead of id
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function uniqueIds()
    {
        return ['uuid'];
    }

    protected static function newFactory()
    {
        return EmployeeFactory::new();
    }
}
