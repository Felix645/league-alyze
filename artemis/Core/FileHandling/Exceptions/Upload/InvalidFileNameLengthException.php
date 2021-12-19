<?php


namespace Artemis\Core\FileHandling\Exceptions\Upload;


use Exception;


class InvalidFileNameLengthException extends Exception
{
    protected $message = 'Uploaded file name exceeds the maximum allowed amount of 255 characters';
}