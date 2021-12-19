<?php


namespace Artemis\Core\FileHandling\Exceptions\Upload;


use Exception;


class InvalidExtensionException extends Exception
{
    protected $message = 'Uploaded file does not match allowed extensions';
}