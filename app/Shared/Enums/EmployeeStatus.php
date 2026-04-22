<?php

namespace App\Shared\Enums;

enum EmployeeStatus: string
{
    case Permanent = 'permanent';
    case Casual = 'casual';
    case Contractual = 'contractual';
    case Coterminous = 'coterminous';
    case Resigned = 'resigned';
    case Retired = 'retired';
    case Terminated = 'terminated';

    public function label(): string
    {
        return match ($this) {
            self::Permanent => 'Permanent',
            self::Casual => 'Casual',
            self::Contractual => 'Contractual',
            self::Coterminous => 'Co-terminous',
            self::Resigned => 'Resigned',
            self::Retired => 'Retired',
            self::Terminated => 'Terminated',
        };
    }

    public function isActive(): bool
    {
        return in_array($this, [
            self::Permanent,
            self::Casual,
            self::Contractual,
            self::Coterminous,
        ]);
    }

    public static function names(): array
    {
        return array_map(fn ($e) => $e->value, self::cases());
    }
}
