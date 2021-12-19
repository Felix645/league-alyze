<?php


namespace Artemis\Core\Auth\Traits;


use Exception;


trait hasLoginExceptions
{
    /**
     * Throws exception with given message
     *
     * @param string $message
     * @throws Exception
     *
     * @return void
     */
    protected function throwException(string $message)
    {
        throw new Exception($message);
    }
}