<?php

namespace Artemis\Core\DI\Exceptions;

use Throwable;

class UnresolvableMethodException extends \Exception
{
    public function __construct($class, $method, $code = 0, Throwable $previous = null)
    {
        $message = "Method '$method' of class '$class' could not be resolved by the container. It is either protected/private or it does not exist";
        parent::__construct($message, $code, $previous);
    }
}