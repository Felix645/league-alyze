<?php


namespace Artemis\Core\Http\Interfaces;


interface HttpHeaders
{
    /**
     * Lists content types that are accepted by the request.
     */
    public const HEADER_REQUEST_ACCEPT = 'Accept';

    /**
     * Lists charsets that the request can display.
     */
    public const HEADER_REQUEST_ACCEPT_CHAR = 'Accept-Charset';

    /**
     * Lists the encoded data format the client can understand.
     */
    public const HEADER_ACCEPT_ENCODE = 'Accept-Encoding';

    /**
     * Lists the languages that the client accepts.
     */
    public const HEADER_REQUEST_LANG = 'Accept-Language';

    /**
     * Provides authentication method like Bearer or Basic Auth.
     */
    public const HEADER_REQUEST_AUTH = 'Authorization';

    /**
     * Provides authentication for proxy.
     */
    public const HEADER_REQUEST_PROXY_AUTH = 'Proxy-Authorization';

    /**
     * Provides cache control options during request/response chain.
     */
    public const HEADER_CACHE = 'Cache-Control';

    /**
     * Which connection type the client prefers.
     */
    public const HEADER_CONNECTION = 'Connection';

    /**
     * HTTP-Cookie that was set from the server via Set-Cookie before.
     */
    public const HEADER_REQUEST_COOKIE = 'Cookie';

    /**
     * Host that sent the request.
     */
    public const HEADER_REQUEST_HOST = 'Host';

    /**
     * Redirect to a different URL.
     */
    public const HEADER_REQUEST_REFERER = 'Referer';

    /**
     * Restrict access to resource to specific methods. Typically send with a 405 Method Not Allowed.
     */
    public const HEADER_RESPONSE_ALLOW = 'Allow';

    /**
     * Encoding of the content.
     */
    public const HEADER_CONTENT_ENCODE = 'Content-Encoding';

    /**
     * Language of the content.
     */
    public const HEADER_CONTENT_LANG = 'Content-Language';

    /**
     * Size of the content body.
     */
    public const HEADER_CONTENT_LENGTH = 'Content-Length';

    /**
     * Used to generate download windows.
     */
    public const HEADER_CONTENT_DISPOSITION = 'Content-Disposition';

    /**
     * Mimetype of content.
     */
    public const HEADER_CONTENT_TYPE = 'Content-Type';

    /**
     * Used for redirects.
     */
    public const HEADER_RESPONSE_LOCATION = 'Location';

    /**
     * Sets a cookie.
     */
    public const HEADER_RESPONSE_SET_COOKIE = 'Set-Cookie';
}