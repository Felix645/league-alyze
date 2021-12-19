<?php


namespace Artemis\Core\Interfaces;


interface ProviderInterface
{
    /**
     * Registers services.
     *
     * @return void
     */
    public function register();

    /**
     * Boots services after all providers executed the register method.
     *
     * @return void
     */
    public function boot();
}