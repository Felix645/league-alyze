<?php


namespace Artemis\Core\Pipeline\Exceptions;


class NoPipeException extends \Exception
{
    protected $message = 'No pipe was defined for the pipeline';
}