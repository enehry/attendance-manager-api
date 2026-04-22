<?php

namespace App\Modules\Attendance\DTOs;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * Spatie Data Transfer Object for ZKTeco ADMS Punches.
 */
#[MapName(SnakeCaseMapper::class)]
class ZkTecoPunchData extends Data
{
    public function __construct(
        public string $terminalSn,
        public string $employeePin,
        public Carbon $punchTime,
        public ?string $punchState = null,
        public ?string $verifyType = null,
    ) {}
}
