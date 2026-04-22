<?php

namespace App\Shared\Traits;

use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * Apply to every model that requires government audit compliance.
 * Logs all creates, updates, and deletes with the authenticated user,
 * old values, and new values.
 *
 * Usage:
 *   class Employee extends Model { use HasAuditLog; }
 */
trait HasAuditLog
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            // ->dontSubmitEmptyLogs()
            ->useLogName(static::class)
            ->setDescriptionForEvent(fn (string $eventName) => match ($eventName) {
                'created' => 'Record created',
                'updated' => 'Record updated',
                'deleted' => 'Record deleted',
                default => $eventName,
            });
    }
}
