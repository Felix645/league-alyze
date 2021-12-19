<?php


namespace Artemis\Client\Facades;


use Artemis\Core\Routing\GroupRegistration;
use Artemis\Core\Routing\Route;
use Artemis\Core\Routing\ResourceRoute;


/**
 * Class Router
 * @package Artemis\Client\Facades
 *
 * @method static void group(array $options, $callback) Creates a new route group with given options
 * @method static GroupRegistration middleware(array $middlewares) Registers a route group with given middlewares
 * @method static GroupRegistration prefix(string $prefix) Registers a route group with given prefix
 * @method static GroupRegistration name(string $name) Registers a route group with given name prefix
 * @method static Route get(string $route, $callback) Adds a route for get requests
 * @method static Route post(string $route, $callback) Adds a route for post requests
 * @method static Route put(string $route, $callback) Adds a route for put requests
 * @method static Route patch(string $route, $callback) Adds a route for patch requests
 * @method static Route delete(string $route, $callback) Adds a route for delete requests
 * @method static Route view(string $route, string $view, array $data = []) Builds a get route with given view
 * @method static ResourceRoute resource(string $route, string $controller) Creates all resource routes for given controller based on given route
 * @method static Route fallback(\Closure $callback) Adds a fallback route
 * @method static Route maintenance(\Closure $callback) Adds a custom maintenance render function
 * @method static string getActiveRoute() Gets the current active route
 *
 * @uses \Artemis\Core\Routing\Router::group()
 * @uses \Artemis\Core\Routing\Router::middleware()
 * @uses \Artemis\Core\Routing\Router::prefix()
 * @uses \Artemis\Core\Routing\Router::name()
 * @uses \Artemis\Core\Routing\Router::get()
 * @uses \Artemis\Core\Routing\Router::post()
 * @uses \Artemis\Core\Routing\Router::put()
 * @uses \Artemis\Core\Routing\Router::patch()
 * @uses \Artemis\Core\Routing\Router::delete()
 * @uses \Artemis\Core\Routing\Router::view()
 * @uses \Artemis\Core\Routing\Router::resource()
 * @uses \Artemis\Core\Routing\Router::fallback()
 * @uses \Artemis\Core\Routing\Router::getActiveRoute()
 * @uses \Artemis\Core\Routing\Router::maintenance()
 */
class Router extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getAccessor()
    {
        return 'router';
    }
}