<?php


namespace Artemis\Core\Exception;


class ForbiddenException extends \Exception
{
    protected $message = 'Forbidden';
    protected $code = 403;
}