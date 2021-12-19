<?php


namespace Artemis\Core\Events;


use Artemis\Core\Events\Exceptions\NoHandleException;
use Artemis\Core\Events\Exceptions\NotDispatchableException;
use Artemis\Core\Events\Traits\Dispatchable;


class Dispatcher
{
    /**
     * Colletion of listeners by event.
     *
     * @var array
     */
    private $listeners = [];

    /**
     * Registers a listener for given event.
     *
     * @param string $event
     * @param string $listener
     *
     * @return $this
     */
    public function listen($event, $listener)
    {
        $this->listeners[$event][] = $listener;

        return $this;
    }

    /**
     * Dispatches given event.
     *
     * @param $event
     *
     * @return void
     */
    public function dispatch($event)
    {
        $event = $this->buildEvent($event);
        $event_class = get_class($event);

        $listeners = $this->listeners[$event_class] ?? [];

        foreach( $listeners as $listener ) {
            $this->checkListener($listener);

            $instance = container($listener);
            $instance->handle($event);
        }
    }

    /**
     * Gets the given event instance.
     *
     * @param object|string $event
     *
     * @return object
     */
    private function buildEvent($event)
    {
        if( is_string($event) && class_exists($event) && $this->eventHasDispatchable($event) ) {
            return container($event);
        }

        if( is_object($event) && $this->eventHasDispatchable($event) ) {
            return $event;
        }

        report(new NotDispatchableException($event));
        exit;
    }

    /**
     * Checks if given listener has the 'handle' method, throws exception if not.
     *
     * @param string $listener
     *
     * @return void
     */
    private function checkListener($listener)
    {
        if( !$this->listenerHasHandleMethod($listener) ) {
            report(new NoHandleException($listener));
        }
    }

    /**
     * Checks if given event has the Dispatchable trait.
     *
     * @param string|object $event
     *
     * @return bool
     */
    private function eventHasDispatchable($event)
    {
        return in_array(Dispatchable::class, class_uses_recursive($event), true);
    }

    /**
     * Checks if given listener has the 'handle' method.
     *
     * @param string $listener
     *
     * @return bool
     */
    private function listenerHasHandleMethod($listener)
    {
        return method_exists($listener, 'handle');
    }
}