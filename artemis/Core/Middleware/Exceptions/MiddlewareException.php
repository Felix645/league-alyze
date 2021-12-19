<?php


namespace Artemis\Core\Middleware\Exceptions;


class MiddlewareException extends \Exception
{
    protected $code = 500;
}