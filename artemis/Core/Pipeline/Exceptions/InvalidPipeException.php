<?php


namespace Artemis\Core\Pipeline\Exceptions;


use Exception;


class InvalidPipeException extends Exception
{
    protected $message = 'Provided Pipe must implement \Artemis\Core\Pipeline\PipelineInterface';
}