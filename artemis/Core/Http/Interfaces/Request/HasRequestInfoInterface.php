<?php


namespace Artemis\Core\Http\Interfaces\Request;


interface HasRequestInfoInterface
{
    /**
     * Gets the full request URL
     *
     * @return string
     */
    public function getRequestURL();

    /**
     * Gets the current request uri
     *
     * @return string $uri
     */
    public function getRequestURI();

    /**
     * Gets the url bits of the current request
     *
     * @return array $url_bits
     */
    public function getURLBits();

    /**
     * Checks if the current request matches the given url path pattern.
     * A * may be provided inside the string to signal a wildcard.
     *
     * @param string $path URL path to be checked.
     * @return bool True if the pattern matches, false if not.
     */
    public function like($path);

    /**
     * Checks if the current request matches the given route.
     *
     * @param string $route_name Name of the route against to validate the request.
     *
     * @return bool True if the request matches the route, false if it does not.
     */
    public function likeRoute($route_name);

    /**
     * Gets the current request method
     *
     * @return string $request_method
     */
    public function getRequestMethod();

    /**
     * Checks if the requests method matches the given method.
     *
     * @param string $method Method to be checked against.
     *
     * @return bool True if the methods match, false if they don't.
     */
    public function isMethod($method);
}