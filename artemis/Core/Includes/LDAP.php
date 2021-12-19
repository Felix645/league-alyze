<?php


namespace Artemis\Core\Includes;


use Artemis\Core\Auth\Interfaces\LdapAuthentication;


class LDAP 
{
    /**
     * Defines LDAP connection and then calls checkNTUser for authentication
     *
     * @param string $username
     * @param string $password
     * @param LdapAuthentication $user
     *
     * @return array $ldap_return
     */
    public function ldap_connection($username, $password, $user)
    {
        define('LDAP_SERVER', $user->getLdapServer());
        define('LDAP_DN', $user->getLdapDomainName());
        define('LDAP_PWD', $user->getLdapPassword());
        define('LDAP_BASEDN', $user->getLdapBaseDN());
			
		if( !empty($username) && !empty($password) )
			return  $this->checkNTUser($username, $password, $user->getDomain(), $user->getLdapServer());

		return ["success" => FALSE, "error" => "ldap_passing_data"];
	}

	/**
     * Establishes LDAP connection an then tries to authenticate the given user.
     * v0.9
	 * 
     * @param string $username
     * @param string $password
     * @param string $domain_name
     * @param string $ldap_server
	 * 
     * @return array $flag
     */
    public function checkNTuser($username, $password, $domain_name, $ldap_server)
    {
		$auth_user = $username . "@" . $domain_name;

        if( !$connect = @ldap_connect( $ldap_server ) ) {
            @ldap_close($connect);
            return ["success" => false, "error" => "ldap_connection"];
        }

        if( !@ldap_bind($connect, $auth_user, utf8_decode($password)) ) {
            @ldap_close($connect);
            return ["success" => false, "error" => "ldap_credentials"];
        }

        @ldap_close($connect);
        return ["success" => true];
	}
}
