<?php
namespace App\Providers;

use App\Events\NotificationCreated;
use App\Listeners\SendNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
/**
* The event to listener mappings for the application.
*
* @var array
*/
        protected $listen = [
            NotificationCreated::class => [
                 SendNotification::class,
            ],
        ];

         public function boot()
    {
        parent::boot();
    }

// ...
}