<?php


namespace Artemis\Client\Facades;


/**
 * Class API
 * @package Artemis\Client\Facades
 *
 * @method static \Artemis\Utils\API message(string $message) Sets a message for the response
 * @method static \Artemis\Utils\API data(array $data) Sets data for the response
 * @method static string response(int $code) Returns the API response as JSON with the given HTTP status code
 * @method static string ok() Returns an API response that the request was successful
 * @method static string notFound() Returns an API response that the requested resource was not found
 * @method static string unauthorized() Returns an API response that the request could not be authorized
 * @method static string forbidden() Returns an API response that the resource is forbidden for that request
 * @method static string badRequest() Returns an API response that the request was missing parameters
 * @method static string created() Returns an API response that a ressource was created successfully
 * @method static string noContent() Returns an API response that the request was sucessful but no content is returned
 * @method static string internalError() Returns an API response that an internal server error occured
 *
 * @uses \Artemis\Utils\API::message()
 * @uses \Artemis\Utils\API::data()
 * @uses \Artemis\Utils\API::response()
 * @uses \Artemis\Utils\API::ok()
 * @uses \Artemis\Utils\API::notFound()
 * @uses \Artemis\Utils\API::unauthorized()
 * @uses \Artemis\Utils\API::forbidden()
 * @uses \Artemis\Utils\API::badRequest()
 * @uses \Artemis\Utils\API::created()
 * @uses \Artemis\Utils\API::noContent()
 * @uses \Artemis\Utils\API::internalError()
 */
class API extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getAccessor()
    {
        return 'api';
    }
}