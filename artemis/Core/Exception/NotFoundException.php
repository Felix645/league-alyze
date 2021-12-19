<?php


namespace Artemis\Core\Exception;


use Artemis\Client\Facades\API;
use Artemis\Core\Interfaces\ApiExceptionInterface;


class NotFoundException extends \Exception
{
    protected $message = 'Resource not found';
    protected $code = 404;
}