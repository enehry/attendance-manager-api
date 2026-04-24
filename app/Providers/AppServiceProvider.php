<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        Gate::define('viewApiDocs', function (?User $user) {
            return true;
        });

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        // Load Domain Specific Migrations
        $this->loadMigrationsFrom([
            app_path('Modules/HRIS/Database/Migrations'),
            app_path('Modules/Schedule/Database/Migrations'),
            app_path('Modules/Attendance/Database/Migrations'),
        ]);
    }
}
