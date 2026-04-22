<?php

namespace App\Modules\Schedule\Repositories;

use App\Modules\Schedule\Models\Schedule;

/**
 * Schedule Repository
 *
 * Purpose: Handles database queries for Employee Schedules.
 */
class ScheduleRepository
{
    public function __construct(
        protected Schedule $model
    ) {}

    public function getActiveSchedules()
    {
        return $this->model->all();
    }
}
