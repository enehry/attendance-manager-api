<?php

namespace App\Modules\HRIS\Events;

use App\Modules\HRIS\Models\Employee;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmployeeCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly Employee $employee) {}
}
