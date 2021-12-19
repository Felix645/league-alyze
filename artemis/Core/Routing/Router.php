<?php


namespace Artemis\Core\Routing;


use Artemis\Client\Facades\Hash;
use Artemis\Core\ErrorHandling\Renderers\HtmlRenderer;
use Artemis\Core\Exception\NotFoundException;
use Artemis\Core\Http\ResponseHandler;
use Artemis\Core\Maintenance\Maintenance;
use Artemis\Core\Providers\RouteServiceProvider;
use Artemis\Core\Routing\Exceptions\RouteException;
use Artemis\Core\Routing\Exceptions\RouteGroupException;
use Artemis\Core\Middleware\MiddlewareStack;
use Artemis\Resource\Extensions\CustomBladeExtension;
use Artemis\Support\FileSystem;
use Closure;


class Router
{
    /**
     * Collection of the routes
     * 
     * @var RouteCollection
     */
    private $routes;

    /**
     * The request method
     * 
     * @var string
     */
    private $request_method = '';

    /**
     * Active route
     * 
     * @var null|Route
     */
    private $active_route = null;

    /**
     * Route group attributes
     * 
     * @var array 
     */
    private $groups = [];

    /**
     * Maintenance view render function
     *
     * @var Closure
     */
    private $maintenance_render;

    /**
     * Available options for a route group and their corresponding route methods
     *
     * @var string[]
     */
    private $group_options = [
        'prefix'        => 'modifyPath',
        'middleware'    => 'middleware',
        'name'          => 'name'
    ];

    /**
     * Router Constructor.
     */
    public function __construct()
    {
        $this->routes = new RouteCollection();

        if( Maintenance::isActive() ) {
            $this->maintenance_render = function() {
                if( request()->needsJson() ) {
                    return response()->json([
                        'status' => 503,
                        'message' => 'Application is currently down for maintenance'
                    ])->code(503);
                }

                $blade = new CustomBladeExtension(HtmlRenderer::VIEW_PATH, HtmlRenderer::CACHE_PATH);

                $data = [
                    'css' => FileSystem::getContents(HtmlRenderer::PRODUCTION_CSS),
                    'code' => 503,
                    'message' => 'Application is currently down for maintenance'
                ];

                $view = $blade->run('errors.production-template', $data);

                return response()->text($view)->code(503);
            };
        }
    }

    /**
     * Gets the RouteCollection object
     * 
     * @return RouteCollection
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Gets the current active route
     * 
     * @return null|Route
     */
    public function getActiveRoute()
    {
        return $this->active_route;
    }

    /**
     * Creates a new route group with given options
     *
     * @param array $options
     * @param $routes
     *
     * @return void
     */
    public function group($options, $routes)
    {
        $this->checkOptions($options);

        $this->updateGroup($options);
        
        $this->loadRoutes($routes);

        array_pop($this->groups);
    }

    /**
     * Checks if provided route group options are valid
     *
     * @param array $options
     *
     * @return void
     */
    private function checkOptions($options)
    {
        try {
            foreach( $options as $key => $value ) {
                if( !isset($this->group_options[$key]) ) {
                    $message = "Unknown option '$key' for route group provided!";
                    throw new RouteGroupException($message);
                }
            }
        } catch(RouteGroupException $e) {
            report($e);
        }
    }

    /**
     * Updates the group property
     *
     * @param array $options
     *
     * @return void
     */
    private function updateGroup($options)
    {
        if( $this->hasGroup() )
            $options = $this->mergeGroup($options);

        $this->groups[$this->getNewGroupId()] = $options;
    }

    /**
     * Builds a new unique group ID
     *
     * @return string
     */
    private function getNewGroupId()
    {
        $group_id = Hash::hexToken(20);

        if( isset($this->groups[$group_id]) ) {
            return $this->getNewGroupId();
        }

        return $group_id;
    }

    /**
     * Merges the new group with the old one
     *
     * @param array $options
     *
     * @return array
     */
    private function mergeGroup($options)
    {
        return RouteGroup::merge($options, end($this->groups));
    }

    /**
     * Checks if a route group is already defined
     *
     * @return bool
     */
    private function hasGroup()
    {
        return !empty($this->groups);
    }

    /**
     * Executes the group closure
     *
     * @param $routes
     *
     * @return void
     */
    private function loadRoutes($routes)
    {
        try {
            if( !$routes instanceof Closure )
                throw new RouteGroupException('Route group doesnt have a proper callback!');
            else
                $routes();
        } catch(RouteGroupException $e) {
            report($e);
            exit;
        }


        $this->triggerResourceRegistration();
    }

    /**
     * Registers a route group with given middlewares
     *
     * @param array $middlewares
     *
     * @return GroupRegistration
     */
    public function middleware($middlewares)
    {
        $option = ['middleware' => $middlewares];

        return $this->registerGroupOption($option);
    }

    /**
     * Registers a route group with given prefix
     *
     * @param string $prefix
     *
     * @return GroupRegistration
     */
    public function prefix($prefix)
    {
        $option = ['prefix' => $prefix];

        return $this->registerGroupOption($option);
    }

    /**
     * Registers a route group with given name prefix
     *
     * @param string $name
     *
     * @return GroupRegistration
     */
    public function name($name)
    {
        $option = ['name' => $name];

        return $this->registerGroupOption($option);
    }

    /**
     * Creates the GroupRegistration object and adds the option
     *
     * @param array $option
     *
     * @return GroupRegistration
     */
    private function registerGroupOption($option)
    {
        $group = new GroupRegistration($this);
        $group->addOption($option);
        return $group;
    }

    /**
     * Adds a route for get requests
     * 
     * @param string $route
     * @param Closure|array $callback
     * 
     * @return Route
     */
    public function get($route, $callback)
    {
        $result = $this->extractMethod( $callback );
        $route = $this->buildRouteObject('get', $route, $result["callback"], $result["method"]);
        $this->routes->add($route);
        return $route;
    }

    /**
     * Adds a route for post requests
     * 
     * @param string $route
     * @param Closure|array $callback
     * 
     * @return Route
     */
    public function post($route, $callback)
    {
        $result = $this->extractMethod( $callback );
        $route = $this->buildRouteObject('post', $route, $result["callback"], $result["method"]);
        $this->routes->add($route);
        return $route;
    }

    /**
     * Adds a route for put requests
     * 
     * @param string $route
     * @param Closure|array $callback
     * 
     * @return Route
     */
    public function put($route, $callback)
    {
        $result = $this->extractMethod( $callback );
        $route = $this->buildRouteObject('put', $route, $result["callback"], $result["method"]);
        $this->routes->add($route);
        return $route;
    }

    /**
     * Adds a route for patch requests
     *
     * @param string $route
     * @param Closure|array $callback
     *
     * @return Route
     */
    public function patch($route, $callback)
    {
        $result = $this->extractMethod( $callback );
        $route = $this->buildRouteObject('patch', $route, $result["callback"], $result["method"]);
        $this->routes->add($route);
        return $route;
    }

    /**
     * Adds a route for delete requests
     * 
     * @param string $route
     * @param Closure|array $callback
     * 
     * @return Route
     */
    public function delete($route, $callback)
    {
        $result = $this->extractMethod( $callback );
        $route = $this->buildRouteObject('delete', $route, $result["callback"], $result["method"]);
        $this->routes->add($route);
        return $route;
    }

    /**
     * Builds a get route with given view
     *
     * @param string $route
     * @param string $view
     * @param array $data
     *
     * @return Route
     */
    public function view($route, $view, $data = [])
    {
        $callback = function() use ($view, $data) {
            return view($view, $data);
        };

        return $this->get($route, $callback);
    }

    /**
     * Creates all resource routes for given controller based on given route
     *
     * @param string $route
     * @param string $controller
     *
     * @return ResourceRoute
     */
    public function resource($route, $controller)
    {
        $resource = new ResourceRoute($this, $route, $controller);
        $this->routes->addResourceRoute($resource, $this->getCurrentGroupID());

        return $resource;
    }

    /**
     * Adds a fallback route
     *
     * @param Closure|array $callback
     *
     * @return Route
     */
    public function fallback($callback)
    {
        $result = $this->extractMethod($callback);
        $route = $this->buildRouteObject('get', '', $result["callback"], $result["method"]);
        $this->routes->addFallbackRoute($route);
        return $route;
    }

    /**
     * Adds a custom maintenance render action.
     *
     * @param Closure $callback Callback function to be executed if app is maintenance mode.
     *
     * @return void
     */
    public function maintenance($callback)
    {
        $this->maintenance_render = $callback;
    }

    /**
     * Extracts method from given class string
     * 
     * @param Closure|array $callback
     * 
     * @return array $result
     */
    private function extractMethod($callback)
    {
        $method = '';

        if( is_array( $callback) ) {
            $array = $callback;
            $callback = $array[0];
            $method = $array[1];
        }
        
        $result["callback"] = $callback;
        $result["method"] = $method;

        return $result;
    }

    /**
     * Builds a route object
     * 
     * @param string $request_method
     * @param string $uri
     * @param mixed $callback
     * @param mixed $method
     * 
     * @return Route
     */
    private function buildRouteObject($request_method, $uri, $callback, $method)
    {
        $route = new Route($request_method, $uri);

        if( $this->isCallback($callback) ) {
            $route->setCallback($callback);
        } else {
            $route->setController($callback)->setAction($method);
        }

        return $this->setRouteGroupOptions($route);
    }

    /**
     * Sets the route group options if any are set
     *
     * @param Route $route
     *
     * @return Route
     */
    private function setRouteGroupOptions($route)
    {
        if( !empty($this->groups) ) {
            $group = end($this->groups);

            foreach( $group as $option => $value ) {
                $method = $this->group_options[$option];
                $route->$method($value);
            }
        }

        return $route;
    }

    /**
     * Checks if given variable is a callback function
     * 
     * @param mixed $function
     * 
     * @return bool
     */
    private function isCallback($function)
    {
        return (is_string($function) && function_exists($function)) || ($function instanceof Closure);
    }

    /**
     * Triggers the resource route registration by deleting the objects and thus calling the destructor of each route
     * 
     * @return void
     */
    public function triggerResourceRegistration()
    {
        $this->routes->destroyResourceRoutes($this->getCurrentGroupID());
    }

    /**
     * Gets the ID of the current route, returns NULL if no group is active.
     *
     * @return string|null
     */
    private function getCurrentGroupID()
    {
        if( empty($this->groups) ) {
            return null;
        }

        return array_key_last($this->groups);
    }

    /**
     * Resolves a request with a static route
     * 
     * @throws NotFoundException|RouteException
     * 
     * @return mixed
     */
    private function resolveRoutes()
    {
        $request_bits = container('request')->getURLBits();

        $routes = $this->routes->getByMethod($this->request_method) ?? [];
        foreach( $routes as $route ) {
            $action = $this->resolveSingleRoute($route, $request_bits);
            if( false !== $action )
                return $action;
        }

        if( $this->routes->hasFallback() )
            return (new RouteAction($this->routes->getFallback()))->get();

        // No matching route was found => throw an exception
        throw new NotFoundException();
    }

    /**
     * Resolves given route with given request_bits
     *
     * @param Route $route
     * @param array $request_bits
     * @throws RouteException
     *
     * @return false|mixed
     */
    private function resolveSingleRoute($route, $request_bits)
    {
        if( !RouteValidator::match($route, $request_bits) )
            return false;

        $this->active_route = $route;

        if( $route->hasMiddlewares() )
            $this->handleRouteMiddlewares($route);

        return (new RouteAction($route))->get();
    }

    /**
     * Handles middlewares of given route
     *
     * @param Route $route
     *
     * @return void
     */
    private function handleRouteMiddlewares($route)
    {
        /* @var MiddlewareStack $middleware_stack */
        $middleware_stack = container(MiddlewareStack::class);

        $middlewares = $route->getMiddlewares();
        foreach( $middlewares as $alias ) {
            $middleware_stack->addRouteMiddleware($alias);
        }

        $middleware_stack->runRouteMiddlewares();
    }

    /**
     * Imports the routes defined in the routes files
     * 
     * @return Router
     */
    public function importRoutes()
    {
        foreach( $this->routes->getRoutes() as $route ) {
            $this->routes->addToNameList($route);
        }

        return $this;
    }

    /**
     * Resolves the given routing
     * 
     * @throws NotFoundException|RouteException
     * 
     * @return mixed
     */
    public function resolve()
    {
        $this->checkMaintenance();

        // get the current request method
        $this->request_method = container('request')->getRequestMethod();

        // Resolve routes
        return $this->resolveRoutes();
    }

    /**
     * Checks the maintenance status.
     *
     * @return void
     */
    private function checkMaintenance()
    {
        if( !Maintenance::isActive() ) {
            return;
        }

        if( request()->likeRoute(RouteServiceProvider::MAINTENANCE_SECRET_ROUTE_NAME) ) {
            return;
        }

        if( request()->needsJson() ) {
            if( request()->has('_maintenance') ) {
                if( Maintenance::secret() === request()->input('_maintenance') ) {
                    return;
                }
            }

            ResponseHandler::new(($this->maintenance_render)());
        }

        if( !isset($_COOKIE[RouteServiceProvider::MAINTENANCE_SECRET_ROUTE_NAME]) ) {
            ResponseHandler::new(($this->maintenance_render)());
        }

        if( $_COOKIE[RouteServiceProvider::MAINTENANCE_SECRET_ROUTE_NAME] === Maintenance::secret() ) {
            return;
        }

        ResponseHandler::new(($this->maintenance_render)());
    }
}