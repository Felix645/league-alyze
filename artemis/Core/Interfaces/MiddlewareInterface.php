<?php


namespace Artemis\Core\Interfaces;


interface MiddlewareInterface
{
    /**
     * Executes the middleware
     * 
     * @return void
     */
    public function execute();
}