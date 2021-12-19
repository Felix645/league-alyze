<?php


namespace Artemis\Core\Http\Interfaces;


interface HttpStatusCodes
{
    /**
     * Standard response for successful HTTP requests.
     */
    public const HTTP_OK = 200;

    /**
     * The request has been fulfilled, resulting in the creation of a new resource.
     */
    public const HTTP_CREATED = 201;

    /**
     * The server successfully processed the request, and is not returning any content.
     */
    public const HTTP_NO_CONTENT = 204;

    /**
     * This and all future requests should be directed to the given URI.
     */
    public const HTTP_MOVED_PERMANENTLY = 301;

    /**
     * This and all future requests should be directed to the given URI.
     */
    public const HTTP_REDIRECT_FOUND = 302;

    /**
     * The server cannot or will not process the request due to an apparent client error.
     * (e.g., malformed request syntax, size too large, invalid request message framing, or deceptive request routing).
     */
    public const HTTP_BAD_REQUEST = 400;

    /**
     * Authentication is required and failed.
     */
    public const HTTP_UNAUTHORIZED = 401;

    /**
     * Similar to 401 Unauthorized, but specifically for use when authentication is required and has failed
     * or has not yet been provided.
     * The user does not have valid authentication credentials for the target resource.
     */
    public const HTTP_FORBIDDEN = 403;

    /**
     * The requested resource could not be found but may be available in the future.
     * Subsequent requests by the client are permissible.
     */
    public const HTTP_NOT_FOUND = 404;

    /**
     * A request method is not supported for the requested resource.
     * For example, a GET request on a form that requires data to be presented via POST,
     * or a PUT request on a read-only resource.
     */
    public const HTTP_METHOD_NOT_ALLOWED = 405;

    /**
     * The given media is not supported by the server, like image/jpg.
     */
    public const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;

    /**
     * The request entity has a media type which the server or resource does not support.
     * For example, the client uploads an image as image/svg+xml,
     * but the server requires that images use a different format.
     */
    public const HTTP_UNPROCESSABLE_ENTITY = 422;

    /**
     * A generic error message, given when an unexpected condition was encountered
     * and no more specific message is suitable.
     */
    public const HTTP_INTERNAL_ERROR = 500;

    /**
     * The server cannot handle the request (because it is overloaded or down for maintenance).
     * Generally, this is a temporary state.
     */
    public const HTTP_SERVICE_UNAVAILABLE = 503;
}