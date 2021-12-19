<?php


namespace Artemis\Core;


class Alert
{
    /**
     * Alert key
     * 
     * @var string
     */
    private $key;

    /**
     * Alert message
     * 
     * @var string
     */
    private $message;

    /**
     * Alert constructor.
     * 
     * @param string $key
     * @param string $message
     */
    public function __construct($key, $message)
    {
        $this->key = $key;
        $this->message = $message;
    }

    /**
     * Gets the alert key
     * 
     * @return string
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Gets the alert message
     * 
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}