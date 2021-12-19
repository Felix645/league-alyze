<?php


namespace Artemis\Core\Pipeline\Exceptions;


class NoPayloadException extends \Exception
{
    protected $message = 'No payload was defined for the pipeline';
}