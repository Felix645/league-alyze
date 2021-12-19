<?php


namespace Artemis\Core\Hash\Exceptions;


class DecryptionException extends \Exception
{
    protected $code = 401;

    protected $message = 'Given hash could not be decrypted';
}