<?php


namespace Artemis\Core\FileHandling\Exceptions\Upload;


use Exception;


class MoveFileException extends Exception
{
    protected $message = 'Uploaded file could not be moved to its destination';
}