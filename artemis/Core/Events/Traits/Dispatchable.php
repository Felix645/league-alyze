<?php


namespace Artemis\Core\Events\Traits;


trait Dispatchable
{
    /**
     * Dispatches this event.
     *
     * @return void
     */
    public static function dispatch()
    {
        event(static::class);
    }
}