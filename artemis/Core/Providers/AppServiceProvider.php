<?php


namespace Artemis\Core\Providers;


use Artemis\Core\DI\Container;
use Artemis\Core\Interfaces\ProviderInterface;


abstract class AppServiceProvider implements ProviderInterface
{
    /**
     * DI-Container instance
     *
     * @var Container
     */
    protected $container;

    /**
     * AppServiceProvider constructor.
     */
    public function __construct()
    {
        $this->container = container();
    }
}