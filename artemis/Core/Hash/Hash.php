<?php


namespace Artemis\Core\Hash;


use Artemis\Core\Auth\Interfaces\TokenAuthentication;
use Artemis\Core\Exception\AuthException;
use Artemis\Core\Hash\Exceptions\DecryptionException;
use Artemis\Utils\Encryptor;
use Artemis\Core\Hash\Encryptor as NewEncryptor;
use Exception;


class Hash
{
    /**
     * Generates an encrypted api token for a logged in user
     * 
     * @param string $secret_key
     * @param string $secret_iv
     * @throws AuthException
     * 
     * @return string
     */
    public function apiToken($secret_key, $secret_iv = null)
    {
        if( !auth()->valid )
            throw new AuthException("User needs to be logged in in order to generate an api token!");

        $user = auth()->user();

        if( !$user instanceof TokenAuthentication )
            throw new AuthException("User needs to implement the TokenAuthentication interface in order to generate an api token!");

        $data = [
            "id" => $user->getPrimaryKey(),
            "token" => $user->getToken()
        ];

        if( !is_null($secret_iv) ) {
            $encryptor = new Encryptor($secret_key, $secret_iv);
            return $encryptor->setData($data)->encrypt()->getOutput();
        }

        return $this->encrypt($data, $secret_key);
    }

    /**
     * Encrypts given data and returns encrypted hash.
     *
     * @param mixed $data
     * @param string $secret_key
     *
     * @return string
     */
    public function encrypt($data, $secret_key)
    {
        return (new NewEncryptor($secret_key))->encrypt($data);
    }

    /**
     * Decrypts given hash and return decrypted data.
     *
     * @param string $hash
     * @param string $secret_key
     *
     * @throws DecryptionException
     *
     * @return array|mixed|null
     */
    public function decrypt($hash, $secret_key)
    {
        return (new NewEncryptor($secret_key))->decrypt($hash);
    }

    /**
     * Hashes a given passsword
     * 
     * @param string $password
     * 
     * @return string
     */
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verifies a given password
     * 
     * @param string $password
     * @param string $hash
     * 
     * @return bool
     */
    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Creates a random hexadecimal token with the given length
     * 
     * @param int $length
     * @throws Exception
     * 
     * @return string
     */
    public function hexToken($length)
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Generates a random v4 UUID.
     *
     * @throws Exception Is thrown if no source for random numbers can be found.
     *
     * @return string Generated UUID String.
     */
    public function uuid()
    {
        $data = random_bytes(16);

        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * Generates a random string with numbers and letters.
     * If the second argument is set to true, lower case letters are included as well.
     *
     * @param int $length
     * @param bool $lower
     *
     * @return string
     */
    public function randString($length = 10, $lower = false)
    {
        if( $lower ) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        } else {
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}

