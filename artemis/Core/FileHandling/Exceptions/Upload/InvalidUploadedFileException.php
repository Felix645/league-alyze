<?php


namespace Artemis\Core\FileHandling\Exceptions\Upload;


use Exception;


class InvalidUploadedFileException extends Exception
{
    protected $message = 'Uploaded file is not valid';
}