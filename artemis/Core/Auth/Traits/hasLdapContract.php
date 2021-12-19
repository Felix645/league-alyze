<?php


namespace Artemis\Core\Auth\Traits;


use Artemis\Core\Auth\Models\UserMech;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasOne;


/**
 * Trait hasLdapContract
 *
 * @property UserMech $user_mech
 */
trait hasLdapContract
{
    /**
     * Defines a 1:1 relationship to the users_auth_mech table
     *
     * @return HasOne
     */
    public function user_mech()
    {
        return $this->hasOne(UserMech::class, 'users_id');
    }

    /**
     * @param $model
     * @throws Exception
     *
     * @return void
     */
    private function checkUserMechModel($model)
    {
        if( is_null($model) )
            throw new Exception('user_mech_data');
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function authenticateWithLdap()
    {
        $this->loadMissing(['user_mech']);
        $this->checkUserMechModel($this->user_mech);
        return $this->user_mech->mechanism_id;
    }

    /**
     * @inheritDoc
     */
    public function checkForLdapSettings()
    {
        $this->loadMissing(['user_mech']);
        if( !$this->user_mech->ldap_setting )
            return false;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getLdapServer()
    {
        return $this->user_mech->ldap_setting->sys_auth_mech_ldap_server;
    }

    /**
     * @inheritDoc
     */
    public function getLdapDomainName()
    {
        return $this->user_mech->ldap_setting->sys_auth_mech_ldap_dn;
    }

    /**
     * @inheritDoc
     */
    public function getDomain()
    {
        return $this->user_mech->ldap_setting->domain;
    }

    /**
     * @inheritDoc
     */
    public function getLdapPassword()
    {
        return $this->user_mech->ldap_setting->sys_auth_mech_ldap_pwd;
    }

    /**
     * @inheritDoc
     */
    public function getLdapBaseDN()
    {
        return $this->user_mech->ldap_setting->sys_auth_mech_ldap_basedn;
    }
}