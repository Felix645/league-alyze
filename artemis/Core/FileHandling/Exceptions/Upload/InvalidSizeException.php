<?php


namespace Artemis\Core\FileHandling\Exceptions\Upload;


use Exception;


class InvalidSizeException extends Exception
{
    protected $message = 'Uploaded file exceeds allowed file size';
}