<?php


namespace Artemis\Core\Interfaces;


use Artemis\Core\Http\Exceptions\RequestException;


interface RedirectionInterface
{
    /**
     * Redirects to a given route name
     *
     * @param string $route_name
     * @param array $params
     *
     * @return RedirectionInterface
     */
    public function route($route_name, $params = []);

    /**
     * Redirects to a given URL
     *
     * @param string $url
     *
     * @return RedirectionInterface
     */
    public function url($url);

    /**
     * Redirects back to the last visited view
     *
     * @throws RequestException
     *
     * @return RedirectionInterface
     */
    public function back();

    /**
     * Redirects to a given controller action
     *
     * @param string $class
     * @param string $method
     *
     * @return RedirectionInterface
     */
    public function action($class, $method);

    /**
     * Adds a error message to the session
     *
     * @param string $key
     * @param string $message
     *
     * @return RedirectionInterface
     */
    public function withError($key, $message);

    /**
     * Adds a success message to the session
     *
     * @param string $key
     * @param string $message
     *
     * @return RedirectionInterface
     */
    public function withSuccess($key, $message);

    /**
     * Bypasses the ajax check.
     *
     * @return RedirectionInterface
     */
    public function forceRedirect();

    /**
     * Checks if the ajax check should be bypassed or not.
     *
     * @return bool
     */
    public function bypassAjaxCheck();

    /**
     * Executes the redirection
     *
     * @return void
     */
    public function execute();
}