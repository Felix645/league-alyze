<?php


namespace Artemis\Core\Auth\Login;


use Artemis\Core\Auth\AbstractLogin;
use Artemis\Core\Auth\Interfaces\LdapAuthentication;
use Artemis\Core\Includes\LDAP;
use Exception;


class LdapLogin extends AbstractLogin
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function login($password)
    {
        $this->checkAdditionalAttributes();

        if( !$this->user instanceof LdapAuthentication )
            $this->throwException('ldap_interface');

        if( !$this->user->checkForLdapSettings() )
            $this->throwException('ldap_settings');

        $ldap = new LDAP();

        $result = $ldap->ldap_connection($this->user->getIdentfier(), $password, $this->user);

        if( !$result['success'] )
            $this->throwException($result['error']);

        return true;
    }
}