<?php


namespace Artemis\Core\Middleware\Middlewares\Auth;


use Artemis\Client\Facades\Hash;
use Artemis\Core\Auth\Interfaces\TokenAuthentication;
use Artemis\Core\Hash\Exceptions\DecryptionException;
use Artemis\Core\Interfaces\MiddlewareInterface;
use Artemis\Core\Middleware\Traits\hasBearerToken;
use Artemis\Core\Middleware\Traits\hasFails;
use Artemis\Core\Middleware\Traits\hasUser;
use Artemis\Utils\Encryptor;


class AuthBearerMiddleware implements MiddlewareInterface
{
    use hasUser, hasFails, hasBearerToken;

    /**
     * Database key
     *
     * @var string
     */
    private $database;

    /**
     * Secret Key token
     *
     * @var string
     */
    private $secret_key;

    /**
     * Secret IV token
     *
     * @var string
     */
    private $secret_iv;

    /**
     * Identifier if the IV should be randomized.
     *
     * @var bool
     */
    private $random_iv = false;

    /**
     * AuthDefaultMiddleware constructor.
     *
     * @param string $database Database key
     * @param string $secret_key Secret Encryption Key
     * @param string $secret_iv Secret Encryption IV
     */
    public function __construct($database, $secret_key, $secret_iv = null)
    {
        $this->database = $database;
        $this->secret_key = $secret_key;
        $this->secret_iv = $secret_iv;

        if( is_null($secret_iv) ) {
            $this->random_iv = true;
        }
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if( !$this->requestBearerToken() ) {
            $this->middlewareFails();
        }

        if( $this->random_iv ) {
            try {
                $data = Hash::decrypt($this->request_token, $this->secret_key);
            } catch(DecryptionException $e) {
                $this->middlewareFails();
                exit;
            }
        } else {
            $decryptor = new Encryptor($this->secret_key, $this->secret_iv);
            $decryptor->setInput($this->request_token);

            if( !$decryptor->decrypt() ) {
                $this->middlewareFails();
            }

            $data = $decryptor->getData();
        }

        if( !isset($data) ) {
            $this->middlewareFails();
        }

        $id = $data["id"];
        $token = $data["token"];

        if( !$this->findUser($this->database, $id) ) {
            $this->middlewareFails();
        }

        if( !$this->user instanceof TokenAuthentication ) {
            $this->middlewareFails();
        }

        if( $this->user->getToken() !== $token || $this->user->getTokenExpires() <= now() ) {
            $this->middlewareFails();
        }

        auth($this->database)->setUser($this->user)->valid = true;
    }
}