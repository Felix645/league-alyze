<?php


namespace Artemis\Resource\Providers;


use Artemis\Core\Providers\EventServiceProvider as ServiceProvider;


class EventServiceProvider extends ServiceProvider
{
    /**
     * Collection of events and their listeners.
     *
     * @var array
     */
    protected $listeners = [
        // \App\Events\MyEvent::class => [
        //     \App\Listeners\FirstListener::class,
        //     \App\Listeners\SecondListener::class
        // ]
    ];

    /**
     * @inheritDoc
     */
    public function register()
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->bootListeners();
    }
}