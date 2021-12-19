<?php


namespace Artemis\Core\FileHandling\Exceptions\Upload;


use Exception;


class DirCreationException extends Exception
{
    protected $message = 'Destination directory could not be created';
}