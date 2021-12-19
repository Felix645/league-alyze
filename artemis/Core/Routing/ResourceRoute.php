<?php


namespace Artemis\Core\Routing;


use Artemis\Support\Arr;
use Artemis\Support\Str;
use Artemis\Core\Routing\Exceptions\RouteException;
use Exception;


class ResourceRoute
{
    /**
     * Available actions for resource route
     *
     * @var string[]
     */
    private $available_actions = ['index', 'new', 'create', 'show', 'edit', 'update', 'delete'];

    /**
     * Actions to be registered
     * 
     * @var string[]
     */
    private $actions;

    /**
     * Collection of the route names
     * 
     * @var string []
     */
    private $names;

    /**
     * Router object
     *
     * @var Router
     */
    private $router;

    /**
     * String which all routes are based on
     *
     * @var string
     */
    private $route_base;

    /**
     * Entity string which all route parameters are based on
     *
     * @var string
     */
    private $entity;

    /**
     * Full qualified class name of the route controller
     *
     * @var string
     */
    private $controller;

    /**
     * Array of middlewares to be attached to each route
     * 
     * @var array
     */
    private $middleware = [];

    /**
     * ResourceRoute constructor.
     *
     * @param Router $router
     * @param string $route_base
     * @param string $controller
     */
    public function __construct($router, $route_base, $controller)
    {
        $this->router = $router;
        $this->route_base = $this->trimSlashes($route_base);
        $this->entity = $this->makeEntity();
        $this->controller = $controller;
        $this->actions = $this->available_actions;
        $this->setStandardNames();
    }

    /**
     * All resource routes except those specified will be registered
     * 
     * @param array $except
     * 
     * @return ResourceRoute
     */
    public function except($except)
    {
        $this->checkProvidedActions($except);

        foreach( $except as $action ) {
            foreach( $this->actions as $key => $value) {
                if( $action === $value )
                    unset($this->actions[$key]);
            }
        }

        return $this;
    }

    /**
     * Only the specified resource routes will be registered
     * 
     * @param array $only
     * 
     * @return ResourceRoute
     */
    public function only($only)
    {
        $this->checkProvidedActions($only);

        $this->actions = $only;
        return $this;
    }

    /**
     * Changes the route names for the given resource method
     * 
     * @param array $names
     * 
     * @return ResourceRoute
     */
    public function names($names)
    {
        $this->names = Arr::merge($this->names, $names);
        return $this;
    }

    /**
     * Changes the parameter names
     *
     * @param string $newParamName
     *
     * @return ResourceRoute
     */
    public function parameter($newParamName)
    {
        $this->entity = $newParamName;
        return $this;
    }

    /**
     * Assigns middlewares to each route
     * 
     * @param array $middleware
     * 
     * @return ResourceRoute
     */
    public function middleware($middleware)
    {
        $this->middleware = $middleware;
        return $this;
    }

    /**
     * Registers all resource routes 
     * 
     * @return void
     */
    private function make()
    {
        $this->makeIndexRoute();
        $this->makeNewRoute();
        $this->makeCreateRoute();
        $this->makeShowRoute();
        $this->makeEditRoute();
        $this->makeUpdateRoute();
        $this->makeDeleteRoute();
    }

    /**
     * Registers a get route for 'index' method
     * 
     * @return void
     */
    private function makeIndexRoute()
    {
        $key = 'index';

        if( !$this->checkAction($key) )
            return;

        $route = '/' . $this->route_base;
        $this->router->get($route, [$this->controller, $key])
            ->name($this->names[$key])
            ->middleware($this->middleware);
    }

    /**
     * Registers a get route for 'new' method
     * 
     * @return void
     */
    private function makeNewRoute()
    {
        $key = 'new';

        if( !$this->checkAction($key) )
            return;

        $route = '/' . $this->route_base . '/new';
        $this->router->get($route, [$this->controller, $key])
            ->name($this->names[$key])
            ->middleware($this->middleware);
    }

    /**
     * Registers a post route for 'create' method
     * 
     * @return void
     */
    private function makeCreateRoute()
    {
        $key = 'create';

        if( !$this->checkAction($key) )
            return;

        $route = '/' . $this->route_base;
        $this->router->post($route, [$this->controller, $key])
            ->name($this->names[$key])
            ->middleware($this->middleware);
    }

    /**
     * Registers a get route for 'show' method
     * 
     * @return void
     */
    private function makeShowRoute()
    {
        $key = 'show';

        if( !$this->checkAction($key) )
            return;

        $route = '/' . $this->route_base . '/{' . $this->entity . '}' ;
        $this->router->get($route, [$this->controller, $key])
            ->name($this->names[$key])
            ->middleware($this->middleware);
    }

    /**
     * Registers a get route for 'edit' method
     * 
     * @return void
     */
    private function makeEditRoute()
    {
        $key = 'edit';

        if( !$this->checkAction($key) )
            return;

        $route = '/' . $this->route_base . '/{' . $this->entity . '}/edit' ;
        $this->router->get($route, [$this->controller, $key])
            ->name($this->names[$key])
            ->middleware($this->middleware);
    }

    /**
     * Registers a put route for 'update' method
     * 
     * @return void
     */
    private function makeUpdateRoute()
    {
        $key = 'update';

        if( !$this->checkAction($key) )
            return;

        $route = '/' . $this->route_base . '/{' . $this->entity . '}' ;
        $this->router->put($route, [$this->controller, $key])
            ->name($this->names[$key])
            ->middleware($this->middleware);
    }

    /**
     * Registers a delete route for 'delete' method
     * 
     * @return void
     */
    private function makeDeleteRoute()
    {
        $key = 'delete';

        if( !$this->checkAction($key) )
            return;

        $route = '/' . $this->route_base . '/{' . $this->entity . '}' ;
        $this->router->delete($route, [$this->controller, $key])
            ->name($this->names[$key])
            ->middleware($this->middleware);
    }

    /**
     * Makes the entity string user for route parameters
     * 
     * @return string
     */
    private function makeEntity()
    {
        return rtrim($this->route_base, 's');
    }

    /**
     * Sets the standard route names
     * 
     * @return void
     */
    private function setStandardNames()
    {
        $this->names = [
            'index'     => $this->route_base . '.index',
            'new'       => $this->route_base . '.new',
            'create'    => $this->route_base . '.create',
            'show'      => $this->route_base . '.show',
            'edit'      => $this->route_base . '.edit',
            'update'    => $this->route_base . '.update',
            'delete'    => $this->route_base . '.delete',
        ];
    }

    /**
     * Checks if the given key is present in the actions array
     *
     * @param string $key
     *
     * @return bool
     */
    private function checkAction($key)
    {
        return Arr::hasValue($key, $this->actions);
    }

    /**
     * Checks if given actions are valid actions
     * 
     * @param array $actions
     * 
     * @return void
     */
    private function checkProvidedActions($actions)
    {
        try {
            foreach( $actions as $action ) {
                if( !in_array($action, $this->available_actions) ) {
                    $message = "Invalid action '$action' provided in resource route";
                    throw new RouteException($message);
                }
            }
        } catch(RouteException $e) {
            report($e);
        }
    }

    /**
     * Trims slashes from given uri 
     * 
     * @param string $path
     * 
     * @return string
     */
    private function trimSlashes($path)
    {
        return Str::trim($path, '/');
    }

    /**
     * Handles the objects destruction
     * 
     * @return void
     */
    public function __destruct()
    {
        $this->make();
    }
}