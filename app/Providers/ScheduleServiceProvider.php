<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use Illuminate\Console\Scheduling\Schedule;

class ScheduleServiceProvider extends ServiceProvider
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
        // $schedule->command('appointments:reminders')->everyMinute();

    }
}