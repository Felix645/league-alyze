<?php


namespace Artemis\Core\Auth;


use Artemis\Core\Auth\Interfaces\Authenticatable;
use Artemis\Core\Auth\Interfaces\LdapAuthentication;
use Artemis\Core\Auth\Interfaces\TokenAuthentication;
use Artemis\Core\Auth\Login\DefaultLogin;
use Artemis\Core\Auth\Login\LdapLogin;
use Artemis\Core\Auth\Traits\hasLoginExceptions;
use Exception;


class Auth
{
    use hasLoginExceptions;

    /**
     * Database key
     *
     * @var string
     */
    private $database;

    /**
     * User object
     *
     * @var Authenticatable
     */
    private $user;

    /**
     * Identifier if the application has active session
     *
     * @var bool
     */
    private $is_stateless = false;

    /**
     * Identifier if authentication is valid
     *
     * @var bool
     */
    public $valid = false;

    /**
     * Error message if the login attempt was not successful
     *
     * @var string
     */
    private $error = '';

    /**
     * Service Provider to get correct user class
     *
     * @var UserRepository
     */
    private $user_repository;

    /**
     * Auth constructor.
     *
     * @param string $db
     */
    public function __construct($db)
    {
        $this->database = $db;
        $this->user_repository = container(UserRepository::class);

        if( !session()->isActive() )
            $this->is_stateless = true;
    }

    /**
     * Gets the active database key
     *
     * @return string
     */
    public function getDB()
    {
        return $this->database;
    }

    /**
     * Gets the user object
     *
     * @return Authenticatable|null
     */
    public function user()
    {
        return $this->user ?? null;
    }

    /**
     * Gets the authenticated user id or null if the user is not authenticated.
     *
     * @return null|mixed
     */
    public function id()
    {
        if( is_null($this->user) ) {
            return null;
        }

        return $this->user->getPrimaryKey() ?? null;
    }

    /**
     * Sets the user object
     *
     * @param Authenticatable $user
     *
     * @return $this
     */
    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Attempts login with given user identifier and password
     *
     * @param string $identifier
     * @param string $password
     *
     * @return bool
     */
    public function attempt($identifier, $password)
    {
        try {
            $loginHandler = $this->getLoginHandler($identifier);

            if( $loginHandler->login($password) ) {
                $this->forceLogin($this->user);
                return true;
            }

            $this->error = 'unexpected_failure';
            return false;
        } catch(Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * Forces the logged in state with given user object
     *
     * @param Authenticatable $user
     *
     * @return void
     */
    public function forceLogin($user)
    {
        $this->valid = true;

        if( $this->is_stateless )
            return;

        session()->setUserID($this->database, $user->getPrimaryKey());

        if( $user instanceof TokenAuthentication )
            session()->setUserToken($this->database, $user->getToken());
    }

    /**
     * Clears the current auth instance
     *
     * @return void
     */
    public function logout()
    {
        if( !$this->is_stateless )
            session()->clearAuthSession($this->database);

        unset($this->user);
        $this->valid = false;
    }

    /**
     * Gets error message if login attempt was not successful
     *
     * @return string
     */
    public function error()
    {
        return $this->error;
    }

    /**
     * Gets the login handler and sets the user object with given identifier
     *
     * @param string $identifier
     * @throws Exception
     *
     * @return AbstractLogin
     */
    private function getLoginHandler($identifier)
    {
        $user_class = $this->user_repository->getUser($this->database);

        if( !is_subclass_of($user_class, Authenticatable::class) )
            $this->throwException('auth_interface');

        $user = $user_class::findByIdentifier($identifier, $this->database);

        if( !$user )
            $this->throwException('no_user');

        $this->setUser($user);

        if( $user instanceof LdapAuthentication ) {
            return $user->authenticateWithLdap()
                ? new LdapLogin($this->user)
                : new DefaultLogin($this->user);
        }

        return new DefaultLogin($this->user);
    }
}