<?php


namespace Artemis\Core\Http;


use Artemis\Core\Http\Interfaces\ContentTypes;
use Artemis\Core\Http\Interfaces\HttpHeaders;
use Artemis\Core\Http\Interfaces\HttpStatusCodes;
use Artemis\Core\Http\Traits\hasHeaderHandling;
use Artemis\Core\Interfaces\RedirectionInterface;
use Artemis\Core\Traits\hasSessionAlerts;


class Response implements HttpStatusCodes, HttpHeaders, ContentTypes
{
    use hasSessionAlerts, hasHeaderHandling;

    /**
     * HTTP response code.
     *
     * @var int
     */
    private $response_code = self::HTTP_OK;

    /**
     * Response content.
     *
     * @var string
     */
    private $content = '';

    /**
     * Sets a string as response.
     *
     * @param string $content
     *
     * @return $this
     */
    public function text(string $content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Converts given array to a json response.
     *
     * @param array $data
     *
     * @return $this
     */
    public function json(array $data) : Response
    {
        $this->addHeader(self::HEADER_CONTENT_TYPE, self::CONTENT_JSON);
        $this->content = json_encode($data, JSON_UNESCAPED_UNICODE);
        return $this;
    }

    /**
     * Sets the http response code.
     *
     * @param int $code
     *
     * @return $this
     */
    public function code(int $code)
    {
        $this->response_code = $code;
        return $this;
    }

    /**
     * Sets a view response with given view, data and response code.
     *
     * @param string $view
     * @param array $data
     * @param int $code
     *
     * @return $this
     */
    public function view(string $view, array $data, int $code = self::HTTP_OK)
    {
        $this->addHeader(self::HEADER_CONTENT_TYPE, self::CONTENT_HTML);
        $this->response_code = $code;
        $this->content = view($view, $data)->render();
        return $this;
    }

    /**
     * Sets a response header.
     *
     * @param string|array $header
     * @param string $value
     *
     * @return $this
     */
    public function header($header, string $value = '')
    {
        if( is_string($header) ) {
            if( empty($value) ) {
                return $this;
            }

            $header = trim($header);
            $value = trim($value);

            $this->addHeader($header, $value);

            return $this;
        }

        if( is_array($header) ) {
            foreach($header as $header_key => $header_value) {
                if( is_string($header_value) ) {
                    $this->addHeader($header_key, $header_value);
                }

                if( is_array($header_value) ) {
                    foreach($header_value as $value) {
                        $this->addHeader($header_key, $value);
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Gets http response code.
     *
     * @return int
     */
    public function getResponseCode() : int
    {
        return $this->response_code;
    }

    /**
     * Gets response content.
     *
     * @return string
     */
    public function getContent() : string
    {
        return $this->content;
    }

    /**
     * Returns the redirector object
     * If parameter is set it will redirect to the given uri
     * 
     * @param string $uri
     * 
     * @return RedirectionInterface
     */
    public function redirect($uri = '')
    {
        return redirect($uri);
    }

    /**
     * Adds a success message to the session
     * 
     * @param string $key
     * @param string $message
     * 
     * @return void
     */
    public function addSuccess($key, $message)
    {
        $this->setSuccess($key, $message);
    }

    /**
     * Adds a error message to the session
     * 
     * @param string $key
     * @param string $message
     * 
     * @return void
     */
    public function addError($key, $message)
    {
        $this->setError($key, $message);
    }

    /**
     * Adds a validation error message to the session
     * 
     * @param string $key
     * @param string $message
     * 
     * @return void
     */
    public function addValidationError($key, $message)
    {
        container('session')->addAlert('validation', $key, $message);
    }
}