<?php


namespace Artemis\Utils;


use Artemis\Support\Arr;
use Artemis\Core\Http\File;
use CURLFile;


class Curl
{
    /**
     * Curl Handle Object
     *
     * @var mixed
     */
    private $ch;

    /**
     * Request URL
     *
     * @var string
     */
    private $url;

    /**
     * Collection of curl options
     *
     * @var array
     */
    private $curl_options = [];

    /**
     * Collection of curl posts values
     *
     * @var array
     */
    private $curl_posts = [];

    /**
     * Params to be appended to the url string
     *
     * @var array
     */
    private $curl_params = [];

    /**
     * Curl Constructor.
     */
    public function __construct()
    {
        $this->ch = curl_init();

        $this->curl_options[CURLOPT_HEADER] = false;
        $this->curl_options[CURLOPT_RETURNTRANSFER] = true;
    }

    /**
     * Adds the request URL.
     *
     * @param string $url
     *
     * @return Curl
     */
    public function addRequestURL($url)
    {
        // curl_setopt( $this->ch, CURLOPT_URL, $url );
        $this->url = $url;

        return $this;
    }

    /**
     * Adds a curl option.
     *
     * @param $option
     * @param mixed $value
     *
     * @return Curl
     */
    public function addOption($option, $value)
    {
        $this->curl_options[$option] = $value;

        return $this;
    }

    /**
     * Adds a curl post parameter.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return Curl
     */
    public function addPOST($key, $value)
    {
        if( null !== $value )
            $this->curl_posts[$key] = $value;

        return $this;
    }

    /**
     * Adds a GET URL query parameter.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return Curl
     */
    public function addParam($key, $value)
    {
        if( null !== $value )
            $this->curl_params[$key] = urlencode($value);

        return $this;
    }

    /**
     * Adds curl post files from a multipart form.
     *
     * @param string $key
     * @param array $files
     *
     * @return Curl
     */
    public function addPOSTFiles($key, $files)
    {
        $files = self::reArrayFiles($files);

        foreach( $files as $index => $file ) {
            $curl_file = new CURLFile($file["tmp_name"], $file["type"], $file["name"]);
            $this->addPOST($key.'['.$index.']', $curl_file);
        }

        return $this;
    }

    /**
     * Adds curl post files from a multipart form.
     *
     * @param string $key
     * @param array $file
     *
     * @return Curl
     */
    public function addPOSTFile($key, $file)
    {
        $curl_file = new CURLFile($file["tmp_name"], $file["type"], $file["name"]);
        $this->addPOST($key, $curl_file);

        return $this;
    }

    /**
     * Adds curl post files from File object or an array of File objects.
     *
     * @param string $key
     * @param File|File[] $input
     *
     * @return Curl
     */
    public function addPOSTFileObject($key, $input)
    {
        if( $input instanceof File) {
            $curl_file = new CURLFile($input->getTmpName(), $input->getType(), $input->getName());
            $this->addPOST($key, $curl_file);
        }

        if( is_array($input) ) {
            foreach( $input as $index => $file ) {
                $curl_file = new CURLFile($file->getTmpName(), $file->getType(), $file->getName());
                $this->addPOST($key.'['.$index.']', $curl_file);
            }
        }

        return $this;
    }

    /**
     * Adds a given bearer token to the http header.
     *
     * @param string $token
     *
     * @return Curl
     */
    public function addBearer($token)
    {
        $header_string = "Authorization: Bearer " . $token;
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, [$header_string]);

        return $this;
    }

    /**
     * Executes the defined curl handle.
     *
     * @return CurlResponse
     */
    public function execute()
    {
        $this->setURL();
        $this->setOptions();
        $this->setBody();

        $response = curl_exec($this->ch);

        curl_close($this->ch);

        return new CurlResponse($response);
    }

    /**
     * Builds the request URL string
     *
     * @return void
     */
    private function setURL()
    {
        if( isset($this->url) ) {
            $this->url .= Arr::queryString($this->curl_params);

            curl_setopt( $this->ch, CURLOPT_URL, $this->url );
        }
    }

    /**
     * Sets all defined options
     *
     * @return void
     */
    private function setOptions()
    {
        if( !empty($this->curl_options) )
        {
            foreach( $this->curl_options as $option => $value )
            {
                curl_setopt($this->ch, $option, $value);
            }
        }
    }

    /**
     * Sets all defined body parameters
     *
     * @return void
     */
    private function setBody()
    {
        if( !empty($this->curl_posts) )
        {
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->curl_posts);
        }
    }

    /**
     * Modifies a given file array to be user for addPOSTFiles()
     *
     * @param array $files
     *
     * @return array
     */
    private static function reArrayFiles($files)
    {
        $new_array = array();

        foreach( $files as $key => $key_array) {
            $i = 0;
            foreach( $key_array as $value ) {
                $new_array[$i][$key] = $value;
                $i++;
            }
        }

        return $new_array;
    }
}