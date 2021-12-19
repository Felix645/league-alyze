<?php


namespace Artemis\Core\Http\Exceptions;


class ResponseException extends \Exception
{
    protected $code = 500;
}