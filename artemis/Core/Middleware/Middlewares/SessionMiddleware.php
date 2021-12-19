<?php


namespace Artemis\Core\Middleware\Middlewares;


use Artemis\Core\Interfaces\MiddlewareInterface;


class SessionMiddleware implements MiddlewareInterface
{
    /**
     * @inheritDoc
     */
    public function execute()
    {
        container('session')->startSession();
    }
}