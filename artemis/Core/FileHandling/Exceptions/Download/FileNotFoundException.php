<?php


namespace Artemis\Core\FileHandling\Exceptions\Download;


use Exception;


class FileNotFoundException extends Exception
{
    protected $message = 'File not found';
}