<?php


namespace Artemis\Core\FileHandling\Exceptions\Upload;


use Exception;


class InvalidMimeTypeException extends Exception
{
    protected $message = 'Uploaded file does not match allowed mime types';
}