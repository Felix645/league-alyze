<?php


namespace Artemis\Core\Http\Traits;


trait hasHeaderHandling
{
    /**
     * Collection of headers and their values.
     *
     * @var array
     */
    private $headers = [];

    /**
     * Gets the header collection.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Adds a new header/value-pair.
     *
     * @param string $header_key
     * @param string $header_value
     *
     * @return void
     */
    protected function addHeader($header_key, $header_value)
    {
        $this->headers[$header_key][] = $header_value;
    }
}