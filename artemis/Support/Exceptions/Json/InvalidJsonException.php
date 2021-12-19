<?php


namespace Artemis\Support\Exceptions\Json;


class InvalidJsonException extends \Exception
{
    protected $code = 500;
    protected $message = 'Content to be decoded is not valid json';
}