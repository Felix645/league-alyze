<?php


namespace Artemis\Resource\Providers;


use Artemis\Core\Providers\ValidationServiceProvider as ServiceProvider;


class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Here you may add your custom rule implemenations.
     * Note that each rule MUST extend from \Artemis\Core\Validation\Rule .
     *
     * @inheritDoc
     */
    public function register()
    {
        // $this->rules['myRule'] = new \App\Validation\MyRule();
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->registerRules();
    }
}