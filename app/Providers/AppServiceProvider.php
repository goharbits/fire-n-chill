<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        Passport::ignoreRoutes();
        // Passport::ignoreRoutes();
        // Passport::loadKeysFrom(__DIR__ . '/../secrets/oauth');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }
        Vite::macro('image', fn (string $asset) => $this->asset("resources/images/{$asset}"));
        Vite::macro('video', fn (string $asset) => $this->asset("resources/videos/{$asset}"));
    }
}
