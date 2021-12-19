<?php


namespace Artemis\Client\Facades;


/**
 * Class Upload
 * @package Artemis\Client\Facades
 *
 * @method static void execute() Executes the download
 * @method static \Artemis\Core\FileHandling\Download file(string $file_path) Sets the path to the file to be downloaded
 * @method static \Artemis\Core\FileHandling\Download api_response($response) Sets the response data from a curl request
 * @method static \Artemis\Core\FileHandling\Download binary(mixed $binary_data) Sets binary data to be downloaded.
 * @method static \Artemis\Core\FileHandling\Download fromHex(string $hex) Sets binary data from an hexadecimal string.
 * @method static \Artemis\Core\FileHandling\Download as(string $filename) Sets the file name for the download
 * @method static \Artemis\Core\FileHandling\Download setFileType(string $type) Sets the mime-type of the file
 *
 * @uses \Artemis\Core\FileHandling\Download::execute()
 * @uses \Artemis\Core\FileHandling\Download::file()
 * @uses \Artemis\Core\FileHandling\Download::api_response()
 * @uses \Artemis\Core\FileHandling\Download::binary()
 * @uses \Artemis\Core\FileHandling\Download::fromHex()
 * @uses \Artemis\Core\FileHandling\Download::as()
 * @uses \Artemis\Core\FileHandling\Download::setFileType()
 */
class Download extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getAccessor()
    {
        return 'download';
    }
}