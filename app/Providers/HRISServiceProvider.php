<?php

namespace App\Providers;

use App\Modules\HRIS\Services\EmployeeService;
use App\Shared\Contracts\EmployeeDataContract;
use Illuminate\Support\ServiceProvider;

class HRISServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(EmployeeDataContract::class, EmployeeService::class);
    }
}
