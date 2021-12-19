<?php


namespace Artemis\Core\Providers;


use Artemis\Core\Events\Dispatcher;
use Artemis\Core\Interfaces\ProviderInterface;


abstract class EventServiceProvider implements ProviderInterface
{
    /**
     * Dispatcher instance.
     *
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Collection of events and their listeners.
     *
     * @var array
     */
    protected $listeners = [];

    /**
     * EventServiceProvider constructor.
     */
    public function __construct()
    {
        $this->dispatcher = container('event');
    }

    /**
     * Boots the event listeners.
     *
     * @return void
     */
    protected function bootListeners()
    {
        foreach( $this->listeners as $event => $listeners ) {
            $this->addEventListeners($event, $listeners);
        }
    }

    /**
     * Adds all listeners for given event.
     *
     * @param string $event
     * @param array $listeners
     *
     * @return void
     */
    private function addEventListeners($event, $listeners)
    {
        foreach( $listeners as $listener ) {
            $this->dispatcher->listen($event, $listener);
        }
    }
}