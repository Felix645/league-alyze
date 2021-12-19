<?php


namespace Artemis\Utils;


class SoapResponse
{
    /**
     * Raw soap response.
     *
     * @var mixed
     */
    private $response_raw;

    /**
     * SAP response as JSON
     *
     * @var false|string
     */
    private $response;

    /**
     * SOAPResponse constructor.
     *
     * @param $response
     */
    public function __construct($response)
    {
        $this->response_raw = $response;
        $this->response = json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Gets the raw soap response.
     *
     * @return mixed
     */
    public function getRawResponse()
    {
        return $this->response_raw;
    }


    /**
     * Encodes UTF-8
     *
     * @return SoapResponse
     */
    public function utf8_encode()
    {
        $this->utf8_encode_deep($this->response);
        return $this;
    }

    /**
     * Gets the SOAP response as JSON
     *
     * @return null|string
     */
    public function getJSON()
    {
        return $this->response;
    }

    /**
     * Gets the SOAP response as php array
     *
     * @return null|array
     */
    public function getArray()
    {
        return json_decode($this->response, true);
    }

    /**
     * Encodes UTF-8
     *
     * @param $input
     *
     * @return void
     */
    private function utf8_encode_deep(&$input)
    {
        if (is_string($input)) {
            $input = utf8_encode($input);
        } elseif (is_array($input)) {
            foreach ($input as &$value) {
                $this->utf8_encode_deep($value);
            }

            unset($value);
        } elseif (is_object($input)) {
            $vars = array_keys(get_object_vars($input));

            foreach ($vars as $var) {
                $this->utf8_encode_deep($input->$var);
            }
        }
    }
}