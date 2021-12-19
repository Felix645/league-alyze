<?php


namespace Artemis\Client\Facades;


use Artemis\Core\Http\File;


/**
 * Class Upload
 * @package Artemis\Client\Facades
 *
 * @method static bool save(string $destination) Stores file on given destination
 * @method static \Artemis\Core\FileHandling\Upload input(File $file) Sets the file to be uploaded
 * @method static \Artemis\Core\FileHandling\Upload maxSize(int $max_size) Sets maximum size allowed for the file
 * @method static \Artemis\Core\FileHandling\Upload as(string $name) Name under which the file is to be stored
 * @method static \Artemis\Core\FileHandling\Upload allowMimeType(string|array $input) Sets the allowed mime types, provided as single string or an array of mime types
 * @method static \Artemis\Core\FileHandling\Upload allowExtension(string|array $input) Sets the allowed extensions, provided as single string or an array of extensions
 *
 * @uses \Artemis\Core\FileHandling\Upload::save()
 * @uses \Artemis\Core\FileHandling\Upload::input()
 * @uses \Artemis\Core\FileHandling\Upload::maxSize()
 * @uses \Artemis\Core\FileHandling\Upload::as()
 * @uses \Artemis\Core\FileHandling\Upload::allowMimeType()
 * @uses \Artemis\Core\FileHandling\Upload::allowExtension()
 */
class Upload extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getAccessor()
    {
        return 'upload';
    }
}