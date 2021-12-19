<?php


namespace Artemis\Core\Auth\Interfaces;


interface LdapAuthentication
{
    /**
     * Gets the info if the user is to be authenticated via LDAP.
     * If this return false the user will be authenticated via password hash.
     *
     * @return bool
     */
    public function authenticateWithLdap();

    /**
     * Checks if ldap settings are defined for that user
     *
     * @return bool
     */
    public function checkForLdapSettings();

    /**
     * Gets the ldap server
     *
     * @return string
     */
    public function getLdapServer();

    /**
     * Gets the ldap domain name
     *
     * @return string
     */
    public function getLdapDomainName();

    /**
     * Gets the ldap domain
     *
     * @return string
     */
    public function getDomain();

    /**
     * Gets the ldap password
     *
     * @return string
     */
    public function getLdapPassword();

    /**
     * Gets the ldap BaseDN
     *
     * @return string
     */
    public function getLdapBaseDN();
}