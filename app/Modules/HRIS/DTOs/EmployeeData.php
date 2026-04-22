<?php

declare(strict_types=1);

namespace App\Modules\HRIS\DTOs;

use App\Shared\Enums\EmploymentStatus;
use Spatie\LaravelData\Data;

class EmployeeData extends Data
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly string $employee_number,
        public readonly string $last_name,
        public readonly string $first_name,
        public readonly ?string $middle_name,
        public readonly string $phone,
        public readonly string $address,
        public readonly EmploymentStatus $employment_status,
        public readonly int $user_id,
        public readonly int $schedule_id,
    ) {}
}
