<?php


namespace Artemis\Core\Auth;


class AuthManager
{
    /**
     * Current Auth instance
     * 
     * @var Auth
     */
    private $auth_instance;

    /**
     * Creates a new auth instance with given database key.
     *
     * @param string $db
     *
     * @return Auth
     */
    public function new($db)
    {
        $this->auth_instance = new Auth($db);
        return $this->auth_instance;
    }

    /**
     * Gets the current auth instance.
     *
     * @return Auth
     */
    public function get()
    {
        return $this->auth_instance ?? new Auth('');
    }
}