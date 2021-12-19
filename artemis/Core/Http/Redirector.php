<?php


namespace Artemis\Core\Http;


use Artemis\Core\Http\Traits\hasAjaxRedirectionCheck;
use Artemis\Core\Http\Traits\hasHeaderHandling;
use Artemis\Core\Interfaces\RedirectionInterface;
use Artemis\Core\Traits\hasSessionAlerts;
use Artemis\Utils\RouteBuilder;


class Redirector implements RedirectionInterface
{
    use hasSessionAlerts, hasHeaderHandling, hasAjaxRedirectionCheck;

    /**
     * Domain of the application
     *
     * @var string
     */
    private $app_domain;

    /**
     * URL of the last view that the user visited
     *
     * @var string
     */
    private $last_visited_page;

    /**
     * URL to be redirected to.
     *
     * @var string
     */
    private $redirection_url;

    /**
     * Identifier if the redirect points to a controller action
     *
     * @var bool
     */
    private $has_controller_return = false;

    /**
     * What ever the controller returned from being called.
     *
     * @var mixed
     */
    private $controller_return;

    /**
     * Redirector constructor.
     */
    public function __construct()
    {
        $this->app_domain = app()->domain();
        $this->last_visited_page = container('request')->getLastPage();
    }

    /**
     * @inheritDoc
     */
    public function route($route_name, $params = [])
    {
        $routeBuilder = new RouteBuilder($route_name, $params);
        $route = $routeBuilder->get();

        $this->redirection_url = $this->app_domain.$route;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function url($url)
    {
        $this->redirection_url = $url;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function back()
    {
        $this->redirection_url = $this->app_domain.$this->last_visited_page;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function action($class, $method)
    {
        $this->has_controller_return = true;
        $this->controller_return = container()->getWithMethod($class, $method);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withError($key, $message)
    {
        $this->setError($key, $message);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withSuccess($key, $message)
    {
        $this->setSuccess($key, $message);
        return $this;
    }

    /**
     * @inheritDoc
     *
     * @throws Exceptions\RequestException
     */
    public function execute()
    {
        $this->checkAjax();

        if( $this->has_controller_return ) {
            ResponseHandler::new($this->controller_return);
            exit;
        }

        $this->addHeader(Response::HEADER_RESPONSE_LOCATION, $this->redirection_url);
    }
}