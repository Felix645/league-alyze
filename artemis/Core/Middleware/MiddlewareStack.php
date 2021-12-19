<?php


namespace Artemis\Core\Middleware;


use Artemis\Core\Middleware\Exceptions\MiddlewareException;
use Artemis\Core\Interfaces\MiddlewareInterface;


class MiddlewareStack
{
    /**
     * Mapping of middleware alias and its class.
     *
     * @var array
     */
    private $middleware_map = [];

    /**
     * Collection of middlewares to be executed on every request.
     *
     * @var MiddlewareInterface[]
     */
    private $global_middlewares = [];

    /**
     * Collection of middlewares that were added by the router.
     *
     * @var MiddlewareInterface[]
     */
    private $route_middlewares = [];

    /**
     * Runs global middlewares.
     *
     * @return void
     */
    public function runGlobalMiddlewares()
    {
        foreach( $this->global_middlewares as $middleware) {
            $middleware->execute();
        }
    }

    /**
     * Runs route middlewares.
     *
     * @return void
     */
    public function runRouteMiddlewares()
    {
        foreach( $this->route_middlewares as $middleware) {
            $middleware->execute();
        }
    }

    /**
     * Registers a middleware.
     *
     * @param string $key
     * @param string $middleware
     *
     * @return void
     */
    public function registerMiddleware($key, $middleware)
    {
        $this->middleware_map[$key] = $middleware;
    }

    /**
     * Adds a global middleware to be executed.
     *
     * @param string $alias
     *
     * @return void
     */
    public function addGlobalMiddleware($alias)
    {
        try {
            $this->checkAlias($alias);

            $this->global_middlewares[] = $this->middleware_map[$alias];
        } catch(\Throwable $e) {
            $this->handleException($e);
        }
    }

    /**
     * Adds a route middleware to be executed.
     *
     * @param string $alias
     *
     * @return void
     */
    public function addRouteMiddleware($alias)
    {
        try {
            $this->checkAlias($alias);

            $middleware = container($this->middleware_map[$alias]);

            if( !$middleware instanceof MiddlewareInterface ) {
                $message = "Middleware '$alias' does not implement \Artemis\Core\Interfaces\MiddlewareInterface";
                throw new MiddlewareException($message);
            }

            $this->route_middlewares[] = container($this->middleware_map[$alias]);
        } catch(\Throwable $e) {
            $this->handleException($e);
        }
    }

    /**
     * Checks if given alias is registered.
     *
     * @param string $alias
     * @throws MiddlewareException
     *
     * @return void
     */
    private function checkAlias($alias)
    {
        if( !array_key_exists($alias, $this->middleware_map) ) {
            throw new MiddlewareException("The provided middleware alias '$alias' is not registered");
        }
    }

    /**
     * Handles a given exception.
     *
     * @param \Exception $e
     *
     * @return void
     */
    private function handleException($e)
    {
        report($e);
    }
}