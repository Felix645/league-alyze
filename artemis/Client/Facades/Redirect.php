<?php


namespace Artemis\Client\Facades;


/**
 * Class Redirect
 * @package Artemis\Client\Facades
 *
 * @method static void route(string $route_name) Redirects to a given route name
 * @method static void url(string $url) Redirects to a given URL
 * @method static void back() Redirects back to the last visited view
 * @method static void action(string $class, string $method) Redirects to a given controller action
 *
 * @uses \Artemis\Core\Http\Redirector::route()
 * @uses \Artemis\Core\Http\Redirector::url()
 * @uses \Artemis\Core\Http\Redirector::back()
 * @uses \Artemis\Core\Http\Redirector::action()
 */
class Redirect extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getAccessor()
    {
        return 'redirector';
    }
}