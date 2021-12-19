<?php


namespace Artemis\Utils;


class Encryptor
{
    /**
     * Encryption Method
     * 
     * @var string
     */
    public $method = "AES-256-CBC";

    /**
     * Input for decryption
     * 
     * @var string
     */
    private $input;

    /**
     * Output from successful encryption
     * 
     * @var string
     */
    private $output;

    /**
     * Private key
     * 
     * @var string
     */
    private $key;

    /**
     * Private IV
     * 
     * @var string
     */
    private $iv;

    /**
     * Data to be encrypted or data resulting decryption
     * 
     * @var null|mixed
     */
    private $data = [];

    /**
     * Encryptor Constructor.
     * 
     * @param string $key
     * @param string $iv
     */
    public function __construct($key, $iv)
    {
        $this->key = $key;
        $this->iv = $iv;
    }

    /**
     * Encrypts given data
     * 
     * @return Encryptor
     */
    public function encrypt()
    {
        $serialized_data = json_encode($this->data, JSON_UNESCAPED_UNICODE);

        $output = openssl_encrypt($serialized_data, $this->method, $this->hashKey(), 0, $this->hashIV());
        $this->output = base64_encode($output);

        return $this;
    }

    /**
     * Decrypts given input, returns true on success and false on failure
     * 
     * @return bool
     */
    public function decrypt()
    {
        $serialized_data = openssl_decrypt(base64_decode($this->input), $this->method, $this->hashKey(), 0, $this->hashIV());

        if( $serialized_data !== false ) {
            $this->data = json_decode($serialized_data, true);
            return true;
        }

        return false;
    }

    /**
     * Get the value of data
     * 
     * @return mixed
     */ 
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the value of data
     * 
     * @param mixed $data
     * 
     * @return Encryptor
     */ 
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the value of output
     * 
     * @return string
     */ 
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Set the value of input
     * 
     * @param mixed $input
     * 
     * @return Encryptor
     */ 
    public function setInput($input)
    {
        $this->input = $input;

        return $this;
    }

    /**
     * Hashes the given IV
     * 
     * @return string
     */
    private function hashIV()
    {
        return substr(hash('sha256', $this->iv), 0, 16);
    }

    /**
     * Hashes the given key
     * 
     * @return string
     */
    private function hashKey()
    {
        return hash('sha256', $this->key);
    }
}