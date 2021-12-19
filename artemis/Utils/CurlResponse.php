<?php


namespace Artemis\Utils;


class CurlResponse
{
    /**
     * Curl response
     *
     * @var mixed
     */
    private $response;

    /**
     * Curl response as json string
     *
     * @var string
     */
    private $response_json;

    /**
     * Curl response as php array
     *
     * @var array
     */
    private $response_array;

    /**
     * Identifier if curl response is a json string
     *
     * @var bool
     */
    private $is_json = false;

    /**
     * CurlResponse constructor.
     *
     * @param mixed $response
     */
    public function __construct($response)
    {
        $this->response = $response;
        $this->json_validator();
        $this->setResponseJson();
        $this->setResponseArray();
    }

    /**
     * Gets the raw response
     *
     * @return mixed
     */
    public function getRaw()
    {
        return $this->response;
    }

    /**
     * Gets the curl response as a php array
     *
     * @return null|array
     */
    public function getArray()
    {
        return $this->response_array;
    }

    /**
     * Gets the curl response as a json string
     *
     * @return null|string
     */
    public function getJSON()
    {
        return $this->response_json;
    }

    /**
     * Returns if the curl response is a valid json string or not
     *
     * @return bool
     */
    public function isJSON()
    {
        return $this->is_json;
    }

    /**
     * Sets the response json property
     *
     * @return void
     */
    private function setResponseJson()
    {
        if( !$this->is_json )
            return;

        $this->response_json = $this->response;
    }

    /**
     * Sets the response array property
     *
     * @return void
     */
    private function setResponseArray()
    {
        if( !$this->is_json )
            return;

        $this->response_array = json_decode($this->response, true);
    }

    /**
     * Checks if curl response is a valid json string and sets the corresponding property
     *
     * @return void
     */
    private function json_validator()
    {
        if ( !empty($this->response) ) {
            @json_decode($this->response);
            $this->is_json = (json_last_error() === JSON_ERROR_NONE);
        }
    }
}