<?php


namespace Artemis\Client\Facades;


/**
 * Class Hash
 * @package Artemis\Client\Facades
 *
 * @method static string apiToken(string $secret_key, string $secret_iv = null)
 * @method static string hashPassword(string $password)
 * @method static bool verifyPassword(string $password, string $hash)
 * @method static string hexToken(int $length)
 * @method static string uuid() Generates a random v4 UUID.
 * @method static string randString($length = 10, $lower = false)
 * @method static string encrypt(mixed $data, string $secret_key) Encrypts given data and returns encrypted hash.
 * @method static mixed decrypt(string $hash, string $secret_key) Decrypts given hash and return decrypted data.
 *
 * @uses \Artemis\Core\Hash\Hash::apiToken()
 * @uses \Artemis\Core\Hash\Hash::hashPassword()
 * @uses \Artemis\Core\Hash\Hash::verifyPassword()
 * @uses \Artemis\Core\Hash\Hash::hexToken()
 * @uses \Artemis\Core\Hash\Hash::uuid()
 * @uses \Artemis\Core\Hash\Hash::randString()
 * @uses \Artemis\Core\Hash\Hash::encrypt()
 * @uses \Artemis\Core\Hash\Hash::decrypt()
 */
class Hash extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getAccessor()
    {
        return 'hash';
    }
}