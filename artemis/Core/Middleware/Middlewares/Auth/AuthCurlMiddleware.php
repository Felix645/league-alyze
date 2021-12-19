<?php


namespace Artemis\Core\Middleware\Middlewares\Auth;


use Artemis\Core\Auth\Interfaces\TokenAuthentication;
use Artemis\Core\Interfaces\MiddlewareInterface;
use Artemis\Core\Middleware\Traits\hasFails;
use Artemis\Core\Middleware\Traits\hasUser;


class AuthCurlMiddleware implements MiddlewareInterface
{
    use hasUser, hasFails;

    /**
     * Database key
     *
     * @var string
     */
    private $database;

    /**
     * User key
     *
     * @var string
     */
    private $user_key;

    /**
     * Token key
     *
     * @var string
     */
    private $token_key;

    /**
     * Request body.
     *
     * @var array
     */
    private $request_body;

    /**
     * AuthCurlMiddleware constructor.
     *
     * @param string $database Database key
     */
    public function __construct($database)
    {
        $this->database = $database;
        $this->user_key = config('auth_curl_id');
        $this->token_key = config('auth_curl_token');
        $this->request_body = container('request')->all();
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if( !isset($this->request_body[$this->user_key]) || intval($this->request_body[$this->user_key]) <= 0 ) {
            $this->middlewareFails();
        }

        if( !isset($this->request_body[$this->token_key]) || empty($this->request_body[$this->token_key]) ) {
            $this->middlewareFails();
        }

        $curl_user_id = $this->request_body[$this->user_key];
        $curl_auth_token = $this->request_body[$this->token_key];

        if( !$this->findUser($this->database, $curl_user_id) ) {
            $this->middlewareFails();
        }

        if( !$this->user instanceof TokenAuthentication ) {
            $this->middlewareFails();
        }

        if( null === $this->user->getToken() ) {
            $this->middlewareFails();
        }

        $token = $this->user->getToken();
        $token_expires = $this->user->getTokenExpires();

        if( $token !== $curl_auth_token || $token_expires <= now() ) {
            $this->middlewareFails();
        }

        auth($this->database)->setUser($this->user)->valid = true;
    }

    /**
     * Sets the user key that is used in the request body.
     *
     * @param string $key
     *
     * @return $this
     */
    public function setUserKey($key)
    {
        $this->user_key = $key;
        return $this;
    }

    /**
     * Sets the token key that is used in the request body.
     *
     * @param string $key
     *
     * @return $this
     */
    public function setTokenKey($key)
    {
        $this->token_key = $key;
        return $this;
    }
}