<?php


namespace Artemis\Core\Http;


use Artemis\Core\FileHandling\Download;
use Artemis\Core\FileHandling\Exceptions\Download\FileNotFoundException;
use Artemis\Core\FileHandling\Exceptions\Download\NoInputException;
use Artemis\Core\Http\Exceptions\ResponseException;
use Artemis\Core\Http\Interfaces\ContentTypes;
use Artemis\Core\Http\Interfaces\HttpHeaders;
use Artemis\Core\Http\Interfaces\HttpStatusCodes;
use Artemis\Core\Http\Traits\hasHeaderHandling;
use Artemis\Core\Interfaces\RedirectionInterface;
use Artemis\Core\Template\View;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;


class ResponseHandler implements HttpStatusCodes, HttpHeaders, ContentTypes
{
    /**
     * Collection of headers to be sent.
     *
     * @var array
     */
    private $headers = [];

    /**
     * Hands a new response to the response handler.
     *
     * @param mixed $response
     *
     * @return void
     */
    public static function new($response)
    {
        try {
            (new self())->handle($response);
        } catch( FileNotFoundException | NoInputException $e ) {
            report($e);
        }
    }

    /**
     * Handles a View response.
     *
     * @param View $view
     *
     * @return void
     */
    private function handleView(View $view)
    {
        $this->headers[self::HEADER_CONTENT_TYPE][] = self::CONTENT_HTML;

        $this->sendResponse($view->render());
    }

    /**
     * Handles a Redirection response.
     *
     * @param RedirectionInterface $redirect
     *
     * @return void
     */
    private function handleRedirectionInterface(RedirectionInterface $redirect)
    {
        $redirect->execute();

        if( !$this->hasHeaderHandlerTrait($redirect) ) {
            return;
        }

        $this->addHeadersFromTrait($redirect);

        $this->sendResponse('', self::HTTP_REDIRECT_FOUND);
    }

    /**
     * Handles a response object.
     *
     * @param Response $response
     *
     * @return void
     */
    private function handleResponseObject(Response $response)
    {
        $this->addHeadersFromTrait($response);

        $this->sendResponse($response->getContent(), $response->getResponseCode());
    }

    /**
     * Handles an object response.
     *
     * @param $object
     *
     * @return void
     */
    private function handleObject($object)
    {
        if( method_exists($object, '__toString') ) {
            $this->reportResponseException();
            return;
        }

        $this->sendResponse($object->__toString());
    }

    /**
     * Handles a primitive like string, int, double.
     *
     * @param string|int|double $value
     *
     * @return void
     */
    private function handlePrimitve($value)
    {
        $this->sendResponse($value);
    }

    /**
     * Handles an array response.
     *
     * @param array $array
     *
     * @return void
     */
    private function handleArray(array $array)
    {
        $this->headers[self::HEADER_CONTENT_TYPE][] = self::CONTENT_JSON;
        $this->sendResponse(json_encode($array, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handles an Arrayable object response.
     *
     * @param Arrayable $object
     *
     * @return void
     */
    private function handleArrayable(Arrayable $object)
    {
        $this->handleArray($object->toArray());
    }

    /**
     * Handles an Jsonable object response.
     *
     * @param Jsonable $object
     *
     * @return void
     */
    private function handleJsonable(Jsonable $object)
    {
        $this->headers[self::HEADER_CONTENT_TYPE][] = self::CONTENT_JSON;
        $this->sendResponse($object->toJson(JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handles a Download response.
     *
     * @param Download $download
     * @throws \Artemis\Core\FileHandling\Exceptions\Download\FileNotFoundException
     * @throws \Artemis\Core\FileHandling\Exceptions\Download\NoInputException
     *
     * @return void
     */
    private function handleDownload(Download $download)
    {
        $download->execute();
        exit;
    }

    /**
     * Sends the headers from the header collection.
     *
     * @return void
     */
    private function sendHeaders()
    {
        if( headers_sent() ) {
            return;
        }

        foreach( $this->headers as $header_key => $header_values) {
            $header_value = implode('; ', $header_values);
            $header_key = strtolower($header_key);
            header("$header_key: $header_value");
        }
    }

    /**
     * Sends a response with given given content and response code.
     *
     * @param string|int|float $content
     * @param int $status_code
     *
     * @return void
     */
    private function sendResponse($content = '', $status_code = self::HTTP_OK)
    {
        $this->sendHeaders();
        http_response_code($status_code);

        if( empty($content) ) {
            return;
        }

        echo $content;
    }

    /**
     * Checks if the given class or object uses the hasHeaderHandling trait.
     *
     * @param string|object $class
     *
     * @return bool
     */
    private function hasHeaderHandlerTrait($class)
    {
        return in_array(hasHeaderHandling::class, class_uses_recursive($class), true);
    }

    /**
     * Populates the header collection from the hasHeaderHandling trait.
     *
     * @param object $object
     *
     * @return void
     */
    private function addHeadersFromTrait($object)
    {
        if( !$this->hasHeaderHandlerTrait($object) ) {
            return;
        }

        $headers = $object->getHeaders();

        foreach( $headers as $header_key => $header_values ) {
            $this->headers[$header_key] = $header_values;
        }
    }

    /**
     * Handles the given response.
     *
     * @param mixed $response
     * @throws \Artemis\Core\FileHandling\Exceptions\Download\FileNotFoundException
     * @throws \Artemis\Core\FileHandling\Exceptions\Download\NoInputException
     *
     * @return void
     */
    public function handle($response)
    {
        switch(true) {
            case $response instanceof View:
                $this->handleView($response);
            break;

            case $response instanceof RedirectionInterface:
                $this->handleRedirectionInterface($response);
            break;

            case $response instanceof Response:
                $this->handleResponseObject($response);
            break;

            case is_string($response):
            case is_int($response):
            case is_float($response):
            case is_double($response):
                $this->handlePrimitve($response);
            break;

            case is_array($response):
                $this->handleArray($response);
            break;

            case $response instanceof Jsonable:
                $this->handleJsonable($response);
            break;

            case $response instanceof Arrayable:
                $this->handleArrayable($response);
            break;

            case $response instanceof Download:
                $this->handleDownload($response);
            break;

            case is_object($response):
                $this->handleObject($response);
            break;

            default:
                $this->reportResponseException();
        }

        exit;
    }

    /**
     * Reports a ResponseException to the Error Handler.
     *
     * @return void
     */
    private function reportResponseException()
    {
        report(new ResponseException('Unknown response type'));
    }
}