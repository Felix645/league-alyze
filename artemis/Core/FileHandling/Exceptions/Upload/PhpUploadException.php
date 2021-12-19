<?php


namespace Artemis\Core\FileHandling\Exceptions\Upload;


use Exception;
use Throwable;


class PhpUploadException extends Exception
{
    private const ERROR_MAP = [
        UPLOAD_ERR_INI_SIZE     => "Uploaded file exceeds the upload_max_filesize directive in php.ini",
        UPLOAD_ERR_FORM_SIZE    => "Uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
        UPLOAD_ERR_PARTIAL      => "The uploaded file was only partially uploaded",
        UPLOAD_ERR_NO_FILE      => "No file was uploaded",
        UPLOAD_ERR_NO_TMP_DIR   => "Missing a temporary folder",
        UPLOAD_ERR_CANT_WRITE   => "Failed to write file to disk",
        UPLOAD_ERR_EXTENSION    => "File upload stopped by extension",
    ];

    private const DEFAULT_ERROR = "An unknown upload error occured";

    public function __construct($upload_error_code, $code = 0, Throwable $previous = null)
    {
        $message = self::ERROR_MAP[$upload_error_code] ?? self::DEFAULT_ERROR;
        parent::__construct($message, $code, $previous);
    }
}