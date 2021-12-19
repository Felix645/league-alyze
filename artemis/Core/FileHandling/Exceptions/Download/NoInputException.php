<?php


namespace Artemis\Core\FileHandling\Exceptions\Download;


use Exception;


class NoInputException extends Exception
{
    protected $message = 'No download input specified';
}