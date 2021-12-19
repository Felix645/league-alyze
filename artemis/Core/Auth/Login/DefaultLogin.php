<?php


namespace Artemis\Core\Auth\Login;


use Artemis\Client\Facades\Hash;
use Artemis\Core\Auth\AbstractLogin;
use Exception;


class DefaultLogin extends AbstractLogin
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function login($password)
    {
        $this->checkAdditionalAttributes();
        $password_hash = $this->user->getPassword();

        if( !Hash::verifyPassword($password, $password_hash) )
            $this->throwException('credentials');

        return true;
    }
}