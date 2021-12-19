<?php


namespace Artemis\Core\Routing;


class RouteCollection
{
    /**
     * Collection of all routes
     *
     * @var Route[]
     */
    private $routes;

    /**
     * Collection of routes by their request method
     *
     * @var array
     */
    private $methodList;

    /**
     * Collection of routes by their names
     *
     * @var Route[]
     */
    private $nameList;

    /**
     * Collection of resource routes
     * 
     * @var ResourceRoute[]
     */
    private $resourceRoutes = [];

    /**
     * Route that will be executed if no other matching route was found
     *
     * @var Route
     */
    private $fallbackRoute;

    /**
     * Adds a route to the collections
     *
     * @param Route $route
     *
     * @return void
     */
    public function add($route)
    {
        $this->addToCollections($route);
    }

    /**
     * Tries to add a given route to the name list
     *
     * @param Route $route
     * 
     * @return void
     */
    public function addToNameList($route)
    {
        if( $name = $route->getName() )
            $this->nameList[$name] = $route;
    }

    /**
     * Adds a resource route to the collection
     * 
     * @param ResourceRoute $route
     * @param null|string $group_id
     * 
     * @return void
     */
    public function addResourceRoute($route, $group_id)
    {
        if( is_null($group_id) ) {
            $this->resourceRoutes['default'][] = $route;
        } else {
            $this->resourceRoutes[$group_id][] = $route;
        }
    }

    /**
     * Adds fallback route
     *
     * @param Route $route
     */
    public function addFallbackRoute($route)
    {
        $this->fallbackRoute = $route;
    }

    /**
     * Gets a collection of all routes
     *
     * @return Route[]
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Gets a collection of routes by request method
     *
     * @param string $method
     *
     * @return null|Route[]
     */
    public function getByMethod($method)
    {
        return $this->methodList[$method] ?? null;
    }

    /**
     * Gets a route by its name
     *
     * @param string $name
     *
     * @return null|Route
     */
    public function getByName($name)
    {
        return $this->nameList[$name] ?? null;
    }

    /**
     * Destroys the resource routes => calling their destructors
     *
     * @param null|string $group_id
     * 
     * @return void
     */
    public function destroyResourceRoutes($group_id)
    {
        if( is_null($group_id) ) {
            unset($this->resourceRoutes['default']);
        } else {
            unset($this->resourceRoutes[$group_id]);
        }
    }

    /**
     * Checks if a fallback route was defined
     *
     * @return bool
     */
    public function hasFallback()
    {
        return isset($this->fallbackRoute);
    }

    /**
     * Gets the fallback route object
     *
     * @return Route
     */
    public function getFallback() : Route
    {
        return $this->fallbackRoute;
    }

    /**
     * Adds a route to their respective collections
     *
     * @param Route $route
     *
     * @return void
     */
    private function addToCollections($route)
    {
        $this->routes[] = $route;
        $this->methodList[$route->requestMethod()][] = $route;
    }
}