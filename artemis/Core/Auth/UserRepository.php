<?php


namespace Artemis\Core\Auth;


use Artemis\Core\Auth\Interfaces\Authenticatable;
use Artemis\Core\Exception\AuthException;


class UserRepository
{
    /**
     * Class of the default user.
     *
     * @var string
     */
    private $default_user;

    /**
     * List of user class with their database as key and their class string as value.
     *
     * @var array
     */
    private $users;

    /**
     * Gets a user by its database key. If no user was registered the default user is returned.
     *
     * @param $database
     *
     * @return string
     */
    public function getUser($database)
    {
        return $this->users[$database] ?? $this->default_user;
    }

    /**
     * Registers a default user.
     *
     * @param string $user_class
     *
     * @return void
     */
    public function registerDefaultUser($user_class)
    {
        try {
            $this->checkUserClass('default_user', $user_class);
            $this->default_user = $user_class;
        } catch(\Throwable $e) {
            report($e);
        }
    }

    /**
     * Registers a user by its database key.
     *
     * @param string $database
     * @param string $user_class
     *
     * @return void
     */
    public function registerUser($database, $user_class)
    {
        try {
            $this->checkUserClass($database, $user_class);
            $this->users[$database] = $user_class;
        } catch(\Throwable $e) {
            report($e);
        }
    }

    /**
     * Checks if given user class implements the Authenticatable Interface
     *
     * @param string $database
     * @param string $class
     * @throws AuthException
     *
     * @return void
     */
    private function checkUserClass($database, $class)
    {
        if( !is_subclass_of($class, Authenticatable::class) ) {
            throw new AuthException("provided user with database key '$database' does not implement \Artemis\Core\Auth\Interfaces\Authenticatable");
        }
    }
}