<?php


namespace Artemis\Core\FileHandling\Exceptions\Upload;


use Exception;


class InvalidFileNameException extends Exception
{
    protected $message = 'Uploaded file has an invalid or empty file name';
}