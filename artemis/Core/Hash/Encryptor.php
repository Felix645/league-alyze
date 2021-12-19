<?php


namespace Artemis\Core\Hash;


use Artemis\Core\Hash\Exceptions\DecryptionException;


class Encryptor
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $separator;

    /**
     * Encryptor constructor.
     */
    public function __construct($key)
    {
        $this->method = 'AES-256-CBC';
        $this->key = $key;
        $this->separator = ':';
    }

    /**
     * Generates the initialization vector.
     *
     * @return string
     */
    private function getIv()
    {
        return openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->method));
    }

    /**
     * Encrypts given data and returns encrypted hash.
     *
     * @param mixed $data
     *
     * @return string
     */
    public function encrypt($data)
    {
        $serialized_data = json_encode($data, JSON_UNESCAPED_UNICODE);

        $iv = $this->getIv();
        return base64_encode(openssl_encrypt($serialized_data, $this->method, $this->key, 0, $iv) . $this->separator . base64_encode($iv));
    }

    /**
     * Decrypts given hash and return decrypted data.
     *
     * @param string $dataAndVector
     *
     * @throws DecryptionException
     *
     * @return mixed
     */
    public function decrypt($dataAndVector)
    {
        $parts = explode($this->separator, base64_decode($dataAndVector));

        if( !isset($parts[0]) || !isset($parts[1]) ) {
            throw new DecryptionException();
        }

        $serialized_data = openssl_decrypt($parts[0], $this->method, $this->key, 0, base64_decode($parts[1]));

        if( false === $serialized_data ) {
            throw new DecryptionException();
        }

        return json_decode($serialized_data, true);
    }

}