<?php


namespace Artemis\Core\Http;


use Artemis\Core\Http\Exceptions\RequestException;
use Artemis\Core\Http\Traits\hasAjaxRedirectionCheck;
use Artemis\Core\Interfaces\RedirectionInterface;
use Artemis\Core\Traits\hasSessionAlerts;
use Artemis\Utils\RouteBuilder;


class LegacyRedirector implements RedirectionInterface
{
    use hasSessionAlerts, hasAjaxRedirectionCheck;

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
     * Redirector constructor.
     */
    public function __construct()
    {
        $this->app_domain = app()->domain();
        $this->last_visited_page = container('request')->getLastPage();
    }

    /**
     * @inheritDoc
     *
     * @throws RequestException
     */
    public function route($route_name, $params = [])
    {
        $routeBuilder = new RouteBuilder($route_name, $params);
        $route = $routeBuilder->get();

        $this->redirection_url = $this->app_domain.$route;

        $this->execute();
        return $this;
    }

    /**
     * @inheritDoc
     *
     * @throws RequestException
     */
    public function url($url)
    {
        $this->redirection_url = $url;
        $this->execute();
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function back()
    {
        $this->redirection_url = $this->app_domain.$this->last_visited_page;
        $this->execute();
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function action($class, $method)
    {
        ResponseHandler::new(container()->getWithMethod($class, $method));
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
     * @throws RequestException
     */
    public function execute()
    {
        $this->checkAjax();

        header('Location: ' . $this->redirection_url);
        exit;
    }
}