<?php

namespace App\Modules\Schedule\Models;

use App\Modules\HRIS\Models\Employee;
use Database\Factories\ScheduleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $casts = [
        'work_days' => 'array', // Convert JSON automatically to PHP Array
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    protected static function newFactory()
    {
        return ScheduleFactory::new();
    }
}
