<?php


namespace Artemis\Core\Http\Exceptions;


use Exception;


class RequestException extends Exception
{
    protected $code = 500;
}