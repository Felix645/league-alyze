<?php


namespace Artemis\Core\Exception;


use Artemis\Client\Facades\API;
use Artemis\Core\Interfaces\ApiExceptionInterface;


class UnauthorizedException extends \Exception
{
    protected $message = 'Not authorized';
    protected $code = 401;
}