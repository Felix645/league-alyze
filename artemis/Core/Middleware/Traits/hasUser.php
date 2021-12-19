<?php


namespace Artemis\Core\Middleware\Traits;


use Artemis\Core\Auth\Interfaces\Authenticatable;
use Artemis\Core\Auth\UserRepository;


trait hasUser
{
    /**
     * The User object
     *
     * @var Authenticatable
     */
    protected $user;

    /**
     * Finds a user object with given database key and user id
     *
     * @param string $db
     * @param int $user_id
     *
     * @return bool
     */
    final protected function findUser($db, $user_id)
    {
        /* @var UserRepository $user_repository */
        $user_repository = container(UserRepository::class);

        $user_class = $user_repository->getUser($db);

        if( !is_subclass_of($user_class, Authenticatable::class) )
            return false;

        $user = $user_class::findByPrimaryKey($user_id, $db);

        if( !$user )
            return false;

        $this->user = $user;

        return true;
    }

    /**
     * Gets the user object
     *
     * @return Authenticatable
     */
    final public function getUser()
    {
        return $this->user;
    }
}