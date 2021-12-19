<?php


namespace Artemis\Utils;


use Artemis\Support\Str;
use Artemis\Core\Routing\Exceptions\RouteException;


class RouteBuilder
{
    /**
     * Route uri of given routeName
     *
     * @var string
     */
    private $route;

    /**
     * Route name
     *
     * @var string
     */
    private $routeName;

    /**
     * Parameters of the route that are to be replaced
     *
     * @var array
     */
    private $params;

    /**
     * Gets the built route.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->get();
    }

    /**
     * RouteBuilder constructor.
     *
     * @param string $routeName
     * @param array $params
     */
    public function __construct($routeName, $params)
    {
        $this->routeName = $routeName;
        $this->params = $params;
        $this->setRoute();
        $this->replaceRouteParams();
    }

    /**
     * Gets the built route
     *
     * @return string
     */
    public function get()
    {
        return $this->route;
    }

    /**
     * Route should be returned as full url.
     *
     * @return $this
     */
    public function full()
    {
        $this->route = app()->domain() . $this->route;
        return $this;
    }

    /**
     * Sets the route uri
     *
     * @return void
     */
    private function setRoute()
    {
        $routes = container('router')->getRoutes();

        if( $route = $routes->getByName($this->routeName) ) {
            $this->route = $route->getPath();
            return;
        }

        $message = "The route with the name '$this->routeName' does not exist!";
        report(new RouteException($message));
    }

    /**
     * Replaces uri parameters
     *
     * @return void
     */
    private function replaceRouteParams()
    {
        foreach( $this->params as $key => $value ) {
            $param = '{' . $key . '}';
            $this->route = Str::replace($param, $value, $this->route);
        }
    }
}