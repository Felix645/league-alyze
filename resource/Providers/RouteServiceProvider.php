<?php


namespace Artemis\Resource\Providers;


use Artemis\Core\Providers\RouteServiceProvider as ServiceProvider;


class RouteServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register()
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->bootWebRoutes();
        $this->bootApiRoutes();
    }
}