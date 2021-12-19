<?php


namespace Artemis\Resource\Providers;


use Artemis\Core\Providers\MiddlewareServiceProvider as ServiceProvider;


class MiddlewareServiceProvider extends ServiceProvider
{
    /**
     * List of middlewares with their alias as key and their class string as value.
     * Important: Each middleware MUST implement \Artemis\Core\Interfaces\MiddlewareInterface.
     *
     * @var array
     */
    protected $middlewares = [
        // 'alias' => \App\Middlewares\MyMiddleware::class
    ];

    /**
     * List of middleware aliases to be executed as global middlewares.
     *
     * @var string[]
     */
    protected $global_middlewares = [
        // 'alias'
    ];

    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->bindCoreMiddlewares();
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->registerMiddlewares();
        $this->addGlobalMiddlewares();
    }
}