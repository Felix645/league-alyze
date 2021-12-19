<?php


namespace Artemis\Core\Events\Exceptions;


use Throwable;

class NoHandleException extends \Exception
{
    public function __construct($listener, $message = "", $code = 0, Throwable $previous = null)
    {
        $message = "Listener $listener does not implement public 'handle' method";
        parent::__construct($message, $code, $previous);
    }
}