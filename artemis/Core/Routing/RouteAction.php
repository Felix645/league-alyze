<?php


namespace Artemis\Core\Routing;


use Artemis\Core\Routing\Exceptions\RouteException;
use Closure;


class RouteAction 
{
    /**
     * Route object
     *
     * @var Route
     */
    private $route;

    /**
     * RouteAction constructor.
     *
     * @param Route $route
     */
    public function __construct($route)
    {
        $this->route = $route;
    }

    /**
     * Gets the route action
     *
     * @throws RouteException
     *
     * @return mixed
     */
    public function get()
    {
        if( $this->route->hasCallback() ) {
            $return = $this->resolveCallback($this->route->getCallback());
        } elseif( $this->route->hasController() ) {
            $return = $this->resolveController($this->route->getController(), $this->route->getAction());
        } else {
            $return = false;
        }

        return $return;
    }

    /**
     * Resolves a route action with a callback function
     *
     * @param Closure $callback
     * @throws RouteException
     *
     * @return mixed
     */
    private function resolveCallback($callback)
    {
        // check if callback is actually callable, if yes return the function call, if not throw an exception
        if( is_callable( $callback ) )
            return container()->getCallback($callback);

        throw new RouteException('A route contains a callback function parameter but the parameter is not callable');
    }

    /**
     * Resolve a route action with controller method call
     *
     * @param string $className
     * @param string $method
     * @throws RouteException
     *
     * @return mixed
     */
    private function resolveController($className, $method)
    {
        if( '' === $method ) {
            $message = "The Route with the URI '{$this->route->getPath()}' and controller '{$this->route->getController()}' contains an empty method";
            throw new RouteException($message);
        }

        return container()->getWithMethod($className, $method);
    }
}