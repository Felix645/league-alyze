<?php


namespace Artemis\Core\Middleware\Middlewares\Auth;


use Artemis\Core\Interfaces\MiddlewareInterface;
use Artemis\Core\Auth\Interfaces\TokenAuthentication;
use Artemis\Core\Middleware\Traits\hasFails;
use Artemis\Core\Middleware\Traits\hasUser;


class AuthTokenMiddleware implements MiddlewareInterface
{
    use hasUser, hasFails;

    /**
     * Database key
     *
     * @var string
     */
    private $database;

    /**
     * AuthDefaultMiddleware constructor.
     *
     * @param string $database Database key
     */
    public function __construct($database)
    {
        $this->database = $database;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $session = container('session');
        $user_id = $session->getUserID($this->database);
        $user_session_token = $session->getUserToken($this->database);

        if( !isset($user_id) ) {
            $this->middlewareFails();
        }

        if( !isset($user_token) || empty($user_token) ) {
            $this->middlewareFails();
        }

        if( !$this->findUser($this->database, $user_id) ) {
            $this->middlewareFails();
        }

        if( !$this->user instanceof TokenAuthentication ) {
            $this->middlewareFails();
        }

        if( null === $this->user->getToken() ) {
            $this->middlewareFails();
        }

        $user_token = $this->user->getToken();
        $token_expires = $this->user->getTokenExpires();

        if( $user_session_token !== $user_token || $token_expires <= now() ) {
            $this->middlewareFails();
        }

        auth($this->database)->setUser($this->user)->valid = true;
    }
}