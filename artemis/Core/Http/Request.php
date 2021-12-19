<?php


namespace Artemis\Core\Http;


use Artemis\Support\Arr;
use Artemis\Support\Str;
use Artemis\Core\Http\Exceptions\RequestException;
use Notihnio\MultipartFormDataParser\MultipartFormDataParser;


class Request extends RequestContract
{
    /**
     * The request url
     * 
     * @var string
     */
    private $url;

    /**
     * The request uri
     * 
     * @var string
     */
    private $uri;

    /**
     * URL Bits of the request uri
     * 
     * @var array
     */
    private $url_bits = array();

    /**
     * The request method
     * 
     * @var string
     */
    private $request_method = '';

    /**
     * The request body
     * 
     * @var array
     */
    private $request_body = [];

    /**
     * Array of validated request variables
     *
     * @var array
     */
    private $validated = [];

    /**
     * Collection of URL parameters (set by Router)
     * 
     * @var array
     */
    private $url_params = array();

    /**
     * CSRF-Token
     * 
     * @var null|string
     */
    private $csrf_token = null;

    /**
     * Collection of file objects
     *
     * @var File[]
     */
    private $files = [];

    /**
     * Header collection object.
     *
     * @var HeaderCollection
     */
    private $headers;

    /**
     * Starts the request
     *
     * @return void
     */
    public function start()
    {
        try {
            $this->headers = new HeaderCollection();
            $this->setRequestURL();
            $this->setRequestURI();
            $this->setRequestMethod();
            $this->setRequestBody();
            $this->calculateURLBits();
            $this->setFiles();

            if( config('csrf_protection') ) {
                $this->setCSRFToken();
            }
        } catch(\Throwable $e) {
            report($e);
        }
    }

    /**
     * @inheritDoc
     * 
     * @return string
     */
    public function getRequestURL()
    {
        return $this->url;
    }

    /**
     * @inheritDoc
     * 
     * @return string $uri
     */
    public function getRequestURI()
    {
        return $this->uri;
    }

    /**
     * @inheritDoc
     * 
     * @return string $request_method
     */
    public function getRequestMethod()
    {
        return $this->request_method;
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers->all();
    }

    /**
     * @inheritDoc
     *
     * @return bool True if the header is present, false if not.
     */
    public function hasHeader($header_key)
    {
        return $this->headers->has($header_key);
    }

    /**
     * @inheritDoc
     *
     * @return array|null List of the header values. Default value if the header does not exist.
     */
    public function header($header_key, $default = null)
    {
        return $this->headers->get($header_key, $default);
    }

    /**
     * @inheritDoc
     *
     * @return string|mixed|null First header key if header is present. Otherwise returns null or the specified default.
     */
    public function headerFirst($header_key, $default = null)
    {
        return $this->headers->first($header_key, $default);
    }

    /**
     * @inheritDoc
     *
     * @return string Bearer token if it is present on the request. Empty string when it is not.
     */
    public function bearerToken()
    {
        $headers = $this->headerFirst(self::HEADER_REQUEST_AUTH);

        // HEADER: Get the access token from the header
        if( !empty( $headers ) ) {
            if( $bearer = Str::match('/Bearer\s(\S+)/', $headers) ) {
                return $bearer;
            }
        }

        return '';
    }

    /**
     * @inheritDoc
     *
     * @return bool True if the header exists and has the given values. False otherwise.
     */
    public function headerContains($header, $value)
    {
        if( is_string($value) ) {
            return $this->headers->contains($header, $value);
        }

        if( is_array($value) ) {
            foreach( $value as $header_value ) {
                if( !$this->headerContains($header, $header_value) ) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     *
     * @return bool True if any of content-types are accepted by the request. False otherwise.
     */
    public function accepts($value, ...$values)
    {
        if( is_array($value) ) {
            foreach( $value as $header_value ) {
                if( $this->headerContains(self::HEADER_REQUEST_ACCEPT, $header_value) ) {
                    return true;
                }
            }

            return false;
        }

        if( is_string($value) ) {
            $header_values = Arr::merge([$value], $values);
            return $this->accepts($header_values);
        }

        return false;
    }

    /**
     * @inheritDoc
     *
     * @return bool True if text/html is present in accept header. False otherwise.
     */
    public function needsHtml()
    {
        return $this->accepts(self::CONTENT_HTML);
    }

    /**
     * @inheritDoc
     *
     * @return bool True if application/json is present in accept header. False otherwise.
     */
    public function needsJson()
    {
        return $this->accepts(self::CONTENT_JSON);
    }

    /**
     * @inheritDoc
     *
     * @return bool True if text/xml is present in accept header. False otherwise.
     */
    public function needsXml()
    {
        return $this->accepts(self::CONTENT_XML_2);
    }

    /**
     * @inheritDoc
     *
     * @return bool True if request was made via an ajax call, false otherwise.
     */
    public function isAjax()
    {
        $x_requested_with = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? null;

        return !empty($x_requested_with) && Str::lower($x_requested_with) === 'xmlhttprequest';
    }

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function validate($rules, $body = [])
    {
        if( empty($body) )
            $body = $this->all();

        /* @var \Artemis\Core\Validation\Validation $validation */
        $validation = container('validation');

        $validation->validate($body, $rules);

        if( $validation->fails() ) {
            $errors = $validation->errors();

            foreach( $errors as $error ) {
                container('response')->addError($error["key"], $error["message"]);
            }
        }
    }

    /**
     * @inheritDoc
     * 
     * @return array
     */
    public function all()
    {
        return $this->request_body;
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function validated()
    {
        return $this->validated;
    }

    /**
     * @inheritDoc
     * 
     * @return string|null $value
     */
    public function getURLParam($key)
    {
        return $this->url_params[$key] ?? null;
    }

    /**
     * @inheritDoc
     * 
     * @return array $url_bits
     */
    public function getURLBits()
    {
        return $this->url_bits;
    }

    /**
     * @inheritDoc
     * 
     * @return string
     */
    public function getLastPage()
    {
        return container('session')->getlastPage();
    }

    /**
     * @inheritDoc
     * 
     * @return string|null
     */
    public function getCSRFToken()
    {
        return $this->csrf_token;
    }

    /**
     * @inheritDoc
     *
     * @return null|File|array
     */
    public function files($key)
    {
        return $this->files[$key] ?? null;
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function filesAll()
    {
        return $this->files;
    }

    /**
     * @inheritDoc
     *
     * @return $this
     */
    public function addToValidated($key, $value)
    {
        $this->validated[$key] = $value;
        return $this;
    }

    /**
     * Sets the request csrf token
     * 
     * @return void
     */
    private function setCSRFToken()
    {
        if( $this->request_method === 'get' )
            return;

        if( !isset($_POST["_csrf"]) )
            return;

        $this->csrf_token = $_POST["_csrf"];
        $this->request_body = $this->removeKeys($this->request_body, ["_csrf"]);
    }

    /**
     * Sets the full current request URL
     * 
     * @return void
     */
    private function setRequestURL()
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            $url = "https";
        else
            $url = "http";

        // Here append the common URL characters.
        $url .= "://";

        // Append the host(domain name, ip) to the URL.
        $url .= $_SERVER['HTTP_HOST'];

        // Append the requested resource location to the URL
        $url_temp = $url . $_SERVER['REQUEST_URI'];

        $url .= parse_url($url_temp, PHP_URL_PATH);

        $this->url = $url;
    }

    /**
     * Sets the current request uri
     * 
     * @return void
     */
    private function setRequestURI()
    {
        $this->uri = $_GET['uri'] ?? '';
    }

    /**
     * Sets the current request method
     * 
     * @throws RequestException
     * 
     * @return void
     */
    private function setRequestMethod()
    {
        $this->request_method = strtolower($_SERVER['REQUEST_METHOD']);

        if( "POST" === $_SERVER['REQUEST_METHOD'] ) {
            if( isset($_POST["_method"]) ) {
                $method = strtolower($_POST["_method"]);

                if( "get" === $method || "post" === $method || "put" === $method || "patch" === $method || "delete" === $method )
                    $this->request_method = $method;
                else 
                    throw new RequestException('Invalid "_method"-value for request provided');
            }
        }
    }

    /**
     * Sets the request body
     * 
     * @return void
     */
    private function setRequestBody()
    {
        $body = [];
        $remove = ["_method", "_csrf"];
        $request_method = $this->request_method;

        if( 'post' === $request_method ) {
            if( !empty($_POST) )
                $body = $this->removeKeys($_POST, $remove);
        } elseif( 'get' === $request_method ) {
            if( !empty($_GET) )
                $body = $this->removeKeys($_GET, $remove);
        } else {
            if( !empty($_POST) ) {
                $body = $this->removeKeys($_POST, $remove);
            } else {
                $request = MultipartFormDataParser::parse();
                $body = $this->removeKeys($request->params ?? [], $remove);
            }
        }

        unset($body['uri']);
        $this->request_body = $this->sanitizeBody($body);
    }

    /**
     * Sanitizes the given array.
     *
     * @param $body
     *
     * @return array
     */
    private function sanitizeBody($body) : array
    {
        $clear_body = [];

        foreach( $body as $key => $value ) {
            if( is_array($value) ) {
                $clear_body[$key] = $this->sanitizeBody($value);
                continue;
            }

            if( is_string($value) || is_int($value) || is_float($value) ) {
                $clear_body[$key] = filter_var($value, FILTER_SANITIZE_STRING);
                continue;
            }

            $clear_body[$key] = $value;
        }

        return $clear_body;
    }

    /**
     * @inheritDoc
     *
     * @param $value
     */
    public function addToBody($key, $value)
    {
        $this->request_body[$key] = $value;
    }

    /**
     * Removes keys from haystack on given $remove array
     * 
     * @param array $haystack
     * @param array $remove
     * 
     * @return array
     */
    private function removeKeys($haystack, $remove)
    {
        return Arr::removeKeys($haystack, $remove);
    }

    /**
     * @inheritDoc
     * 
     * @return void
     */
    public function addURLParam($key, $value)
    {
        $this->url_params[$key] = $value;
    }

    /**
     * Calculates the url bits of the current request
     * 
     * @return void
     */
    private function calculateURLBits()
    {
        $this->url_bits = explode('/', Str::trim($this->uri, '/'));
    }

    /**
     * Populates the file collection when uploaded files are present
     *
     * @return void
     */
    private function setFiles()
    {
        if( empty($_FILES) )
            return;

        foreach( $_FILES as $field_name => $file ) {
            if( is_array($file["name"]) ) {
                $array_files = $this->reArrayFiles($file);

                foreach( $array_files as $array_file ) {
                    if( $array_file['error'] === 0 )
                        $this->files[$field_name][] = new File($array_file);
                }
            } else {
                if( $file['error'] === 0 )
                    $this->files[$field_name] = new File($file);
            }
        }
    }

    /**
     * Reformats a file array from a multipart form
     *
     * @param array $field
     *
     * @return array
     */
    private function reArrayFiles($field)
    {
        $new_files = [];
        foreach( $field as $key => $file_info ) {
            foreach( $file_info as $file_key => $value ) {
                $new_files[$file_key][$key] = $value;
            }
        }

        return $new_files;
    }
}