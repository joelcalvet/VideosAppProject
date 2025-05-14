<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\VideoCreated::class => [
            \App\ManualListeners\SendVideoCreatedNotification::class,
        ],
    ];



    public function boot() : void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false; // Desactiva la descoberta automàtica
    }
}
