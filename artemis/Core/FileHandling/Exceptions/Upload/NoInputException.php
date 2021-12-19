<?php


namespace Artemis\Core\FileHandling\Exceptions\Upload;


use Exception;


class NoInputException extends Exception
{
    protected $message = 'No upload input provided';
}