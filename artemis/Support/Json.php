<?php


namespace Artemis\Support;


use Artemis\Support\Exceptions\Json\InvalidJsonException;


class Json
{
    /**
     * Writes the given data to a given json file.
     *
     * @param array $data       Data to be written.
     * @param string $filepath  File to be written.
     * @param int $flag         (optional) JSON flag constants for encoding.
     *
     * @return void
     */
    public static function writeJsonFile(array $data, string $filepath, int $flag = JSON_UNESCAPED_UNICODE) : void
    {
        $json = self::encode($data, $flag);

        FileSystem::overwrite($filepath, $json);
    }

    /**
     * Gets the decoded json data from the given filepath.
     *
     * @param string $filepath Path to the file.
     *
     * @return array Decoded json data.
     *
     * @throws InvalidJsonException Content of file contains invalid json.
     */
    public static function jsonFileContent(string $filepath) : array
    {
        $content = FileSystem::getContents($filepath);

        if( !self::isJson($content) ) {
            throw new InvalidJsonException();
        }

        return self::decode($content);
    }

    /**
     * Checks if given content is valid JSON.
     *
     * @param string $content Content to be checked.
     *
     * @return bool TRUE if the content is valid JSON. FALSE otherwise.
     */
    public static function isJson(string $content) : bool
    {
        @json_decode($content);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Encodes the given data.
     *
     * @param array $data   Data to be encoded.
     * @param int $flag     (optional) Additional flag
     *
     * @return false|string Encoded json string on success. FALSE on failure.
     */
    public static function encode(array $data, int $flag = JSON_UNESCAPED_UNICODE)
    {
        return json_encode($data, $flag);
    }

    /**
     * Decodes the given json data.
     *
     * @param string $content Content to be decoded.
     * @param int $flag (optional) Decoding flags.
     *
     * @return array Decoded json data as array.
     */
    public static function decode(string $content, int $flag = JSON_OBJECT_AS_ARRAY) : array
    {
        return json_decode($content, $flag);
    }
}