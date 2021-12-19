<?php


namespace Artemis\Core\Routing;


use Artemis\Support\Str;
use Closure;


class Route 
{
    /**
     * The request method
     * 
     * @var string
     */
    private $request_method;

    /**
     * The route uri
     * 
     * @var string
     */
    private $path;

    /**
     * Segments of the route uri
     * 
     * @var array
     */
    private $segments;

    /**
     * Controller that is to be called
     * 
     * @var string
     */
    private $controller = '';

    /**
     * Callback function that is to be called
     * 
     * @var null|Closure
     */
    private $callback = null;

    /**
     * Action of the controller that is to be called
     * 
     * @var string
     */
    private $action = '';

    /**
     * Middlewares to be executed on that route
     * 
     * @var string[]
     */
    private $middlewares = [];

    /**
     * Route name
     * 
     * @var string
     */
    private $name;

    /**
     * Route Constructor
     * 
     * @param string $request_method
     * @param string $path
     */
    public function __construct($request_method, $path)
    {
        $this->request_method = $request_method;
        $this->path = '/' . $this->trimSlashes($path);   
        $this->calculateSegments();
    }

    /**
     * Specifies a name for the route
     * 
     * @param string $name
     * 
     * @return Route
     */
    public function name($name)
    {
        if( isset($this->name) )
            $this->name = $this->name.$name;
        else
            $this->name = $name;

        return $this;
    }

    /**
     * Gets the route name
     * 
     * @return string|null
     */
    public function getName()
    {
        return $this->name ?? null;
    }

    /**
     * Specifies by which middlewares this route is protected by
     * 
     * @param array $middlewares
     * 
     * @return Route
     */
    public function middleware($middlewares)
    {
        foreach( $middlewares as $middleware ) {
            $this->middlewares[] = $middleware;
        }
        return $this;
    }

    /**
     * Gets the middlewares of that route
     * 
     * @return array
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    /**
     * Route has middlewares registered
     * 
     * @return bool
     */
    public function hasMiddlewares()
    {
        return !empty($this->middlewares);
    }

    /**
     * Gets request method of the route
     * 
     * @return string
     */
    public function requestMethod()
    {
        return $this->request_method;
    }

    /**
     * Gets the uri path of the route
     * 
     * @return string
     */
    public function getPath()
    {
        return $this->path;    
    }

    /**
     * Gets the uri segments of the route
     * 
     * @return array
     */
    public function getSegments()
    {
        return $this->segments;
    }

    /**
     * Gets the controller of the route
     * 
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Gets the controller action of the route
     * 
     * @return string
     */
    public function getAction()
    {
        return $this->action;    
    }

    /**
     * Gets the callback function of the route
     * 
     * @return Closure
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Sets the controller for the route
     * 
     * @param string $controller
     * 
     * @return Route
     */
    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * Sets the callback function for the route
     * 
     * @param Closure $callback
     * 
     * @return Route
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * Sets the controller action for the route
     * 
     * @param string $action
     * 
     * @return Route
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * Route has a callback function defined
     * 
     * @return bool
     */
    public function hasCallback()
    {
        return isset($this->callback) && $this->callback instanceof Closure;    
    }

    /**
     * Route has a controller defined
     * 
     * @return bool
     */
    public function hasController()
    {
        return !empty($this->controller);    
    }

    /**
     * Modifies the route path with given prefix
     *
     * @param string $prefix
     *
     * @return void
     */
    public function modifyPath($prefix)
    {
        $this->path = '/' . $this->trimSlashes($this->trimSlashes($prefix) . '/' . $this->trimSlashes($this->path));
        $this->calculateSegments();
    }

    /**
     * Calculates the route segments
     *
     * @return void
     */
    private function calculateSegments()
    {
        $path = $this->trimSlashes($this->path);
        $this->segments = explode( '/', $path );
    }

    /**
     * Trims leading and trailing forward slashes from string
     *
     * @param string $path
     *
     * @return string
     */
    private function trimSlashes($path)
    {
        return Str::trim($path, '/');
    }
}