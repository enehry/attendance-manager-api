<?php

namespace App\Providers;

use App\Modules\HRIS\Services\DepartmentService;
use Illuminate\Support\ServiceProvider;

class DepartmentProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // $this->app->bind(DepartmentService::class);
    }
}
