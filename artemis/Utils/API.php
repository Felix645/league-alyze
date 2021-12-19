<?php


namespace Artemis\Utils;


use Artemis\Core\Http\Response;


class API
{
    /**
     * The HTTP status code
     *
     * @var int
     */
    private $http_response_code;

    /**
     * API response message
     *
     * @var null|string
     */
    private $message = null;

    /**
     * API response data
     *
     * @var null|array
     */
    private $data = null;

    /**
     * Sets a message for the response
     *
     * @param string $message
     *
     * @return API
     */
    public function message($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Sets data for the response
     *
     * @param array $data
     *
     * @return API
     */
    public function data($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Returns just the json-encoded data with given response code
     *
     * @param array $data
     * @param int $code
     *
     * @return string
     */
    public function raw($data, $code)
    {
        $this->http_response_code = $code;
        return $this->buildJson($data);
    }

    /**
     * Returns the API response as JSON with the given HTTP status code
     *
     * @param int $code
     *
     * @return string
     */
    public function response($code)
    {
        $this->http_response_code = $code;
        return $this->buildResponse();
    }

    /**
     * Returns an API response that the request was successful
     *
     * @return string
     */
    public function ok()
    {
        $this->http_response_code = Response::HTTP_OK;
        return $this->buildResponse();
    }

    /**
     * Returns an API response that a ressource was created successfully
     *
     * @return string
     */
    public function created()
    {
        $this->http_response_code = Response::HTTP_CREATED;
        return $this->buildResponse();
    }

    /**
     * Returns an API response that the request was sucessful but no content is returned
     *
     * @return string
     */
    public function noContent()
    {
        $this->http_response_code = Response::HTTP_NO_CONTENT;
        return $this->buildResponse();
    }

    /**
     * Returns an API response that the requested resource was not found
     *
     * @return string
     */
    public function notFound()
    {
        $this->http_response_code = Response::HTTP_NOT_FOUND;
        $this->message = $this->message ?? app()->api_config()['notFoundMessage'];
        return $this->buildResponse();
    }

    /**
     * Returns an API response that the request could not be authorized
     *
     * @return string
     */
    public function unauthorized()
    {
        $this->http_response_code = Response::HTTP_UNAUTHORIZED;
        $this->message = $this->message ?? app()->api_config()['unauthorizedMessage'];
        return $this->buildResponse();
    }

    /**
     * Returns an API response that the resource is forbidden for that request
     *
     * @return string
     */
    public function forbidden()
    {
        $this->http_response_code = Response::HTTP_FORBIDDEN;
        $this->message = $this->message ?? app()->api_config()['forbiddenMessage'];
        return $this->buildResponse();
    }

    /**
     * Returns an API response that the request was missing parameters
     *
     * @return string
     */
    public function badRequest()
    {
        $this->http_response_code = Response::HTTP_BAD_REQUEST;
        $this->message = $this->message ?? app()->api_config()['badRequestMessage'];
        return $this->buildResponse();
    }

    /**
     * Returns an API response that an internal server error occured
     *
     * @return string
     */
    public function internalError()
    {
        $this->http_response_code = Response::HTTP_INTERNAL_ERROR;
        return $this->buildResponse();
    }

    /**
     * Builds the API response JSON
     *
     * @return string
     */
    private function buildResponse()
    {
        $response = [
            'status' => $this->http_response_code,
            'message' => $this->message,
            'data' => $this->data
        ];

        return $this->buildJson($response);
    }

    /**
     * Builds JSON output
     *
     * @param array $data
     *
     * @return string
     */
    private function buildJson($data)
    {
        header('Content-Type: application/json');
        http_response_code($this->http_response_code);

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}