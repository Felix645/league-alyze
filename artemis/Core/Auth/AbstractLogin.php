<?php


namespace Artemis\Core\Auth;


use Artemis\Core\Auth\Interfaces\Activatable;
use Artemis\Core\Auth\Interfaces\Authenticatable;
use Artemis\Core\Auth\Interfaces\Bannable;
use Artemis\Core\Auth\Interfaces\Deletable;
use Artemis\Core\Auth\Traits\hasLoginExceptions;
use Exception;


abstract class AbstractLogin
{
    use hasLoginExceptions;

    /**
     * User object of the user to be authenticated
     *
     * @var Authenticatable
     */
    protected $user;

    /**
     * AbstractLogin constructor.
     *
     * @param Authenticatable $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Checks user for additional attributes
     *
     * @throws Exception
     */
    protected function checkAdditionalAttributes()
    {
        if( $this->user instanceof Activatable )
            $this->checkIsActive($this->user);

        if( $this->user instanceof Bannable )
            $this->checkIsBanned($this->user);

        if( $this->user instanceof Deletable )
            $this->checkIsDeleted($this->user);
    }

    /**
     * Checks if given user is active
     *
     * @param Activatable $user
     * @throws Exception
     *
     * @return void
     */
    private function checkIsActive($user)
    {
        if( !$user->isActive() )
            $this->throwException('not_active');
    }

    /**
     * Checks if given user is banned
     *
     * @param Bannable $user
     * @throws Exception
     *
     * @return void
     */
    private function checkIsBanned($user) : void
    {
        if( $user->isBanned() )
            $this->throwException('banned');
    }

    /**
     * Checks if given user is deleted
     *
     * @param Deletable $user
     * @throws Exception
     *
     * @return void
     */
    private function checkIsDeleted($user) : void
    {
        if( $user->isDeleted() )
            $this->throwException('deleted');
    }

    /**
     * Performs login with given identifier and password
     *
     * @param string $password
     *
     * @return bool
     */
    abstract public function login($password);
}