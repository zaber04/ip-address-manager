<?php

namespace Authentication\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // \Authentication\Events\UserEvent::class => [
        //     \IpHandler\Listeners\AuditTrailListener::class,
        //     // this is an issue --> how will I sync now? docker don't give acccess to this location
        // ],
    ];

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
