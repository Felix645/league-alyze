<?php


namespace Artemis\Core\FileHandling\Exceptions\Upload;


use Exception;


class NoDestinationException extends Exception
{
    protected $message = 'No storage destination provided';
}