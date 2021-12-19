<?php


namespace Artemis\Core\Http\Interfaces\Request;


interface HasHeadersInterface
{
    /**
     * Gets all request headers.
     *
     * @return array
     */
    public function getHeaders();

    /**
     * Checks if the given header key is present on the request.
     *
     * @param string $header_key Header key to be checked.
     *
     * @return bool True if the header is present, false if not.
     */
    public function hasHeader($header_key);

    /**
     * Gets the values of the given header key as an array list.
     *
     * @param string $header_key    Header key.
     * @param mixed $default        Default value should the header not exist.
     *
     * @return array|null List of the header values. Default value if the header does not exist.
     */
    public function header($header_key, $default = null);

    /**
     * Gets the first value of the given header key.
     *
     * @param string $header_key    Header key.
     * @param mixed $default        Default value.
     *
     * @return string|mixed|null First header key if header is present. Otherwise returns null or the specified default.
     */
    public function headerFirst($header_key, $default = null);

    /**
     * Gets the bearer token from the request.
     *
     * @return string Bearer token if it is present on the request. Empty string when it is not.
     */
    public function bearerToken();

    /**
     * Checks if a given header contains a given value.
     *
     * @param string $header            Header key.
     * @param string|string[] $value    Header value. Optionally a list of header values may be provided.
     *
     * @return bool True if the header exists and has the given values. False otherwise.
     */
    public function headerContains($header, $value);

    /**
     * Checks if any of the given content types are accepted by the request.
     *
     * @param string|string[] $value List of content-types or first content-type.
     * @param mixed ...$values Additional content-types.
     *
     * @return bool True if any of content-types are accepted by the request. False otherwise.
     */
    public function accepts($value, ...$values);

    /**
     * Checks if the request needs text/html as response content type.
     *
     * @return bool True if text/html is present in accept header. False otherwise.
     */
    public function needsHtml();

    /**
     * Checks if the request needs application/json as response content type.
     *
     * @return bool True if application/json is present in accept header. False otherwise.
     */
    public function needsJson();

    /**
     * Checks if the request needs text/xml as response content type.
     *
     * @return bool True if text/xml is present in accept header. False otherwise.
     */
    public function needsXml();

    /**
     * Determines if the request was made via an ajax call.
     *
     * @return bool True if request was made via an ajax call, false otherwise.
     */
    public function isAjax();
}