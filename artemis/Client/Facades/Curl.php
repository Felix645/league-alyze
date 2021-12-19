<?php


namespace Artemis\Client\Facades;


use Artemis\Core\Http\File;


/**
 * Class Curl
 * @package Artemis\Client\Facades
 *
 * @method static \Artemis\Utils\Curl addRequestURL(string $url) Adds the request URL.
 * @method static \Artemis\Utils\Curl addOption(mixed $option, mixed $value) Adds a curl option.
 * @method static \Artemis\Utils\Curl addPOST(string $key, mixed $value) Adds a curl post parameter.
 * @method static \Artemis\Utils\Curl addParam(string $key, mixed $value) Adds a GET URL query parameter.
 * @method static \Artemis\Utils\Curl addPOSTFiles(string $key, array $files) Adds curl post files from a multipart form.
 * @method static \Artemis\Utils\Curl addPOSTFile(string $key, array $files) Adds curl post files from a multipart form.
 * @method static \Artemis\Utils\Curl addPOSTFileObject($key, File|File[] $input) Adds curl post files from File object or an array of File objects.
 * @method static \Artemis\Utils\Curl addBearer(string $token) Adds a given bearer token to the http header.
 * @method static \Artemis\Utils\CurlResponse execute() Executes the defined curl handle.
 *
 * @uses \Artemis\Utils\Curl::addRequestURL()
 * @uses \Artemis\Utils\Curl::addOption()
 * @uses \Artemis\Utils\Curl::addPOST()
 * @uses \Artemis\Utils\Curl::addPOSTFiles()
 * @uses \Artemis\Utils\Curl::addPOSTFile()
 * @uses \Artemis\Utils\Curl::addPOSTFileObject()
 * @uses \Artemis\Utils\Curl::addBearer()
 * @uses \Artemis\Utils\Curl::execute()
 */
class Curl extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getAccessor()
    {
        return 'curl';
    }
}