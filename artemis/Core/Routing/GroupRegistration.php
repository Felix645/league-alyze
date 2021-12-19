<?php


namespace Artemis\Core\Routing;


use Closure;


class GroupRegistration
{
    /**
     * Router object
     *
     * @var Router
     */
    private $router;

    /**
     * Array of route options
     *
     * @var array
     */
    private $options;

    /**
     * GroupRegistration constructor.
     *
     * @param Router $router
     */
    public function __construct($router)
    {
        $this->router = $router;
    }

    /**
     * Adds given prefix to the route group
     *
     * @param string $prefix
     *
     * @return GroupRegistration
     */
    public function prefix($prefix)
    {
        $this->options['prefix'] = $prefix;
        return $this;
    }

    /**
     * Adds given name to the route group
     *
     * @param string $name
     *
     * @return GroupRegistration
     */
    public function name($name)
    {
        $this->options['name'] = $name;
        return $this;
    }

    /**
     * Adds given middlewares to the route group
     *
     * @param array $middlewares
     *
     * @return GroupRegistration
     */
    public function middleware($middlewares)
    {
        $this->options['middleware'] = $middlewares;
        return $this;
    }

    /**
     * Adds an option to the registration
     *
     * @param array $option
     *
     * @return GroupRegistration
     */
    public function addOption($option)
    {
        $this->options = $option;
        return $this;
    }

    /**
     * Groups routes inside given closure
     *
     * @param Closure $callback
     *
     * @return void
     */
    public function group($callback)
    {
        $this->router->group($this->options, $callback);
    }
}