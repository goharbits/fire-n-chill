<?php

namespace App\Providers;

use App\Repositories\AuthRepository\AuthRepository;
use Illuminate\Support\ServiceProvider;

class RespositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('AuthRepository', function () {
            return new AuthRepository();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
