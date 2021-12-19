<?php


namespace Artemis\Core\Http;


use Artemis\Support\Arr;
use Artemis\Support\Str;
use Artemis\Core\Http\Interfaces\HttpHeaders;


class HeaderCollection
{
    /**
     * Key transform search values.
     *
     * @var string
     */
    protected const UPPER = '_ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Key transform replace values.
     *
     * @var string
     */
    protected const LOWER = '-abcdefghijklmnopqrstuvwxyz';

    /**
     * Header collection
     *
     * @var array
     */
    private $headers = [];

    /**
     * HeaderCollection constructor.
     */
    public function __construct()
    {
        $this->setHeaders($this->getServerHeaders());
    }

    /**
     * Gets all header keys and their values.
     *
     * @return array
     */
    public function all()
    {
        return $this->headers;
    }

    /**
     * Gets all header keys.
     *
     * @return int[]|string[]
     */
    public function keys()
    {
        return array_keys($this->headers);
    }

    /**
     * Gets the values for the given header key.
     * Optionally a default value may be provided.
     *
     * @param string $key           Header key
     * @param mixed|null $default   Default value
     *
     * @return array|null
     */
    public function get($key, $default = null)
    {
        return $this->headers[$this->key($key)] ?? $default;
    }

    /**
     * Gets the first value of the given header key.
     * Optionally a default value may be provided.
     *
     * @param string $key       Header key
     * @param null $default     Default value
     *
     * @return string|null|mixed
     */
    public function first($key, $default = null)
    {
        if( !$this->has($key) ) {
            return $default;
        }

        return $this->headers[$this->key($key)][0];
    }

    /**
     * Checks if the given header key exists.
     *
     * @param string $key   Header key
     *
     * @return bool
     */
    public function has($key)
    {
        return Arr::exists($this->key($key), $this->headers);
    }

    /**
     * Checks if given header value exists in given header key.
     *
     * @param string $key       Header key
     * @param string $value     Header value
     *
     * @return bool
     */
    public function contains($key, $value)
    {
        if( !$this->has($key) ) {
            return false;
        }

        return in_array($value, $this->get($key));
    }

    /**
     * Sets a header with its values.
     *
     * @param string $key       Header key
     * @param array $values     Header values
     *
     * @return void
     */
    private function set($key, $values)
    {
        $this->headers[$this->key($key)] = $values;
    }

    /**
     * Populates the headers property from given array.
     *
     * @param array $headers Request headers
     *
     * @return void
     */
    private function setHeaders($headers)
    {
        foreach( $headers as $key => $values ) {
            $values = explode(',', $values);
            $values = $values === false ? [] : $values;

            if( $this->key(HttpHeaders::HEADER_REQUEST_AUTH) !== $this->key($key) ) {
                $values = $this->clearValuesOfSpaces($values);
            }

            $this->set($key, $values);
        }
    }

    /**
     * Clears the values of given array from spaces.
     *
     * @param array $values Array of values
     *
     * @return array
     */
    private function clearValuesOfSpaces($values)
    {
        return Arr::map($values, function($item) {
            return Str::replace(' ', '', $item);
        });
    }

    /**
     * Gets all http request headers from the server.
     *
     * @return array
     */
    private function getServerHeaders()
    {
        if( !function_exists('getallheaders') )
        {
            function getallheaders()
            {
                $headers = [];
                foreach ($_SERVER as $name => $value)
                {
                    if (substr($name, 0, 5) == 'HTTP_')
                    {
                        $key = Str::replace(' ', '-', Str::upperWords(Str::lower(Str::replace('_', ' ', Str::sub($name, 5)))));
                        $headers[$key] = $value;
                    }
                }
                return $headers;
            }
        }

        return getallheaders();
    }

    /**
     * Transform the given key to be consistent.
     *
     * @param string $key Header key to be transformed
     *
     * @return string
     */
    private function key($key)
    {
        return Str::translate($key, self::UPPER, self::LOWER);
    }
}