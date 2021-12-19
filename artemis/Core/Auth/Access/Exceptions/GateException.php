<?php

namespace Artemis\Core\Auth\Access\Exceptions;

use Exception;

class GateException extends Exception
{
    public function __construct(string $gate, string $message)
    {
        $message = "Exception for gate '$gate': $message";

        parent::__construct($message, 500);
    }
}