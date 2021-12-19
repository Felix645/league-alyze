<?php


namespace Artemis\Core\ErrorHandling\DebugData;


class Request
{
    /**
     * Request object.
     *
     * @var \Artemis\Core\Http\Request
     */
    private $request;

    /**
     * Request URL.
     *
     * @var string
     */
    private $url;

    /**
     * Request path.
     *
     * @var string
     */
    private $path;

    /**
     * Request method.
     *
     * @var string
     */
    private $method;

    /**
     * Request headers.
     *
     * @var array
     */
    private $headers;

    /**
     * Request body.
     *
     * @var array
     */
    private $body;

    /**
     * Request files.
     *
     * @var array
     */
    private $files;

    /**
     * Session array.
     *
     * @var array
     */
    private $session;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->request = request();
        $this->buildDebugData();
    }

    /**
     * Gets the request URL.
     *
     * @return string
     */
    public function url()
    {
        return $this->url;
    }

    /**
     * Gets the request path.
     *
     * @return string
     */
    public function path()
    {
        return $this->path;
    }

    /**
     * Gets the request method.
     *
     * @return string
     */
    public function method()
    {
        return $this->method;
    }

    /**
     * Gets the request headers.
     *
     * @return array
     */
    public function headers()
    {
        return $this->headers;
    }

    /**
     * Gets the request body.
     *
     * @return array
     */
    public function body()
    {
        return $this->body;
    }

    /**
     * Gets the request files.
     *
     * @return array
     */
    public function files()
    {
        return $this->files;
    }

    /**
     * Gets the session array.
     *
     * @return array
     */
    public function session()
    {
        return $this->session;
    }

    /**
     * Preparing request data for display in debug page.
     *
     * @return void
     */
    private function buildDebugData()
    {
        $this->url = $this->request->getRequestURL();
        $this->path = $this->request->getRequestURI();
        $this->method = $this->request->getRequestMethod();
        $this->headers = $this->buildHeader($this->request->getHeaders());
        $this->body = $this->buildBody($this->request->all());
        $this->files = $this->buildFiles($this->request->filesAll());
        $this->session = $this->buildSession(session()->all());
    }

    /**
     * Preparing the session data.
     *
     * @param array $session
     *
     * @return array
     */
    private function buildSession($session)
    {
        $session_built = [];

        foreach( $session as $key => $value ) {

            if( is_array($value) ) {
                $value = $this->buildSession($value);
            } elseif( is_bool($value) ) {
                $value = $value ? "TRUE" : "FALSE";
            } elseif( is_object($value) ) {
                $class = get_class($value);
                $value = "object '$class'";
            } elseif( $value instanceof \Closure ) {
                $value = "closure";
            } else {
                $value = "'$value'";
            }

            $session_built[$key] = $value;
        }

        return $session_built;
    }

    /**
     * Preparing the files data.
     *
     * @param array $files
     *
     * @return array
     */
    private function buildFiles($files)
    {
        $built_files = [];

        foreach( $files as $key => $value ) {
            if( is_array($value) ) {
                $built_files[$key] = $this->buildFiles($value);
                continue;
            }

            $built_files[$key] = $value->getName();
        }

        return $built_files;
    }

    /**
     * Preparing the request body data.
     *
     * @param array $body
     *
     * @return array
     */
    private function buildBody($body)
    {
        $built_body = [];

        foreach( $body as $key => $value ) {
            if( is_array($value) ) {
                $value = $this->buildBody($value);
            } elseif( is_bool($value) ) {
                $value = $value ? "TRUE" : "FALSE";
            } elseif( is_object($value) ) {
                $class = get_class($value);
                $value = "object '$class'";
            } elseif( $value instanceof \Closure ) {
                $value = "closure";
            } else {
                $value = "'$value'";
            }

            $built_body[$key] = $value;
        }

        return $built_body;
    }

    /**
     * Preparing the headers data.
     *
     * @param array $headers
     *
     * @return array
     */
    private function buildHeader($headers)
    {
        $built_headers = [];

        foreach( $headers as $header_key => $values ) {
            $built_headers[$header_key] = '';

            foreach( $values as $value ) {
                $built_headers[$header_key] .= "$value; ";
            }

            $built_headers[$header_key] = rtrim(rtrim($built_headers[$header_key], ' '), ';');
        }

        return $built_headers;
    }
}