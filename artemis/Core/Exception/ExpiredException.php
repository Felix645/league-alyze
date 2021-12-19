<?php


namespace Artemis\Core\Exception;


use Artemis\Core\Http\Interfaces\HttpStatusCodes;


class ExpiredException extends \Exception
{
    protected $code = HttpStatusCodes::HTTP_UNAUTHORIZED;
    protected $message = 'Page expired';
}