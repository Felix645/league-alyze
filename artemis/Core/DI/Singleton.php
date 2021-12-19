<?php


namespace Artemis\Core\DI;


use Closure;


class Singleton
{
    /**
     * Holds the singleton instance.
     * If it was not yet retrieved it holds the binding instance instead.
     *
     * @var Binding|object
     */
    private $instance;

    /**
     * Adds a binding
     *
     * @param Binding $binding
     */
    public function addBinding($binding)
    {
        $this->instance = $binding;
    }

    /**
     * Adds an existing instance
     *
     * @param object $instance
     */
    public function addInstance($instance)
    {
        $this->instance = $instance;
    }

    /**
     * Gets the singleton instance
     *
     * @return Closure|mixed|string|null
     */
    public function get()
    {
        if( $this->instance instanceof Binding ) {
            $this->instance = $this->instance->getBinding();
        }

        return $this->instance;
    }
}